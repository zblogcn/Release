<?php

if (!defined('ZBP_PATH')) {
    exit('Access denied');
}

/**
 * Z-Blog with PHP.
 *
 * @author  Z-BlogPHP Team
 * @version 1.0 2020-07-02
 */

$GLOBALS['setting_keys'] = array(
    'ZC_BLOG_NAME',
    'ZC_BLOG_SUBNAME',
    'ZC_BLOG_COPYRIGHT',
    'ZC_TIME_ZONE_NAME',
    'ZC_BLOG_LANGUAGEPACK',
    'ZC_API_ENABLE',
    'ZC_DEBUG_MODE',
    'ZC_DEBUG_MODE_WARNING',
    'ZC_ADDITIONAL_SECURITY',
    'ZC_USING_CDN_GUESTIP_TYPE',
    'ZC_CLOSE_SITE',
    'ZC_DISPLAY_COUNT',
    'ZC_DISPLAY_SUBCATEGORYS',
    'ZC_PAGEBAR_COUNT',
    'ZC_SEARCH_COUNT',
    'ZC_SYNTAXHIGHLIGHTER_ENABLE',
    'ZC_COMMENT_TURNOFF',
    'ZC_COMMENT_AUDIT',
    'ZC_COMMENT_REVERSE_ORDER',
    'ZC_COMMENTS_DISPLAY_COUNT',
    'ZC_COMMENT_VERIFY_ENABLE',
    'ZC_UPLOAD_FILETYPE',
    'ZC_UPLOAD_FILESIZE',
    'ZC_ARTICLE_INTRO_WITH_TEXT',
    'ZC_ARTICLE_THUMB_SWITCH',
    'ZC_ARTICLE_THUMB_TYPE',
    'ZC_ARTICLE_THUMB_WIDTH',
    'ZC_ARTICLE_THUMB_HEIGHT',
    'ZC_MANAGE_COUNT',
    'ZC_POST_BATCH_DELETE',
);

/**
 * 基本信息接口（载入页面后获取的）.
 *
 * @return array
 */
function api_system_basic_info()
{
    global $zbp;

    ApiCheckAuth(false, 'view');

    $info = array(
        'zbp' => array(
            'name' => $zbp->name,
            'subname' => $zbp->subname,
            'host' => $zbp->host,
            'version' => $zbp->version,
            'ajaxurl' => $zbp->ajaxurl,
            'cookiespath' => $zbp->cookiespath,
            'manage_count' => $zbp->option['ZC_MANAGE_COUNT'],
            'pagebar_count' => $zbp->option['ZC_PAGEBAR_COUNT'],
            'search_count' => $zbp->option['ZC_SEARCH_COUNT'],
            'display_count' => $zbp->option['ZC_DISPLAY_COUNT'],
            'comment_display_count' => $zbp->option['ZC_COMMENTS_DISPLAY_COUNT'],
            'comment_turnoff' => $zbp->option['ZC_COMMENT_TURNOFF'],
            'comment_verify_enable' => $zbp->option['ZC_COMMENT_VERIFY_ENABLE'],
            'comment_reverse_order' => $zbp->option['ZC_COMMENT_REVERSE_ORDER'],
        ),
        'is_logged_in' => $zbp->user->ID != 0,
        'current_member' => $zbp->user,
    );

    if ($zbp->islogin) {
        $info['zbp']['lang'] = $zbp->lang;
    }

    return array(
        'data' => $info,
    );
}

/**
 * 获取系统信息接口.
 *
 * @return array
 */
function api_system_get_info()
{
    global $zbp;

    ApiCheckAuth(true, 'admin');
    
    $info = array(
        'environment' => $zbp->cache->system_environment,
        'full_version' => ZC_VERSION_FULL,
        'articles' => (int) $zbp->cache->all_article_nums,
        'categories' => (int) $zbp->cache->all_category_nums,
        'pages' => (int) $zbp->cache->all_page_nums,
        'comments' => (int) $zbp->cache->all_comment_nums,
        'views' => (int) $zbp->cache->all_view_nums,
        'members' => (int) $zbp->cache->all_member_nums,
        'theme' => $zbp->theme,
        'style' => $zbp->style,
        'xml_rpc' => $zbp->xmlrpcurl,
    );

    return array(
        'data' => array('info' => $info,),
    );
}

/**
 * 清空缓存并重新编译模板接口.
 *
 * @return array
 */
function api_system_misc_statistic()
{
    ApiVerifyCSRF(true);
    ApiCheckAuth(true, 'root');
    ApiCheckAuth(true, 'misc');

    include ZBP_PATH . 'zb_system/function/c_system_misc.php';
    ob_clean();

    misc_statistic();

    return array(
        'message' => $GLOBALS['lang']['msg']['operation_succeed'],
    );
}

/**
 * Misc获取常用tags
 *
 * @return array
 */
function api_system_misc_showtags()
{
    global $zbp;

    $type = (int) GetVars('type');
    $actions = $zbp->GetPostType($type, 'actions');
    ApiCheckAuth(true, 'misc');

    if (!$zbp->CheckRights($actions['new']) || !$zbp->CheckRights($actions['edit'])) {
        $zbp->ShowError(6);
    }

    $array = $zbp->GetTagList(null, array('=', 'tag_Type', $type), array('tag_Count' => 'DESC', 'tag_ID' => 'ASC'), array(100), null);

    $listArr = ApiGetObjectArrayList(
        $array,
        array('Url', 'Template')
    );

    return array(
        'data' => array(
            'list' => $listArr,
        ),
    );
}

/**
 * 设置获取接口.
 *
 * @return array
 */
function api_system_get_setting()
{
    global $zbp, $setting_keys;

    ApiCheckAuth(true, 'root');
    ApiCheckAuth(true, 'SettingMng');

    $settingList = array();
    foreach ($setting_keys as $key) {
        if (isset($zbp->option[$key])) {
            $settingList[$key] = $zbp->option[$key];
        }
    }

    return array(
        'data' => array('list' => $settingList),
    );
}

/**
 * 设置更新接口.
 *
 * @return array
 */
function api_system_save_setting()
{
    global $zbp, $setting_keys;

    ApiCheckAuth(true, 'root');
    ApiCheckAuth(true, 'SettingSav');

    foreach ($_POST as $key => $value) {
        if (!in_array($key, $setting_keys, true)) {
            continue;
        }

        $zbp->option[strtoupper($key)] = $value;
    }
    $zbp->SaveOption();

    $settingList = array();
    foreach ($setting_keys as $key) {
        if (isset($zbp->option[$key])) {
            $settingList[$key] = $zbp->option[$key];
        }
    }
    
    return array(
        'data' => array('list' => $settingList,),
        'message' => $GLOBALS['lang']['msg']['operation_succeed'],
    );
}
