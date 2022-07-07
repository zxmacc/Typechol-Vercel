<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-25 15:13
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class Freewind_Cookie
{
    private static $key = "freewind";

    private static function encrypt($data)
    {
        return urlencode(base64_encode($data));
    }

    private static function decrypt($data)
    {
        return base64_decode(urldecode($data));
    }


    public static function get($key)
    {
        $k = md5($key);
        $value = $_COOKIE[$k];
        if ($value) {
            return self::decrypt($value);
        }
        return false;
    }


    public static function set($key, $value, $expires = 0)
    {
        $k = md5($key);
        $v = self::encrypt($value);
        $time = time() + ($expires > 0 ? $expires : __LOCAL_COOKIE_EXPIRE__);
        setcookie($k, $v, $time, '/');
    }
}