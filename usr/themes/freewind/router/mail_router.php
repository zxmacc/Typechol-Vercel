<?php


/**
 * Author: Mr丶冷文
 * Date: 2021-11-25 15:33
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class Mail_Router implements Router_Interface
{

    function action($request)
    {
        $mailserver = Helper::options()->freeMailServer;
        $port = Helper::options()->freeMailPort;
        $mailuser = Helper::options()->freeMailUser;
        $mailpass = Helper::options()->freeMailPwd;
        $mailto = Helper::options()->freeMailRevice;
        $subject = '测试邮件';
        $content = '这是由' . Helper::options()->title . '发送的一封测试邮件';
        $state = \Freewind_Mail::send_mail($mailserver, $port, $mailuser, $mailpass, $mailto, $subject, $content);
        $state ? Freewind_Ajax::ajax_success('测试邮件发送成功') : Freewind_Ajax::ajax_error('测试邮件发送失败');
    }
}