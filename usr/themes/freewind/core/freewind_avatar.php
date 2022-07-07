<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-25 14:54
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: 头像工具类
 */
class Freewind_Avatar
{
    /**
     * 检验gravatar头像是否存在
     * @param string $email 邮箱
     * @return bool
     */
    private static function exist_gravatar($email)
    {
        $hash = md5(strtolower(trim($email)));
        $uri = __TYPECHO_GRAVATAR_PREFIX__ . $hash . '?d=404';
        $headers = @get_headers($uri);
        if (!preg_match("|200|", $headers[0])) {
            $has_valid_avatar = FALSE;
        } else {
            $has_valid_avatar = TRUE;
        }
        return $has_valid_avatar;
    }

    /**
     * 邮箱获取头像，有7天缓存
     * @param string $mail 邮箱
     * @return string
     */
    public static function get_avatar($mail)
    {
        if (empty($mail)) return Freewind_Helper::freeCdn('image/avatar/' . mt_rand(1, 40) . '.png');
        $avatar = Freewind_Cache::get_cache(__CACHE_AVATAR_PREFIX__ . $mail);
        if ($avatar) return $avatar;
        $avatar = Freewind_Helper::freeCdn('image/avatar/' . mt_rand(1, 40) . '.png');
        $qq = str_replace("@qq.com", "", $mail);
        if (is_numeric(trim($qq))) {
            $avatar = 'https://q1.qlogo.cn/g?b=qq&nk=' . $qq . '&s=100';
        } elseif (self::exist_gravatar($mail)) {
            $avatar = Typecho_Common::gravatarUrl($mail, 220, 'X', 'mm');
        }
        if ($avatar) {
            Freewind_Cache::put_cache(__CACHE_AVATAR_PREFIX__ . $mail, $avatar, __CACHE_AVATAR_EXPIRE__);
        }
        return $avatar;
    }

}