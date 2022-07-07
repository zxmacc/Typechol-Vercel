<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-28 13:37
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class VerifyCode_Router implements Router_Interface
{

    function action($request)
    {
        ob_clean();
        Freewind_Code::create_code();
        exit();
    }
}