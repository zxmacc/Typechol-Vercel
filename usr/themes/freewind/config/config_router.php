<?php


/**
 * Author: Mr丶冷文
 * Date: 2021-11-25 15:27
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: 路由配置
 */
class Config_Router
{
    public static $router = [
        '/mail/check' => ['Mail_Router', true],
        '/update/check' => ['Update_Router', false],
        '/local/color' => ['Color_Router', false],
        '/login' => ['Login_Router', false],
        '/verify/code' => ['VerifyCode_Router', false],
        '/register' => ['Regist_Router', false],
        '/upload' => ['Upload_Router', true],
        '/support' => ['Support_Router', false],
        '/close/notice' => ['Notice_Router', false],
        '/report' => ['Report_Router', false],
    ];

    public static $params = [
        __COMMENT_PARAMS__ => ['Comment_Widget', false],
    ];
}