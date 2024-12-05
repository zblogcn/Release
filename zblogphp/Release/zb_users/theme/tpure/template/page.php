{* Template Name:无侧栏文章/页面模板 * Template Type:single *}
{template:header}
<body class="{$type}">
<div class="wrapper">
    {template:navbar}
    <div class="main{if $zbp->Config('tpure')->PostFIXMENUON=='1'} fixed{/if}">
        <div class="mask"></div>
        <div class="wrap">
            {if $zbp->Config('tpure')->PostSITEMAPON=='1'}
            <div class="sitemap">{$lang['tpure']['sitemap']}<a href="{$host}">{$zbp->Config('tpure')->PostSITEMAPTXT ? $zbp->Config('tpure')->PostSITEMAPTXT : $lang['tpure']['index']}</a> &gt;
                {if $type=='article'}{if is_object($article.Category) && $article.Category.ParentID}<a href="{$article.Category.Parent.Url}">{$article.Category.Parent.Name}</a> &gt;{/if} <a href="{$article.Category.Url}">{$article.Category.Name}</a> &gt; {if $zbp->Config('tpure')->PostSITEMAPSTYLE == '1'}{$article.Title}{else}{$lang['tpure']['text']}{/if}{elseif $type=='page'}{$article.Title}{/if}
            </div>
            {/if}
            {if $article.Type==ZC_POST_TYPE_ARTICLE}
                {template:post-widesingle}
            {else}
                {template:post-widepage}
            {/if}
        </div>
    </div>
    {template:footer}