<div class="content">
    <div data-cateurl="{if $type=='article' && is_object($article.Category)}{if $article.Category.ParentID}{$article.Category.Parent.Url}{else}{$article.Category.Url}{/if}{/if}" class="block">
        <div class="post">
            <h1>{$article.Title}</h1>
            <div class="info">
                {php}
                $post_info = array(
                    'user' => '<a href="'.$article->Author->Url.'" rel="nofollow">'.$article->Author->StaticName.'</a>',
                    'date' => tpure_TimeAgo($article->Time(),$zbp->Config('tpure')->PostTIMESTYLE),
                    'cate' => '<a href="'.$article->Category->Url.'">'.$article->Category->Name.'</a>',
                    'view' => $article->ViewNums >= 10000 ? round($article->ViewNums / 10000,2).$lang['tpure']['viewunit']:$article->ViewNums,
                    'cmt' => $article->CommNums,
                    'edit' => '<a href="'.$host.'zb_system/cmd.php?act=ArticleEdt&id='.$article->ID.'" target="_blank">'.$lang['tpure']['edit'].'</a>',
                    'del' => '<a href="'.$host.'zb_system/cmd.php?act=ArticleDel&id='.$article->ID.'&csrfToken='.$zbp->GetToken().'" data-confirm="'.$lang['tpure']['delconfirm'].'">'.$lang['tpure']['del'].'</a>',
                );
                $article_info = json_decode($zbp->Config('tpure')->PostARTICLEINFO,true);
                if(count((array)$article_info)){
                    foreach($article_info as $key => $info){
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
                    <span class="date">'.tpure_TimeAgo($article->Time(),$zbp->Config('tpure')->PostTIMESTYLE).'</span>
                    <span class="view">'.$article->ViewNums.'</span>';
                }
                {/php}
            </div>
            <div class="single postcon">
                {$article.Content}
                {if count($article.Tags)>0}
                <div class="tags">
                    {$lang['tpure']['tags']}
                    {foreach $article.Tags as $tag}<a href='{$tag.Url}' title='{$tag.Name}'>{$tag.Name}</a>{/foreach}
                </div>
                {/if}
                {if $zbp->Config('tpure')->PostSHARE}
                <div class="bdshare">
                    {$zbp->Config('tpure')->PostSHARE}
                </div>
                {/if}
            </div>
        </div>
        <div class="pages">
            <a href="{$article.Category.Url}" class="backlist">{$lang['tpure']['backlist']}</a>
            <p>{if $article.Prev}{$lang['tpure']['prev']}<a href="{$article.Prev.Url}" class="single-prev">{$article.Prev.Title}</a>{else}<span>{$lang['tpure']['noprev']}</span>{/if}</p>
            <p>{if $article.Next}{$lang['tpure']['next']}<a href="{$article.Next.Url}" class="single-next">{$article.Next.Title}</a>{else}<span>{$lang['tpure']['nonext']}</span>{/if}</p>
        </div>
    </div>
{template:mutuality}
{if !$article.IsLock && $zbp->Config('tpure')->PostARTICLECMTON=='1'}
    {template:comments}
{/if}
</div>
<div class="sidebar">
    {template:sidebar3}
</div>