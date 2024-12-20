{* Template Name:列表页单条置顶文章 *}
<div class="post">
    <h2><span title="[{$lang['msg']['top']}]" class="istop"></span><a href="{$article.Url}" target="_blank">{$article.Title}</a></h2>
    <div class="info">
        {php}
        $post_info = array(
            'user'=>'<a href="'.$article->Author->Url.'" rel="nofollow">'.$article->Author->StaticName.'</a>',
            'date'=>tpure_TimeAgo($article->Time()),
            'cate'=>'<a href="'.$article->Category->Url.'">'.$article->Category->Name.'</a>',
            'view'=>$article->ViewNums,
            'cmt'=>$article->CommNums,
            'edit'=>'<a href="'.$host.'zb_system/cmd.php?act=ArticleEdt&id='.$article->ID.'" target="_blank">'.$lang['tpure']['edit'].'</a>',
            'del'=>'<a href="'.$host.'zb_system/cmd.php?act=ArticleDel&id='.$article->ID.'&csrfToken='.$zbp->GetToken().'" data-confirm="'.$lang['tpure']['delconfirm'].'">'.$lang['tpure']['del'].'</a>',
        );
        $list_info = json_decode($zbp->Config('tpure')->PostLISTINFO,true);
        if(count((array)$list_info)){
            foreach($list_info as $key => $info){
                if($info == '1'){
                    if($user->Level == '1' && isset($post_info[$key])){
                        echo '<span class="'.$key.'">'.$post_info[$key].'</span>';
                    }else{
                        if($key == 'edit' || $key == 'del'){
                            echo '';
                        }else{
                            echo '<span class="'.$key.'">'.$post_info[$key].'</span>';
                        }
                    }
                }
            }
        }else{
            echo '<span class="user"><a href="'.$article->Author->Url.'" rel="nofollow">'.$article->Author->StaticName.'</a></span>
            <span class="date">'.tpure_TimeAgo($article->Time()).'</span>
            <span class="view">'.$article->ViewNums.'</span>';
        }
        {/php}
    </div>
    {if tpure_Thumb($article) != ''}<div class="postimg"><a href="{$article.Url}" target="_blank"><img src="{tpure_Thumb($article)}" alt="{$article.Title}"></a></div>{/if}
    <div class="intro{if tpure_Thumb($article) != ''} isimg{/if}">
        {if tpure_isMobile()}<a href="{$article.Url}" target="_blank">{/if}
            {if $zbp->Config('tpure')->PostINTRONUM}
            {php}$intro = preg_replace('/[\r\n\s]+/', ' ', trim(SubStrUTF8(TransferHTML($article->Intro,'[nohtml]'),$zbp->Config('tpure')->PostINTRONUM)).'...');{/php}{$intro}
            {else}{$article.Intro}{/if}
        {if tpure_isMobile()}</a>{/if}
    </div>
    {if $zbp->Config('tpure')->PostMOREBTNON}<div><a href="{$article.Url}" target="_blank" class="readmore">{$lang['tpure']['viewmore']}</a></div>{/if}
</div>