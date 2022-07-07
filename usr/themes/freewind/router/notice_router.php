<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-28 16:49
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class Notice_Router implements Router_Interface
{

    function action($request)
    {
        Freewind_Cookie::set(__CACHE_ADVISE__, 1);
        Freewind_Ajax::ajax_success();
    }
}