<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-25 23:42
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class Update_Router implements Router_Interface
{

    function action($request)
    {
        $version = Freewind_Helper::instance()->getVersion();
        $lastVersion = Freewind_Helper::last_version();
        $msg = '当前版本为【' . $version . '】，最新版本为【' . $lastVersion . '】';
        Freewind_Ajax::ajax_success($msg, [
            'islast' => $lastVersion == $version,
            'url' => Freewind_Http::send_http('https://blog-1252410096.cos.ap-nanjing.myqcloud.com/freewind/version/url', [])
        ]);
    }
}