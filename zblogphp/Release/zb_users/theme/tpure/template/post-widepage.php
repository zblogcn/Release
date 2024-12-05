<div class="content wide">
    <div class="block">
        <div class="post">
            <h1>{$article.Title}</h1>
            <div class="info">
                {php}
                $post_info = array(
                    'user'=>'<a href="'.$article->Author->Url.'" rel="nofollow">'.$article->Author->StaticName.'</a>',
                    'date'=>tpure_TimeAgo($article->Time(),$zbp->Config('tpure')->PostTIMESTYLE),
                    'view'=>$article->ViewNums,
                    'cmt'=>$article->CommNums,
                    'edit'=>'<a href="'.$host.'zb_system/cmd.php?act=PageEdt&id='.$article->ID.'" target="_blank">'.$lang['tpure']['edit'].'</a>',
                    'del'=>'<a href="'.$host.'zb_system/cmd.php?act=PageDel&id='.$article->ID.'&csrfToken='.$zbp->GetToken().'" data-confirm="'.$lang['tpure']['delconfirm'].'">'.$lang['tpure']['del'].'</a>',
                );
                $page_info = json_decode($zbp->Config('tpure')->PostPAGEINFO,true);
                if(count((array)$page_info)){
                    foreach($page_info as $key => $info){
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
            <div class="single">
                {$article.Content}
            </div>
        </div>
    </div>
{if !$article.IsLock && $zbp->Config('tpure')->PostPAGECMTON=='1'}
    {template:comments}
{/if}
</div>