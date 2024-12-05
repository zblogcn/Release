{* Template Name:列表页模板 * Template Type:list,author *}
{template:header}
<body class="{$type}">
<div class="wrapper">
    {template:navbar}
    <div class="main{if $zbp->Config('tpure')->PostFIXMENUON=='1'} fixed{/if}">
        <div class="mask"></div>
        <div class="wrap">
        {if $zbp->Config('tpure')->PostSITEMAPON=='1'}
            <div class="sitemap">{$lang['tpure']['sitemap']}<a href="{$host}">{$lang['tpure']['index']}</a>
{if $type == 'category'}
{tpure_navcate($category.ID)}
{else}
> {$title}
{/if}
            </div>
            {/if}
            <div>
                <div class="content">
                    <div class="block">
                        {foreach $articles as $article}
                            {if $article.IsTop}
                            {template:post-istop}
                            {else}
                            {template:post-multi}
                            {/if}
                        {/foreach}
                    </div>
                    {if $pagebar && $pagebar.PageAll > 1}
                    <div class="pagebar">
                        {template:pagebar}
                    </div>
                    {/if}
                </div>
                <div class="sidebar">
                    {template:sidebar2}
                </div>
            </div>
        </div>
    </div>
    {template:footer}