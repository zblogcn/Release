<?php
require dirname(__FILE__) . DIRECTORY_SEPARATOR . 'plugin' . DIRECTORY_SEPARATOR . 'searchstr.php';

RegisterPlugin("tpure", "ActivePlugin_tpure");
function ActivePlugin_tpure()
{
    global $zbp;
    $zbp->LoadLanguage('theme', 'tpure');
    Add_Filter_Plugin('Filter_Plugin_Admin_TopMenu', 'tpure_AddMenu');
    Add_Filter_Plugin('Filter_Plugin_Admin_Header', 'tpure_Header');
    Add_Filter_Plugin('Filter_Plugin_Login_Header', 'tpure_LoginHeader');
    Add_Filter_Plugin('Filter_Plugin_Zbp_Load', 'tpure_Refresh');
    Add_Filter_Plugin('Filter_Plugin_ViewSearch_Template', 'tpure_SearchMain');
    Add_Filter_Plugin('Filter_Plugin_ViewList_Core', 'tpure_Exclude_Category');
    Add_Filter_Plugin('Filter_Plugin_Edit_Response5', 'tpure_Edit_Response');
    if ($zbp->Config('tpure')->SEOON == '1') {
        Add_Filter_Plugin('Filter_Plugin_Category_Edit_Response', 'tpure_CategorySEO');
        Add_Filter_Plugin('Filter_Plugin_Tag_Edit_Response', 'tpure_TagSEO');
        Add_Filter_Plugin('Filter_Plugin_Edit_Response5', 'tpure_SingleSEO');
    }
    //自定义侧栏模块名称
    $zbp->lang['msg']['sidebar'] = $zbp->lang['tpure']['index'].$zbp->lang['tpure']['sidebar'];
    $zbp->lang['msg']['sidebar2'] = $zbp->lang['tpure']['catalog'].$zbp->lang['tpure']['sidebar'];
    $zbp->lang['msg']['sidebar3'] = $zbp->lang['tpure']['article'].$zbp->lang['tpure']['sidebar'];
    $zbp->lang['msg']['sidebar4'] = $zbp->lang['tpure']['page'].$zbp->lang['tpure']['sidebar'];
    $zbp->lang['msg']['sidebar5'] = $zbp->lang['tpure']['search'].$zbp->lang['tpure']['page'].$zbp->lang['tpure']['sidebar'];
    $zbp->lang['msg']['sidebar6'] = $zbp->lang['msg']['sidebar7'] = $zbp->lang['msg']['sidebar8'] = $zbp->lang['msg']['sidebar9'] = $zbp->lang['tpure']['themeunused'];
}

function tpure_SubMenu($id)
{
    global $zbp;
    $arySubMenu = array(
        0 => array('基本设置', 'base', 'left', false),
        1 => array('SEO设置', 'seo', 'left', false),
        2 => array('色彩设置', 'color', 'left', false),
    );
    foreach ($arySubMenu as $k => $v) {
        echo '<li><a href="?act=' . $v[1] . '" ' . ($v[3] == true ? 'target="_blank"' : '') . ' class="' . ($id == $v[1] ? 'on' : '') . '">' . $v[0] . '</a></li>';
    }
}

function tpure_AddMenu(&$m)
{
    global $zbp;
    $m[] = MakeTopMenu("root", '主题设置', $zbp->host . "zb_users/theme/tpure/main.php?act=base", "", "topmenu_tpure");
}

function tpure_Header()
{
    global $zbp,$bloghost;
    if($zbp->Config('tpure')->PostAJAXPOSTON == '0'){$ajaxpost = 0;}else{$ajaxpost = 1;}
    echo '<style>.header{background:url(' . $bloghost . 'zb_users/theme/tpure/style/images/banner.jpg) no-repeat center center;background-size:cover;}</style>';
    echo '<script>window.theme = {ajaxpost:'.$ajaxpost.'}</script>';
}

function tpure_LoginHeader()
{
    global $zbp;
    $logo = $zbp->Config('tpure')->PostLOGO && $zbp->Config('tpure')->PostLOGOON == 1 ? $zbp->Config('tpure')->PostLOGO : '欢迎登录';
    $banner = $zbp->Config('tpure')->PostLOGINBG ? $zbp->Config('tpure')->PostLOGINBG : $zbp->host . 'zb_users/theme/tpure/style/images/banner.jpg';
    echo <<<CSSJS
    <style>
        input:-webkit-autofill { -webkit-text-fill-color:#000 !important; background-color:transparent; background-image:none; transition:background-color 50000s ease-in-out 0s; }
        .bg { height:100%; background:url({$banner}) no-repeat center top; background-size:cover; }
        .logo { width:100%; height:auto; margin:0; padding:20px 0 10px; text-align:center; border-bottom:1px solid #eee; }
        .logo img { width:auto; height:50px; margin:auto; background:none; display:block; }
        #wrapper { width:440px; min-height:400px; height:auto; border-radius:8px; background:#fff; position:absolute; top:50%; left:50%; transform:translate(-50%, -50%); }
        .login { width:auto; height:auto; padding:30px 40px 20px; }
        .login input[type="text"], .login input[type="password"] { width:100%; height:42px; float:none; padding:0 14px; font-size:16px; line-height:42px; border:1px solid #e4e8eb; outline:0; border-radius:3px; box-sizing:border-box; }
        .login input[type="password"] { font-size:24px; letter-spacing:5px; }
        .login input[type="text"]:focus, .login input[type="password"]:focus { color:#0188fb; background-color:#fff; border-color:#aab7c1; outline:0; box-shadow:0 0 0 0.2rem rgba(31,73,119,0.1); }
        .login dl { height:auto; }
        .login dd { margin-bottom:14px; }
        .login dd.submit, .login dd.password, .login dd.username, .login dd.validcode { width:auto; float:none; overflow:visible; }
        .login dd.validcode { height:auto; position:relative; }
        .login dd.validcode label { margin-bottom:4px; }
        .login dd.validcode img { height:38px; position:absolute; top:auto; right:2px; bottom:2px; }
        .login dd.checkbox { width:170px; float:none; margin:0 0 10px; }
        .login dd.checkbox input[type="checkbox"] { width:16px; height:16px; margin-right:6px;; }
        .login label { width:auto; margin-bottom:5px; padding:0; font-size:16px; text-align:left; }
        .logintitle { padding:0 70px; font-size:24px; color:#0188fb; line-height:40px; white-space:nowrap; text-overflow:ellipsis; overflow:hidden; position:relative; display:block; }
        .button { width:100%; height:42px; float:none; font-size:16px; line-height:42px; border-radius:3px; outline:0; box-shadow:1px 3px 5px 0 rgba(72,108,255,0.3); background:#0188fb; }
        .button:hover { background:#0188fb; }
        @media only screen and (max-width: 768px){
            .login { padding:30px 30px 10px; }
            .login dd { float:left; margin-bottom:14px; padding:0; }
            .login dd.validcode label { margin-bottom:5px; }
            .login dd.checkbox { width:auto; padding:0; }
            .login dd.submit { margin-right:0; }
        }
        @media only screen and (max-width: 520px){
            #wrapper { width:96%; margin:0 auto; }
            .login dd.username label, .login dd.password label { width:100%; }
        }
        </style>
        <script>
        $(function(){
        function check_is_img(url) {
            return (url.match(/\.(jpeg|jpg|gif|png|svg)$/) != null)
        }
        if(check_is_img("{$logo}")){
            $(".logo").find("img").replaceWith('<img src="{$logo}"/>').end().wrapInner("<a href='"+bloghost+"'/>");
        }else{
            $(".logo").find("img").replaceWith('<span class="logintitle">{$logo}<span>').end().wrapInner("<a href='"+bloghost+"'/>");
        }
        });
    </script>
CSSJS;
}

function tpure_Refresh()
{
    global $zbp;
    if ($zbp->ismanage){
        return;
    }
    if (defined("ZBP_IN_CMD")) {
        return;
    }
    $zbp->lang['msg']['first_button'] = $zbp->lang['tpure']['index'];
    $zbp->lang['msg']['prev_button'] = $zbp->lang['tpure']['prevpage'];
    $zbp->lang['msg']['next_button'] = $zbp->lang['tpure']['nextpage'];
    $zbp->lang['msg']['last_button'] = $zbp->lang['tpure']['endpage'];
    $zbp->option['ZC_SEARCH_TYPE'] = 'list';
    //$zbp->option['ZC_PAGEBAR_COUNT'] = 3;
    //$zbp->option['ZC_SEARCH_COUNT'] = 10;
}

//分类列表页面包屑分类获取
function tpure_navcate($id)
{
   $html = '';
   $navcate = new Category;
   $navcate->LoadInfoByID($id);
   $html = ' &gt; <a href="' .$navcate->Url.'" title="查看' .$navcate->Name. '中的全部文章">' .$navcate->Name. '</a> '.$html;
   if(($navcate->ParentID)>0){tpure_navcate($navcate->ParentID);}
   echo $html;
}

function tpure_SearchMain(&$template)
{
    global $zbp;
    $articles = $template->GetTags('articles');
    $q = $template->GetTags('search');
    $qc = '<span class="schwords">' . $q . '</span>';

    foreach ($articles as $key => $article) {
        $a = $zbp->GetPostByID($article->ID);
        $intro = preg_replace('/[\r\n\s]+/', '', trim(tpure_SubStrStartUTF8(TransferHTML($a->Content, '[nohtml]'), $q, $zbp->Config('tpure')->PostINTRONUM)) . '...');
        $article->Intro = str_ireplace($q, $qc, $intro);
        $article->Title = str_ireplace($q, $qc, $article->Title);
    }
}

function tpure_FormatID($ids)
{
    $filter = str_replace('，', ',', $ids);
    $filter = preg_replace('/,+/', ',', $filter);
    return trim($filter, ',');
}

function tpure_Exclude_Category($type, $page, $category, $author, $datetime, $tag, &$w, $pagebar)
{
    global $zbp;
    $filter = tpure_FormatID($zbp->Config('tpure')->PostFILTERCATEGORY);
    if ($type == 'index' && $filter) {
        $w[] = array('NOT IN', 'log_CateID', explode(',',$filter));
        $pagebar->Count = null;
    }
}

function tpure_Exclude_CategorySelect($default)
{
    global $zbp;
    foreach ($GLOBALS['hooks']['Filter_Plugin_OutputOptionItemsOfCategories'] as $fpname => &$fpsignal) {
        $fpreturn = $fpname($default);
        if ($fpsignal == PLUGIN_EXITSIGNAL_RETURN) {
            $fpsignal = PLUGIN_EXITSIGNAL_NONE;
            return $fpreturn;
        }
    }
    $s = '';
    $s .= '<option value="0">'.$zbp->lang['tpure']['shieldcateid'].'</option>';
    foreach ($zbp->categoriesbyorder as $id => $cate) {
        $s .= '<option ' . ($default == $cate->ID ? 'selected="selected"' : '') . ' value="' . $cate->ID . '">' . $cate->SymbolName . '</option>';
    }
    return $s;
}

function tpure_TimeAgo($ptime)
{
    global $zbp;
    if ($zbp->Config('tpure')->PostTIMEAGOON == '1') {
        $ptime = strtotime($ptime);
        $etime = time() - $ptime;
        if ($etime < 1) {
            return '刚刚';
        }
        $interval = array(
            12 * 30 * 24 * 60 * 60  => $zbp->lang['tpure']['yearsago'].'<span class="datetime"> (' . date('Y-m-d', $ptime) . ')</span>',
            30 * 24 * 60 * 60       => $zbp->lang['tpure']['monthago'].'<span class="datetime"> (' . date('m-d', $ptime) . ')</span>',
            7 * 24 * 60 * 60        => $zbp->lang['tpure']['weeksago'].'<span class="datetime"> (' . date('m-d', $ptime) . ')</span>',
            24 * 60 * 60            => $zbp->lang['tpure']['daysago'],
            60 * 60                 => $zbp->lang['tpure']['monthsago'],
            60                      => $zbp->lang['tpure']['minutesago'],
            1                       => $zbp->lang['tpure']['secondsago'],
        );
        foreach ($interval as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);

                return $r . $str;
            }
        }
    } else {
        $ptime = strtotime($ptime);
        $etime = date('Y-m-d', $ptime)/* .' <time class="datetime">'. date('H:i:s', $ptime).'</time>'*/;

        return $etime;
    }
}

function tpure_color()
{
    global $zbp;
    $skin = '';
    $color = $zbp->Config('tpure')->PostCOLOR;
    $skin .= "a, a:hover,.menu li a:hover,.menu li.on a,.menu li .subnav a:hover:after,.menu li .subnav a.on,.menu li.subcate:hover a,.menu li.subcate:hover .subnav a:hover,.menu li.subcate:hover .subnav a.on,.menu li.subcate:hover .subnav a.on:after,.sch-m input,.sch-m button:after,.schfixed input,.schclose,.schform input,.schform button:after,.post h2 a:hover,.post h2 .istop:before,.post .user a:hover,.post .date a:hover,.post .cate a:hover,.post .views a:hover,.post .cmtnum a:hover,.post .readmore:hover,.post .readmore:hover:after,.post .tags a:hover,.pages a:hover,a.backlist:hover,.cmtsfoot .reply:hover,.cmtsfoot .reply:hover:before,.cmtsubmit button:hover,.cmtsubmit button:hover:before,.sidebox dd a:hover,#divTags ul li a:hover,#divCalendar td a,#divCalendar #today,#divContorPanel .cp-login a:hover,#divContorPanel .cp-vrs a:hover,#divContorPanel .cp-login a:hover:before,#divContorPanel .cp-vrs a:hover:before,.footer a:hover,.goback:hover,.goback:hover:after,.relateinfo h3 a:hover { color:#{$color}; }@media screen and (max-width:1080px){.menu ul li.subcate.slidedown > a:after {color:#{$color}}}"; //color
    $skin .= ".menu li:before,.schfixed button,.pagebar .now-page,.cmtpagebar .now-page,.pagebar a:hover,.cmtpagebar a:hover,a.backtotop {background:#{$color}}"; //background
    $skin .= ".menuico span {background-color:#{$color}}"; //background-color
    $skin .= ".menu li .subnav,.schfixed {border-top-color:#{$color}}"; //border-top-color
    $skin .= ".menu li.subcate .subnav a {color:#333}";
    $skin .= ".menu li .subnav:before,.sch-m input,.schfixed:before,.schform input,.single h1:after,.single h2:after,.single h3:after,.single h4:after,.single h5:after,.single h6:after,.contitle h1,.contitle h2 {border-bottom-color:#{$color}}"; //border-bottom-color
    $skin .= ".post .readmore:hover,.post .tags a:hover,.pagebar .now-page,.cmtpagebar .now-page,.pagebar a:hover,.cmtpagebar a:hover,a.backlist:hover,.cmtsubmit button:hover,#divTags ul li a:hover,#divCalendar td a,#divContorPanel .cp-login a:hover,#divContorPanel .cp-vrs a:hover,.goback:hover {border-color:#{$color}}"; //border-color
    $bgcolor = $zbp->Config('tpure')->PostBGCOLOR;
    $skin .= ".wrapper { background:#{$bgcolor}; }";
    $sidelayout = $zbp->Config('tpure')->PostSIDELAYOUT;
    if ($sidelayout == 'l') {
        $skin .= ".sidebar { float:left; } .content { float:right; }@media screen and (max-width:1080px){.content { float:none; margin:0; }}";
    } else {
        $skin .= "";
    }
    $customcss = $zbp->Config('tpure')->PostCUSTOMCSS;
    $skin .= "{$customcss}";

    return $skin;
}

function tpure_Edit_Response()
{
    global $zbp,$article;
    tpure_CustomMeta_Response($article);
}

function tpure_CustomMeta_Response(&$object)
{
    global $zbp; ?>
    <link rel="stylesheet" href="<?php echo $zbp->host; ?>zb_users/theme/tpure/script/admin.css">
    <script src="<?php echo $zbp->host; ?>zb_users/theme/tpure/script/custom.js" type="text/javascript"></script>
    <?php
    $array = array('proimg');
    $proimg_intro = '自定义缩略图';
    if (is_array($array) == false) {
        return null;
    }
    if (count($array) == 0) {
        return null;
    }
    foreach ($array as $key => $value) {
        $single_meta_intro = $proimg_intro;
        echo '<p><label for="' . $value . '">' . $single_meta_intro . '（列表缩略图片，未设置则调用文章首图）</label><span><input type="text" name="meta_' . $value . '" placeholder="请点击上传按钮选择图片或手动输入图片地址..." value="' . htmlspecialchars($object->Metas->$value) . '" class="metasrc"/></span><span><input type="button" class="uploadimg button" value="上传图片" /></span></p>';
    }
}

function tpure_CategorySEO()
{
    global $zbp,$cate; ?>
    <link rel="stylesheet" href="<?php echo $zbp->host; ?>zb_users/theme/tpure/script/admin.css">
    <script type="text/javascript" src="<?php echo $zbp->host; ?>zb_users/theme/tpure/script/custom.js"></script>
    <?php
    if ($zbp->CheckPlugin('UEditor')) {
        echo '<script type="text/javascript" src="' . $zbp->host . 'zb_users/plugin/UEditor/ueditor.config.php"></script>';
        echo '<script type="text/javascript" src="' . $zbp->host . 'zb_users/plugin/UEditor/ueditor.all.min.js"></script>';
    }
    $array = array('catetitle', 'catekeywords', 'catedescription');
    $catetitle_intro = '分类SEO标题';
    $catekeywords_intro = '分类SEO关键词';
    $catedescription_intro = '分类SEO描述';
    if (is_array($array) == false) {
        return null;
    }
    if (count($array) == 0) {
        return null;
    }
    foreach ($array as $key => $value) {
        if ($key == 0) {
            $cate_meta_intro = $catetitle_intro;
            echo '<div class="introbox"><div class="togglelabel">+++++ 分类列表SEO设置 +++++</div><p><span class="title">' . $cate_meta_intro . '</span><br /><input type="text" name="meta_' . $value . '" value="' . htmlspecialchars($cate->Metas->$value) . '" class="metasrc"/></p>';
        } elseif ($key == 1) {
            $cate_meta_intro = $catekeywords_intro;
            echo '<p><span class="title">' . $cate_meta_intro . '</span><br /><input type="text" name="meta_' . $value . '" value="' . htmlspecialchars($cate->Metas->$value) . '" class="metasrc"/></p>';
        } else {
            $cate_meta_intro = $catedescription_intro;
            echo '<p><span class="title">' . $cate_meta_intro . '</span><br /><textarea cols="3" rows="6" id="edtIntro" name="meta_' . $value . '" class="metaintro">' . htmlspecialchars($cate->Metas->$value) . '</textarea></p></div>';
        }
    }
}

function tpure_TagSEO()
{
    global $zbp,$tag; ?>
    <link rel="stylesheet" href="<?php echo $zbp->host; ?>zb_users/theme/tpure/script/admin.css">
    <script type="text/javascript" src="<?php echo $zbp->host; ?>zb_users/theme/tpure/script/custom.js"></script>
    <?php
    if ($zbp->CheckPlugin('UEditor')) {
        echo '<script type="text/javascript" src="' . $zbp->host . 'zb_users/plugin/UEditor/ueditor.config.php"></script>';
        echo '<script type="text/javascript" src="' . $zbp->host . 'zb_users/plugin/UEditor/ueditor.all.min.js"></script>';
    }
    $array = array('tagtitle', 'tagkeywords', 'tagdescription');
    $tagtitle_intro = '标签SEO标题';
    $tagkeywords_intro = '标签SEO关键词';
    $tagdescription_intro = '标签SEO描述';
    if (is_array($array) == false) {
        return null;
    }
    if (count($array) == 0) {
        return null;
    }
    foreach ($array as $key => $value) {
        if ($key == 0) {
            $tag_meta_intro = $tagtitle_intro;
            echo '<div class="introbox"><div class="togglelabel">+++++ TAGS列表SEO设置 +++++</div><p><span class="title">' . $tag_meta_intro . '</span><br /><input type="text" name="meta_' . $value . '" value="' . htmlspecialchars($tag->Metas->$value) . '" class="metasrc"/></p>';
        } elseif ($key == 1) {
            $tag_meta_intro = $tagkeywords_intro;
            echo '<p><span class="title">' . $tag_meta_intro . '</span><br /><input type="text" name="meta_' . $value . '" value="' . htmlspecialchars($tag->Metas->$value) . '" class="metasrc"/></p>';
        } else {
            $tag_meta_intro = $tagdescription_intro;
            echo '<p><span class="title">' . $tag_meta_intro . '</span><br /><textarea cols="3" rows="6" id="edtIntro" name="meta_' . $value . '" class="metaintro">' . htmlspecialchars($tag->Metas->$value) . '</textarea></p></div>';
        }
    }
}

function tpure_SingleSEO()
{
    global $zbp,$article;
    $array = array('singletitle', 'singlekeywords', 'singledescription');
    $singletitle_intro = 'SEO标题';
    $singlekeywords_intro = 'SEO关键词';
    $singledescription_intro = 'SEO描述';
    if (is_array($array) == false) {
        return null;
    }
    if (count($array) == 0) {
        return null;
    }
    foreach ($array as $key => $value) {
        if ($key == 0) {
            $single_meta_intro = $singletitle_intro;
            echo '<div class="introbox"><div class="togglelabel">+++++ 文章页面SEO设置 +++++</div><p><label>' . $single_meta_intro . '</label><input type="text" name="meta_' . $value . '" placeholder="请输入' . $single_meta_intro . '..." value="' . htmlspecialchars($article->Metas->$value) . '" class="metasrc"/></p>';
        } elseif ($key == 1) {
            $single_meta_intro = $singlekeywords_intro;
            echo '<p><label>' . $single_meta_intro . '</label><input type="text" name="meta_' . $value . '" placeholder="请输入' . $single_meta_intro . '..." value="' . htmlspecialchars($article->Metas->$value) . '" class="metasrc"/></p>';
        } else {
            $single_meta_intro = $singledescription_intro;
            echo '<p><span class="title">' . $single_meta_intro . '</span><br /><textarea cols="3" rows="6" name="meta_' . $value . '" placeholder="请输入' . $single_meta_intro . '..." class="metaintro">' . htmlspecialchars($article->Metas->$value) . '</textarea></p></div>';
        }
    }
}

function tpure_isMobile()
{
    global $zbp;
    if (isset($_GET['must_use_mobile'])) {
        return true;
    }
    $is_mobile = false;
    $regex = '/android|adr|iphone|ipad|linux|windows\sphone|kindle|gt\-p|gt\-n|rim\stablet|opera|meego|Mobile|Silk|BlackBerry|opera\smini/i';
    if (preg_match($regex, GetVars('HTTP_USER_AGENT', 'SERVER'))) {
        $is_mobile = true;
    }

    return $is_mobile;
}

function tpure_Thumb($Source, $IsThumb = '0')
{
    global $zbp;
    $ThumbSrc = '';
    $pattern = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.jpg|\.png]))[\'|\"].*?[\/]?>/i";
    $content = $Source->Content;
    preg_match_all($pattern, $content, $matchContent);
    if ($zbp->Config('tpure')->PostIMGON == '1') {
        if (isset($Source->Metas->proimg)) {
            $temp = $Source->Metas->proimg;
        } elseif (isset($matchContent[1][0])) {
            $temp = $matchContent[1][0];
        } else {
            if ($zbp->Config('tpure')->PostTHUMBON == '1') {
                $temp = $zbp->Config('tpure')->PostTHUMB;
            } else {
                $temp = '';
            }
        }
    } else {
        $temp = '';
    }
    $ThumbSrc = $temp;

    return $ThumbSrc;
}

function InstallPlugin_tpure()
{
    global $zbp;
    if (!$zbp->Config('tpure')->HasKey('Version')) {
        $array = array(
            'PostLOGO'            => $zbp->host . 'zb_users/theme/tpure/style/images/logo.svg',
            'PostLOGOON'          => '0',
            'PostFAVICON'         => $zbp->host . 'zb_users/theme/tpure/style/images/favicon.ico',
            'PostFAVICONON'       => '0',
            'PostTHUMB'           => $zbp->host . 'zb_users/theme/tpure/style/images/thumb.png',
            'PostTHUMBON'         => '0',
            'PostBANNERON'        => '1',
            'PostBANNER'          => $zbp->host . 'zb_users/theme/tpure/style/images/banner.jpg',
            'PostBANNERDISPLAYON' => '1',
            'PostBANNERFONT'      => 'Good Luck To You!',
            'PostBANNERPCHEIGHT'  => '360',
            'PostBANNERMHEIGHT'   => '150',
            'PostIMGON'           => '1',
            'PostSEARCHON'        => '1',
            'PostSCHTXT'          => '搜索...',
            'PostVIEWALLON'       => '1',
            'PostVIEWALLHEIGHT'   => '1000',
            'PostVIEWALLSTYLE'    => '1',
            'PostLISTINFO' => '{"user":"1","date":"1","cate":"0","view":"1","cmt":"0","edit":"1","del":"1"}',
            'PostARTICLEINFO' => '{"user":"1","date":"1","cate":"1","view":"1","cmt":"0","edit":"1","del":"1"}',
            'PostPAGEINFO' => '{"user":"1","date":"0","view":"0","cmt":"0","edit":"1","del":"1"}',
            'PostSINGLEKEY'       => '1',
            'PostPAGEKEY'         => '1',
            'PostRELATEON'        => '1',
            'PostRELATECATE'      => '1',
            'PostRELATENUM'       => '6',
            'PostFILTERCATEGORY'  => '',
            'PostINTRONUM'        => '100',
            'PostSITEMAPON'       => '1',
            'PostMOREBTNON'       => '1',
            'PostARTICLECMTON'    => '1',
            'PostPAGECMTON'       => '1',
            'PostFIXMENUON'       => '1',
            'PostLOGOHOVERON'     => '1',
            'PostBLANKON'         => '0',
            'PostGREYON'          => '0',
            'PostREMOVEPON'       => '1',
            'PostTIMEAGOON'       => '1',
            'PostBACKTOTOPON'     => '1',
            'PostAJAXPOSTON'      => '1',
            'PostSAVECONFIG'      => '1',

            'SEOON'          => '0',
            'SEOTITLE'       => $zbp->name . ' - ' . $zbp->title,
            'SEOKEYWORDS'    => '关键词1,关键词2,关键词3',
            'SEODESCRIPTION' => '此处为网站描述内容',

            'PostCOLORON'    => '0',
            'PostCOLOR'      => '0188fb',
            'PostBGCOLOR'    => 'f6f8f9',
            'PostSIDELAYOUT' => 'r',
            'PostCUSTOMCSS'  => '',
        );
        foreach ($array as $value => $intro) {
            $zbp->Config('tpure')->$value = $intro;
        }
        $zbp->SaveConfig('tpure');
    }
}

function UninstallPlugin_tpure()
{
    global $zbp;
    if ($zbp->Config('tpure')->PostSAVECONFIG == 0) {
        $zbp->DelConfig('tpure');
    }
}
