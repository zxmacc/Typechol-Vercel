<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-26 00:01
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class Color_Router implements Router_Interface
{

    function action($request)
    {
        $name = $request->get('name');
        if ($name) {
            try {
                if ($name == Freewind_Helper::theme_color()) {
                    Freewind_Ajax::ajax_error('目标配色与当前配色一样');
                }
                Freewind_Helper::user_local_color($name);
                Freewind_Ajax::ajax_success('配色更换成功');
            } catch (Exception $e) {
                Freewind_Ajax::ajax_error($e->getMessage());
            }
        }
        Freewind_Ajax::ajax_error('配色不能为空');
    }
}