<?php

if (!defined('ZBP_PATH')) {
    exit('Access denied');
}
/**
 * zbp全局操作类.
 */
class ZBlogPHP
{

    private static $private_zbp = null;

    /**
     * @var string 版本号
     */
    public $version = null;

    /**
     * @var Database__Interface 数据库
     */
    public $db = null;

    /**
     * @var array 配置选项
     */
    public $option = array();

    /**
     * @var array 语言
     */
    public $lang = array();

    /**
     * @var json类型 语言
     */
    public $langs = null;

    /**
     * @var array 语言包list
     */
    public $langpacklist = array();

    /**
     * @var string 路径
     */
    public $path = null;

    /**
     * @var string 域名
     */
    public $host = null;

    /**
     * @var string cookie作用域
     */
    public $cookiespath = null;

    /**
     * @var string guid
     */
    public $guid = null;

    /**
     * @var string 当前链接
     */
    public $currenturl = null;

    /**
     * @var string 当前链接
     */
    public $fullcurrenturl = null;

    /**
     * @var string System目录
     */
    public $systemdir = null;

    /**
     * @var string Admin目录
     */
    public $admindir = null;

    /**
     * @var string 用户目录
     */
    public $usersdir = null;

    /**
     * @var string System Url
     */
    public $systemurl = null;

    /**
     * @var string Admin Url
     */
    public $adminurl = null;

    /**
     * @var string 用户Url
     */
    public $usersurl = null;

    /**
     * @var string Cache目录
     */
    public $cachedir = null;

    /**
     * @var string Logs目录
     */
    public $logsdir = null;

    /**
     * @var string Data目录
     */
    public $datadir = null;

    /**
     * @var string 验证码地址
     */
    public $verifyCodeUrl = null;

    /**
     * @var string 验证码地址（拼写错误）
     *
     * @deprecated
     */
    public $validcodeurl = null;

    /**
     * @var string
     */
    public $feedurl = null;

    /**
     * @var string
     */
    public $searchurl = null;

    /**
     * @var string
     */
    public $ajaxurl = null;

    /**
     * @var string
     */
    public $xmlrpcurl = null;

    /**
     * @var string
     */
    public $apiurl = null;

    /**
     * @var string
     */
    public $cmdurl = null;

    /**
     * @var Member[] 用户数组
     */
    public $members = array();

    /**
     * @var Member[] 用户数组（以用户名为键）
     */
    public $membersbyname = array();

    /**
     * @var Category[] 分类数组 ($categorys已废弃)
     */
    public $categorys = array();

    public $categories = null;

    /**
     * @var Category[] 分类数组ALL
     */
    public $categories_all = array();

    /**
     * @var Category[] 分类数组（已排序） ($categorysbyorder已废弃)  categories引用
     */
    public $categorysbyorder = array();

    public $categoriesbyorder = null;

    /**
     * @var Category[] 按类型分类的2维数组 //本身无意义了，现引用自$categoriesbyorder_type
     */
    public $categories_type = array();

    /**
     * @var Category[] 按类型分类2维数组（已排序）categories_type引用
     */
    public $categoriesbyorder_type = array();

    /**
     * @var Module[] 模块数组
     */
    public $modules = array();

    /**
     * @var Module[] 模块数组（以文件名为键）
     */
    public $modulesbyfilename = array();

    /**
     * @var Config[] 配置选项
     */
    public $configs = array();

    /**
     * @var Tag[] 标签数组
     */
    public $tags = array();

    /**
     * @var Tag[] 标签数组（以标签名为键）
     */
    public $tagsbyname = array();

    /**
     * @var array 标签数组 ALL
     */
    public $tags_all = array();

    /**
     * @var array 标签数组 By Type 2维数组
     */
    public $tags_type = array();

    /**
     * @var array 标签数组 By Type（以标签名为键）2维数组
     */
    public $tagsbyname_type = array();

    /**
     * @var Comment[] 评论数组
     */
    public $comments = array();

    /**
     * @var Post[] 文章列表数组
     */
    public $posts = array();

    /**
     * @var string 当前页面标题
     */
    public $title = null;

    /**
     * @var string 网站名
     */
    public $name = null;

    /**
     * @var string 网站子标题
     */
    public $subname = null;

    /**
     * @var App 当前主题
     */
    public $theme = null;

    /**
     * @var array() 当前主题版本信息
     */
    public $themeinfo = array();

    /**
     * @var string 当前主题风格
     */
    public $style = null;

    /**
     * @var Member 当前用户
     */
    public $user = null;

    /**
     * @var Config 缓存
     */
    public $cache = null;

    /**
     * @var array|null 数据表
     */
    public $table = null;

    public $t = null;

    /**
     * @var array|null 数据表信息
     */
    public $datainfo = null;

    public $d = null;

    /**
     * @var array|null 类型序列
     */
    public $posttype = null;

    /**
     * @var array|null 操作列表
     */
    public $actions = null;

    /**
     * @var mixed|null|string 当前操作
     */
    public $action = null;

    private $isinitialized = false; //是否初始化成功

    private $isconnected = false; //是否连接成功

    private $isload = false; //是否载入

    private $ispreload = false; //是否预加载

    private $issession = false; //是否使用session

    public $ismanage = false; //是否加载管理模式

    public $isapi = false; //是否加载API模式

    public $iscmd = false; //是否加载CMD模式

    public $isajax = false; //是否加载AJAX模式

    public $isxmlrpc = false; //是否加载XML-RPC模式

    public $ishttps = false; //链接至isHttps

    public $isHttps = false; //是否HTTPS

    public $isdebug = false; //是否Debug Mode

    public $islogin = false; //是否Login

    /**
     * @var Template 当前模板
     */
    public $template = null;

    /**
     * @var null 社会化评论
     */
    public $socialcomment = null;

    /**
     * @var null 模板头部
     */
    public $header = null;

    /**
     * @var null 模板尾部
     */
    public $footer = null;

    /**
     * @var array 激活的插件列表
     */
    public $activedapps = array();

    public $activeapps;

    public $cookie_tooken_httponly = true;

    /**
     * @var int 管理页面显示条数
     */
    public $managecount = 50;

    /**
     * @var int 页码显示条数
     */
    public $pagebarcount = 10;

    /**
     * @var int 搜索返回条数
     */
    public $searchcount = 10;

    /**
     * @var int 文章列表显示条数
     */
    public $displaycount = 10;

    /**
     * @var int 评论显示数量
     */
    public $commentdisplaycount = 10;

    /**
     * @var int API 每页最多条数
     */
    public $apiMaxCountPerPage = 50;

    /**
     * @var int 当前实例下CSRF Token过期时间（小时）
     */
    public $csrfExpiration = 1;

    /**
     * @var App 当前主题类
     */
    public $themeapp = null;

    /**
     * @var 分类最大递归层数
     */
    public $category_recursion_level = 5;

    /**
     * @var 分类实际递归层数
     */
    public $category_recursion_real_deep = 0;

    /**
     * @var 评论最大递归层数
     */
    public $comment_recursion_level = 4;

    /**
     * @var html js hash (使用时请用“追加”去添加自己的hash)
     */
    public $html_js_hash = '';

    /**
     * @var admin js hash (使用时请用“追加”去添加自己的hash)
     */
    public $admin_js_hash = '';

    //默认路由url数组
    public $routes = array();

    //总缓存对象
    public $cacheobject = array();

    const OPTION_RESERVE_KEYS = 'ZC_DATABASE_TYPE|ZC_SQLITE_NAME|ZC_SQLITE_PRE|ZC_MYSQL_SERVER|ZC_MYSQL_USERNAME|ZC_MYSQL_PASSWORD|ZC_MYSQL_NAME|ZC_MYSQL_CHARSET|ZC_MYSQL_COLLATE|ZC_MYSQL_PRE|ZC_MYSQL_ENGINE|ZC_MYSQL_PORT|ZC_MYSQL_PERSISTENT|ZC_MYSQL_PORT|ZC_PGSQL_SERVER|ZC_PGSQL_USERNAME|ZC_PGSQL_PASSWORD|ZC_PGSQL_NAME|ZC_PGSQL_CHARSET|ZC_PGSQL_PRE|ZC_PGSQL_PORT|ZC_PGSQL_PERSISTENT|ZC_CLOSE_WHOLE_SITE|ZC_PERMANENT_DOMAIN_FORCED_URL|ZC_PERMANENT_DOMAIN_WHOLE_DISABLE';

    /**
     * 获取唯一实例.
     *
     * @return null|ZBlogPHP
     */
    public static function GetInstance()
    {
        if (!isset(self::$private_zbp)) {
            if (isset($GLOBALS['option']['ZC_GODZBP_FILE']) && isset($GLOBALS['option']['ZC_GODZBP_NAME']) && is_readable(ZBP_PATH . $GLOBALS['option']['ZC_GODZBP_FILE'])) {
                include ZBP_PATH . $GLOBALS['option']['ZC_GODZBP_FILE'];
                self::$private_zbp = new $GLOBALS['option']['ZC_GODZBP_NAME']();
            } else {
                self::$private_zbp = new self();
            }
        }

        return self::$private_zbp;
    }

    /**
     * 初始化数据库连接.
     *
     * @param string $type 数据连接类型
     *
     * @return Database__Interface
     */
    public static function InitializeDB($type)
    {
        if (!trim($type)) {
            return;
        }

        $newtype = 'Database__' . trim($type);

        return new $newtype();
    }

    /**
     * 构造函数，加载基本配置到$zbp.
     */
    public function __construct()
    {
        global $option, $lang, $langs, $blogpath, $bloghost, $cookiespath,  $cachedir, $logsdir, $datadir,
            $table, $datainfo, $actions, $action, $blogversion, $blogtitle, $blogname, $blogsubname, $routes,
            $blogtheme, $blogstyle, $currenturl, $activedapps, $posttype, $fullcurrenturl,
            $usersdir, $systemdir, $admindir, $usersurl, $systemurl, $adminurl;

        if (ZBP_HOOKERROR) {
            ZBlogException::SetErrorHook();
        }

        //基本配置加载到$zbp内
        $this->version = &$blogversion;
        $this->option = &$option;
        $this->lang = &$lang;
        $this->langs = &$langs;
        $this->routes = &$routes;

        $this->path = &$blogpath;
        $this->host = &$bloghost; //此值在后边初始化时可能会变化!
        $this->currenturl = &$currenturl;
        $this->fullcurrenturl = &$fullcurrenturl;
        $this->cookiespath = &$cookiespath;
        $this->usersdir = &$usersdir;
        $this->cachedir = &$cachedir;
        $this->logsdir = &$logsdir;
        $this->datadir = &$datadir;
        $this->systemdir = &$systemdir;
        $this->admindir = &$admindir;
        $this->usersurl = &$usersurl;
        $this->systemurl = &$systemurl;
        $this->adminurl = &$adminurl;

        $this->table = &$table;
        $this->datainfo = &$datainfo;
        $this->t = &$this->table;
        $this->d = &$this->datainfo;
        $this->actions = &$actions;
        $this->posttype = &$posttype;

        $this->action = &$action;
        $this->activedapps = &$activedapps;
        $this->activeapps = &$this->activedapps;

        $this->guid = &$this->option['ZC_BLOG_CLSID'];

        $this->title = &$blogtitle;
        $this->name = &$blogname;
        $this->subname = &$blogsubname;
        $this->theme = &$blogtheme;
        $this->style = &$blogstyle;

        $this->managecount = &$this->option['ZC_MANAGE_COUNT'];
        $this->pagebarcount = &$this->option['ZC_PAGEBAR_COUNT'];
        $this->searchcount = &$this->option['ZC_SEARCH_COUNT'];
        $this->displaycount = &$this->option['ZC_DISPLAY_COUNT'];
        $this->commentdisplaycount = &$this->option['ZC_COMMENTS_DISPLAY_COUNT'];

        $this->categoriesbyorder_type[0] = array();
        $this->categoriesbyorder = &$this->categoriesbyorder_type[0];
        $this->categories = &$this->categoriesbyorder;
        $this->categorys = &$this->categories;
        $this->categorysbyorder = &$this->categoriesbyorder;

        $this->tags_type[0] = array();
        $this->tagsbyname_type[0] = array();
        $this->tags = &$this->tags_type[0];
        $this->tagsbyname = &$this->tagsbyname_type[0];

        $this->user = new stdClass();
        foreach ($this->datainfo['Member'] as $key => $value) {
            $this->user->$key = $value[3];
        }
        $this->user->Metas = new Metas();

        $this->ishttps = &$this->isHttps;

        $this->BindingCache();
    }

    /**
     *析构函数，释放资源.
     */
    public function __destruct()
    {
        $this->Terminate();
    }

    /**
     * @api Filter_Plugin_Zbp_Call
     *
     * @param $method
     * @param $args
     *
     * @return mixed
     */
    public function __call($method, $args)
    {
        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_Call'] as $fpname => &$fpsignal) {
            $fpreturn = $fpname($method, $args);
            if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
                $fpsignal = PLUGIN_EXITSIGNAL_NONE;

                return $fpreturn;
            }
        }
        trigger_error($this->lang['error'][81], E_USER_WARNING);
    }

    /**
     * 设置参数值
     *
     * @param $name
     * @param $value
     *
     * @return mixed
     */
    public function __set($name, $value)
    {
        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_Set'] as $fpname => &$fpsignal) {
            $fpreturn = $fpname($name, $value);
            if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
                $fpsignal = PLUGIN_EXITSIGNAL_NONE;

                return $fpreturn;
            }
        }
        trigger_error($this->lang['error'][81], E_USER_WARNING);
    }

    /**
     * 获取参数值
     *
     * @param $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_Get'] as $fpname => &$fpsignal) {
            $fpreturn = $fpname($name);
            if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
                $fpsignal = PLUGIN_EXITSIGNAL_NONE;

                return $fpreturn;
            }
        }
        trigger_error($this->lang['error'][81], E_USER_WARNING);
    }

    /**
     * 初始化$zbp.
     *
     * @throws Exception
     *
     * @return bool
     */
    public function Initialize()
    {
        if ($this->isinitialized) {
            return false;
        }

        $oldZone = $this->option['ZC_TIME_ZONE_NAME'];
        date_default_timezone_set($oldZone);

        $oldLang = $this->option['ZC_BLOG_LANGUAGEPACK'];
        $this->LoadLanguage('system', '');

        if ($this->option['ZC_CLOSE_WHOLE_SITE'] == true) {
            Http503();
            $this->ShowError(82, __FILE__, __LINE__);

            return false;
        }

        if (!$this->OpenConnect()) {
            return false;
        }

        $this->ConvertTableAndDatainfo();

        $this->LoadConfigsOnlySystem(true);
        $this->LoadOption();

        if ($this->option['ZC_BLOG_LANGUAGEPACK'] === 'SimpChinese') {
            $this->option['ZC_BLOG_LANGUAGEPACK'] = 'zh-cn';
        }

        if ($this->option['ZC_BLOG_LANGUAGEPACK'] === 'TradChinese') {
            $this->option['ZC_BLOG_LANGUAGEPACK'] = 'zh-tw';
        }

        if ($oldLang != $this->option['ZC_BLOG_LANGUAGEPACK']) {
            $this->LoadLanguage('system', '');
        }

        if (isset($this->option['ZC_DEBUG_MODE_WARNING'])) {
            ZBlogException::$iswarning = (bool) $this->option['ZC_DEBUG_MODE_WARNING'];
        }
        if (isset($this->option['ZC_DEBUG_MODE_STRICT'])) {
            ZBlogException::$isstrict = (bool) $this->option['ZC_DEBUG_MODE_STRICT'];
        }
        if (isset($this->option['ZC_DEBUG_LOG_ERROR'])) {
            ZBlogException::$islogerror = (bool) $this->option['ZC_DEBUG_LOG_ERROR'];
        }

        //ZC_PERMANENT_DOMAIN_WHOLE_DISABLE不存在 或是 ZC_PERMANENT_DOMAIN_WHOLE_DISABLE存在但为假
        $domain_disable = GetValueInArray($this->option, 'ZC_PERMANENT_DOMAIN_WHOLE_DISABLE');
        if ($domain_disable == false) {
            $forced_url = GetValueInArray($this->option, 'ZC_PERMANENT_DOMAIN_FORCED_URL');
            if ($forced_url != '') {
                //如果ZC_PERMANENT_DOMAIN_FORCED_URL存在 且不为空
                $this->host = (string) $forced_url;
                $this->cookiespath = strstr(str_replace('://', '', $this->host), '/');
            } elseif ($this->option['ZC_PERMANENT_DOMAIN_ENABLE'] == true) {
                //如果ZC_PERMANENT_DOMAIN_ENABLE已开启的话
                $this->host = $this->option['ZC_BLOG_HOST'];
                $this->cookiespath = strstr(str_replace('://', '', $this->host), '/');
            } else {
                //默认自动识别域名
                $this->option['ZC_BLOG_HOST'] = $this->host;
            }
        } else {
            $this->option['ZC_BLOG_HOST'] = $this->host;
        }

        $this->option['ZC_BLOG_PRODUCT'] = 'Z-BlogPHP';
        $this->option['ZC_BLOG_VERSION'] = ZC_BLOG_VERSION;
        $this->option['ZC_NOW_VERSION'] = $this->version;  //ZC_LAST_VERSION
        $this->option['ZC_BLOG_PRODUCT_FULL'] = $this->option['ZC_BLOG_PRODUCT'] . ' ' . ZC_VERSION_DISPLAY;
        $this->option['ZC_BLOG_PRODUCT_FULLHTML'] = '<a href="https://www.zblogcn.com/" title="Z-BlogPHP ' . ZC_BLOG_VERSION . '" target="_blank">' . $this->option['ZC_BLOG_PRODUCT_FULL'] . '</a>';
        $this->option['ZC_BLOG_PRODUCT_HTML'] = '<a href="https://www.zblogcn.com/" title="Z-BlogPHP ' . ZC_BLOG_VERSION . '" target="_blank">' . $this->option['ZC_BLOG_PRODUCT'] . '</a>';

        if ($oldZone != $this->option['ZC_TIME_ZONE_NAME']) {
            date_default_timezone_set($this->option['ZC_TIME_ZONE_NAME']);
        }

        /*if(isset($_COOKIE['timezone'])){
            $tz=GetVars('timezone','COOKIE');
            if(is_numeric($tz)){
            $tz=sprintf('%+d',-$tz);
            date_default_timezone_set('Etc/GMT' . $tz);
            $this->timezone=date_default_timezone_get();
            }
        */

        if ($this->option['ZC_VERSION_IN_HEADER'] && !headers_sent()) {
            header('Product:' . $this->option['ZC_BLOG_PRODUCT_FULL']);
        }

        $parsedHost = parse_url($this->host);
        if (isset($parsedHost['scheme']) && isset($parsedHost['host'])) {
            $this->fullcurrenturl = $parsedHost['scheme'] . '://' . $parsedHost['host'];
            if (isset($parsedHost['port'])) {
                $this->fullcurrenturl .= ':' . $parsedHost['port'];
            }
        } else {
            $this->fullcurrenturl = '';
        }
        $this->fullcurrenturl .= $this->currenturl;

        if (stripos($this->host, 'http') === 0) {
            if (stripos($this->host, 'https://') === 0) {
                $this->isHttps = true;
            }
        } else {
            $this->isHttps = (HTTP_SCHEME === 'https://');
        }
        //var_dump($this->isHttps);die;

        $this->verifyCodeUrl = $this->systemurl . 'script/c_validcode.php';
        $this->validcodeurl = &$this->verifyCodeUrl;
        $this->feedurl = $this->host . 'feed.php';
        $this->searchurl = $this->host . 'search.php';
        $this->cmdurl = $this->systemurl . 'cmd.php';
        $this->ajaxurl = $this->cmdurl . '?act=ajax&src=';
        $this->xmlrpcurl = $this->systemurl . 'xml-rpc/index.php';
        $this->apiurl = $this->systemurl . 'api.php';

        $this->LoadConfigsOnlySystem(false);

        $this->LoadCache();

        !defined('ZBP_IN_API') || $this->isapi = true;
        !defined('ZBP_IN_CMD') || $this->iscmd = true;
        !defined('ZBP_IN_AJAX') || $this->isajax = true;
        !defined('ZBP_IN_XMLRPC') || $this->isxmlrpc = true;
        !$this->option['ZC_DEBUG_MODE'] || $this->isdebug = true;

        $this->LoadPostType();
        $this->LoadRoutes();

        $this->isinitialized = true;

        if ($this->isapi) {
            //挂载API错误显示
            Add_Filter_Plugin('Filter_Plugin_Debug_Display', 'ApiDebugDisplay');
            //挂载Token验证
            Add_Filter_Plugin('Filter_Plugin_Zbp_PreLoad', 'ApiTokenVerify');
        }

        return true;
    }

    /**
     * 读取文件里的PostType配置(以后可能会放进数据库里)
     */
    public function LoadPostType()
    {
        foreach (array(0 => 'article', 1 => 'page') as $postid => $postname) {
            $posttype = include ZBP_PATH . 'zb_system/defend/posttype_' . $postname . '.php';
            if (empty($posttype['single_urlrule'])) {
                if ($postid == 0) {
                    $posttype['single_urlrule'] = $this->option['ZC_ARTICLE_REGEX'];
                } else {
                    $posttype['single_urlrule'] = $this->option['ZC_PAGE_REGEX'];
                }
            }
            empty($posttype['list_urlrule']) ? $posttype['list_urlrule'] = $this->option['ZC_INDEX_REGEX'] : null;
            empty($posttype['list_category_urlrule']) ? $posttype['list_category_urlrule'] = $this->option['ZC_CATEGORY_REGEX'] : null;
            empty($posttype['list_author_urlrule']) ? $posttype['list_author_urlrule'] = $this->option['ZC_AUTHOR_REGEX'] : null;
            empty($posttype['list_date_urlrule']) ? $posttype['list_date_urlrule'] = $this->option['ZC_DATE_REGEX'] : null;
            empty($posttype['list_tag_urlrule']) ? $posttype['list_tag_urlrule'] = $this->option['ZC_TAGS_REGEX'] : null;
            empty($posttype['search_urlrule']) ? $posttype['search_urlrule'] = $this->option['ZC_SEARCH_REGEX'] : null;

            $this->posttype[$postid] = $posttype;
        }
    }

    /**
     * 读取文件里的路由配置(以后可能会放进数据库里)
     */
    public function LoadRoutes()
    {
        foreach (array(0 => 'article', 1 => 'page') as $postid => $postname) {
            $route = include ZBP_PATH . 'zb_system/defend/routes_post_' . $postname . '.php';
            foreach ($route as $key2 => $value2) {
                if (empty($value2['urlrule'])) {
                    if ($value2['name'] == 'post_' . $postname . '_single') {
                        $value2['urlrule'] = $this->GetPostType($postid, 'single_urlrule');
                    }
                    if ($value2['name'] == 'post_' . $postname . '_list') {
                        $value2['urlrule'] = $this->GetPostType($postid, 'list_urlrule');
                    }
                    if ($value2['name'] == 'post_' . $postname . '_list_category') {
                        $value2['urlrule'] = $this->GetPostType($postid, 'list_category_urlrule');
                    }
                    if ($value2['name'] == 'post_' . $postname . '_list_author') {
                        $value2['urlrule'] = $this->GetPostType($postid, 'list_author_urlrule');
                    }
                    if ($value2['name'] == 'post_' . $postname . '_list_date') {
                        $value2['urlrule'] = $this->GetPostType($postid, 'list_date_urlrule');
                    }
                    if ($value2['name'] == 'post_' . $postname . '_list_tag') {
                        $value2['urlrule'] = $this->GetPostType($postid, 'list_tag_urlrule');
                    }
                    if ($value2['name'] == 'post_' . $postname . '_search') {
                        $value2['urlrule'] = $this->GetPostType($postid, 'search_urlrule');
                    }
                }
                $this->routes[$key2] = $value2;
                if ($value2['type'] != 'rewrite' || ($this->option['ZC_STATIC_MODE'] == 'REWRITE' && $value2['type'] == 'rewrite')) {
                    $this->SetPostType_Sub($postid, 'routes', $value2['name'], array($value2['type'] => $value2['name']));
                }
            }
        }
        //var_export($this->posttype);
        //var_export($this->routes);
    }

    /**
     * 在Initialize和加载所有插件include之后，在Load之前的过程，被c_system_base调用，1.7里新加的
     *
     * @throws Exception
     *
     * @return bool
     */
    public function PreLoad()
    {
        if (!$this->isinitialized || $this->ispreload) {
            return false;
        }

        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_PreLoad'] as $fpname => &$fpsignal) {
            $fpreturn = $fpname();
        }

        //此处接口应该在下一版本移除，请不要再使用了！！！
        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_Load_Pre'] as $fpname => &$fpsignal) {
            $fpreturn = $fpname();
        }

        $this->ispreload = true;
        return true;
    }

    /**
     * 从数据库里读取信息，启动整个ZBP.
     *
     * @throws Exception
     *
     * @return bool
     */
    public function Load()
    {
        if (!$this->isinitialized || !$this->ispreload || $this->isload) {
            return false;
        }

        if (!headers_sent()) {
            header('Content-type: text/html; charset=utf-8');
        }

        $this->ConvertTableAndDatainfo();
        if ($this->option['ZC_LOADMEMBERS_LEVEL'] == 0) {
            $this->option['ZC_LOADMEMBERS_LEVEL'] = 1;
        }
        $this->LoadMembers($this->option['ZC_LOADMEMBERS_LEVEL']);
        $this->LoadCategories();
        //$this->LoadTags();
        $this->LoadModules();
        $this->RegBuildModules();

        if (!(is_subclass_of($this->user, 'BaseMember') && $this->user->Level > 0 && !empty($this->user->ID))) {
            $this->Verify();
        }

        //创建模板类
        $this->template = $this->PrepareTemplate();

        if ($this->ismanage) {
            $this->LoadManage();
        }

        Add_Filter_Plugin('Filter_Plugin_Login_Header', 'Include_AddonAdminFont');
        Add_Filter_Plugin('Filter_Plugin_Other_Header', 'Include_AddonAdminFont');
        Add_Filter_Plugin('Filter_Plugin_Admin_Header', 'Include_AddonAdminFont');
        Add_Filter_Plugin('Filter_Plugin_BatchPost', 'Include_BatchPost_Article');
        Add_Filter_Plugin('Filter_Plugin_BatchPost', 'Include_BatchPost_Page');
        Add_Filter_Plugin('Filter_Plugin_Index_End', 'Include_Index_End');
        Add_Filter_Plugin('Filter_Plugin_Search_End', 'Include_Index_End');
        Add_Filter_Plugin('Filter_Plugin_Index_Begin', 'Include_Index_Begin');
        Add_Filter_Plugin('Filter_Plugin_Search_Begin', 'Include_Index_Begin');
        Add_Filter_Plugin('Filter_Plugin_Feed_Begin', 'Include_Index_Begin');
        Add_Filter_Plugin('Filter_Plugin_Zbp_CheckRights', 'Include_Frontend_CheckRights');

        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_Load'] as $fpname => &$fpsignal) {
            $fpname();
        }

        if ($this->user->Status == ZC_MEMBER_STATUS_LOCKED) {
            $this->ShowError(80, __FILE__, __LINE__);
        }

        //进后台时已自动检测模板并自动重建了，所以这里只针对开调试后的前台的访问进行
        if ($this->option['ZC_DEBUG_MODE'] || $this->ismanage) {
            $this->CheckTemplate();
        }

        $this->ReflushLanguages();
        $this->ConvertTableAndDatainfo();

        $this->islogin = empty($this->user->ID) ? false : true;
        $this->isload = true;
        return true;
    }

    /**
     * 载入管理.
     *
     * @throws Exception
     */
    public function LoadManage()
    {
        if (!$this->CheckRights('admin')) {
            $this->ShowError(6, __FILE__, __LINE__);
        }

        Add_Filter_Plugin('Filter_Plugin_Admin_PageMng_SubMenu', 'Include_Admin_Addpagesubmenu');
        Add_Filter_Plugin('Filter_Plugin_Admin_TagMng_SubMenu', 'Include_Admin_Addtagsubmenu');
        Add_Filter_Plugin('Filter_Plugin_Admin_CategoryMng_SubMenu', 'Include_Admin_Addcatesubmenu');
        Add_Filter_Plugin('Filter_Plugin_Admin_MemberMng_SubMenu', 'Include_Admin_Addmemsubmenu');
        Add_Filter_Plugin('Filter_Plugin_Admin_ModuleMng_SubMenu', 'Include_Admin_Addmodsubmenu');
        Add_Filter_Plugin('Filter_Plugin_Admin_CommentMng_SubMenu', 'Include_Admin_Addcmtsubmenu');
        Add_Filter_Plugin('Filter_Plugin_Admin_SettingMng_SubMenu', 'Include_Admin_Addsettingsubmenu');
        Add_Filter_Plugin('Filter_Plugin_Zbp_LoadManage', 'Include_Admin_UpdateDB');
        Add_Filter_Plugin('Filter_Plugin_Admin_End', 'Include_Admin_CheckHttp304OK');

        if (isset($GLOBALS['zbpvers'])) {
            $GLOBALS['zbpvers'][$GLOBALS['blogversion']] = ZC_VERSION_DISPLAY . ' Build ' . $GLOBALS['blogversion'];
        }

        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_LoadManage'] as $fpname => &$fpsignal) {
            $fpname();
        }
    }

    /**
     * 终止连接，释放资源.
     */
    public function Terminate()
    {
        if ($this->isinitialized) {
            foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_Terminate'] as $fpname => &$fpsignal) {
                $fpname();
            }

            $this->CloseConnect();
            unset($this->db);
            $this->isinitialized = false;
        }
    }

    /**
     * 连接数据库.
     *
     * @throws Exception
     *
     * @return bool
     */
    public function OpenConnect()
    {
        if ($this->isconnected) {
            return false;
        }

        if (!$this->option['ZC_DATABASE_TYPE']) {
            return false;
        }

        switch ($this->option['ZC_DATABASE_TYPE']) {
            case 'sqlite':
            case 'sqlite3':
            case 'pdo_sqlite':
                $this->db = self::InitializeDB($this->option['ZC_DATABASE_TYPE']);
                if ($this->db->Open(
                    array($this->datadir . '' . $this->option['ZC_SQLITE_NAME'],
                        $this->option['ZC_SQLITE_PRE'],
                    )
                ) == false
                ) {
                    $this->ShowError(69, __FILE__, __LINE__);
                }
                break;
            case 'postgresql':
            case 'pdo_postgresql':
                $this->db = self::InitializeDB($this->option['ZC_DATABASE_TYPE']);
                if ($this->db->Open(
                    array($this->option['ZC_PGSQL_SERVER'],
                        $this->option['ZC_PGSQL_USERNAME'],
                        $this->option['ZC_PGSQL_PASSWORD'],
                        $this->option['ZC_PGSQL_NAME'],
                        $this->option['ZC_PGSQL_PRE'],
                        $this->option['ZC_PGSQL_PORT'],
                        $this->option['ZC_PGSQL_PERSISTENT'],
                    )
                ) == false
                ) {
                    $this->ShowError(67, __FILE__, __LINE__);
                }
                break;
            case 'mysql':
            case 'mysqli':
            case 'pdo_mysql':
            default:
                if ($this->option['ZC_DATABASE_TYPE'] == 'mysql' && version_compare(PHP_VERSION, '7.0.0') >= 0) {
                    if (extension_loaded('mysqli')) {
                        $this->option['ZC_DATABASE_TYPE'] = 'mysqli';
                    } elseif (extension_loaded('pdo_mysql')) {
                        $this->option['ZC_DATABASE_TYPE'] = 'pdo_mysql';
                    }
                }
                $this->db = self::InitializeDB($this->option['ZC_DATABASE_TYPE']);
                if ($this->db->Open(
                    array(
                        $this->option['ZC_MYSQL_SERVER'],
                        $this->option['ZC_MYSQL_USERNAME'],
                        $this->option['ZC_MYSQL_PASSWORD'],
                        $this->option['ZC_MYSQL_NAME'],
                        $this->option['ZC_MYSQL_PRE'],
                        $this->option['ZC_MYSQL_PORT'],
                        $this->option['ZC_MYSQL_PERSISTENT'],
                        $this->option['ZC_MYSQL_ENGINE'],
                    )
                ) == false
                ) {
                    $this->ShowError(67, __FILE__, __LINE__);
                }
                break;
        }
        // utf8mb4支持
        if ($this->db->type == 'mysql' && version_compare($this->db->version, '5.5.3') < 0) {
            Add_Filter_Plugin('Filter_Plugin_DbSql_Filter', 'utf84mb_filter');
            Add_Filter_Plugin('Filter_Plugin_Edit_Begin', 'utf84mb_fixHtmlSpecialChars');
        }
        $this->isconnected = true;

        return true;
    }

    /**
     * 对表名和数据结构进行预转换.
     */
    public function ConvertTableAndDatainfo()
    {
        if ($this->db->dbpre) {
            $this->table = str_replace('%pre%', $this->db->dbpre, $this->table);
        }
        if ($this->db->type === 'postgresql') {
            foreach ($this->datainfo as $key => &$value) {
                foreach ($value as $k2 => &$v2) {
                    $v2[0] = strtolower($v2[0]);
                }
            }
        }
    }

    /**
     * 关闭数据库连接.
     */
    public function CloseConnect()
    {
        if ($this->isconnected) {
            $this->db->Close();
            $this->isconnected = false;
        }
    }

    /**
     * 启用session.
     *
     * @return bool
     */
    public function StartSession()
    {
        if (session_status() == 1) {
            session_start();
            $this->issession = true;

            return true;
        }

        return false;
    }

    /**
     * 终止session.
     *
     * @return bool
     */
    public function EndSession()
    {
        if (session_status() == 2) {
            session_write_close();
            $this->issession = false;

            return true;
        }

        return false;
    }

    /**
     * Load or ReLoad插件Configs表.
     */
    public function LoadConfigs()
    {
        $this->LoadConfigsOnlySystem(true);
        $this->LoadConfigsOnlySystem(false);
    }

    /**
     * 载入插件Configs表 Only System Option.
     */
    private $prvConfigList = null;

    public function LoadConfigsOnlySystem($onlysystemoption = true)
    {
        if ($onlysystemoption == true) {
            $this->configs = array();
            $this->prvConfigList = array();

            $sql = $this->db->sql->get()->select($this->table['Config']);
            /* @var Config[] $array */
            $this->prvConfigList = $this->GetListOrigin($sql);
        }

        $type = 'Config';

        foreach ($this->prvConfigList as $c) {
            $name = $c[$this->d['Config']['Name'][0]];
            if (($name == 'system' && $onlysystemoption == true) || ($name != 'system' && $onlysystemoption == false)) {
                if (!isset($this->configs[$name])) {
                    $l = new $type($name);
                    $this->configs[$name] = $l;
                } else {
                    $l = $this->configs[$name];
                }
                if (get_class($l) != $type) {
                    $l = new $type($name);
                    $this->configs[$name] = $l;
                }
                if (isset($c[$this->d['Config']['Key'][0]]) && $c[$this->d['Config']['Key'][0]] != '') {
                    $l->LoadInfoByAssocSingleWithPre($c);
                } else {
                    $l->LoadInfoByAssoc($c);
                }
            }
        }
        //将读出来的数组再拼成序列化数据再反序列化
        foreach ($this->configs as $key => $value) {
            if (is_object($value) && ($key == 'system' && $onlysystemoption == true) || ($key != 'system' && $onlysystemoption == false)) {
                $value->LoadInfoByAssocSingleWithAfter();
            }
        }

        if ($onlysystemoption == false) {
            $this->prvConfigList = array();
        }
    }

    /**
     * 保存Configs表.
     *
     * @param string $name Configs表名
     *
     * @return bool
     */
    public function SaveConfig($name)
    {
        if (!isset($this->configs[$name])) {
            return false;
        }

        $this->configs[$name]->Save();

        return true;
    }

    /**
     * 删除Configs表.
     *
     * @param string $name Configs表名
     *
     * @return bool
     */
    public function DelConfig($name)
    {
        if (!isset($this->configs[$name])) {
            return false;
        }

        $this->configs[$name]->Delete();
        unset($this->configs[$name]);

        return true;
    }

    /**
     * 获取Configs表值
     *
     * @param string $name Configs表名
     *
     * @return mixed
     */
    public function Config($name)
    {
        if (!isset($this->configs[$name])) {
            $name = FilterCorrectName($name);
            if (!$name) {
                return;
            }

            $this->configs[$name] = new Config($name);
        }

        return $this->configs[$name];
    }

    /**
     * 查某Config是否存在.
     *
     * @param string $name Configs表名
     *
     * @return bool
     */
    public function HasConfig($name)
    {
        return isset($this->configs[$name]) && $this->configs[$name]->CountItem() > 0;
    }

    //###############################################################################################################
    //Cache相关
    private $cache_hash = null;

    /**
     * 保存缓存.
     *
     * @return bool
     */
    public function SaveCache()
    {
        //$s=$this->cachedir . '' . $this->guid . '.cache';
        //$c=serialize($this->cache);
        //@file_put_contents($s, $c);
        //$this->configs['cache']=$this->cache;
        $new_hash = md5($this->Config('cache'));
        if ($this->cache_hash == $new_hash) {
            return true;
        }

        $this->SaveConfig('cache');
        $this->cache_hash = $new_hash;

        return true;
    }

    /**
     * 加载缓存.
     *
     * @return bool
     */
    public function LoadCache()
    {
        $this->cache = $this->Config('cache');
        $this->cache_hash = md5($this->Config('cache'));

        return true;
    }

    /**
     * 保存配置.
     *
     * @return bool
     */
    public function SaveOption()
    {
        $this->option['ZC_BLOG_CLSID'] = $this->guid;

        unset($this->option['ZC_PERMANENT_DOMAIN_WHOLE_DISABLE']);
        unset($this->option['ZC_PERMANENT_DOMAIN_FORCED_URL']);

        $reserve_keys = explode('|', self::OPTION_RESERVE_KEYS);

        if (file_exists($this->usersdir . 'c_option.php') == false) {
            $s = "<";
            $s .= "?php\r\n";
            $s .= "return ";
            $option = array();
            foreach ($this->option as $key => $value) {
                if (in_array($key, $reserve_keys)) {
                    $option[$key] = $value;
                }
            }
            $s .= var_export($option, true);
            $s .= ";";
            @file_put_contents($this->usersdir . 'c_option.php', $s);
        }

        foreach ($this->option as $key => $value) {
            $this->Config('system')->$key = $value;
        }
        foreach ($reserve_keys as $key => $value) {
            $this->Config('system')->DelKey($value);
        }

        $this->Config('system')->ZC_BLOG_HOST = chunk_split($this->Config('system')->ZC_BLOG_HOST, 1, "|");

        $this->SaveConfig('system');

        return true;
    }

    /**
     * 载入配置.
     *
     * @return bool
     */
    public function LoadOption()
    {
        $array = $this->Config('system')->GetData();

        if (empty($array)) {
            return false;
        }

        if (!is_array($array)) {
            return false;
        }

        $reserve_keys = explode('|', self::OPTION_RESERVE_KEYS);

        foreach ($array as $key => $value) {
            if ($key == 'ZC_BLOG_HOST') {
                $value = str_replace('|', '', $value);
            }

            if (in_array($key, $reserve_keys)) {
                continue;
            }

            $this->option[$key] = $value;
        }
        if (!extension_loaded('gd')) {
            $this->option['ZC_COMMENT_VERIFY_ENABLE'] = false;
        }

        return true;
    }

    /**
     * 验证操作权限.
     *
     * @param string     $action 操作
     * @param int|string $level
     *
     * @return bool
     */
    public function CheckRights($action, $level = null)
    {
        if ($level === null) {
            $level = $this->user->Level;
        }

        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_CheckRights'] as $fpname => &$fpsignal) {
            $fpreturn = $fpname($action, $level);
            if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
                $fpsignal = PLUGIN_EXITSIGNAL_NONE;

                return $fpreturn;
            }
        }
        if (!isset($this->actions[$action])) {
            if (is_numeric($action)) {
                return $level <= $action;
            } else {
                return false;
            }
        }

        return $level <= $this->actions[$action];
    }

    /**
     * 根据用户等级验证操作权限 1.5开始参数换顺序.
     *
     * @param string $action 操作
     * @param int    $level  用户等级
     *
     * @return bool
     */
    public function CheckRightsByLevel($action, $level)
    {
        return $this->CheckRights($action, $level);
    }

    /**
     * 验证用户登录.
     *
     * @return bool
     */
    public function Verify()
    {
        // 在普通 Web 页面中
        $username = trim(GetVars('username_' . hash("crc32b", $this->guid), 'COOKIE'));
        $token = trim(GetVars('token_' . hash("crc32b", $this->guid), 'COOKIE'));
        $user = $this->VerifyUserToken($token, $username);

        if (is_object($user)) {
            $this->user = $user;
            $this->islogin = true;
            return true;
        }
        $this->user = new Member();
        $this->user->Guid = GetGuid();
        $this->islogin = false;

        return false;
    }

    /**
     * 返回登录成功后应保存的cookie信息.
     *
     * @param Member $m 已验过成功的member
     *
     * @return string
     */
    public function VerifyResult($m)
    {
        return $this->GenerateUserToken($m);
    }

    /**
     * 生成User Token，用于登录验证
     *
     * @param Member $user
     * @param int    $time
     *
     * @return string
     */
    public function GenerateUserToken($user, $time = 0)
    {
        if ($time === 0) {
            $time = (time() + 3600 * 24);
        }

        return CreateWebToken($user->ID, $time, $user->Guid, $user->PassWord_MD5Path);
    }

    /**
     * 生成Api Token，用于 API 模式下的用户验证.
     *
     * @param Member $user
     * @param int    $time
     *
     * @return string
     */
    public function GenerateApiToken($user, $time = 0)
    {
        return base64_encode($user->Name . '|||' . $this->GenerateUserToken($user, (int) $time));
    }

    /**
     * 验证用户登录Token.
     *
     * @param string $token
     * @param string $username
     *
     * @return Member|null
     */
    public function VerifyUserToken($token, $username)
    {
        $user = $this->GetMemberByName($username);
        if ($user->ID != null) {
            if (VerifyWebToken($token, $user->ID, $user->Guid, $user->PassWord_MD5Path)) {
                return $user;
            }
        }

        return null;
    }

    /**
     * 验证用户登录（一次MD5密码）.
     *
     * @param string $name   用户名
     * @param string $md5pw  md5加密后的密码
     * @param Member $member 返回读取成功的member对象
     *
     * @return bool
     */
    public function Verify_MD5($name, $md5pw, &$member)
    {
        if ($name == '' || $md5pw == '') {
            return false;
        }
        $member = $this->GetMemberByName($name);
        if ($member->ID != null) {
            return $this->Verify_Final($name, md5($md5pw . $member->Guid), $member);
        }

        return false;
    }

    /**
     * 验证用户登录（原始明文密码）.
     *
     * @param string $name       用户名
     * @param string $originalpw 密码明文
     * @param Member $member     返回读取成功的member对象
     *
     * @return bool
     */
    public function Verify_Original($name, $originalpw, &$member = null)
    {
        if ($name == '' || $originalpw == '') {
            return false;
        }
        $m = $this->GetMemberByName($name);
        if ($m->ID != null) {
            return $this->Verify_MD5($name, md5($originalpw), $member);
        }

        return false;
    }

    /**
     * 验证用户登录（数据库保存的最终运算后密码）.
     *
     * @param string $name     用户名
     * @param string $password 二次加密后的密码
     * @param object $member   返回读取成功的member对象
     *
     * @return bool
     */
    public function Verify_Final($name, $password, &$member = null)
    {
        if ($name == '' || $password == '') {
            return false;
        }
        $m = $this->GetMemberByName($name);
        if ($m->ID != null) {
            if (hash_equals($m->Password, $password) === true) {
                $member = $m;

                return true;
            }
        }

        return false;
    }

    /**
     * 验证用户登录（使用Token，替代密码保存）.
     *
     * @param string $name   用户名
     * @param string $wt     WebToken
     * @param string $wt_id  WebToken的ID识别符
     * @param object $member 返回读取成功的member对象
     *
     * @return bool
     */
    public function Verify_Token($name, $wt, $wt_id, &$member = null)
    {
        if ($name == '' || $wt == '') {
            return false;
        }
        $m = null;
        $m = $this->GetMemberByName($name);
        if ($m->ID != null) {
            if (VerifyWebToken($wt, $wt_id, $this->guid, $m->ID, $m->Password) === true) {
                $member = $m;

                return true;
            }
        }

        return false;
    }

    /**
     * 验证 API Token.
     *
     * @param string $api_token
     * @return Member|null
     */
    public function VerifyAPIToken($api_token)
    {
        $api_token = base64_decode($api_token);

        if (empty($api_token)) {
            return null;
        }

        // 验证字符串格式为 {username}|||{token}
        $api_token_array = explode('|||', $api_token);
        if (count($api_token_array) !== 2) {
            return null;
        }

        return $this->VerifyUserToken($api_token_array[1], $api_token_array[0]);
    }

    private $loadmembers_level = 0;

    /**
     * 载入用户列表.
     *
     * @param int $level 用户等级
     *
     * @return bool
     */
    public function LoadMembers($level = 0)
    {
        $this->loadmembers_level = $level;
        if ($this->loadmembers_level < 0) {
            return false;
        }

        $where = null;
        if ($this->loadmembers_level > 0) {
            $where = array(array('<=', 'mem_Level', $this->loadmembers_level));
        }
        $this->members = array();
        $this->membersbyname = array();
        $array = $this->GetMemberList(null, $where);

        return true;
    }

    /**
     * 通过列表（文章/评论）一次性将用户预载入到 members 里.
     *
     * @param array $list 文章/评论列表数组
     *
     * @return boolean
     */
    public function LoadMembersInList($list)
    {
        $mem_ids_need_load = array();
        
        foreach ($list as $obj) {
            if (!isset($obj->AuthorID) || ($obj->AuthorID == null)) {
                continue;
            }

            // 已经载入的用户不重新载入
            if (isset($this->members[$obj->AuthorID])) {
                continue;
            }

            $mem_ids_need_load[] = $obj->AuthorID;
        }

        if (count($mem_ids_need_load) === 0) {
            return true;
        }

        $array = $this->GetMemberList(null, array(array('IN', 'mem_ID', $mem_ids_need_load)));

        return true;
    }

    /**
     * 私有方法之递归加载分类.
     *
     * @return array
     */
    private function LoadCategories_Recursion($deep, $id, &$lv, $type)
    {
        if (($deep + 1) >= $this->category_recursion_real_deep) {
            $this->category_recursion_real_deep = ($deep + 1);
        }
        $subarray = array();
        for ($i = 0; $i < $this->category_recursion_level; $i++) {
            $name = 'lv' . $i;
            ${$name} = &$lv[$i];
        }
        $lvdeep = 'lv' . $deep;
        $this->categoriesbyorder_type[$type][$id] = &$this->categories_all[$id];
        if ($deep < ($this->category_recursion_level - 1)) {
            $deep += 1;
            $lvdeepnext = 'lv' . $deep;
            if (isset(${$lvdeepnext}[$id])) {
                foreach (${$lvdeepnext}[$id] as $idnow) {
                    $subarray[] = $idnow;
                    $b = false;
                    foreach ($this->categoriesbyorder_type[$type][$id]->SubCategories as $key2 => $value2) {
                        if ($value2->ID == $idnow) {
                            $b = true;
                            break;
                        }
                    }
                    if ($b == false) {
                        $this->categoriesbyorder_type[$type][$id]->SubCategories[] = &$this->categories_all[$idnow];
                    }
                    //$this->categoriesbyorder[$id]->ChildrenCategories[] = &$this->categories[$idnow];
                    $array = $this->LoadCategories_Recursion($deep, $idnow, $lv, $type);
                    foreach ($array as $key => $value) {
                        $subarray[] = $value;
                    }
                }
            }
        }
        $subarray = array_unique($subarray);
        foreach ($subarray as $key => $value) {
            $b = false;
            foreach ($this->categoriesbyorder_type[$type][$id]->ChildrenCategories as $key2 => $value2) {
                if ($value2->ID == $value) {
                    $b = true;
                    break;
                }
            }
            if ($b == false) {
                $this->categoriesbyorder_type[$type][$id]->ChildrenCategories[] = &$this->categories_all[$value];
            }
        }
        return $subarray;
    }

    /**
     * 载入分类列表.
     *
     * @return bool
     */
    public function LoadCategories()
    {
        $this->categories_all = array();

        $this->categoriesbyorder = array();
        $this->categories = &$this->categoriesbyorder;

        $this->categoriesbyorder_type = array();
        $this->categories_type = array();

        $array = $this->GetCategoryList(null, null, array('cate_Order' => 'ASC'), null, null);
        if (count($array) == 0) {
            return false;
        }

        foreach ($this->posttype as $key => $value) {
            if (!isset($this->categories_type[$key])) {
                $this->categories_type[$key] = array();
            }
            if (!isset($this->categoriesbyorder_type[$key])) {
                $this->categoriesbyorder_type[$key] = array();
            }
        }

        foreach ($this->posttype as $key => $value) {
            $lv = array();
            for ($i = 0; $i < $this->category_recursion_level; $i++) {
                $name = 'lv' . $i;
                ${$name} = array();
                $lv[$i] = &${$name};
            }
            $categories = $this->categories_type[$key];

            foreach ($categories as $id => $c) {
                $l = 'lv' . $c->Level;
                ${$l}[$c->ParentID][] = $id;
            }

            if (!isset($lv0[0])) {
                $lv0[0] = array();
            }

            foreach ($lv0[0] as $id0) {
                $this->LoadCategories_Recursion(0, $id0, $lv, $key);
            }
        }

        $this->categories_type = &$this->categoriesbyorder_type;
        $this->categoriesbyorder = &$this->categoriesbyorder_type[0];
        $this->categories = &$this->categoriesbyorder;

        return true;
    }

    /**
     * 载入标签列表.
     *
     * @return bool
     */
    public function LoadTags()
    {
        $this->tags_type = array();
        $this->tagsbyname_type = array();

        $this->tags = array();
        $this->tagsbyname = array();

        $this->tags_all = array();

        $array = $this->GetTagList();

        isset($this->tags_type[0]) || $this->tags_type[0] = array();
        isset($this->tagsbyname_type[0]) || $this->tagsbyname_type[0] = array();

        $this->tags = &$this->tags_type[0];
        $this->tagsbyname = &$this->tagsbyname_type[0];

        return true;
    }

    /**
     * 载入模块列表.
     *
     * @return bool
     */
    public function LoadModules()
    {
        $this->modules = array();
        $this->modulesbyfilename = array();
        $array = $this->GetModuleList();

        $dir = $this->usersdir . 'theme/' . $this->theme . '/include/';
        if (file_exists($dir)) {
            $files = GetFilesInDir($dir, 'php');
            foreach ($files as $sortname => $fullname) {
                $m = new Module();
                $m->FileName = $sortname;
                $m->Name = $sortname;
                $m->HtmlID = $sortname;
                $m->Content = file_get_contents($fullname);
                if (stripos($m->Content, '<li') !== false && stripos($m->Content, '</li>') !== false) {
                    $m->Type = 'ul';
                } else {
                    $m->Type = 'div';
                }
                $m->Source = 'themeinclude_' . $this->theme;
                $m->ID = (0 - (int) crc32($m->Source . $m->FileName));
                $this->AddCache($m);
            }
        }

        return true;
    }

    /**
     * 载入主题列表.
     *
     * @return App[]
     */
    public function LoadThemes()
    {
        $allThemes = array();
        $dirs = GetDirsInDir($this->usersdir . 'theme/');
        natcasesort($dirs);
        array_unshift($dirs, $this->theme);
        $dirs = array_unique($dirs);
        foreach ($dirs as $id) {
            $app = new App();
            if ($app->LoadInfoByXml('theme', $id) == true) {
                $allThemes[] = $app;
            }
        }

        return $allThemes;
    }

    /**
     * 载入插件列表.
     *
     * @return App[]
     */
    public function LoadPlugins()
    {
        $allPlugins = array();
        $dirs = GetDirsInDir($this->usersdir . 'plugin/');
        natcasesort($dirs);

        foreach ($dirs as $id) {
            $app = new App();
            if ($app->LoadInfoByXml('plugin', $id) == true) {
                $allPlugins[] = $app;
            }
        }

        return $allPlugins;
    }

    /**
     * 载入指定应用.
     *
     * @param string $type 应用类型(theme|plugin)
     * @param string $id   应用ID
     *
     * @return App
     */
    public function LoadApp($type, $id)
    {
        $app = new App();
        if ($app->LoadInfoByXml($type, $id) != true) {
            $app->isloaded = false;
        }

        return $app;
    }

    /**
     * 检查应用是否安装并启用.
     *
     * @param string $name 应用（插件或主题）的ID
     *
     * @return bool
     */
    public function CheckPlugin($name)
    {
        return in_array($name, $this->activedapps);
    }

    /**
     * 检查应用是否安装并启用.
     *
     * @param string $name 应用ID（插件或主题）
     *
     * @return bool
     */
    public function CheckApp($name)
    {
        return $this->CheckPlugin($name);
    }

    /**
     * 获取预激活插件名数组.
     *
     * @return string[]
     */
    public function GetPreActivePlugin()
    {
        $ap = explode("|", $this->option['ZC_USING_PLUGIN_LIST']);
        $ap = array_unique($ap);

        return $ap;
    }

    /**
     * 载入指定应用语言包.
     *
     * @param string $type    应用类型(system|theme|plugin)
     * @param string $id      应用ID
     * @param string $default 默认语言
     *
     * @throws Exception
     *
     * @return null
     */
    public function LoadLanguage($type, $id, $default = '')
    {
        $languagePath = $this->path;
        $languageRegEx = '/^([0-9A-Z\-_]*)\.php$/ui';
        $languageList = array();
        $language = '';
        $default = str_replace(array('/', '\\'), '', $default);
        $languagePtr = &$this->lang;

        if ($default === '') {
            $default = $this->option['ZC_BLOG_LANGUAGEPACK'];
        }

        $defaultLanguageList = array($default, 'zh-cn', 'zh-tw', 'en');

        switch ($type) {
            case 'system':
                $languagePath .= 'zb_users/language/';
                break;
            case 'plugin':
            case 'theme':
                $languagePath .= 'zb_users/' . $type . '/' . $id . '/language/';
                $languagePtr = &$this->lang[$id];
                break;
            default:
                $languagePath .= $type . '/language/';
                $languagePtr = &$this->lang[$id];
                break;
        }

        $handle = @opendir($languagePath);
        $match = null;
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if (preg_match($languageRegEx, $file, $match)) {
                    $languageList[] = $match[1];
                }
            }
            closedir($handle);
        } else {
            // 这里不会执行到，在opendir时就已经抛出E_WARNING
            throw new Exception('Cannot opendir(' . $languagePath . ')');
        }

        if (count($languageList) === 0) {
            throw new Exception('No language in ' . $languagePath);
        }

        for ($i = 0; $i < count($defaultLanguageList); $i++) {
            // 在效率上，array_search和命名数组没有本质区别，至少在这里如此。
            if (false !== array_search($defaultLanguageList[$i], $languageList)) {
                $language = $defaultLanguageList[$i];
                break;
            }
        }
        if ($language === '') {
            throw new Exception('Language ' . $default . ' is not found in ' . $languagePath);
        }

        $languagePath .= $language . '.php';
        $languagePtr = include $languagePath;
        $this->langpacklist[] = array($type, $id, $language);
        if ($type == 'system') {
            if (is_readable($this->systemdir . 'defend/en.php')) {
                $defend_en = include $this->systemdir . 'defend/en.php';
                $nowlang = $languagePtr;
                $this->lang = ($nowlang + $defend_en);
                foreach ($this->lang as $key => $value) {
                    if (is_array($value)) {
                        $this->lang[$key] = ($nowlang[$key] + $defend_en[$key]);
                    }
                }
            }
            $this->lang['error']['77'] = str_replace(array('%min', '%max'), array($this->option['ZC_USERNAME_MIN'], $this->option['ZC_USERNAME_MAX']), $this->lang['error']['77']);
            $this->langs = json_decode(json_encode($this->lang));
        } else {
            if ($id != '' && isset($this->lang[$id])) {
                $this->langs->$id = json_decode(json_encode($this->lang[$id]));
            }
        }

        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_LoadLanguage'] as $fpname => &$fpsignal) {
            $fpname($this->lang);
            $this->langs = json_decode(json_encode($this->lang));
        }

        return true;
    }

    public function ReflushLanguages()
    {
        $this->langs = json_decode(json_encode($this->lang));
    }

    /**
     * 重新读取语言包.
     *
     * @throws Exception
     */
    public function ReloadLanguages()
    {
        $array = $this->langpacklist;
        $this->lang = $this->langpacklist = array();
        foreach ($array as $v) {
            $this->LoadLanguage($v[0], $v[1], $v[2]);
        }
    }

    /**
     * 创建模板对象，预加载已编译模板
     *
     * @param string $theme 指定主题名
     *
     * @return Template
     */
    public function PrepareTemplate($theme = null, $template_dirname = 'template')
    {
        if (is_null($theme) || empty($theme)) {
            $theme = &$this->theme;
        }

        $template = new Template();
        $template->MakeTemplateTags();

        //老接口，只改templateTags的
        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_MakeTemplatetags'] as $fpname => &$fpsignal) {
            $fpname($template->templateTags);
        }

        //此处增加接口可以在Load时，对$theme, $template_dirname参数可以进行修改
        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_PrepareTemplate'] as $fpname => &$fpsignal) {
            $fpname($theme, $template_dirname);
        }

        $template->theme = $theme;
        $template->template_dirname = $template_dirname;

        $template->SetPath();

        $template->LoadTemplates();

        return $template;
    }

    /**
     * 针对有同一主题下有多套模板的解析
     * 直接在接口中直接调用$zbp->BuildTemplateMore进行重新编译其它模板
     * @return bool
     */
    public function BuildTemplate()
    {
        $this->BuildTemplate_Once();

        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_BuildTemplate_End'] as $fpname => &$fpsignal) {
            $fpname($this->template);
        }

        return true;
    }

    /**
     * 快捷重新编译指定主题模板目录名的模板
     *
     * @return bool
     */
    public function BuildTemplateMore($theme = null, $template_dirname = 'template')
    {
        if (is_null($theme) || empty($theme)) {
            $theme = &$this->theme;
        }
        $this->template->theme = $theme;
        $this->template->template_dirname = $template_dirname;
        $this->template->SetPath();
        return $this->BuildTemplate_Once();
    }

    /**
     * 模板解析.
     *
     * @return bool
     */
    private function BuildTemplate_Once()
    {
        $this->template->LoadTemplates();

        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_BuildTemplate'] as $fpname => &$fpsignal) {
            $fpname($this->template->templates);
        }

        return $this->template->BuildTemplate();
    }

    /**
     * 更新模板缓存.
     *
     * @param bool $onlycheck  为真的话，只判断是否需要而不Build
     * @param bool $forcebuild
     *
     * @return true or false
     */
    public function CheckTemplate($onlycheck = false, $forcebuild = false)
    {
        $this->template->LoadTemplates();
        $s = implode($this->template->templates);
        $md5 = md5($s);

        //本函数的返回值很有意思，为false表示需要rebuild 为true表示已重建完成或是不需要rebuild
        //$zbp->CheckTemplate(true) == false 的意思，就是判断模板需需要重刷新吗？

        //如果对比不一样,$onlycheck就有用了
        if ($md5 != $this->cache->templates_md5) {
            if ($onlycheck == true && $forcebuild == false) {
                return false;
            }
            $this->BuildTemplate();
            $this->cache->templates_md5 = $md5;
            $this->SaveCache();

            return true;
        }
        //如果对比一样的话，$forcebuild就有用了
        if ($md5 == $this->cache->templates_md5) {
            if ($forcebuild == true) {
                $this->BuildTemplate();
                $this->cache->templates_md5 = $md5;
                $this->SaveCache();
            }
        }

        return true;
    }

    /**
     * 生成所有进Ready List的模块的Content内容并保存.
     */
    public function BuildModule()
    {
        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_BuildModule'] as $fpname => &$fpsignal) {
            $fpname();
        }
        ModuleBuilder::Build();
    }

    /**
     * 重建模块.
     *
     * @param string $moduleFileName 模块名
     * @param string $moduleFunction 用户函数
     */
    public function RegBuildModule($moduleFileName, $moduleFunction)
    {
        ModuleBuilder::Reg($moduleFileName, $moduleFunction);
    }

    /**
     * 系统默认注册模块.
     */
    public function RegBuildModules()
    {
        $this->RegBuildModule('catalog', 'ModuleBuilder::Catalog');
        $this->RegBuildModule('calendar', 'ModuleBuilder::Calendar');
        $this->RegBuildModule('comments', 'ModuleBuilder::Comments');
        $this->RegBuildModule('previous', 'ModuleBuilder::LatestArticles');
        $this->RegBuildModule('archives', 'ModuleBuilder::Archives');
        $this->RegBuildModule('navbar', 'ModuleBuilder::Navbar');
        $this->RegBuildModule('tags', 'ModuleBuilder::TagList');
        $this->RegBuildModule('statistics', 'ModuleBuilder::Statistics');
        $this->RegBuildModule('authors', 'ModuleBuilder::Authors');
    }

    /**
     * 添加进入Build Ready列表的模块.
     *
     * @param string $moduleFileName 模块名
     * @param null   $parameters     模块参数
     */
    public function AddBuildModule($moduleFileName, $parameters = null)
    {
        $p = func_get_args();
        call_user_func_array('ModuleBuilder::Add', $p);
    }

    /**
     * 删除进入Build Ready列表模块.
     *
     * @param string $moduleFileName 模块名
     */
    public function DelBuildModule($moduleFileName)
    {
        ModuleBuilder::Del($moduleFileName);
    }

    /**
     * 查询指定数据结构的sql并返回Base对象列表.
     *
     * @param string|array $table    数据表
     * @param array        $datainfo 数据字段
     * @param string       $sql      SQL操作语句
     *
     * @return array
     */
    public function GetListCustom($table, $datainfo, $sql)
    {
        $array = null;
        $list = array();
        $array = $this->db->Query($sql);
        if (!isset($array)) {
            return array();
        }
        foreach ($array as $a) {
            $l = new Base($table, $datainfo);
            $l->LoadInfoByAssoc($a);
            $id = $l->GetIdName();
            if ($this->CheckCache('Base', $l->$id) == false) {
                $this->AddCache($l);
            }
            $list[] = $l;
        }

        return $list;
    }

    /**
     * 查询ID数据的指定数据结构的sql并返回Base对象列表.
     *
     * @param string|array $table    数据表
     * @param array        $datainfo 数据字段
     * @param array        $array    ID数组
     *
     * @return Base[]
     */
    public function GetListCustomByArray($table, $datainfo, $array)
    {
        if (!is_array($array)) {
            return array();
        }

        if (count($array) == 0) {
            return array();
        }

        $where = array();
        $where[] = array('IN', $datainfo['ID'][0], implode(',', $array));
        $sql = $this->db->sql->Select($table, '*', $where);
        $array = null;
        $list = array();
        $array = $this->db->Query($sql);
        if (!isset($array)) {
            return array();
        }
        foreach ($array as $a) {
            $l = new Base($table, $datainfo);
            $l->LoadInfoByAssoc($a);
            $id = $l->GetIdName();
            if ($this->CheckCache('Base', $l->$id) == false) {
                $this->AddCache($l);
            }
            $list[] = $l;
        }

        return $list;
    }

    /**
     * 已改名GetListType,1.5版中扔掉有歧义的GetList.
     *
     * @param $type
     * @param $sql
     *
     * @return Base[]
     */
    public function GetListType($type, $sql)
    {
        if (is_object($sql) && get_parent_class($sql) == 'SQL__Global') {
            $sql = $sql->sql;
        }
        $array = null;
        $list = array();
        $array = $this->db->Query($sql);
        if (!isset($array)) {
            return array();
        }

        foreach ($array as $a) {
            /** @var Base $l */
            $l = new $type();
            $l->LoadInfoByAssoc($a);
            $id = $l->GetIdName();
            if ($this->CheckCache($type, $l->$id) == false) {
                $this->AddCache($l);
            }
            
            $list[] = $l;
        }

        return $list;
    }

    /**
     * GetListOrigin.
     *
     * @param $sql
     *
     * @return Base[]
     */
    public function GetListOrigin($sql)
    {
        if (is_object($sql) && get_parent_class($sql) == 'SQL__Global') {
            $sql = $sql->sql;
        }
        $array = null;
        $list = array();
        $array = $this->db->Query($sql);
        if (!isset($array)) {
            return array();
        }
        foreach ($array as $a) {
            $list[] = $a;
        }

        return $list;
    }

    /**
     * 查询ID数据的指定类型的sql并返回指定类型对象列表.
     *
     * @param string $type  类型
     * @param mixed  $array ID数组
     *
     * @return Base[]
     */
    public function GetListTypeByArray($type, $array)
    {
        if (!is_array($array)) {
            return array();
        }

        if (count($array) == 0) {
            return array();
        }

        $where = array();
        $where[] = array('IN', $this->datainfo[$type]['ID'][0], implode(',', $array));
        $sql = $this->db->sql->Select($this->table[$type], '*', $where);
        $array = null;
        $list = array();
        $array = $this->db->Query($sql);
        if (!isset($array)) {
            return array();
        }
        foreach ($array as $a) {
            /** @var Base $l */
            $l = new $type();
            $l->LoadInfoByAssoc($a);
            $id = $l->GetIdName();
            if ($this->CheckCache($type, $l->$id) == false) {
                $this->AddCache($l);
            }
            $list[] = $l;
        }

        return $list;
    }

    /**
     * @param mixed $select
     * @param mixed $where
     * @param mixed $order
     * @param mixed $limit
     * @param mixed $option
     *
     * @return Post[]
     */
    public function GetPostList($select = null, $where = null, $order = null, $limit = null, $option = null)
    {
        if (is_object($select) && get_parent_class($select) == 'SQL__Global') {
            $sql = $select->sql;
        } else {
            $sql = $this->db->sql->Select($this->table['Post'], $select, $where, $order, $limit, $option);
        }

        /** @var Post[] $array */
        $array = $this->GetListType('Post', $sql, $option);
        if (isset($option['pagebar']) && is_object($option['pagebar'])) {
            $option['pagebar']->CurrentCount = count($array);
        }

        return $array;
    }

    /**
     * @param mixed $select
     * @param mixed $where
     * @param mixed $order
     * @param mixed $limit
     * @param mixed $option
     * @param mixed $readtags
     *
     * @return Post[]
     */
    public function GetArticleList($select = null, $where = null, $order = null, $limit = null, $option = null, $readtags = true)
    {
        if (empty($where)) {
            $where = array();
        }

        if (is_array($where)) {
            array_unshift($where, array('=', 'log_Type', '0'));
        }

        if (is_object($select) && get_parent_class($select) == 'SQL__Global') {
            $sql = $select->sql;
        } else {
            $sql = $this->db->sql->Select($this->table['Post'], $select, $where, $order, $limit, $option);
        }


        /** @var Post[] $array */
        $array = $this->GetListType('Post', $sql, $option);
        if (isset($option['pagebar']) && is_object($option['pagebar'])) {
            $option['pagebar']->CurrentCount = count($array);
        }

        if ($readtags) {
            $tagstring = '';
            foreach ($array as $a) {
                $tagstring .= $a->Tag;
            }
            $this->LoadTagsByIDString($tagstring);
        }

        return $array;
    }

    /**
     * @param mixed $select
     * @param mixed $where
     * @param mixed $order
     * @param mixed $limit
     * @param mixed $option
     *
     * @return Post[]
     */
    public function GetPageList($select = null, $where = null, $order = null, $limit = null, $option = null)
    {
        if (empty($where)) {
            $where = array();
        }
        if (is_array($where)) {
            array_unshift($where, array('=', 'log_Type', '1'));
        }

        if (is_object($select) && get_parent_class($select) == 'SQL__Global') {
            $sql = $select->sql;
        } else {
            $sql = $this->db->sql->Select($this->table['Post'], $select, $where, $order, $limit, $option);
        }

        /** @var Post[] $array */
        $array = $this->GetListType('Post', $sql);
        if (isset($option['pagebar']) && is_object($option['pagebar'])) {
            $option['pagebar']->CurrentCount = count($array);
        }

        return $array;
    }

    /**
     * @param mixed $select
     * @param mixed $where
     * @param mixed $order
     * @param mixed $limit
     * @param mixed $option
     *
     * @return Comment[]
     */
    public function GetCommentList($select = null, $where = null, $order = null, $limit = null, $option = null)
    {
        if (is_object($select) && get_parent_class($select) == 'SQL__Global') {
            $sql = $select->sql;
        } else {
            $sql = $this->db->sql->Select($this->table['Comment'], $select, $where, $order, $limit, $option);
        }

        /** @var Comment[] $array */
        $array = $this->GetListType('Comment', $sql);
        if (isset($option['pagebar']) && is_object($option['pagebar'])) {
            $option['pagebar']->CurrentCount = count($array);
        }

        return $array;
    }

    /**
     * @param mixed $select
     * @param mixed $where
     * @param mixed $order
     * @param mixed $limit
     * @param mixed $option
     *
     * @return Member[]|Base[]
     */
    public function GetMemberList($select = null, $where = null, $order = null, $limit = null, $option = null)
    {
        if (is_object($select) && get_parent_class($select) == 'SQL__Global') {
            $sql = $select->sql;
        } else {
            $sql = $this->db->sql->Select($this->table['Member'], $select, $where, $order, $limit, $option);
        }

        $array = $this->GetListType('Member', $sql);
        if (isset($option['pagebar']) && is_object($option['pagebar'])) {
            $option['pagebar']->CurrentCount = count($array);
        }

        return $array;
    }

    /**
     * @param mixed $select
     * @param mixed $where
     * @param mixed $order
     * @param mixed $limit
     * @param mixed $option
     *
     * @return Tag[]|Base[]
     */
    public function GetTagList($select = null, $where = null, $order = null, $limit = null, $option = null)
    {
        if (is_object($select) && get_parent_class($select) == 'SQL__Global') {
            $sql = $select->sql;
        } else {
            $sql = $this->db->sql->Select($this->table['Tag'], $select, $where, $order, $limit, $option);
        }

        $array = $this->GetListType('Tag', $sql);
        if (isset($option['pagebar']) && is_object($option['pagebar'])) {
            $option['pagebar']->CurrentCount = count($array);
        }

        return $array;
    }

    /**
     * @param mixed $select
     * @param mixed $where
     * @param mixed $order
     * @param mixed $limit
     * @param mixed $option
     *
     * @return Category[]|Base[]
     */
    public function GetCategoryList($select = null, $where = null, $order = null, $limit = null, $option = null)
    {
        if (is_object($select) && get_parent_class($select) == 'SQL__Global') {
            $sql = $select->sql;
        } else {
            $sql = $this->db->sql->Select($this->table['Category'], $select, $where, $order, $limit, $option);
        }

        $array = $this->GetListType('Category', $sql);
        if (isset($option['pagebar']) && is_object($option['pagebar'])) {
            $option['pagebar']->CurrentCount = count($array);
        }

        return $array;
    }

    /**
     * @param mixed $select
     * @param mixed $where
     * @param mixed $order
     * @param mixed $limit
     * @param mixed $option
     *
     * @return Module[]|Base[]
     */
    public function GetModuleList($select = null, $where = null, $order = null, $limit = null, $option = null)
    {
        if (is_object($select) && get_parent_class($select) == 'SQL__Global') {
            $sql = $select->sql;
        } else {
            $sql = $this->db->sql->Select($this->table['Module'], $select, $where, $order, $limit, $option);
        }

        $array = $this->GetListType('Module', $sql);
        if (isset($option['pagebar']) && is_object($option['pagebar'])) {
            $option['pagebar']->CurrentCount = count($array);
        }

        return $array;
    }

    /**
     * @param mixed $select
     * @param mixed $where
     * @param mixed $order
     * @param mixed $limit
     * @param mixed $option
     *
     * @return Upload[]|Base[]
     */
    public function GetUploadList($select = null, $where = null, $order = null, $limit = null, $option = null)
    {
        if (is_object($select) && get_parent_class($select) == 'SQL__Global') {
            $sql = $select->sql;
        } else {
            $sql = $this->db->sql->Select($this->table['Upload'], $select, $where, $order, $limit, $option);
        }

        $array = $this->GetListType('Upload', $sql);
        if (isset($option['pagebar']) && is_object($option['pagebar'])) {
            $option['pagebar']->CurrentCount = count($array);
        }

        return $array;
    }

    /**
     * 通过ID数组获取文章实例.
     *
     * @param mixed[] $array
     *
     * @return Post[]|Base[] Posts
     */
    public function GetPostByArray($array)
    {
        $posts = $this->GetListTypeByArray('Post', $array);

        return $posts;
    }

    /**
     * 通过ID数组获取评论实例.
     */
    public function GetCommentByArray($array)
    {
        $posts = $this->GetListTypeByArray('Comment', $array);

        return $posts;
    }

    /**
     * 通过ID数组获取Member实例.
     */
    public function GetMemberByArray($array)
    {
        $posts = $this->GetListTypeByArray('Member', $array);

        return $posts;
    }

    /**
     * 通过ID数组获取Category实例.
     */
    public function GetCategoryByArray($array)
    {
        $posts = $this->GetListTypeByArray('Category', $array);

        return $posts;
    }

    /**
     * 通过ID数组获取Tag实例.
     */
    public function GetTagByArray($array)
    {
        $posts = $this->GetListTypeByArray('Tag', $array);

        return $posts;
    }

    /**
     * 通过ID数组获取Module实例.
     */
    public function GetModuleByArray($array)
    {
        $posts = $this->GetListTypeByArray('Module', $array);

        return $posts;
    }

    /**
     * 通过ID数组获取Upload实例.
     */
    public function GetUploadByArray($array)
    {
        $posts = $this->GetListTypeByArray('Upload', $array);

        return $posts;
    }

    /**
     * @param $sql
     *
     * @return mixed
     */
    public function get_results($sql)
    {
        return $this->db->Query($sql);
    }

    /**
     * 根据别名得到相应数据.
     *
     * @param Base[]|string &$object   缓存对象
     * @param string        $val
     * @param string        $backAttr
     * @param string        $className
     *
     * @return Base|null
     */
    private function GetSomeThingByAlias($object, $val, $backAttr = null, $className = null)
    {
        $ret = $this->GetSomeThing($object, 'Alias', $val, $className);

        if (!is_null($ret)) {
            return $ret;
        } else {
            if (is_null($backAttr)) {
                $backAttr = $this->option['ZC_ALIAS_BACK_ATTR'];
            }

            return $this->GetSomeThing($object, $backAttr, $val, $className);
        }
    }

    /**
     * 根据ID得到相应数据.
     *
     * @param Base[]     &$object   缓存对象
     * @param string     $className 找不到ID时初始化对象的类名
     * @param int|string $id        与此类相关的ID
     *
     * @return Base|null
     */
    private function GetSomeThingById(&$object, $className, $id)
    {
        if (empty($id)) {
            return;
        }

        //$modules非ID为key
        //if ($className == "Module") {
        //    foreach ($object as $key => $value) {
        //        if ($value->ID == $id) {
        //            return $value;
        //        }
        //    }
        //    $m = new Module();

        //    return $m;
        //}

        if (array_key_exists($id, $object)) {
            return $object[$id];
        } else {
            $p = new $className();
            if ($p->LoadInfoByID($id)) {
                $this->AddCache($p);
            }

            return $p;
        }
    }

    /**
     * 根据属性值得到相应数据.
     *
     * @param Base[] &$object 缓存对象
     * @param string        $className 对象未找到时，初始化类名
     * @param string $attr    属性名
     * @param mixed  $val     要查找的值
     *
     * @return null
     */
    private function GetSomeThingByAttr(&$object, $className, $attr, $val)
    {
        $cacheObject = null;
        if (is_array($object)) {
            $cacheObject = &$object;
        } elseif ($className != '') {
            if (!isset($this->cacheobject[$className])) {
                $this->cacheobject[$className] = array();
            }
            $cacheObject = &$this->cacheobject[$className];
        }

        //如果是多重属性和值查询
        if (is_array($attr) && is_array($val)) {
            $val1 = trim($val[0]);
            $val2 = trim($val[1]);
            $val3 = isset($val[2]) ? $val[2] : null;
            $attr1 = $attr[0];
            $attr2 = $attr[1];
            $attr3 = isset($attr[2]) ? $attr[2] : null;
            foreach ($cacheObject as $key => &$value) {
                if (is_null($value)) {
                    continue;
                }
                if ($attr3 !== null && $value->$attr1 == $val1 && $value->$attr2 == $val2 && $value->$attr3 == $val3) {
                    return $value;
                } elseif ($value->$attr1 == $val1 && $value->$attr2 == $val2) {
                    return $value;
                }
            }
        } else {
            $val = trim($val);
            foreach ($cacheObject as $key => &$value) {
                if (is_null($value)) {
                    continue;
                }
                if ($value->$attr == $val) {
                    return $value;
                }
            }
        }
    }

    /**
     * 获取数据通用函数.
     *
     * @param Base[]|string $object    缓存对象（string / object）
     * @param string        $attr      欲查找的属性
     * @param mixed         $val       要查找内容
     * @param string        $className 对象未找到时，初始化类名
     *
     * @return Base|null
     */
    public function GetSomeThing($object, $attr, $val, $className)
    {
        $cacheObject = null;
        if (is_array($object)) {
            $cacheObject = $object;
        } elseif ($className != '') {
            if (!isset($this->cacheobject[$className])) {
                $this->cacheobject[$className] = array();
            }
            $cacheObject = &$this->cacheobject[$className];
        }
        if ($attr == "ID") {
            $ret = $this->GetSomeThingById($cacheObject, $className, $val);
        } else {
            $ret = $this->GetSomeThingByAttr($cacheObject, $className, $attr, $val);
        }
        if ($ret === null && !is_null($className)) {
            /** @var Base $ret */
            $ret = new $className();
        }

        return $ret;
    }

    /**
     * 通过ID获取文章实例.
     *
     * @param int $id
     *
     * @return Post|Base
     */
    public function GetPostByID($id)
    {
        return $this->GetSomeThing('posts', 'ID', $id, 'Post');
    }

    /**
     * 通过ID获取分类实例.
     *
     * @param int $id
     *
     * @return Category|Base
     */
    public function GetCategoryByID($id)
    {
        return $this->GetSomeThing('categories_all', 'ID', $id, 'Category');
    }

    /**
     * 通过分类名获取分类实例.
     *
     * @param string $name
     *
     * @return Category|Base
     */
    public function GetCategoryByName($name, $type = 0)
    {
        if ($type === null) {
            $categorys = &$this->categories_all;
        } else {
            $categorys = &$this->categoriesbyorder_type[$type];
        }
        foreach ($categorys as $key => $c) {
            if ($name == $c->Name) {
                return $c;
            }
        }
        return new Category;
    }

    /**
     * 通过分类别名获取分类实例.
     *
     * @param string $name
     * @param null   $backKey
     *
     * @return Category|Base
     */
    public function GetCategoryByAlias($name, $type = 0)
    {
        if ($type === null) {
            $categorys = &$this->categories_all;
        } else {
            $categorys = &$this->categoriesbyorder_type[$type];
        }
        foreach ($categorys as $key => $c) {
            if ($name == $c->Alias) {
                return $c;
            }
        }
        return new Category;
    }

    /**
     * 与老版本保持兼容函数.
     *
     * @param string $name
     *
     * @return Category
     */
    public function GetCategoryByAliasOrName($name, $type = 0)
    {
        if ($type === null) {
            $categorys = &$this->categories_all;
        } else {
            $categorys = &$this->categoriesbyorder_type[$type];
        }
        foreach ($categorys as $key => $c) {
            if ($name == $c->Alias || $name == $c->Name) {
                return $c;
            }
        }
        return new Category;
    }

    /**
     * 通过ID获取模块实例.
     *
     * @param int $id
     *
     * @return Module|Base
     */
    public function GetModuleByID($id)
    {
        return $this->GetSomeThing('modules', 'ID', $id, 'Module'); // What the fuck?
    }

    /**
     * 通过FileName获取模块实例.
     *
     * @param string $fn
     *
     * @return Module|Base
     */
    public function GetModuleByFileName($fn)
    {
        return $this->GetSomeThing('modulesbyfilename', 'FileName', $fn, 'Module');
    }

    /**
     * 通过ID获取用户实例.
     *
     * @param int $id
     *
     * @return Member|Base
     */
    public function GetMemberByID($id)
    {
        /** @var Member $ret */
        $ret = $this->GetSomeThing('members', 'ID', $id, 'Member');
        if ($ret->ID == null) {
            $ret->Guid = GetGuid();
        }

        return $ret;
    }

    /**
     * 通过用户名获取用户实例(不区分大小写).
     *
     * @param string $name
     *
     * @return Member|Base
     */
    public function GetMemberByName($name)
    {
        $name = trim($name);
        if (!$name || !CheckRegExp($name, '[username]')) {
            return new Member();
        }

        if (isset($this->membersbyname[$name])) {
            return $this->membersbyname[$name];
        } else {
            $array = array_keys($this->membersbyname);
            foreach ($array as $k => $v) {
                if (strcasecmp($name, $v) == 0) {
                    return $this->membersbyname[$v];
                }
            }
        }

        $like = ($this->db->type == 'pgsql') ? 'ILIKE' : 'LIKE';
        $sql = $this->db->sql->Select($this->table['Member'], '*', array(array($like, 'mem_Name', $name)), array('mem_ID' => 'ASC'), 1, null);

        /** @var Member[] $am */
        $am = $this->GetListType('Member', $sql);
        if (count($am) > 0) {
            $m = $am[0];

            return $m;
        }

        return new Member();
    }

    /**
     * 通过获取用户名或别名实例(不区分大小写).
     *
     * @param string $name
     *
     * @return Member|Base
     */
    public function GetMemberByNameOrAlias($name)
    {
        $name = trim($name);
        if (!$name || !(CheckRegExp($name, '[username]') || CheckRegExp($name, '[nickname]'))) {
            return new Member();
        }

        foreach ($this->members as $key => &$value) {
            if (strcasecmp($value->Name, $name) == 0 || strcasecmp($value->Alias, $name) == 0) {
                return $value;
            }
        }

        $like = ($this->db->type == 'pgsql') ? 'ILIKE' : 'LIKE';

        $sql = $this->db->sql->get()->select($this->table['Member'])->where(
            array(
                "$like array", array(
                    array('mem_Name', $name),
                    array('mem_Alias', $name),
                )
            )
        )->limit(1)->sql;

        /** @var Member[] $am */
        $am = $this->GetListType('Member', $sql);
        if (count($am) > 0) {
            $m = $am[0];

            return $m;
        }

        return new Member();
    }

    /**
     * 通过获取用户的别名的实例(不区分大小写).
     *
     * @param string $name
     *
     * @return Member|Base
     */
    public function GetMemberByAlias($name)
    {
        $name = trim($name);
        if (!$name || !(CheckRegExp($name, '[nickname]'))) {
            return new Member();
        }

        foreach ($this->members as $key => &$value) {
            if (strcasecmp($value->Alias, $name) == 0) {
                return $value;
            }
        }

        $like = ($this->db->type == 'pgsql') ? 'ILIKE' : 'LIKE';

        $sql = $this->db->sql->get()->select($this->table['Member'])->where(
            array(
                array('=', 'mem_Alias', $name)
            )
        )->limit(1)->sql;

        /** @var Member[] $am */
        $am = $this->GetListType('Member', $sql);
        if (count($am) > 0) {
            $m = $am[0];

            return $m;
        }

        return new Member();
    }

    /**
     * 通过邮箱名获取用户实例(不区分大小写).
     *
     * @param string $email
     *
     * @return Member
     */
    public function GetMemberByEmail($email)
    {
        $email = strtolower(trim($email));
        if (!$email || !CheckRegExp($email, '[email]')) {
            return new Member();
        }

        $sql = $this->db->sql->Select($this->table['Member'], '*', array(array('LIKE', 'mem_Email', $email)), null, 1, null);
        /** @var Member[] $am */
        $am = $this->GetListType('Member', $sql);
        if (count($am) > 0) {
            $m = $am[0];

            return $m;
        }

        return new Member();
    }

    /**
     * 检查指定名称的用户是否存在(不区分大小写).
     *
     * @param $name
     *
     * @return bool
     */
    public function CheckMemberNameExist($name)
    {
        $m = $this->GetMemberByName($name);

        return $m->ID != null;
    }

    /**
     * 检查指定名称或别名的用户是否存在(不区分大小写).
     *
     * @param $name
     *
     * @return bool
     */
    public function CheckMemberByNameOrAliasExist($name)
    {
        $m = $this->GetMemberByNameOrAlias($name);

        return $m->ID > 0;
    }

    /**
     * 检查指定邮箱的用户是否存在(不区分大小写).
     *
     * @param $email
     *
     * @return bool
     */
    public function CheckMemberByEmailExist($email)
    {
        $m = $this->GetMemberByEmail($email);

        return $m->ID > 0;
    }

    /**
     * 通过ID获取评论实例.
     *
     * @param int $id
     *
     * @return Comment|Base
     */
    public function GetCommentByID($id)
    {
        return $this->GetSomeThing('comments', 'ID', $id, 'Comment');
    }

    /**
     * 通过ID获取附件实例.
     *
     * @param int $id
     *
     * @return Upload|Base
     */
    public function GetUploadByID($id)
    {
        return $this->GetSomeThing('Upload', 'ID', $id, 'Upload');
    }

    /**
     * 通过tag别名获取tag实例.(先走cacheobject再走查数据库)
     *
     * @param string $name
     * @param null   $backKey
     *
     * @return Tag|Base
     */
    public function GetTagByAlias($name, $type = 0)
    {
        $ret = $this->GetSomeThingByAttr($this->tags, 'Tag', array('Alias','Type'), array($name, $type));
        if (is_object($ret) && $ret->ID >= 0) {
            return $ret;
        }

        $a = array();
        $a[] = array('=','tag_Alias', $name);
        $a[] = array('=', 'tag_Type', $type);
        $array = $this->GetTagList('*', array($a), '', 1, '');
        if (count($array) == 0) {
            return new Tag();
        } else {
            return $array[0];
        }
    }

    /**
     * 通过tag名获取tag实例.(先走cacheobject再走查数据库)
     *
     * @param string $name
     * @param null   $backKey
     *
     * @return Tag|Base
     */
    public function GetTagByName($name, $type = 0)
    {
        $ret = $this->GetSomeThingByAttr($this->tags, 'Tag', array('Name','Type'), array($name, $type));
        if (is_object($ret) && $ret->ID >= 0) {
            return $ret;
        }

        $a = array();
        $a[] = array('=', 'tag_Name', $name);
        $a[] = array('=', 'tag_Type', $type);
        $array = $this->GetTagList('*', array($a), '', 1, '');
        if (count($array) == 0) {
            return new Tag();
        } else {
            return $array[0];
        }
    }

    /**
     * 通过tag的别名或是名称获取tag实例.(查数据库非走cacheobject)
     *
     * @param string $name
     *
     * @return Tag|Base
     */
    public function GetTagByAliasOrName($name, $type = 0)
    {
        //return $this->GetTagByAlias($name, 'Name');
        $a = array();
        $a[] = array('tag_Alias', $name);
        $a[] = array('tag_Name', $name);
        $b = array('=', 'tag_Type', $type);
        $array = $this->GetTagList('*', array(array('array', $a), $b), '', 1, '');
        if (count($array) == 0) {
            return new Tag();
        } else {
            return $array[0];
        }
    }

    /**
     * 通过ID获取tag实例.
     *
     * @param int $id
     *
     * @return Tag|Base
     */
    public function GetTagByID($id)
    {
        $ret = $this->GetSomeThing('tags_all', 'ID', $id, 'Tag');

        return $ret;
    }

    /**
     * 通过类似'{1}{2}{3}{4}'载入tags.
     *
     * @param $s
     *
     * @return array
     */
    public function LoadTagsByIDString($s)
    {
        $s = trim($s);
        if ($s === '') {
            return array();
        }

        $s = str_replace('}{', '|', $s);
        $s = str_replace(array('{', '}'), '', $s);
        $a = explode('|', $s);
        $b = array();
        foreach ($a as &$value) {
            $value = trim($value);
            if ($value) {
                $b[] = $value;
            }
        }
        $t = array_unique($b);

        if (count($t) === 0) {
            return array();
        }

        $c = array();
        $d = array();
        foreach ($t as $v) {
            if (array_key_exists($v, $this->tags_all) == false) {
                $c[] = $v;
            } else {
                $d[$v] = &$this->tags_all[$v];
            }
        }

        if (count($c) === 0) {
            return $d;
        } else {
            $t = array();
            $array = $this->GetTagList('', array(array('IN', 'tag_ID', $c)), '', '', '');
            foreach ($array as $v) {
                $t[$v->ID] = &$this->tags_all[$v->ID];
            }

            return array_merge($d, $t);
        }
    }

    /**
     * 通过类似'aaa,bbb,ccc,ddd'载入tags.
     *
     * @param string $s 标签名字符串，如'aaa,bbb,ccc,ddd
     * @param int $posttype type
     *
     * @return array
     */
    public function LoadTagsByNameString($s, $posttype = 0)
    {
        $s = trim($s);
        $s = str_replace(array(';', '，', '、'), ',', $s);
        $s = trim($s);
        $s = strip_tags($s);
        if ($s === '' || $s === ',') {
            return array();
        }

        $a = explode(',', $s);
        $t = array_unique($a);

        if (count($t) === 0) {
            return array();
        }

        $a = array();
        $b = array();
        foreach ($t as $value) {
            $v = trim($value);
            if (isset($this->tagsbyname_type[$posttype][$v]) == false) {
                $a[] = array('tag_Name', $v);
            } else {
                $b[$v] = &$this->tagsbyname_type[$posttype][$v];
            }
        }

        if (count($a) === 0) {
            return $b;
        } else {
            $t = array();
            $array = $this->GetTagList('', array(array('=', 'tag_Type', $posttype), array('array', $a)), '', '', '');
            foreach ($array as $v) {
                $t[$v->Name] = &$this->tagsbyname_type[$posttype][$v->ID];
            }
            return $t;
        }
    }

    /**
     * 通过数组array[111,333,444,555,666]转换成存储串.
     *
     * @param array $array 标签ID数组
     *
     * @return string
     */
    public function ConvertTagIDtoString($array)
    {
        $s = '';
        foreach ($array as $a) {
            $s .= '{' . $a . '}';
        }

        return $s;
    }

    public function GetTopArticle($type = 0)
    {
        return $this->GetTopPost($type);
    }

    /**
     * 获取全部置顶文章（优先从cache里读数组）.
     *
     * @param int $type
     *
     * @return array
     */
    public function GetTopPost($type = 0)
    {
        $varname = 'top_post_array_' . $type;
        if ($this->cache->HasKey($varname) == false) {
            return array();
        }

        $articles_top_notorder_idarray = unserialize($this->cache->$varname);
        if (!is_array($articles_top_notorder_idarray)) {
            CountTopPost($type, null, null);
            $articles_top_notorder_idarray = unserialize($this->cache->$varname);
        }
        $articles_top_notorder = $this->GetPostByArray($articles_top_notorder_idarray);

        return $articles_top_notorder;
    }

    //###############################################################################################################
    //验证相关

    /**
     * 获取评论key.
     *
     * @param $id
     *
     * @return string
     */
    public function GetCmtKey($id)
    {
        return md5($this->guid . $id . date('Ymdh'));
    }

    /**
     * 验证评论key.
     *
     * @param $id
     * @param $key
     *
     * @return bool
     */
    public function ValidCmtKey($id, $key)
    {
        $nowkey = md5($this->guid . $id . date('Ymdh'));
        $nowkey2 = md5($this->guid . $id . date('Ymdh', (time() - (3600 * 1))));

        return $key == $nowkey || $key == $nowkey2;
    }

    /**
     * 获取CSRF Token.
     *
     * @param string $id 应用ID，可以保证每个应用获取不同的Token
     *
     * @return string
     */
    public function GetCSRFToken($id = '')
    {
        $s = $this->user->ID . $this->user->Password . $this->user->Status;

        return md5($this->guid . $s . $id . date('Ymdh'));
    }

    /**
     * 验证CSRF Token.
     *
     * @param string $token
     * @param string $id    应用ID，可为每个应用生成一个专属token
     *
     * @return bool
     */
    public function VerifyCSRFToken($token, $id = '')
    {
        $userString = $this->user->ID . $this->user->Password . $this->user->Status;
        $tokenString = $this->guid . $userString . $id;

        for ($i = 0; $i <= $this->csrfExpiration; $i++) {
            if ($token === md5($tokenString . date('Ymdh', (time() - (3600 * $i))))) {
                return true;
            }
        }

        return false;
    }

    /**
     * 显示验证码
     *
     * @api Filter_Plugin_Zbp_ShowValidCode 如该接口未被挂载则显示默认验证图片
     *
     * @param string $id 命名事件
     *
     * @return bool
     */
    public function ShowValidCode($id = '')
    {
        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_ShowValidCode'] as $fpname => &$fpsignal) {
            return $fpname($id); //*
        }

        $_vc = new ValidateCode();
        $_vc->GetImg();
        setcookie('captcha_' . crc32($this->guid . $id), md5($this->guid . date("Ymdh") . $_vc->GetCode()), null, $this->cookiespath);

        return true;
    }

    /**
     * 比对验证码
     *
     * @api Filter_Plugin_Zbp_CheckValidCode 如该接口未被挂载则比对默认验证码
     *
     * @param string $verifyCode 验证码数值
     * @param string $id         命名事件
     *
     * @return bool
     */
    public function CheckValidCode($verifyCode, $id = '')
    {
        $verifyCode = strtolower($verifyCode);
        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_CheckValidCode'] as $fpname => &$fpsignal) {
            return $fpname($verifyCode, $id); //*
        }

        $original = GetVars('captcha_' . crc32($this->guid . $id), 'COOKIE');
        setcookie('captcha_' . crc32($this->guid . $id), '', (time() - 3600), $this->cookiespath);

        return md5($this->guid . date("Ymdh") . strtolower($verifyCode)) == $original
            ||
            md5($this->guid . date("Ymdh", (time() - (3600 * 1))) . strtolower($verifyCode)) == $original;
    }

    /**
     * 向导航菜单添加相应条目.
     *
     * @param string $type $type=category,tag,page,item
     * @param string $id
     * @param string $name
     * @param string $url
     */
    public function AddItemToNavbar($type, $id, $name, $url)
    {
        if (!$type) {
            $type = 'item';
        }

        $m = $this->modulesbyfilename['navbar'];
        $s = $m->Content;

        $a = '<li id="navbar-' . $type . '-' . $id . '"><a href="' . $url . '">' . $name . '</a></li>';

        if ($this->CheckItemToNavbar($type, $id)) {
            $s = preg_replace('/<li id="navbar-' . $type . '-' . $id . '">.*?<\/li>/', $a, $s);
        } else {
            $s .= '<li id="navbar-' . $type . '-' . $id . '"><a href="' . $url . '">' . $name . '</a></li>';
        }

        $m->Content = $s;
        $m->Save();
    }

    /**
     * 删除导航菜单中相应条目.
     *
     * @param string $type
     * @param $id
     */
    public function DelItemToNavbar($type, $id)
    {
        if (!$type) {
            $type = 'item';
        }

        $m = $this->modulesbyfilename['navbar'];
        $s = $m->Content;

        $s = preg_replace('/<li id="navbar-' . $type . '-' . $id . '">.*?<\/li>/', '', $s);

        $m->Content = $s;
        $m->Save();
    }

    /**
     * 检查条目是否在导航菜单中.
     *
     * @param string $type
     * @param $id
     *
     * @return bool
     */
    public function CheckItemToNavbar($type, $id)
    {
        if (!$type) {
            $type = 'item';
        }

        $m = $this->modulesbyfilename['navbar'];
        $s = $m->Content;

        return (bool) strpos($s, 'id="navbar-' . $type . '-' . $id . '"');
    }

    //$signal = good,bad,tips
    private $hints = array();

    /**
     * 设置提示消息并存入Cookie.
     *
     * @param string $signal  提示类型（good|bad|tips）
     * @param string $content 提示内容
     * @param int $delay 延时时间
     */
    public function SetHint($signal, $content = '', $delay = 10)
    {
        if ($content == '') {
            if (substr($signal, 0, 4) == 'good') {
                $content = $this->lang['msg']['operation_succeed'];
            }

            if (substr($signal, 0, 3) == 'bad') {
                $content = $this->lang['msg']['operation_failed'];
            }
        }
        $content = substr($content, 0, 255);
        for ($i = 1; $i <= 10; $i++) {
            if (isset($this->hints[$i])) {
                continue;
            }
            $hint = new stdClass;
            $hint->signal = $signal;
            $hint->content = $content;
            $hint->delay = $delay;
            $this->hints[$i] = $hint;
            setcookie("hint_signal" . $i, json_encode($hint), 0, $this->cookiespath);
            break;
        }
    }

    /**
     * 提取Cookie中的提示消息.
     */
    public function GetHint()
    {
        for ($i = 1; $i <= 10; $i++) {
            if (isset($this->hints[$i]) && is_object($this->hints[$i])) {
                $this->ShowHint($this->hints[$i]);
            }
        }
        for ($i = 1; $i <= 10; $i++) {
            $signal = GetVars('hint_signal' . $i, 'COOKIE');
            $hint = json_decode($signal);
            if ($hint !== null) {
                $this->ShowHint($hint);
                setcookie("hint_signal" . $i, '', (time() - 3600), $this->cookiespath);
            }
        }
    }

    /**
     * 由提示消息获取HTML.
     *
     * @param string $signal  提示类型（good|bad|tips）
     * @param string $content 提示内容
     * @param int $delay 延时时间
     */
    public function ShowHint($signal, $content = '', $delay = 10)
    {
        //1.7增加$signal为json类型
        $hint = $signal;
        if (is_object($hint)) {
            $signal = $hint->signal;
            $content = $hint->content;
            $delay = $hint->delay;
        }

        if ($content == '') {
            if (substr($signal, 0, 4) == 'good') {
                $content = $this->lang['msg']['operation_succeed'];
            }

            if (substr($signal, 0, 3) == 'bad') {
                $content = $this->lang['msg']['operation_failed'];
            }
        }
        $delay = ($delay * 1000);
        echo "<div class=\"hint\"><p class=\"hint hint_$signal\" data-delay=\"$delay\">$content</p></div>";
    }

    /**
     * 显示错误信息.
     *
     * @api Filter_Plugin_Zbp_ShowError
     *
     * @param string/int $errorText
     * @param null       $file
     * @param null       $line
     *
     * @throws Exception
     *
     * @return mixed
     */
    public function ShowError($errorText, $file = null, $line = null, $moreinfo = array())
    {
        $errorCode = 0;
        if (is_numeric($errorText)) {
            $errorCode = (int) $errorText;
            $errorText = $this->lang['error'][$errorText];
        }

        if ($errorCode == 2) {
            Http404();
        }

        ZBlogException::$error_id = $errorCode;
        ZBlogException::$error_file = $file;
        ZBlogException::$error_line = $line;
        $array = ZBlogException::$error_moreinfo;
        ZBlogException::$error_moreinfo = array_merge($array, $moreinfo);

        if (stripos('{' . sha1('mustshowerror') . '}', $errorText) === 0) {
            $errorText = str_replace('{' . sha1('mustshowerror') . '}', '', $errorText);
            $GLOBALS['hooks']['Filter_Plugin_Debug_Display'] = array();
            $GLOBALS['hooks']['Filter_Plugin_Debug_Handler'] = array();

            throw new Exception($errorText);
        }

        foreach ($GLOBALS['hooks']['Filter_Plugin_Zbp_ShowError'] as $fpname => &$fpsignal) {
            $fpreturn = $fpname($errorCode, $errorText, $file, $line, $moreinfo);
            if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
                $fpsignal = PLUGIN_EXITSIGNAL_NONE;

                return $fpreturn;
            }
        }

        throw new Exception($errorText);
    }

    /**
     * 检测网站关闭，如果关闭，则抛出错误.
     *
     * @throws Exception
     */
    public function CheckSiteClosed()
    {
        if ($this->option['ZC_CLOSE_SITE']) {
            Http503();
            $this->ShowError(82, __FILE__, __LINE__);
            exit;
        }
    }

    /**
     * 跳转到安装页面.
     */
    public function RedirectInstall()
    {
        if (!$this->option['ZC_DATABASE_TYPE']) {
            $s = $_SERVER['QUERY_STRING'];
            $s = empty($s) ? '' : '?' . $s;
            Redirect('./zb_install/index.php' . $s);
        }
    }

    /**
     * 注册PostType.
     *
     * @param $typeId
     * @param $name
     * @param string $urlRule      默认是array() 为Url Rule组合的数组
     * @param string $template     默认模板名是array() 为一个组合数组
     * @param string $className    默认类名
     * @param string $actions     默认Actions 应是array()
     *
     * @throws Exception
     */
    public function RegPostType($typeId, $name, $urlRule = array(), $template = array(), $className = 'Post', $actions = array(), $routes = array())
    {
        /* 这两个参数在1.7里已经废弃
        * @param string $categoryType 当前文章类的分类Type //已废弃
        * @param string $tagType      当前文章类的标签Type //已废弃
        */
        $urs = array();
        $urs['single_urlrule'] = $this->option['ZC_PAGE_REGEX'];
        $urs['list_urlrule'] = $this->option['ZC_INDEX_REGEX'];
        $urs['list_category_urlrule'] = $this->option['ZC_CATEGORY_REGEX'];
        $urs['list_author_urlrule'] = $this->option['ZC_AUTHOR_REGEX'];
        $urs['list_date_urlrule'] = $this->option['ZC_DATE_REGEX'];
        $urs['list_tag_urlrule'] = $this->option['ZC_TAGS_REGEX'];
        $urs['search_urlrule'] = $this->option['ZC_SEARCH_REGEX'];
        if (!is_array($urlRule)) {
            if ($urlRule != '') {
                $urs['single_urlrule'] = $urlRule;
            }
        } else {
            $urs = array_merge($urs, $urlRule);
        }

        $typeId = (int) $typeId;
        $name = strtolower(trim($name));
        if ($typeId > 99) {
            if (isset($this->posttype[$typeId])) {
                $this->ShowError(87, __FILE__, __LINE__);
            }
        }
        $tps = array();
        $tps['template'] = $this->option['ZC_POST_DEFAULT_TEMPLATE'];
        $tps['list_template'] = $this->option['ZC_INDEX_DEFAULT_TEMPLATE'];
        $tps['category_template'] = $this->option['ZC_INDEX_DEFAULT_TEMPLATE'];
        $tps['tag_template'] = $this->option['ZC_INDEX_DEFAULT_TEMPLATE'];
        $tps['author_template'] = $this->option['ZC_INDEX_DEFAULT_TEMPLATE'];
        $tps['date_template'] = $this->option['ZC_INDEX_DEFAULT_TEMPLATE'];
        $tps['search_template'] = $this->option['ZC_SEARCH_DEFAULT_TEMPLATE'];

        if (!is_array($template)) {
            if ($template != '') {
                $tps['template'] = $template;
            }
        } else {
            $tps = array_merge($tps, $template);
        }

        $this->posttype[$typeId] = array('name' => $name, 'classname' => $className);
        $this->posttype[$typeId] = array_merge($this->posttype[$typeId], $tps, $urs);

        $post_actions = array('new' => 'ArticleNew', 'edit' => 'ArticleEdt', 'del' => 'ArticleDel', 'post' => 'ArticlePst', 'publish' => 'ArticlePub', 'manage' => 'ArticleMng', 'all' => 'ArticleAll', 'view' => 'view', 'search' => 'search');

        if (empty($actions) || is_array($actions) == false) {
            $actions = $post_actions;
        }

        foreach ($post_actions as $key => $value) {
            if (!isset($actions[$key])) {
                $actions[$key] = $value;
            }
        }
        $this->posttype[$typeId]['actions'] = $actions;

        if (!isset($this->tags_type[$typeId])) {
            $this->tags_type[$typeId] = array();
        }
        if (!isset($this->tagsbyname_type[$typeId])) {
            $this->tagsbyname_type[$typeId] = array();
        }
        if (!isset($this->categoriesbyorder_type[$typeId])) {
            $this->categoriesbyorder_type[$typeId] = array();
        }
    }

    /**
     * 设置PostType.
     *
     * @param $typeId
     * @param $name
     * @param $value
     *
     * @throws Exception
     */
    public function SetPostType($typeid, $name, $value)
    {
        $this->posttype[$typeid][$name] = $value;
    }

    /**
     * 获取PostType信息(如果是修改的话请直接编辑$zbp->posttype)
     *
     * @param $key
     *
     * @param $typeid
     *
     * @return string
     */
    public function GetPostType($typeid, $key)
    {
        if ($key == null || empty($key)) {
            return $this->posttype[$typeid];
        }
        if ('name' == $key) {
            if (isset($this->posttype[$typeid]['name'])) {
                return strtolower($this->posttype[$typeid]['name']);
            }

            return '';
        } elseif ('single_urlrule' == $key) {
            if (isset($this->posttype[$typeid]['single_urlrule'])) {
                return $this->posttype[$typeid]['single_urlrule'];
            }

            return $this->option['ZC_PAGE_REGEX'];
        } elseif ('list_urlrule' == $key) {
            if (isset($this->posttype[$typeid]['list_urlrule'])) {
                return $this->posttype[$typeid]['list_urlrule'];
            }

            return $this->option['ZC_INDEX_REGEX'];
        } elseif ('list_category_urlrule' == $key) {
            if (isset($this->posttype[$typeid]['list_category_urlrule'])) {
                return $this->posttype[$typeid]['list_category_urlrule'];
            }

            return $this->option['ZC_CATEGORY_REGEX'];
        } elseif ('list_author_urlrule' == $key) {
            if (isset($this->posttype[$typeid]['list_author_urlrule'])) {
                return $this->posttype[$typeid]['list_author_urlrule'];
            }

            return $this->option['ZC_AUTHOR_REGEX'];
        } elseif ('list_date_urlrule' == $key) {
            if (isset($this->posttype[$typeid]['list_date_urlrule'])) {
                return $this->posttype[$typeid]['list_date_urlrule'];
            }

            return $this->option['ZC_DATE_REGEX'];
        } elseif ('list_tag_urlrule' == $key) {
            if (isset($this->posttype[$typeid]['list_tag_urlrule'])) {
                return $this->posttype[$typeid]['list_tag_urlrule'];
            }

            return $this->option['ZC_TAGS_REGEX'];
        } elseif ('search_urlrule' == $key) {
            if (isset($this->posttype[$typeid]['search_urlrule'])) {
                return $this->posttype[$typeid]['search_urlrule'];
            }

            return $this->option['ZC_SEARCH_REGEX'];
        } elseif ('template' == $key) {
            if (isset($this->posttype[$typeid]['template']) && !empty($this->posttype[$typeid]['template'])) {
                return $this->posttype[$typeid]['template'];
            }

            return $this->option['ZC_POST_DEFAULT_TEMPLATE'];
        } elseif ('list_template' == $key) {
            if (isset($this->posttype[$typeid]['list_template']) && !empty($this->posttype[$typeid]['list_template'])) {
                return $this->posttype[$typeid]['list_template'];
            }

            return $this->option['ZC_INDEX_DEFAULT_TEMPLATE'];
        } elseif ('category_template' == $key) {
            if (isset($this->posttype[$typeid]['category_template']) && !empty($this->posttype[$typeid]['category_template'])) {
                return $this->posttype[$typeid]['category_template'];
            }

            return $this->option['ZC_INDEX_DEFAULT_TEMPLATE'];
        } elseif ('tag_template' == $key) {
            if (isset($this->posttype[$typeid]['tag_template']) && !empty($this->posttype[$typeid]['tag_template'])) {
                return $this->posttype[$typeid]['tag_template'];
            }

            return $this->option['ZC_INDEX_DEFAULT_TEMPLATE'];
        } elseif ('author_template' == $key) {
            if (isset($this->posttype[$typeid]['author_template']) && !empty($this->posttype[$typeid]['author_template'])) {
                return $this->posttype[$typeid]['author_template'];
            }

            return $this->option['ZC_INDEX_DEFAULT_TEMPLATE'];
        } elseif ('date_template' == $key) {
            if (isset($this->posttype[$typeid]['date_template']) && !empty($this->posttype[$typeid]['date_template'])) {
                return $this->posttype[$typeid]['date_template'];
            }

            return $this->option['ZC_INDEX_DEFAULT_TEMPLATE'];
        } elseif ('search_template' == $key) {
            if (isset($this->posttype[$typeid]['search_template']) && !empty($this->posttype[$typeid]['search_template'])) {
                return $this->posttype[$typeid]['search_template'];
            }

            return $this->option['ZC_SEARCH_DEFAULT_TEMPLATE'];
        } elseif ('classname' == $key) {
            if (isset($this->posttype[$typeid]['classname']) && !empty($this->posttype[$typeid]['classname'])) {
                return $this->posttype[$typeid]['classname'];
            }

            return 'Post';
        } elseif ('actions' == $key) {
            $actions = array();
            if (isset($this->posttype[$typeid]['actions'])) {
                $actions = $this->posttype[$typeid]['actions'];
            }
            $post_actions = $this->posttype[0]['actions'];
            if (is_array($actions) && empty($actions) == false) {
                foreach ($post_actions as $key => $value) {
                    if (!isset($actions[$key])) {
                        $actions[$key] = $value;
                    }
                }
                return $actions;
            }
            return $post_actions;
        } else {
            if (isset($this->posttype[$typeid][$key])) {
                return $this->posttype[$typeid][$key];
            }
        }
    }

    /**
     * 设置PostType下Array项目信息
     */
    public function SetPostType_Sub($typeid, $name, $subname, $value)
    {
        $this->posttype[$typeid][$name][$subname] = $value;
    }

    /**
     * 获取PostType下Array项目信息
     */
    public function GetPostType_Sub($typeid, $name, $subname)
    {
        if (isset($this->posttype[$typeid][$name][$subname])) {
            return $this->posttype[$typeid][$name][$subname];
        }
        return null;
    }

    /**
     * 注册Action.
     *
     * @param $name
     * @param $level
     * @param $title
     */
    public function RegAction($name, $level, $title)
    {
        $this->actions[$name] = $level;
        $this->lang['actions'][$name] = $title;
    }

    /**
     * 获得Action权限注释.
     *
     * @param $name
     *
     * @return mixed
     */
    public function GetActionDescription($name)
    {
        return GetValueInArray($this->lang['actions'], $name, $name);
    }

    /**
     * 注册路由函数
     *
     * @param $array 数据数组(详细结构在初始化中有说明)
     * @param $prepend 注册路由时插队在前边
     *
     * @return mixed
     */
    public function RegRoute($array, $prepend = false)
    {
        $routes = &$this->routes;
        if ($prepend == false) {
            $routes[$array['type'] . '_' . $array['name']] = $array;
        } else {
            unset($routes[$array['type'] . '_' . $array['name']]);
            $new_name = $array['type'] . '_' . $array['name'];
            $new_array = array($new_name => $array);
            $routes = array_merge($new_array, $routes);
        }
        return true;
    }

    /**
     * 获取路由函数
     *
     * @param $type 路由类型
     * @param $name 名称
     *
     * @return mixed
     */
    public function GetRoute($type, $name = null)
    {
        $routes = &$this->routes;
        if (is_array($type)) {
            $name = current($type);
            $type = key($type);
        }
        if (isset($routes[$type . '_' . $name])) {
            return $routes[$type . '_' . $name];
        }
    }

    /**
     * 删除路由函数
     *
     * @param $type 路由类型
     * @param $name 名称
     *
     * @return mixed
     */
    public function RemoveRoute($type, $name)
    {
        $routes = &$this->routes;
        unset($routes[$type . '_' . $name]);
        return true;
    }

    //举例：backend-ui,,,
    protected $protect_exclusive = array();

    /**
     * 通知系统控制权.
     */
    public function SetExclusive($function, $appid)
    {
        if ($appid == false) {
            return false;
        }
        $this->protect_exclusive[$function] = $appid;

        return true;
    }

    /**
     * 查询系统控制权.
     */
    public function IsExclusive($function)
    {
        if (isset($this->protect_exclusive[$function])) {
            return $this->protect_exclusive[$function];
        }

        return false;
    }

    /**
     * 绑定zbp之前独立的杂乱的全局对象数组到总缓存对象上.
     */
    public function BindingCache()
    {
        $cacheobject = &$this->cacheobject;
        $cacheobject['Member'] = &$this->members;
        $cacheobject['Category'] = &$this->categories_all;
        $cacheobject['Tag'] = &$this->tags_all;
        $cacheobject['Module'] = &$this->modules;
        $cacheobject['Comment'] = &$this->comments;
        $cacheobject['Post'] = &$this->posts;
    }

    /**
     * 将对象附加到总缓存对象上.
     */
    public function AddCache(&$object)
    {
        if (is_subclass_of($object, 'Base') == false && get_class($object) != 'Base') {
            return;
        }
        $cacheobject = &$this->cacheobject;
        if (!isset($cacheobject[get_class($object)])) {
            $cacheobject[get_class($object)] = array();
        }
        if (empty($object->ID)) {
            return;
        }

        $cacheobject[get_class($object)][$object->ID] = $object;

        switch (get_class($object)) {
            case 'Module':
                $this->modulesbyfilename[$object->FileName] = &$cacheobject[get_class($object)][$object->ID];
                break;
            case 'Tag':
                //isset($this->tags_type[$object->Type]) || $this->tags_type[$object->Type] = array();
                $this->tags_type[$object->Type][$object->ID] = &$cacheobject[get_class($object)][$object->ID];
                //isset($this->tagsbyname_type[$object->Type]) || $this->tagsbyname_type[$object->Type] = array();
                $this->tagsbyname_type[$object->Type][$object->Name] = &$cacheobject[get_class($object)][$object->ID];
                break;
            case 'Category':
                //isset($this->categories_type[$object->Type]) || $this->categories_type[$object->Type] = array();
                $this->categories_type[$object->Type][$object->ID] = &$cacheobject[get_class($object)][$object->ID];
                break;
            case 'Member':
                $this->membersbyname[$object->Name] = &$cacheobject[get_class($object)][$object->ID];
                break;
        }
        return true;
    }

    /**
     * 将对象附从总缓存对象上删除掉.
     */
    public function RemoveCache(&$object)
    {
        if (is_subclass_of($object, 'Base') == false && get_class($object) != 'Base') {
            return;
        }
        $cacheobject = &$this->cacheobject;
        if (!isset($cacheobject[get_class($object)])) {
            $cacheobject[get_class($object)] = array();
        }
        if (empty($object->ID)) {
            return;
        }

        switch (get_class($object)) {
            case 'Module':
                unset($this->modulesbyfilename[$object->FileName]);
                break;
            case 'Member':
                unset($this->membersbyname[$object->Name]);
                break;
            case 'Tag':
                unset($this->tags_type[$object->Type][$object->ID]);
                unset($this->tagsbyname_type[$object->Type][$object->Name]);
                break;
            case 'Category':
                unset($this->categories_type[$object->Type][$object->ID]);
                break;
        }
        unset($cacheobject[get_class($object)][$object->ID]);

        return true;
    }

    /**
     * 将Post类对象附加到Post缓存对象上.
     */
    public function AddPostCache(&$object)
    {
        if (is_subclass_of($object, 'BasePost') == false) {
            return;
        }
        $cacheobject = &$this->cacheobject;
        $cacheobject['Post'][$object->ID] = $object;
    }

    /**
     * 将Post类对象附从Post缓存对象上删除掉.
     */
    public function RemovePostCache(&$object)
    {
        if (is_subclass_of($object, 'BasePost') == false) {
            return;
        }
        $cacheobject = &$this->cacheobject;
        unset($cacheobject['Post'][$object->ID]);
    }

    /**
     * 查询对象的ID的值是否存在于总缓存对象上.
     */
    public function CheckCache($classname, $idvalue)
    {
        $cacheobject = &$this->cacheobject;
        if (!isset($cacheobject[$classname])) {
            return false;
        }

        return array_key_exists($idvalue, $cacheobject[$classname]);
    }

    /**
     * 以下部分为已废弃，但考虑到兼容性保留的代码**************************************************************.
     */

    /**
     * 检测当前url，如果不符合设置就跳转到固定域名的链接.
     */
    public function RedirectPermanentDomain()
    {
        $domain_disable = GetValueInArray($this->option, 'ZC_PERMANENT_DOMAIN_WHOLE_DISABLE');
        if ($domain_disable == true) {
            return;
        }

        $forced = GetValueInArray($this->option, 'ZC_PERMANENT_DOMAIN_FORCED_URL');
        if ($this->option['ZC_PERMANENT_DOMAIN_ENABLE'] == false && $forced == '') {
            return;
        }

        $host = str_replace(array('https://', 'http://'), array('', ''), GetCurrentHost(ZBP_PATH, $null));
        $host2 = str_replace(array('https://', 'http://'), array('', ''), $this->host);

        if ($host != $host2) {
            $u = GetRequestUri();
            $u = $this->host . substr($u, 1);
            Redirect301($u);
        }
    }

    /**
     * 检查并开启Gzip压缩.
     */
    public function CheckGzip()
    {
    }

    /**
     * 启用Gzip.
     */
    public function StartGzip()
    {
    }

    /**
     * 验证用户登录（MD5加zbp->guid盐后的密码）.
     *
     * @deprecated
     *
     * @param string $name         用户名
     * @param string $ps_path_hash MD5加zbp->guid盐后的密码
     * @param object $member       返回读取成功的member对象
     *
     * @return bool
     */
    public function Verify_MD5Path($name, $ps_path_hash, &$member = null)
    {
        if ($name == '' || $ps_path_hash == '') {
            return false;
        }
        $m = $this->GetMemberByName($name);
        if ($m->ID != null) {
            if ($m->PassWord_MD5Path == $ps_path_hash) {
                $member = $m;

                return true;
            }
        }

        return false;
    }

    /**
     * 获取CSRF Token的错误别名.
     *
     * @deprecated Use ``GetCSRFToken``
     *
     * @param string $id 应用ID，可以保证每个应用获取不同的Token
     *
     * @return string
     */
    public function GetToken($id = '')
    {
        return $this->GetCSRFToken($id);
    }

    /**
     * 验证CSRF Token的错误别名.
     *
     * @deprecated Use ``VerifyCSRFToken``
     *
     * @param $t
     * @param $id
     *
     * @return bool
     */
    public function ValidToken($t, $id = '')
    {
        return $this->VerifyCSRFToken($t, $id);
    }

    /**
     * @deprecated
     *
     * @return bool
     */
    public function LoadCategorys()
    {
        return $this->LoadCategories();
    }

    /**
     * 获取会话WebToken.
     *
     * @deprecated 毫无意义，即将废弃
     *
     * @param string $wt_id
     * @param int    $day   默认1天有效期，1小时为1/24，1分钟为1/(24*60)
     *
     * @return string
     */
    public function GetWebToken($wt_id = '', $day = 1)
    {
        $t = (intval($day * 24 * 3600) + time());

        return CreateWebToken($wt_id, $t, $this->guid, $this->user->Status, $this->user->ID, $this->user->Password);
    }

    /**
     * 验证会话WebToken.
     *
     * @deprecated 毫无意义，即将废弃
     *
     * @param $wt
     * @param $wt_id
     *
     * @return bool
     */
    public function ValidWebToken($wt, $wt_id = '')
    {
        if (VerifyWebToken($wt, $wt_id, $this->guid, $this->user->Status, $this->user->ID, $this->user->Password) === true) {
            return true;
        }

        return false;
    }

}
