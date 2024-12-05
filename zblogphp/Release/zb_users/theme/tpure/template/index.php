{* Template Name:首页模板(勿选) * Template Type:index *}
{template:header}
<body class="{$type}">
<div class="wrapper">
    {template:navbar}
    <div class="main{if $zbp->Config('tpure')->PostFIXMENUON=='1'} fixed{/if}">
        {if $zbp->Config('tpure')->PostBANNERON=='1'}
        <div class="banner"{if $zbp->Config('tpure')->PostBANNER} style="{if !tpure_isMobile()}height:{$zbp->Config('tpure')->PostBANNERPCHEIGHT ? $zbp->Config('tpure')->PostBANNERPCHEIGHT : 360}px;{else}height:{$zbp->Config('tpure')->PostBANNERMHEIGHT ? $zbp->Config('tpure')->PostBANNERMHEIGHT : 150}px;{/if} background-image:url({$zbp->Config('tpure')->PostBANNER});"{/if}><div class="wrap"><div class="hellotip">{$zbp->Config('tpure')->PostBANNERFONT}</div></div></div>
        {/if}
        <div class="mask"></div>
        <div class="wrap">
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
                {template:sidebar}
            </div>
        </div>
    </div>
    {template:footer}