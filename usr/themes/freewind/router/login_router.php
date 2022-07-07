<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-28 13:07
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class Login_Router implements Router_Interface
{

    function action($request)
    {
        if (!$request->isPost()) {
            Freewind_Ajax::ajax_error('不被允许的请求方式');
        }
        $login = Typecho_Widget::widget('Widget_Login');
        $options = Helper::options();
        $user = Typecho_Widget::widget('Widget_User');
        if ($user->hasLogin()) {
            Freewind_Ajax::ajax_error('已经有用户登录啦');
        }
        $validator = new Typecho_Validate();
        $validator->addRule('name', 'required', _t('请输入用户名'));
        $validator->addRule('password', 'required', _t('请输入密码'));
        $login_info = [
            'name' => $request->get('name'),
            'password' => $request->get('password'),
        ];
        if ($error = $validator->run($login_info)) {
            //返回第一条错误信息
            Freewind_Ajax::validator_error($error);
        }
        $valid = $user->login($login_info['name'], $login_info['password'], false,
            1 == $request->remember ? $options->time + $options->timezone + 30 * 24 * 3600 : 0);
        if (!$valid) {
            /** 防止穷举,休眠3秒 */
            sleep(3);
            $login->pluginHandle()->loginFail($user, $login_info['name'],
                $login_info['password'], 1 == $request->remember);
            Typecho_Cookie::set('__typecho_remember_name', $login_info['name']);
            Freewind_Ajax::ajax_error(_t('用户名或密码无效'));
        }
        $login->pluginHandle()->loginSucceed($user, $login_info['name'],
            $login_info['password'], 1 == $request->remember);
        Freewind_Ajax::ajax_success(_t(' 登录成功'));
    }
}