{* Template Name:搜索页模板 * Template Type:search *}
{template:header}
<body class="{$type}">
<div class="wrapper">
    {template:navbar}
    <div class="main{if $zbp->Config('tpure')->PostFIXMENUON=='1'} fixed{/if}">
        <div class="mask"></div>
        <div class="wrap">
            <div class="content">
                <div class="block">
                    <div class="contitle">
                        <h2>{$title}</h2>
                    </div>
                    {foreach $articles as $article}
                    {template:post-multi}
                    {/foreach}
                </div>
                {if $pagebar && $pagebar.PageAll > 1}
                <div class="pagebar">
                    {template:pagebar}
                </div>
                {/if}
            </div>
            <div class="sidebar">
                {template:sidebar5}
            </div>
        </div>
    </div>
    {template:footer}