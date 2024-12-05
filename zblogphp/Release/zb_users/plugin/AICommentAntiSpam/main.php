<?php
require '../../../zb_system/function/c_system_base.php';
require '../../../zb_system/function/c_system_admin.php';
$zbp->Load();
$action = 'root';
if (!$zbp->CheckRights($action)) {
    $zbp->ShowError(6);
    die();
}
if (!$zbp->CheckPlugin('AICommentAntiSpam')) {
    $zbp->ShowError(48);
    die();
}

if (count($_POST) > 0) {
    CheckIsRefererValid();
    unset($_POST['csrfToken']);

    foreach ($_POST as $key => $value) {
        $zbp->Config('AICommentAntiSpam')->$key = $value;
    }
    $zbp->SaveConfig('AICommentAntiSpam');
    $zbp->SetHint('good');
    Redirect('main.php');
}

$blogtitle = 'AI反垃圾';
require $blogpath.'zb_system/admin/admin_header.php';
require $blogpath.'zb_system/admin/admin_top.php';
?>
    <div id="divMain">
        <div class="divHeader"><?php
            echo $blogtitle; ?></div>
        <div class="SubMenu">
        </div>
        <div id="divMain2">

            <form method="post" action="">
                <?php
                echo '<input type="hidden" name="csrfToken" value="'.$zbp->GetCSRFToken().'">'; ?>
                <table border="1" width="100%" cellspacing="0" cellpadding="0" class="tableBorder tableBorder-thcenter">
                    <tr>
                        <th width='20%'>&nbsp;</th>
                        <th>&nbsp;</th>
                    </tr>
                    <tr>
                        <td><p><b>· API网址</b><br/>
                            </p></td>
                        <td>
                            <p>&nbsp;
                                <?php
                                zbpform::text('api_url', $zbp->Config('AICommentAntiSpam')->api_url, '450px'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td><p><b>· API路径</b><br/>
                            </p></td>
                        <td>
                            <p>&nbsp;
                                <?php
                                zbpform::text('url_path', $zbp->Config('AICommentAntiSpam')->url_path, '450px'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td><p><b>· 模型名称</b></p></td>
                        <td>
                            <p>&nbsp;
                                <?php
                                zbpform::text('model', $zbp->Config('AICommentAntiSpam')->model, '450px'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td><p><b>· API密钥</b></p></td>
                        <td>
                            <p>&nbsp;
                                <?php
                                zbpform::password('api_key', $zbp->Config('AICommentAntiSpam')->api_key, '450px'); ?>
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td></td>
                        <td>
                            <p>
                                请使用OpenAI的API或者使用兼容的其他服务商API。快速设置常见的API接口：<?php
                                zbpform::select('quick_set', array('' => '请选择',
                                                                   'openai' => 'OpenAI',
                                                                   'doubao' => '豆包',
                                                                   'lingyi' => '零一万物',
                                                                   'ollama' => 'Ollama',
                                                                   'groq' => 'Groq',
                                                                   'deepseek' => 'Deepseek',
                                                                   'openrouter' => 'OpenRouter',
                                ), ''); ?>
                            </p>
                            <script>
                                document.getElementById('quick_set').addEventListener('change', function () {
                                    var selectedValue = this.value;

                                    if (selectedValue==='openai') {
                                        document.getElementById('api_url').value = 'https://api.openai.com';
                                        document.getElementById('url_path').value = '/v1/chat/completions';
                                        document.getElementById('model').value = 'gpt-4o-mini';
                                        document.getElementById('api_key').value = '';
                                    }
                                    if (selectedValue==='doubao') {
                                        document.getElementById('api_url').value = 'https://ark.cn-beijing.volces.com';
                                        document.getElementById('url_path').value = '/api/v3/chat/completions';
                                        document.getElementById('model').value = 'ep-***********-fwfvs';
                                        document.getElementById('api_key').value = '';
                                    }
                                    if (selectedValue==='lingyi') {
                                        document.getElementById('api_url').value = 'https://api.lingyiwanwu.com';
                                        document.getElementById('url_path').value = '/v1/chat/completions';
                                        document.getElementById('model').value = 'yi-lightning';
                                        document.getElementById('api_key').value = '';
                                    }
                                    if (selectedValue==='ollama') {
                                        document.getElementById('api_url').value = 'http://localhost:11434';
                                        document.getElementById('url_path').value = '/v1/chat/completions';
                                        document.getElementById('model').value = 'qwen:14b-chat-v1.5-q4_1';
                                        document.getElementById('api_key').value = '';
                                    }
                                    if (selectedValue==='groq') {
                                        document.getElementById('api_url').value = 'https://api.groq.com';
                                        document.getElementById('url_path').value = '/openai/v1/chat/completions';
                                        document.getElementById('model').value = 'llama3-8b-8192';
                                        document.getElementById('api_key').value = '';
                                    }
                                    if (selectedValue==='deepseek') {
                                        document.getElementById('api_url').value = 'https://api.deepseek.com';
                                        document.getElementById('url_path').value = '/chat/completions';
                                        document.getElementById('model').value = 'deepseek-chat';
                                        document.getElementById('api_key').value = '';
                                    }
                                    if (selectedValue==='openrouter') {
                                        document.getElementById('api_url').value = 'https://openrouter.ai';
                                        document.getElementById('url_path').value = '/api/v1/chat/completions';
                                        document.getElementById('model').value = 'anthropic/claude-3-haiku';
                                        document.getElementById('api_key').value = '';
                                    }
                                });
                            </script>
                        </td>
                    </tr>
                    <tr>
                        <td><p><b>· 是否记录日志</b></p></td>
                        <td>
                            <p>&nbsp;
                                <?php
                                zbpform::zbradio('save_log', $zbp->Config('AICommentAntiSpam')->save_log); ?>保存到网站的/zb_users/logs/目录下
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td><p><b>· 是否保存到数据库</b></p></td>
                        <td>
                            <p>&nbsp;
                                <?php
                                zbpform::zbradio('checking', $zbp->Config('AICommentAntiSpam')->checking); ?>垃圾评论保存到数据库，可在<a href="<?php
                                echo $zbp->host; ?>zb_system/admin/index.php?act=CommentMng&ischecking=1" target="_blank">【审核评论】</a>手动审核
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td><p><b>· 系统提示词</b></p></td>
                        <td>
                            <p>&nbsp;
                                <?php
                                zbpform::textarea('system_content', $zbp->Config('AICommentAntiSpam')->system_content, '650px', '250px'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
                <p>
                    <input type="submit" class="button" value="提交" id="btnPost"/>
                </p>
            </form>
        </div>
    </div>

<?php
require $blogpath.'zb_system/admin/admin_footer.php';
RunTime();
?>