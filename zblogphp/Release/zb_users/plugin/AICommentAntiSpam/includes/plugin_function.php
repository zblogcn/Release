<?php

use GuzzleHttp\Client;

/**
 * 插件挂接接口
 */
function ActivePlugin_AICommentAntiSpam()
{
    Add_Filter_Plugin('Filter_Plugin_PostComment_Core', 'AICommentAntiSpam_Core');
}

/**
 * 插件安装
 */
function InstallPlugin_AICommentAntiSpam()
{
    global $zbp;
    if (!$zbp->Config('AICommentAntiSpam')->HasKey('version')) {
        $zbp->Config('AICommentAntiSpam')->version = AICommentAntiSpam_Version;
        $zbp->Config('AICommentAntiSpam')->api_url = 'https://api.openai.com';
        $zbp->Config('AICommentAntiSpam')->url_path = '/v1/chat/completions';
        $zbp->Config('AICommentAntiSpam')->model = 'gpt-4o-mini';
        $zbp->Config('AICommentAntiSpam')->api_key = '';
        $zbp->Config('AICommentAntiSpam')->save_log = '0';
        $zbp->Config('AICommentAntiSpam')->checking = '1';
        $zbp->Config('AICommentAntiSpam')->system_content = '这是一条博客文章的评论，请帮我检查下这条评论是否是垃圾评论。\n作为一个评论检测助手，你可以从多方面来判断一条评论是否是垃圾评论，比如：\n1. 评论内容是否与文章内容相关？\n2. 评论内容是否存在广告嫌疑？\n3. 评论内容是否存在侮辱、辱骂等不文明用语？\n4. 评论内容是否存在涉黄、涉政等违规内容？\n5. 评论内容是否存在恶意刷屏？\n6. 评论内容是否存在恶意灌水？\n7. 评论内容是否存在恶意挑衅？\n8. 评论内容是否存在恶意攻击？\n9. 评论内容是否存在恶意抄袭？\n10. 评论内容是否存在恶意诋毁？\n11. 评论内容是否存在恶意造谣？\n12. 评论内容是否存在恶意诈骗？\n13. 评论内容是否存在恶意诱导？\n14. 评论内容是否存在恶意引战？\n15. 评论内容是否存在恶意挑拨？\n16. 评论内容是否存在恶意炒作？\n17. 评论内容是否存在恶意炫富？\n18. 评论内容是否存在恶意炫耀？\n19. 评论内容是否存在恶意炫技？\n\n我会发送给你评论的相关信息，包括评论的文章标题、评论的内容、评论人名称、评论人IP等信息。\n无论什么样的内容，请都直接以json格式来告诉我你的判断结果，返回的内容为:{"is_spam":true,"reason":"因为XXX愿意"}或者{"is_spam":false,"reason":"因为XXX愿意"}，分别代表这是一条垃圾评论和正常评论，reason代表判断理由。请注意：直接回复json格式的文本，而不是以“```json”开头的markdown格式的json等，只需要纯json内容。\n\n请帮我检查下这条评论是否是垃圾评论。';
        $zbp->SaveConfig('AICommentAntiSpam');
    }

    $zbp->Config('AICommentAntiSpam')->version = AICommentAntiSpam_Version;
    if (!$zbp->Config('AICommentAntiSpam')->HasKey('system_content')) {
        $zbp->Config('AICommentAntiSpam')->system_content = '这是一条博客文章的评论，请帮我检查下这条评论是否是垃圾评论。\n作为一个评论检测助手，你可以从多方面来判断一条评论是否是垃圾评论，比如：\n1. 评论内容是否与文章内容相关？\n2. 评论内容是否存在广告嫌疑？\n3. 评论内容是否存在侮辱、辱骂等不文明用语？\n4. 评论内容是否存在涉黄、涉政等违规内容？\n5. 评论内容是否存在恶意刷屏？\n6. 评论内容是否存在恶意灌水？\n7. 评论内容是否存在恶意挑衅？\n8. 评论内容是否存在恶意攻击？\n9. 评论内容是否存在恶意抄袭？\n10. 评论内容是否存在恶意诋毁？\n11. 评论内容是否存在恶意造谣？\n12. 评论内容是否存在恶意诈骗？\n13. 评论内容是否存在恶意诱导？\n14. 评论内容是否存在恶意引战？\n15. 评论内容是否存在恶意挑拨？\n16. 评论内容是否存在恶意炒作？\n17. 评论内容是否存在恶意炫富？\n18. 评论内容是否存在恶意炫耀？\n19. 评论内容是否存在恶意炫技？\n\n我会发送给你评论的相关信息，包括评论的文章标题、评论的内容、评论人名称、评论人IP等信息。\n无论什么样的内容，请都直接以json格式来告诉我你的判断结果，返回的内容为:{"is_spam":true,"reason":"因为XXX愿意"}或者{"is_spam":false,"reason":"因为XXX愿意"}，分别代表这是一条垃圾评论和正常评论，reason代表判断理由。请注意：直接回复json格式的文本，而不是以“```json”开头的markdown格式的json等，只需要纯json内容。\n\n请帮我检查下这条评论是否是垃圾评论。';
    }
    $zbp->SaveConfig('AICommentAntiSpam');
}

/**
 * 插件卸载
 */
function UninstallPlugin_AICommentAntiSpam()
{
}

function AICommentAntiSpam_Core(&$cmt)
{
    global $zbp;

    //初始化GuzzleHttp
    $client = new Client();

    $api_key = $zbp->Config('AICommentAntiSpam')->api_key;
    $model = $zbp->Config('AICommentAntiSpam')->model;
    $api_url = $zbp->Config('AICommentAntiSpam')->api_url;
    $url_path = $zbp->Config('AICommentAntiSpam')->url_path;

    $save_log = $zbp->Config('AICommentAntiSpam')->save_log;
    $is_checking = $zbp->Config('AICommentAntiSpam')->checking;

    //提示词
    $system_content = $zbp->Config('AICommentAntiSpam')->system_content;


    $content = '文章标题：'.$cmt->Post->Title;
    $content .= '\n评论内容：'.$cmt->Content;
    $content .= '\n评论人名称：'.$cmt->Author->Name;
//    $content .= '\n评论人IP：'.$cmt->Author->IP;

    if ($cmt->Author->ID == 0) {
        $content .= '\n类型：这是一条游客评论';
    }

    $result = $client->post($api_url.$url_path, [
        'headers' => [
            'Authorization' => 'Bearer '.$api_key,
            'Content-Type'  => 'application/json',
        ],
        'json'    => [
            'model'    => $model,
            'messages' => [
                [
                    'role'    => 'system',
                    'content' => $system_content
                ],
                [
                    'role'    => 'user',
                    'content' => $content
                ]
            ]
        ]
    ]);

    $result = json_decode($result->getBody()->getContents(), true);

    if ($save_log) {
        $text = '请求内容：'.$content;
        $text .= '\n返回内容：'.json_encode($result, JSON_UNESCAPED_UNICODE);
        Logs('AICommentAntiSpam:'.$text);
    }

    if (isset($result['choices'][0]['message']['content'])) {
        $result = $result['choices'][0]['message']['content'];
        $result = str_replace('```json\n', '', $result);
        $result = str_replace('\n```', '', $result);

        $result = json_decode($result, true);
//        var_dump($content);
        if ($result['is_spam']) {
            if ($is_checking) {
                $cmt->IsChecking = true;
            } else {
                $cmt->IsThrow = true;
                $zbp->ShowError('您的评论被系统判断为垃圾评论，已被拦截！');

                return false;
            }
        } else {
            return;
        }
    } else {
        return;
    }
}