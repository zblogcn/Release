<?php
/**
 * 辅助通用函数.
 */

if (!defined('ZBP_PATH')) {
    exit('Access denied');
}

/**
 * 得到请求协议（考虑到不正确的配置反向代理等原因，未必准确）
 * 如果想获取准确的值，请zbp->Load后使用$zbp->isHttps.
 *
 * @param array $array
 *
 * @return string
 */
function GetScheme($array)
{
    $array = array_change_key_case($array, CASE_UPPER);

    if (array_key_exists('REQUEST_SCHEME', $array) && (strtolower($array['REQUEST_SCHEME']) == 'https')) {
        return 'https://';
    } elseif (array_key_exists('HTTPS', $array) && (strtolower($array['HTTPS']) == 'on')) {
        return 'https://';
    } elseif (array_key_exists('SERVER_PORT', $array) && ($array['SERVER_PORT'] == 443)) {
        return 'https://';
    } elseif (array_key_exists('HTTP_X_FORWARDED_PORT', $array) && ($array['HTTP_X_FORWARDED_PORT'] == 443)) {
        return 'https://';
    } elseif (array_key_exists('HTTP_X_FORWARDED_PROTO', $array) && (strtolower($array['HTTP_X_FORWARDED_PROTO']) == 'https')) {
        return 'https://';
    } elseif (array_key_exists('HTTP_X_FORWARDED_PROTOCOL', $array) && (strtolower($array['HTTP_X_FORWARDED_PROTOCOL']) == 'https')) {
        return 'https://';
    } elseif (array_key_exists('HTTP_X_FORWARDED_SSL', $array) && (strtolower($array['HTTP_X_FORWARDED_SSL']) == 'on')) {
        return 'https://';
    } elseif (array_key_exists('HTTP_X_URL_SCHEME', $array) && (strtolower($array['HTTP_X_URL_SCHEME']) == 'https')) {
        return 'https://';
    } elseif (array_key_exists('HTTP_CF_VISITOR', $array) && (stripos($array['HTTP_CF_VISITOR'], 'https') !== false)) {
        return 'https://';
    } elseif (array_key_exists('HTTP_FROM_HTTPS', $array) && (strtolower($array['HTTP_FROM_HTTPS']) == 'on')) {
        return 'https://';
    } elseif (array_key_exists('HTTP_FRONT_END_HTTPS', $array) && (strtolower($array['HTTP_FRONT_END_HTTPS']) == 'on')) {
        return 'https://';
    } elseif (array_key_exists('SERVER_PORT_SECURE', $array) && ($array['SERVER_PORT_SECURE'] == 1)) {
        return 'https://';
    }
    return 'http://';
}

/**
 * 获取服务器.
 *
 * @return int
 */
function GetWebServer()
{
    if (!isset($_SERVER['SERVER_SOFTWARE'])) {
        return SERVER_UNKNOWN;
    }
    $webServer = strtolower($_SERVER['SERVER_SOFTWARE']);
    if (strpos($webServer, 'apache') !== false) {
        return SERVER_APACHE;
    } elseif (strpos($webServer, 'microsoft-iis') !== false) {
        return SERVER_IIS;
    } elseif (strpos($webServer, 'nginx') !== false) {
        return SERVER_NGINX;
    } elseif (strpos($webServer, 'lighttpd') !== false) {
        return SERVER_LIGHTTPD;
    } elseif (strpos($webServer, 'kangle') !== false) {
        return SERVER_KANGLE;
    } elseif (strpos($webServer, 'caddy') !== false) {
        return SERVER_CADDY;
    } elseif (strpos($webServer, 'development server') !== false) {
        return SERVER_BUILTIN;
    } else {
        return SERVER_UNKNOWN;
    }
}

/**
 * 获取操作系统
 *
 * @return int
 */
function GetSystem()
{
    if (in_array(strtoupper(PHP_OS), array('WINNT', 'WIN32', 'WINDOWS'))) {
        return SYSTEM_WINDOWS;
    } elseif ((strtoupper(PHP_OS) === 'UNIX')) {
        return SYSTEM_UNIX;
    } elseif (strtoupper(PHP_OS) === 'LINUX') {
        return SYSTEM_LINUX;
    } elseif (strtoupper(PHP_OS) === 'DARWIN') {
        return SYSTEM_DARWIN;
    } elseif (strtoupper(substr(PHP_OS, 0, 6)) === 'CYGWIN') {
        return SYSTEM_CYGWIN;
    } elseif (in_array(strtoupper(PHP_OS), array('NETBSD', 'OPENBSD', 'FREEBSD'))) {
        return SYSTEM_BSD;
    } else {
        return SYSTEM_UNKNOWN;
    }
}

/**
 * 获取PHP解析引擎.
 *
 * @return int
 */
function GetPHPEngine()
{
    return ENGINE_PHP;
}

/**
 * 获取PHP Version.
 *
 * @return string
 */
function GetPHPVersion()
{
    $p = phpversion();
    if (strpos($p, '-') !== false) {
        $p = substr($p, 0, strpos($p, '-'));
    }

    return $p;
}

/**
 * 自动加载类文件.
 *
 * @param string $className 类名
 *
 * @api    Filter_Plugin_Autoload
 * *
 * @return mixed
 */
function AutoloadClass($className)
{
    foreach ($GLOBALS['hooks']['Filter_Plugin_Autoload'] as $fpname => &$fpsignal) {
        $fpreturn = $fpname($className);
        if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
            $fpsignal = PLUGIN_EXITSIGNAL_NONE;

            return $fpreturn;
        }
    }
    $className = str_replace('__', '/', $className);
    $fileName = ZBP_PATH . 'zb_system/function/lib/' . strtolower($className) . '.php';
    if (is_readable($fileName)) {
        include $fileName;

        return true;
    }

    return false;
}

/**
 * 记录日志.
 *
 * @param string $logString
 * @param string $level INFO|ERROR|WARNING|FATAL|DEBUG|TRACE
 * @param string $source system or plugin ID
 *
 * @return bool
 */
function Logs($logString, $level = 'INFO', $source = 'system')
{
    global $zbp;
    $time = date('Y-m-d') . ' ' . date('H:i:s') . ' ' . substr(microtime(), 1, 9) . ' ' . date('P');
    $isError = false;
    if ($level === true) {
        $level = 'ERROR';
    } elseif ($level === false) {
        $level = 'INFO';
    }
    $level = strtoupper($level);
    if ($level == 'WARNING' || $level == 'ERROR' || $level == 'FATAL') {
        $isError = true;
    }

    foreach ($GLOBALS['hooks']['Filter_Plugin_Logs'] as $fpname => &$fpsignal) {
        $fpreturn = $fpname($logString, $level, $source, $time);
        if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
            $fpsignal = PLUGIN_EXITSIGNAL_NONE;

            return $fpreturn;
        }
    }
    if ($zbp->guid) {
        if ($isError) {
            $f = $zbp->logsdir . '' . $zbp->guid . '-error' . date("Ymd") . '.txt';
        } else {
            $f = $zbp->logsdir . '' . $zbp->guid . '-log' . date("Ymd") . '.txt';
        }
    } else {
        if ($isError) {
            $f = $zbp->logsdir . '' . md5($zbp->path) . '-error.txt';
        } else {
            $f = $zbp->logsdir . '' . md5($zbp->path) . '.txt';
        }
    }
    ZBlogException::SuspendErrorHook();
    $handle = @fopen($f, 'a+');
    if ($handle) {
        $t = $time;
        @fwrite($handle, '[' . $t . ']' . " " . $level . " " . $source . "\r\n" . $logString . "\r\n");
        @fclose($handle);
    }
    ZBlogException::ResumeErrorHook();

    return true;
}

/**
 * 输出页面运行时长
 *
 * @param bool $isOutput 是否输出（考虑历史原因，默认输出）
 *
 * @return array
 */
function RunTime($isOutput = true)
{
    global $zbp;

    $rt = array();
    $_end_time = microtime(true);
    $rt['time'] = number_format((1000 * ($_end_time - $_SERVER['_start_time'])), 2);
    $rt['query'] = $_SERVER['_query_count'];
    $rt['memory'] = $_SERVER['_memory_usage'];
    $rt['error'] = $_SERVER['_error_count'];
    $rt['error_detail'] = ZBlogException::$errors_msg;
    if (function_exists('memory_get_usage')) {
        $rt['memory'] = (int) ((memory_get_usage() - $_SERVER['_memory_usage']) / 1024);
    }

    $_SERVER['_runtime_result'] = $rt;

    if (array_key_exists('_end_time', $_SERVER)) {
        return $rt;
    } else {
        $_SERVER['_end_time'] = $_end_time;
    }

    if (isset($zbp->option['ZC_RUNINFO_DISPLAY']) && $zbp->option['ZC_RUNINFO_DISPLAY'] == false) {
        return $rt;
    }

    if ($isOutput) {
        echo '<!--' . $rt['time'] . ' ms , ';
        echo $rt['query'] . ' query';
        echo ' , ' . $rt['memory'] . 'kb memory';
        echo ' , ' . $rt['error'] . ' error';
        echo '-->';
    }

    return $rt;
}

/**
 * 获得系统信息.
 *
 * @return string 系统信息
 *
 * @since 1.4
 */
function GetEnvironment()
{
    global $zbp;
    $ajax = Network::Create();
    if ($ajax) {
        $ajax = substr(get_class($ajax), 9);
    }
    if ($ajax == 'curl') {
        if (ini_get("safe_mode")) {
            $ajax .= '-s';
        }
        if (ini_get("open_basedir")) {
            $ajax .= '-o';
        }
        $array = curl_version();
        $ajax .= $array['version'];
    }
    if (function_exists('php_uname') == true) {
        $uname = SplitAndGet(php_uname('r'), '-', 0);
    } else {
        $uname = '';
    }
    $system_environment = PHP_OS . $uname . '; ' .
        GetValueInArray(
            explode(
                ' ',
                str_replace(array('Microsoft-', '/'), array('', ''), GetVars('SERVER_SOFTWARE', 'SERVER'))
            ),
            0
        ) . '; PHP' . GetPHPVersion() . (IS_X64 ? 'x64' : '') . '; ' .
        $zbp->option['ZC_DATABASE_TYPE'] . $zbp->db->version . '; ' . $ajax;

    if (defined('OPENSSL_VERSION_TEXT')) {
        $a = explode(' ', OPENSSL_VERSION_TEXT);
        $system_environment .= '; ' . GetValueInArray($a, 0) . GetValueInArray($a, 1);
    }

    return $system_environment;
}

/**
 * 通过文件获取应用URL地址
 *
 * @param string $file 文件名
 *
 * @return string 返回URL地址
 */
function plugin_dir_url($file)
{
    global $zbp;
    $s1 = $zbp->path;
    $s2 = str_replace('\\', '/', dirname($file) . '/');
    $s = substr($s2, strspn($s1, $s2, 0));
    if (strpos($s, 'zb_users/plugin/') !== false) {
        $s = substr($s, strspn($s, $s3 = 'zb_users/plugin/', 0));
    } else {
        $s = substr($s, strspn($s, $s3 = 'zb_users/theme/', 0));
    }
    $a = explode('/', $s);
    $s = $a[0];
    $s = $zbp->host . $s3 . $s . '/';

    return $s;
}

/**
 * 通过文件获取应用目录路径.
 *
 * @param $file
 *
 * @return string
 */
function plugin_dir_path($file)
{
    global $zbp;
    $s1 = $zbp->path;
    $s2 = str_replace('\\', '/', dirname($file) . '/');
    $s = substr($s2, strspn($s1, $s2, 0));
    if (strpos($s, 'zb_users/plugin/') !== false) {
        $s = substr($s, strspn($s, $s3 = 'zb_users/plugin/', 0));
    } else {
        $s = substr($s, strspn($s, $s3 = 'zb_users/theme/', 0));
    }
    $a = explode('/', $s);
    $s = $a[0];
    $s = $zbp->path . $s3 . $s . '/';

    return $s;
}

/**
 * 通过Key从数组获取数据.
 *
 * @param array  $array 数组名
 * @param string $name  下标key
 *
 * @return mixed
 */
function GetValueInArray($array, $name, $default = null)
{
    if (is_array($array)) {
        if (array_key_exists($name, $array)) {
            return $array[$name];
        } else {
            return $default;
        }
    }
}

/**
 * 获取数组中的当前元素(还是数组)的数据.
 *
 * @param string $array 数组名
 * @param string $name  下标key
 *
 * @return mixed
 */
function GetValueInArrayByCurrent($array, $name, $default = null)
{
    if (is_array($array)) {
        $array = current($array);

        return GetValueInArray($array, $name, $default);
    }
}

/**
 * 分割string并取某项数据.
 *
 * @param string $string
 * @param string $delimiter
 * @param int    $n
 *
 * @return string
 */
function SplitAndGet($string, $delimiter = ';', $n = 0)
{
    $a = explode($delimiter, $string);
    if (!is_array($a)) {
        $a = array();
    }
    if (isset($a[$n])) {
        return (string) $a[$n];
    }

    return '';
}

/**
 * 删除连续空格
 *
 * @param $s
 *
 * @return null|string|string[]
 */
function RemoveMoreSpaces($s)
{
    return preg_replace("/\s(?=\s)/", "\\1", $s);
}

/**
 * 获取Guid.
 *
 * @return string
 */
function GetGuid()
{
    $charid = strtolower(md5(uniqid(mt_rand(), true)));

    return $charid;
}

/**
 * 获取参数值
 *
 * @param string $name 数组key名
 * @param string $type 默认为REQUEST
 *
 * @return mixed|null
 */
function GetVars($name, $type = 'REQUEST', $default = null)
{
    $array = &$GLOBALS[strtoupper("_$type")];

    if (isset($array[$name])) {
        return $array[$name];
    } else {
        return $default;
    }
}

/**
 * 获取参数值（可设置默认返回值）.本函数在1.7已经废弃了，改用GetVars！
 *
 * @param string $name    数组key名
 * @param string $type    默认为REQUEST
 * @param string $default 默认为null
 *
 * @return mixed|null
 *
 * @since 1.3.140614
 */
function GetVarsByDefault($name, $type = 'REQUEST', $default = null)
{
    $array = &$GLOBALS[strtoupper("_$type")];

    if (isset($array[$name])) {
        return $array[$name];
    } else {
        return $default;
    }
}

/**
 * 获取数据库名.
 *
 * @return string 返回一个随机的SQLite数据文件名
 */
function GetDbName()
{
    return str_replace('-', '', '#%20' . strtolower(GetGuid())) . '.db';
}

/**
 * 获取当前网站地址
 *
 * @param string $blogpath     网站物理实际路径
 * @param string &$cookiesPath 返回cookie作用域值，要传引入
 *
 * @return string 返回网站完整地址，如http://localhost/zbp/
 */
function GetCurrentHost($blogpath, &$cookiesPath)
{
    $host = HTTP_SCHEME;

    if (isset($_SERVER['HTTP_HOST'])) {
        $host .= $_SERVER['HTTP_HOST'];
    } elseif (isset($_SERVER["SERVER_NAME"])) {
        $host .= $_SERVER["SERVER_NAME"];
        if (!($_SERVER["SERVER_PORT"] == '443' || $_SERVER["SERVER_PORT"] == '80')) {
            $host .= ':' . $_SERVER["SERVER_PORT"];
        }
    } else {
        $cookiesPath = '/';
        return '/';
    }
    
    if (isset($_SERVER['SCRIPT_NAME']) && $_SERVER['SCRIPT_NAME']) {
        $x = $_SERVER['SCRIPT_NAME'];
        $y = $blogpath;
        for ($i = 0; $i < strlen($x); $i++) {
            $f = $y . substr($x, ($i - strlen($x)));
            $z = substr($x, 0, $i);
            if (file_exists($f) && is_file($f)) {
                $z = trim($z, '/');
                $z = '/' . $z . '/';
                $z = str_replace('//', '/', $z);
                $cookiesPath = $z;

                return $host . $z;
            }
        }
    }

    if (isset($_SERVER['SCRIPT_NAME'])) {
        $x = $_SERVER['SCRIPT_NAME'];
        $y = $blogpath;
        if (isset($_SERVER["CONTEXT_DOCUMENT_ROOT"]) && isset($_SERVER["CONTEXT_PREFIX"])) {
            if ($_SERVER["CONTEXT_DOCUMENT_ROOT"] && $_SERVER["CONTEXT_PREFIX"]) {
                $y = $_SERVER["CONTEXT_DOCUMENT_ROOT"] . $_SERVER["CONTEXT_PREFIX"] . '/';
            }
        }

        $z = '';
        for ($i = strlen($x); $i > 0; $i--) {
            $z = substr($x, 0, $i);
            if (strtolower(substr($y, (strlen($y) - $i))) == strtolower($z)) {
                break;
            }
        }
        $cookiesPath = $z;
        return $host . $z;
    }

    $cookiesPath = '/';
    return $host . $cookiesPath;
}

/**
 * 通过URL获取远程页面内容.
 *
 * @param string $url URL地址
 *
 * @return string 返回页面文本内容，默认为null
 */
function GetHttpContent($url)
{
    $ajax = Network::Create();
    if (!$ajax) {
        return;
    }

    $ajax->open('GET', $url);
    $ajax->enableGzip();
    $ajax->setTimeOuts(60, 60, 0, 0);
    $ajax->send();

    return ($ajax->status == 200) ? $ajax->responseText : null;
}

/**
 * 获取目录下文件夹列表(递归).
 *
 * @param string $dir 目录
 *
 * @return array 文件夹列表(递归函数返回的是路径的全称，和非递归返回的有区别)
 */
function GetDirsInDir_Recursive($dir)
{
    $dirs = array();

    if (!file_exists($dir)) {
        return array();
    }
    if (!is_dir($dir)) {
        return array();
    }
    $dir = str_replace('\\', '/', $dir);
    if (substr($dir, -1) !== '/') {
        $dir .= '/';
    }

    if (function_exists('scandir')) {
        foreach (scandir($dir, 0) as $d) {
            if (is_dir($dir . $d)) {
                if (($d != '.') && ($d != '..')) {
                    $array = GetDirsInDir($dir . $d);
                    if (count($array) > 0) {
                        foreach ($array as $key => $value) {
                            $dirs[] = $dir . $d . '/' . $value;
                        }
                    }
                    $dirs[] = $dir . $d;
                }
            }
        }
    } else {
        $handle = opendir($dir);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_dir($dir . $file)) {
                        $array = GetDirsInDir($dir . $file);
                        if (count($array) > 0) {
                            foreach ($array as $key => $value) {
                                $dirs[] = $dir . $file . '/' . $value;
                            }
                        }
                        $dirs[] = $dir . $file;
                    }
                }
            }
            closedir($handle);
        }
    }

    return $dirs;
}

/**
 * 获取目录下指定类型文件列表(递归)..
 *
 * @param string $dir  目录
 * @param string $type 文件类型，以｜分隔
 *
 * @return array 文件列表
 */
function GetFilesInDir_Recursive($dir, $type)
{
    $dirs = GetDirsInDir_Recursive($dir);
    $files = array();
    foreach ($dirs as $key => $d) {
        $f = GetFilesInDir($d, $type);
        foreach ($f as $key => $value) {
            $files[] = $value;
        }
    }
    return $files;
}

/**
 * 获取当前目录下文件夹列表.
 *
 * @param string $dir 目录
 *
 * @return array 文件夹列表
 */
function GetDirsInDir($dir)
{
    $dirs = array();

    if (!file_exists($dir)) {
        return array();
    }
    if (!is_dir($dir)) {
        return array();
    }
    $dir = str_replace('\\', '/', $dir);
    if (substr($dir, -1) !== '/') {
        $dir .= '/';
    }

    // 此处的scandir虽然是PHP 5就已加入的内容，但必须加上兼容处理
    // 部分一键安装包的早期版本对其进行了禁用
    // 这一禁用对安全没有任何帮助，推测是早期互联网流传下来的“安全秘笈”。
    // @see: https://github.com/licess/lnmp/commit/bd34d5c803308afdac61626018e4168716d089ae#diff-6282e7667da1e2fc683bed06f87f74c1
    if (function_exists('scandir')) {
        foreach (scandir($dir, 0) as $d) {
            if (is_dir($dir . $d)) {
                if (($d != '.') && ($d != '..')) {
                    $dirs[] = $d;
                }
            }
        }
    } else {
        $handle = opendir($dir);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_dir($dir . $file)) {
                        $dirs[] = $file;
                    }
                }
            }
            closedir($handle);
        }
    }

    return $dirs;
}

/**
 * 获取当前目录下指定类型文件列表.
 *
 * @param string $dir  目录
 * @param string $type 文件类型，以｜分隔
 *
 * @return array 文件列表
 */
function GetFilesInDir($dir, $type)
{
    $files = array();
    $dir = str_replace('\\', '/', $dir);
    if (substr($dir, -1) !== '/') {
        $dir .= '/';
    }
    if (!is_dir($dir)) {
        return array();
    }

    if (function_exists('scandir')) {
        foreach (scandir($dir) as $f) {
            if ($f != "." && $f != ".." && is_file($dir . $f)) {
                foreach (explode("|", $type) as $t) {
                    $t = '.' . $t;
                    $i = strlen($t);
                    if (substr($f, -$i, $i) == $t) {
                        $sortname = substr($f, 0, (strlen($f) - $i));
                        $files[$sortname] = $dir . $f;
                        break;
                    }
                }
            }
        }
    } else {
        $handle = opendir($dir);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != "..") {
                    if (is_file($dir . $file)) {
                        foreach (explode("|", $type) as $t) {
                            $t = '.' . $t;
                            $i = strlen($t);
                            if (substr($file, -$i, $i) == $t) {
                                $sortname = substr($file, 0, (strlen($file) - $i));
                                $files[$sortname] = $dir . $file;
                                break;
                            }
                        }
                    }
                }
            }
            closedir($handle);
        }
    }

    return $files;
}

/**
 * 设置http状态头.
 *
 * @param int $number HttpStatus
 *
 * @internal param string $status 成功获取状态码设置静态参数status
 *
 * @return bool
 */
function SetHttpStatusCode($number, $force = false)
{
    static $status = '';
    if ($status != '' && $force == false) {
        return false;
    }

    $codes = array(
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',
        102 => 'Processing',

        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        207 => 'Multi-Status',

        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found', // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        306 => 'Switch Proxy',
        307 => 'Temporary Redirect',

        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',
        451 => 'Unavailable For Legal Reasons',

        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        506 => 'Variant Also Negotiates',
        507 => 'Insufficient Storage',
        508 => 'Loop Detected',
        509 => 'Bandwidth Limit Exceeded',
        510 => 'Not Extended',
    );

    if (isset($codes[$number])) {
        header('HTTP/1.1 ' . $number . ' ' . $codes[$number]);
        $status = $number;

        return true;
    }

    return false;
}

/**
 * 用script标签进行跳转.
 *
 * @param string $url 跳转链接
 */
function RedirectByScript($url)
{
    echo '<script>location.href = decodeURIComponent("' . urlencode($url) . '");</script>';
    die();
}

/**
 * 302跳转.
 *
 * @param string $url 跳转链接
 */
function Redirect($url)
{
    SetHttpStatusCode(302);
    header('Location: ' . $url);
    die();
}

/**
 * 301跳转.
 *
 * @param string $url 跳转链接
 */
function Redirect301($url)
{
    SetHttpStatusCode(301);
    header('Location: ' . $url);
    die();
}

/**
 * Http404
 */
function Http404()
{
    SetHttpStatusCode(404);
    header("Status: 404 Not Found");
}

/**
 * Http500
 */
function Http500()
{
    SetHttpStatusCode(500);
}

/**
 * Http503
 */
function Http503()
{
    SetHttpStatusCode(503);
}

/**
 * 设置304缓存头.
 *
 * @param string $filename 文件名
 * @param string $time     缓存时间
 */
function Http304($filename, $time)
{
    $url = $filename;
    $md5 = md5($url . $time);
    $etag = '"' . $md5 . '"';
    header('Last-Modified: ' . gmdate('D, d M Y H:i:s', $time) . ' GMT');
    header("ETag: $etag");
    if ((isset($_SERVER['HTTP_IF_NONE_MATCH']) && $_SERVER['HTTP_IF_NONE_MATCH'] == $etag)) {
        SetHttpStatusCode(304);
        die();
    }
}

/**
 * 获取客户端IP.
 *
 * @return string 返回IP地址
 */
function GetGuestIP()
{
    global $zbp;
    if (isset($zbp->option['ZC_USING_CDN_GUESTIP_TYPE']) && $zbp->option['ZC_USING_CDN_GUESTIP_TYPE'] != '') {
        $user_ip = GetVars($zbp->option['ZC_USING_CDN_GUESTIP_TYPE'], "SERVER");
    } else {
        $user_ip = GetVars("REMOTE_ADDR", "SERVER");
    }

    return $user_ip;
}

/**
 * 获取客户端Agent.
 *
 * @return string 返回Agent
 */
function GetGuestAgent()
{
    return GetVars("HTTP_USER_AGENT", "SERVER");
}

/**
 * 获取请求来源URL.
 *
 * @return string 返回URL
 */
function GetRequestUri()
{
    if (isset($_SERVER['HTTP_X_ORIGINAL_URL'])) {
        $url = $_SERVER['HTTP_X_ORIGINAL_URL'];
    } elseif (isset($_SERVER['HTTP_X_REWRITE_URL'])) {
        $url = $_SERVER['HTTP_X_REWRITE_URL'];
        if (strpos($url, '?') !== false) {
            $queries = GetValueInArray(explode('?', $url), '1');
            foreach (explode('&', $queries) as $query) {
                $name = GetValueInArray(explode('=', $query), '0');
                $value = GetValueInArray(explode('=', $query), '1');
                $name = urldecode($name);
                $value = urldecode($value);
                if (!isset($_GET[$name])) {
                    $_GET[$name] = $value;
                }

                if (!isset($_GET[$name])) {
                    $_REQUEST[$name] = $value;
                }
            }
        }
    } elseif (isset($_SERVER['REQUEST_URI'])) {
        $url = $_SERVER['REQUEST_URI'];
    } elseif (isset($_SERVER['REDIRECT_URL'])) {
        $url = $_SERVER['REDIRECT_URL'];
        if (isset($_SERVER['REDIRECT_QUERY_STRIN'])) {
            $url .= '?' . $_SERVER['REDIRECT_QUERY_STRIN'];
        }
    } else {
        $url = $_SERVER['PHP_SELF'] . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : '');
    }

    return $url;
}

/**
 * 获取文件后缀名.
 *
 * @param string $f 文件名
 *
 * @return string 返回小写的后缀名
 */
function GetFileExt($f)
{
    if (strpos($f, '.') === false) {
        return '';
    }

    $a = explode('.', $f);

    return strtolower(end($a));
}

/**
 * 获取文件权限.
 *
 * @param string $f 文件名
 *
 * @return string|null 返回文件权限，数值格式，如0644
 */
function GetFilePermsOct($f)
{
    if (!file_exists($f)) {
        return;
    }

    return substr(sprintf('%o', fileperms($f)), -4);
}

/**
 * 获取文件权限.
 *
 * @param string $f 文件名
 *
 * @return string|null 返回文件权限，字符表达格式，如-rw-r--r--
 */
function GetFilePerms($f)
{
    if (!file_exists($f)) {
        return;
    }

    $perms = fileperms($f);
    switch ($perms & 0xF000) {
        case 0xC000: // socket
            $info = 's';
            break;
        case 0xA000: // symbolic link
            $info = 'l';
            break;
        case 0x8000: // regular
            $info = '-';
            break;
        case 0x6000: // block special
            $info = 'b';
            break;
        case 0x4000: // directory
            $info = 'd';
            break;
        case 0x2000: // character special
            $info = 'c';
            break;
        case 0x1000: // FIFO pipe
            $info = 'p';
            break;
        default: // unknown
            $info = 'u';
    }

    // Owner
    $info .= (($perms & 0x0100) ? 'r' : '-');
    $info .= (($perms & 0x0080) ? 'w' : '-');
    $info .= (($perms & 0x0040) ? (($perms & 0x0800) ? 's' : 'x') : (($perms & 0x0800) ? 'S' : '-'));

    // Group
    $info .= (($perms & 0x0020) ? 'r' : '-');
    $info .= (($perms & 0x0010) ? 'w' : '-');
    $info .= (($perms & 0x0008) ? (($perms & 0x0400) ? 's' : 'x') : (($perms & 0x0400) ? 'S' : '-'));

    // Other
    $info .= (($perms & 0x0004) ? 'r' : '-');
    $info .= (($perms & 0x0002) ? 'w' : '-');
    $info .= (($perms & 0x0001) ? (($perms & 0x0200) ? 't' : 'x') : (($perms & 0x0200) ? 'T' : '-'));

    return $info;
}

/**
 * 向字符串型的参数表加入一个新参数.
 *
 * @param string $s    字符串型的参数表，以|符号分隔
 * @param string $name 参数名
 *
 * @return string 返回新字符串，以|符号分隔
 */
function AddNameInString($s, $name)
{
    $pl = $s;
    $name = (string) $name;
    $apl = explode('|', $pl);
    if (in_array($name, $apl) == false) {
        $apl[] = $name;
    }
    $pl = trim(implode('|', $apl), '|');

    return $pl;
}

/**
 * 从字符串型的参数表中删除一个参数.
 *
 * @param string $s    字符串型的参数表，以|符号分隔
 * @param string $name 参数名
 *
 * @return string 返回新字符串，以|符号分隔
 */
function DelNameInString($s, $name)
{
    $pl = $s;
    $name = (string) $name;
    $apl = explode('|', $pl);
    $count = count($apl);
    for ($i = 0; $i < $count; $i++) {
        if ($apl[$i] == $name) {
            unset($apl[$i]);
        }
    }
    $pl = trim(implode('|', $apl), '|');

    return $pl;
}

/**
 * 在字符串参数值查找参数.
 *
 * @param string $s    字符串型的参数表，以|符号分隔
 * @param string $name 参数名
 *
 * @return bool
 */
function HasNameInString($s, $name)
{
    $pl = $s;
    $name = (string) $name;
    $apl = explode('|', $pl);

    return in_array($name, $apl);
}

/**
 * 以JSON形式输出错误信息（用于ShowError接口）.
 *
 * @param $errorCode
 * @param $errorString
 * @param $file
 * @param $line
 */
function JsonError4ShowErrorHook($errorCode, $errorString, $file, $line)
{
    if ($errorCode === 0) {
        $errorCode = 1;
    }
    JsonError($errorCode, $errorString, null);
}

/**
 * 以JSON形式输出错误信息.(err code为(int)0认为是没有错误，所以把0转为1)
 *
 * @param string $errorCode   错误编号
 * @param string $errorString 错误内容
 * @param object $data 具体内容
 */
function JsonError($errorCode, $errorString, $data)
{
    $exit = true;
    if ($errorCode === 0) {
        $exit = false;
    }
    $result = array(
        'data' => $data,
        'err'  => array(
            'code' => $errorCode,
            'msg'  => $errorString,
            //'runtime' => RunTime(),
            'timestamp' => time(),
        ),
    );
    @ob_clean();
    echo json_encode($result);
    if ($exit) {
        exit;
    }
}

/**
 * 当代码正常运行时，以JSON形式输出信息.
 *
 * @param object 待返回内容
 */
function JsonReturn($data)
{
    JsonError(0, '', $data);
}

/**
 * XML-RPC应答错误页面.
 *
 * @param $errorCode
 * @param $errorString
 * @param $file
 * @param $line
 *
 * @return void
 */
function RespondError($errorCode, $errorString = '', $file = '', $line = '')
{
    $strXML = '<?xml version="1.0" encoding="UTF-8"?><methodResponse><fault><value><struct><member><name>faultCode</name><value><int>$1</int></value></member><member><name>faultString</name><value><string>$2</string></value></member></struct></value></fault></methodResponse>';
    $strError = $strXML;
    $strError = str_replace("$1", FormatString($errorCode, "[html-format]"), $strError);
    $strError = str_replace("$2", FormatString($errorString, "[html-format]"), $strError);

    ob_clean();
    echo $strError;
    exit;
}

/**
 * XML-RPC脚本错误页面.
 *
 * @param string $errorCode 错误提示字符串
 * @param string $errorText
 * @param string $file
 * @param string $line
 *
 * @return void
 */
function ScriptError($errorCode, $errorText = '', $file = '', $line = '')
{
    header('Content-type: application/x-javascript; Charset=utf-8');
    ob_clean();
    echo 'alert("' . str_replace('"', '\"', $errorCode . ':' . $errorText) . '")';
    die();
}

/**
 *  验证字符串是否符合正则表达式.
 *
 * @param string $source 字符串
 * @param string $para   正则表达式，可用[username]|[password]|[email]|[homepage]或自定义表达式
 *
 * @return bool
 */
function CheckRegExp($source, $para)
{
    if (strpos($para, '[username]') !== false) {
        $para = "/^[\.\_A-Za-z0-9·@\x{4e00}-\x{9fa5}]+$/u";
    } elseif (strpos($para, '[nickname]') !== false) {
        $para = '/([^\x{01}-\x{1F}\x{80}-\x{FF}\/:\\~&%;@\'"?<>|#$\*}{,\+=\[\]\(\)\{\}\t\r\n\p{C}])/u';
    } elseif (strpos($para, '[password]') !== false) {
        $para = "/^[A-Za-z0-9`~!@#\$%\^&\*\-_\?]+$/u";
    } elseif (strpos($para, '[email]') !== false) {
        $para = "/^([0-9a-zA-Z]([-.\w]*[0-9a-zA-Z])*@([0-9a-zA-Z][-\w]*\.)+[a-zA-Z]*)$/u";
    } elseif (strpos($para, '[homepage]') !== false) {
        $para = "/^[a-zA-Z]+:\/\/[a-zA-Z0-9\_\-\.\&\?\/:=#\x{4e00}-\x{9fa5}]+$/u";
    } elseif (!$para) {
        return false;
    }

    return (bool) preg_match($para, $source);
}

/**
 *  格式化字符串.
 *
 * @param string $source 字符串
 * @param string $para   正则表达式，可用[html-format]|[nohtml]|[noscript]|[enter]|[noenter]|[filename]|[normalname]或自定义表达式
 *
 * @return string
 */
function FormatString($source, $para)
{
    if (strpos($para, '[html-format]') !== false) {
        $source = htmlspecialchars($source);
    }

    if (strpos($para, '[nohtml]') !== false) {
        $source = preg_replace("/<([^<>]*)>/si", "", $source);
        $source = str_replace("<", "˂", $source);
        $source = str_replace(">", "˃", $source);
    }

    if (strpos($para, '[noscript]') !== false) {
        $class  = new XssHtml($source);
        $source = trim($class->getHtml());
    }
    if (strpos($para, '[enter]') !== false) {
        $source = str_replace("\r\n", "<br/>", $source);
        $source = str_replace("\n", "<br/>", $source);
        $source = str_replace("\r", "<br/>", $source);
        $source = preg_replace("/(<br\/>)+/", "<br/>", $source);
    }
    if (strpos($para, '[noenter]') !== false) {
        $source = str_replace("\r\n", "", $source);
        $source = str_replace("\n", "", $source);
        $source = str_replace("\r", "", $source);
    }
    if (strpos($para, '[filename]') !== false) {
        $source = str_replace(array("/", "#", "$", "\\", ":", "?", "*", "\"", "<", ">", "|", " "), array(""), $source);
    }
    if (strpos($para, '[normalname]') !== false) {
        $source = str_replace(array("#", "$", "(", ")", "*", "+", "[", "]", "{", "}", "?", "\\", "^", "|", ":", "'", "\"", ";", "@", "~", "=", "%", "&"), array(""), $source);
    }

    return $source;
}

/**
 * 格式化字符串
 *
 * @param string $source
 * @param string $param
 *
 * @Deprecated
 **/
function TransferHTML($source, $param)
{
    return FormatString($source, $param);
}

/**
 *  封装HTML标签.
 *
 * @param string $html html源码
 *
 * @return string
 */
function CloseTags($html)
{
    preg_match_all('#<(?!meta|img|br|hr|input\b)\b([a-z]+)(?: .*)?(?<![/|/ ])>#iU', $html, $result);
    $openedtags = $result[1];
    preg_match_all('#</([a-z]+)>#iU', $html, $result);
    $closedtags = $result[1];
    $len_opened = count($openedtags);
    if (count($closedtags) == $len_opened) {
        return $html;
    }
    $openedtags = array_reverse($openedtags);
    for ($i = 0; $i < $len_opened; $i++) {
        if (!in_array($openedtags[$i], $closedtags)) {
            $html .= '</' . $openedtags[$i] . '>';
        } else {
            unset($closedtags[array_search($openedtags[$i], $closedtags)]);
        }
    }
    return $html;
}

/**
 * 获取UTF8格式的字符串的子串.
 *
 * @param string $sourcestr 源字符串
 * @param int    $start     起始位置
 *
 * @return string
 */
function SubStrUTF8_Start($sourcestr, $start)
{
    $args = func_get_args();
    if (function_exists('mb_substr') && function_exists('mb_internal_encoding')) {
        mb_internal_encoding('UTF-8');
        return call_user_func_array('mb_substr', $args);
    }

    if (function_exists('iconv_substr') && function_exists('iconv_set_encoding')) {
        call_user_func('iconv_set_encoding', 'internal_encoding', "UTF-8");
        call_user_func('iconv_set_encoding', 'output_encoding', "UTF-8");

        return call_user_func_array('iconv_substr', $args);
    }

    return call_user_func_array('substr', $args);
}

/**
 *  获取UTF8格式的字符串的子串.
 *
 * @param string $sourcestr 源字符串
 * @param int    $cutlength 子串长度
 *
 * @return string
 */
function SubStrUTF8($sourcestr, $cutlength)
{
    if (function_exists('mb_substr') && function_exists('mb_internal_encoding')) {
        mb_internal_encoding('UTF-8');

        return mb_substr($sourcestr, 0, $cutlength);
    }

    if (function_exists('iconv_substr') && function_exists('iconv_set_encoding')) {
        call_user_func('iconv_set_encoding', 'internal_encoding', "UTF-8");
        call_user_func('iconv_set_encoding', 'output_encoding', "UTF-8");

        return iconv_substr($sourcestr, 0, $cutlength);
    }

    $ret = '';
    $i = 0;
    $n = 0;

    $str_length = strlen($sourcestr); //字符串的字节数

    while (($n < $cutlength) && ($i <= $str_length)) {
        $temp_str = substr($sourcestr, $i, 1);
        $ascnum = ord($temp_str); //得到字符串中第$i位字符的ascii码
        if ($ascnum >= 224) { //如果ASCII位高与224，
            $ret = $ret . substr($sourcestr, $i, 3); //根据UTF-8编码规范，将3个连续的字符计为单个字符
            $i = ($i + 3); //实际Byte计为3
            $n++; //字串长度计1
        } elseif ($ascnum >= 192) { //如果ASCII位高与192，
            $ret = $ret . substr($sourcestr, $i, 2); //根据UTF-8编码规范，将2个连续的字符计为单个字符
            $i = ($i + 2); //实际Byte计为2
            $n++; //字串长度计1
        } elseif ($ascnum >= 65 && $ascnum <= 90) { //如果是大写字母，
            $ret = $ret . substr($sourcestr, $i, 1);
            $i = ($i + 1); //实际的Byte数仍计1个
            $n++; //但考虑整体美观，大写字母计成一个高位字符
        } else {
            //其他情况下，包括小写字母和半角标点符号，

            $ret = $ret . substr($sourcestr, $i, 1);
            $i = ($i + 1); //实际的Byte数计1个
            $n = ($n + 0.5); //小写字母和半角标点等与半个高位字符宽...
        }
        /*
        if ($str_length > $cutlength) {
            $ret = $ret;
        }
        */
    }

    return $ret;
}

/**
 *  ZBP版获取UTF8格式的字符串的子串.
 *
 * @param string $sourcestr
 * @param int    $start
 *
 * @return string
 */
function Zbp_SubStr($sourcestr, $start)
{
    $args = func_get_args();
    return call_user_func_array('SubStrUTF8_Start', $args);
}

/**
 *  ZBP版StrLen.
 *
 * @param string $string
 *
 * @return string
 */
function Zbp_StrLen($string)
{
    if (function_exists('grapheme_strlen')) {
        return grapheme_strlen($string);
    }
    if (function_exists('mb_strlen') && function_exists('mb_internal_encoding')) {
        mb_internal_encoding('UTF-8');
        return mb_strlen($string);
    }
    if (function_exists('iconv_strlen') && function_exists('iconv_set_encoding')) {
        call_user_func('iconv_set_encoding', 'internal_encoding', "UTF-8");
        call_user_func('iconv_set_encoding', 'output_encoding', "UTF-8");
        return iconv_strlen($string);
    }
    return strlen($string);
}

/**
 *  ZBP版Strpos
 *
 * @param string $haystack
 * @param string $needle
 * @param int $offset
 *
 * @return string
 */
function Zbp_Strpos($haystack, $needle, $offset = 0)
{
    if (function_exists('mb_strpos') && function_exists('mb_internal_encoding')) {
        mb_internal_encoding('UTF-8');
        return mb_strpos($haystack, $needle, $offset);
    }
    if (function_exists('iconv_strpos') && function_exists('iconv_set_encoding')) {
        call_user_func('iconv_set_encoding', 'internal_encoding', "UTF-8");
        call_user_func('iconv_set_encoding', 'output_encoding', "UTF-8");
        return iconv_strpos($haystack, $needle, $offset);
    }
    return strpos($haystack, $needle, $offset);
}

/**
 *  ZBP版Stripos
 *
 * @param string $haystack
 * @param string $needle
 * @param int $offset
 *
 * @return string
 */
function Zbp_Stripos($haystack, $needle, $offset = 0)
{
    if (function_exists('mb_strpos') && function_exists('mb_internal_encoding')) {
        mb_internal_encoding('UTF-8');
        return mb_stripos($haystack, $needle, $offset);
    }
    if (function_exists('iconv_strpos') && function_exists('iconv_set_encoding')) {
        call_user_func('iconv_set_encoding', 'internal_encoding', "UTF-8");
        call_user_func('iconv_set_encoding', 'output_encoding', "UTF-8");
        $haystack = strtolower($haystack);
        $needle = strtolower($needle);
        return iconv_strpos($haystack, $needle, $offset);
    }
    return stripos($haystack, $needle, $offset);
}

/**
 * 截取HTML格式的UTF8格式的字符串的子串.
 *
 * @param string $source 源字符串
 * @param int    $length 子串长度
 *
 * @return string
 */
function SubStrUTF8_Html($source, $length)
{
    if (function_exists('mb_substr') && function_exists('mb_internal_encoding')) {
        mb_internal_encoding('UTF-8');
        $j = mb_strlen($source);
        $s = mb_substr($source, 0, $length);
        $l = mb_substr_count($s, '<');
        $r = mb_substr_count($s, '>');
        if ($l > 0 && $l > $r) {
            for ($i = $length; $i < $j; $i++) {
                $s .= mb_substr($source, $i, 1);
                if (mb_substr($source, $i, 1) == '>') {
                    break;
                }
            }
        }

        return $s;
    }

    if (function_exists('iconv_substr') && function_exists('iconv_set_encoding')) {
        call_user_func('iconv_set_encoding', 'internal_encoding', "UTF-8");
        call_user_func('iconv_set_encoding', 'output_encoding', "UTF-8");
        $j = iconv_strlen($source);
        $s = iconv_substr($source, 0, $length);
        $l = substr_count($s, '<');
        $r = substr_count($s, '>');
        if ($l > 0 && $l > $r) {
            for ($i = $length; $i < $j; $i++) {
                $s .= iconv_substr($source, $i, 1);
                if (iconv_substr($source, $i, 1) == '>') {
                    break;
                }
            }
        }

        return $s;
    }

    $j = strlen($source);
    $s = substr($source, 0, $length);
    $l = substr_count($s, '<');
    $r = substr_count($s, '>');
    if ($l > 0 && $l > $r) {
        for ($i = $length; $i < $j; $i++) {
            $s .= substr($source, $i, 1);
            if (substr($source, $i, 1) == '>') {
                break;
            }
        }
    }

    return $s;
}

/**
 * 删除文件BOM头.
 *
 * @param string $s 文件内容
 *
 * @return string
 */
function RemoveBOM($s)
{
    $charset = array();
    $charset[1] = substr($s, 0, 1);
    $charset[2] = substr($s, 1, 1);
    $charset[3] = substr($s, 2, 1);
    if (ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
        $s = substr($s, 3);
    }

    return $s;
}

/**
 * 获取指定时区名.
 *
 * @param int $z 时区号
 *
 * @return string 时区名
 *
 * @since 1.3.140614
 */
function GetTimeZoneByGMT($z)
{
    $timezones = array(
        -12 => 'Etc/GMT+12',
        -11 => 'Pacific/Midway',
        -10 => 'Pacific/Honolulu',
        -9  => 'America/Anchorage',
        -8  => 'America/Los_Angeles',
        -7  => 'America/Denver',
        -6  => 'America/Tegucigalpa',
        -5  => 'America/New_York',
        -4  => 'America/Halifax',
        -3  => 'America/Argentina/Buenos_Aires',
        -2  => 'Atlantic/South_Georgia',
        -1  => 'Atlantic/Azores',
        0   => 'UTC',
        1   => 'Europe/Berlin',
        2   => 'Europe/Sofia',
        3   => 'Africa/Nairobi',
        4   => 'Europe/Moscow',
        5   => 'Asia/Karachi',
        6   => 'Asia/Dhaka',
        7   => 'Asia/Bangkok',
        8   => 'Asia/Shanghai',
        9   => 'Asia/Tokyo',
        10  => 'Pacific/Guam',
        11  => 'Australia/Sydney',
        12  => 'Pacific/Fiji',
        13  => 'Pacific/Tongatapu',
    );
    if (!isset($timezones[$z])) {
        return 'UTC';
    }

    return $timezones[$z];
}

/**
 * 对数组内的字符串进行htmlspecialchars.
 *
 * @param array $array 待过滤字符串
 *
 * @return array
 *
 * @since 1.4
 */
function htmlspecialchars_array($array)
{
    $newArray = array();
    foreach ($array as $key => $value) {
        $newKey = htmlspecialchars($key);
        if (is_array($value)) {
            $newArray[$newKey] = htmlspecialchars_array($value);
        } elseif (is_string($value)) {
            $newArray[$newKey] = htmlspecialchars($value);
        }
    }

    return $newArray;
}

/**
 * 获得一个只含数字字母和-线的string.
 *
 * @param string $s 待过滤字符串
 *
 * @return string|string[]
 *
 * @since 1.4
 */
function FilterCorrectName($s)
{
    return preg_replace('|[^0-9a-zA-Z_/-]|', '', $s);
}

/**
 * 确认一个对象是否可被转换为string.
 *
 * @param object $obj
 *
 * @return bool
 *
 * @since 1.4
 */
function CheckCanBeString($obj)
{
    // Fuck PHP 5.2!!!!
    // return $obj === null || is_scalar($obj) || is_callable([$obj, '__toString']);
    if (is_object($obj) && method_exists($obj, '__toString')) {
        return true;
    }

    if ($obj === null) {
        return true;
    }

    return is_scalar($obj);
}

/**
 * 构造带Token的安全URL.
 *
 * @param string $url
 * @param string $appId 应用ID，可以生成一个应用专属的Token
 *
 * @return string
 *
 * @since 1.5.2
 */
function BuildSafeURL($url, $appId = '')
{
    global $zbp;
    if (strpos($url, '?') !== false) {
        $url .= '&csrfToken=';
    } else {
        $url .= '?csrfToken=';
    }
    if (substr($url, 0, 1) === '/') {
        $url = $zbp->host . substr($url, 1);
    }
    $url = $url . $zbp->GetCSRFToken($appId);

    return $url;
}

/**
 * 构造cmd.php的访问链接.
 *
 * @param string $paramters cmd.php参数
 *
 * @return bool
 *
 * @since 1.5.2
 */
function BuildSafeCmdURL($paramters)
{
    return BuildSafeURL('/zb_system/cmd.php?' . $paramters);
}

/**
 * 实现utf84mb4的过滤
 *
 * @param string $sql
 *
 * @return string
 */
function utf84mb_filter(&$sql)
{
    $sql = preg_replace_callback("/[\x{10000}-\x{10FFFF}]/u", 'utf84mb_convertToUCS4', $sql);
}

/**
 * 实现utf84mb的fixHtmlSpecialChars
 *
 * @return string
 */
function utf84mb_fixHtmlSpecialChars()
{
    global $article;
    $article->Content = preg_replace_callback("/\&\#x([0-9A-Z]{2,6})\;/u", 'utf84mb_convertToUTF8', $article->Content);
    $article->Intro = preg_replace_callback("/\&\#x([0-9A-Z]{2,6})\;/u", 'utf84mb_convertToUTF8', $article->Intro);
}

/**
 * 实现utf84mb的convertToUCS4
 *
 * @param string $matches
 *
 * @return string
 */
function utf84mb_convertToUCS4($matches)
{
    return sprintf("&#x%s;", ltrim(strtoupper(bin2hex(iconv('UTF-8', 'UCS-4', $matches[0]))), "0"));
}

/**
 * 实现utf84mb的convertToUTF8
 *
 * @param string $matches
 *
 * @return string
 */
function utf84mb_convertToUTF8($matches)
{
    return iconv('UCS-4', 'UTF-8', hex2bin(str_pad($matches[1], 8, "0", STR_PAD_LEFT)));
}

/**
 * 验证Web Token是否合法.
 *
 * @param $webTokenString
 * @param $webTokenId
 * @param string $key
 *
 * @return bool
 */
function VerifyWebToken($webTokenString, $webTokenId, $key = '')
{
    global $zbp;
    $args = array();
    for ($i = 3; $i < func_num_args(); $i++) {
        $args[] = func_get_arg($i);
    }
    $time = substr($webTokenString, 64);
    $wt = substr($webTokenString, 0, 64);
    if ($key == '') {
        $key = $zbp->guid;
    }
    $sha = hash_hmac('sha256', $time . $webTokenId . implode($args), $key);
    if ($wt === $sha) {
        if ($time > time()) {
            return true;
        }
    }

    return false;
}

/**
 * 创建Web Token.
 *
 * @param $webTokenId
 * @param $time
 * @param string $key
 *
 * @return string
 */
function CreateWebToken($webTokenId, $time, $key = '')
{
    global $zbp;
    $args = array();
    for ($i = 3; $i < func_num_args(); $i++) {
        $args[] = func_get_arg($i);
    }
    if ($key == '') {
        $key = $zbp->guid;
    }
    $time = (int) $time;
    return hash_hmac('sha256', $time . $webTokenId . implode($args), $key) . $time;
}

/**
 * 检测来源是否合法，这包括CSRF检测，在开启增强安全模式时加入来源检测.
 *
 * @throws Exception
 */
function CheckIsRefererValid()
{
    global $zbp;
    $flag = CheckCSRFTokenValid();
    if ($flag && $zbp->option['ZC_ADDITIONAL_SECURITY']) {
        $flag = CheckHTTPRefererValid();
    }

    if (!$flag) {
        $zbp->ShowError(5, __FILE__, __LINE__);
        exit;
    }
}

/**
 * 验证CSRF Token是否合法.
 *
 * @param string $fieldName
 * @param array  $methods
 *
 * @return bool
 */
function CheckCSRFTokenValid($fieldName = 'csrfToken', $methods = array('get', 'post'))
{
    global $zbp;
    $flag = false;
    if (is_string($methods)) {
        $methods = array($methods);
    }
    foreach ($methods as $method) {
        if ($zbp->VerifyCSRFToken(GetVars($fieldName, $method))) {
            $flag = true;
            break;
        }
    }

    return $flag;
}

/**
 * 检测HTTP Referer是否合法.
 *
 * @return bool
 */
function CheckHTTPRefererValid()
{
    global $bloghost;
    $referer = GetVars('HTTP_REFERER', 'SERVER');
    if (trim($referer) === '') {
        return true;
    }
    $s = $bloghost;
    $s = str_replace(':80/', '/', $s);
    $s = str_replace(':443/', '/', $s);
    if (stripos($referer, $s) === false) {
        return false;
    }

    return true;
}

/**
 * 清除一串代码内所有的PHP代码
 *
 * @param string $code
 *
 * @return string
 */
function RemovePHPCode($code)
{
    // PHP Start tags: <?php <? <?=
    // PHP 5 supports: <% <script language="php">
    // Depends on PHP
    $continue = true;
    while ($continue) {
        $tokens = token_get_all($code);
        $continue = false;
        foreach ($tokens as $tt) {
            $name = is_numeric($tt[0]) ? token_name($tt[0]) : '';
            if ($name === 'T_OPEN_TAG' || $name === 'T_OPEN_TAG_WITH_ECHO' || $name === 'T_CLOSE_TAG') {
                $code = str_replace($tt[1], "", $code);
                $continue = true;
            }
        }
    }

    return $code;
}

/**
 * 拿到ID数组byList列表
 *
 * @param array $array
 * @param string $keyname
 *
 * @return array
 */
function GetIDArrayByList($array, $keyname = null)
{
    $ids = array();
    foreach ($array as $key => $value) {
        if ($keyname == null) {
            $ids[] = reset($value->GetData());
        } else {
            $a = $value->GetData();
            $ids[] = $a[$keyname];
        }
    }

    return $ids;
}

/**
 * 拿到后台的CSP Heaeder
 *
 * @return string
 */
function GetBackendCSPHeader()
{
    $defaultCSP = array(
        'default-src' => "'self' data: blob:",
        'img-src'     => "* data: blob:",
        'media-src'   => "* data: blob:",
        'script-src'  => "'self' 'unsafe-inline' 'unsafe-eval'",
        'style-src'   => "'self' 'unsafe-inline'",
    );
    foreach ($GLOBALS['hooks']['Filter_Plugin_CSP_Backend'] as $fpname => &$fpsignal) {
        $fpreturn = $fpname($defaultCSP);
    }
    $ret = array();
    foreach ($defaultCSP as $key => $value) {
        $ret[] = $key . ' ' . $value;
    }

    return implode('; ', $ret);
}

/**
 * 检查重复加载的.
 *
 * @param string $file
 *
 * @return boolean
 */
function CheckIncludedFiles($file)
{
    $a = get_included_files();
    $file = str_replace('\\', '/', $file);
    foreach ($a as $key => $value) {
        $a[$key] = trim(str_replace('\\', '/', $value));
    }

    return in_array(trim($file), $a);
}

/**
 * Logs指定的变量的值
 */
function Logs_Dump()
{
    $a = func_get_args();
    foreach ($a as $key => $value) {
        $s = var_export($value, true);
        Logs($s);
    }
}

/**
 * 中文与特殊字符友好的 JSON 编码.
 *
 * @param array $arr
 *
 * @return string
 */
function JsonEncode($arr)
{
    RecHtmlSpecialChars($arr);

    if (version_compare(PHP_VERSION, '5.4.0', '<')) {
        return str_ireplace(
            '\\/',
            '/',
            preg_replace_callback(
                '#\\\u([0-9a-f]{4})#i',
                'Ucs2Utf8',
                json_encode($arr)
            )
        );
    } else {
        return call_user_func('json_encode', $arr, (JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    }
}

/**
 * UCS-2BE 转 UTF-8，解决 JSON 中文转码问题.
 *
 * @param $matchs
 *
 * @return false|string
 */
function Ucs2Utf8($matchs)
{
    return iconv('UCS-2BE', 'UTF-8', pack('H4', $matchs[1]));
}

/**
 * 递归转义 HTML 实体.
 *
 * @param array $arr
 */
function RecHtmlSpecialChars(&$arr)
{
    if (is_array($arr)) {
        foreach ($arr as &$value) {
            if (is_array($value)) {
                RecHtmlSpecialChars($value);
            } elseif (is_string($value)) {
                $value = htmlspecialchars($value);
            }
        }
    }
}

/**
 * 判断数组是否已经有$key了，如果没有就set一次$default
 */
function Array_Isset(&$array, $key, $default)
{
    if (!array_key_exists($key, $array)) {
        $array[$key] = $default;
    }
    return true;
}

/**
 * 从 HTML 中获取所有图片.
 *
 * @param  string $html
 * @return array
 */
function GetImagesFromHtml($html)
{
    $pattern = "/<img.*?src=[\\'|\"](.*?)[\\'|\"].*?[\\/]?>/i";
    preg_match_all($pattern, $html, $matches);
    return is_array($matches[1]) ? array_unique($matches[1]) : array();
}

/**
 * 判断 URL 是否为本地.
 *
 * @return array
 */
function CheckUrlIsLocal($url)
{
    global $zbp;

    return substr($url, 0, strlen($zbp->host)) === $zbp->host;
}

/**
 * 把 URL 中的 Host 转换为本地路径.
 *
 * @param string $url
 * @return string
 */
function UrlHostToPath($url)
{
    global $zbp;

    if (! CheckUrlIsLocal($url)) {
        return $url;
    }

    return ZBP_PATH . substr($url, strlen($zbp->host));
}
