<?php
/**
 * Author: Mr丶冷文
 * Date: 2021-11-24 19:12
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: 主题相关工具
 */

include_once 'freewind_cookie.php';

class Freewind_Helper
{

    private static $helper;

    private $options;

    private $version = "1.0";


    /**
     * 构造方法
     * @throws Typecho_Exception
     */
    private function __construct()
    {
        $this->options = Helper::options();

        $theme = $this->options->theme;
        $themes = Typecho_Widget::widget('Widget_Themes_List');
        while ($themes->next()) {
            if ($theme == $themes->name) {
                $this->version = $themes->version;
                break;
            }
        }

    }


    /**
     * 获取freewind版本
     * @return mixed|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * 设置本地配色
     * @param string $name 目标配色
     */
    public static function user_local_color($name = "default.css")
    {
        Freewind_Cookie::set(__COLOR_LOCAL_COOKIE__, $name, __LOCAL_COOKIE_EXPIRE__);
    }

    /**
     * 获取主题配色
     * @return mixed|string
     */
    public static function theme_color()
    {
        if (self::instance()->options->freeAllowColor == 1) {
            $color = Freewind_Cookie::get(__COLOR_LOCAL_COOKIE__);
            if ($color) return $color;
        }
        return self::instance()->options->freeStyle ?: 'default.css';
    }

    /**
     * 获取最新版本
     * @return bool|string
     * @throws Exception
     */
    public static function last_version()
    {
        return Freewind_Http::send_http('https://blog-1252410096.cos.ap-nanjing.myqcloud.com/freewind/version/version');
    }

    /**
     * 获取本地所有配色
     * @return array|false
     */
    public static function color_list()
    {
        $colors = scandir(__FREEWIND_ROOT__ . '/static/css/color');
        $colors = array_filter($colors, function ($filename) {
            return strpos($filename, ".css");
        });
        $colors = array_map(function ($name) {
            $filename = __FREEWIND_ROOT__ . '/static/css/color/' . $name;
            $fp = fopen($filename, 'r');
            $content = fread($fp, 1024);
            $is_color = preg_match('/@color-name: (.*)/', $content, $matche_name);
            $is_top = preg_match('/@top-color: (.*)/', $content, $matche_top);
            $is_left = preg_match('/@left-color: (.*)/', $content, $matche_left);
            return [
                'name' => $is_color ? $matche_name[1] : '',
                'filename' => $name,
                'top' => $is_top ? $matche_top[1] : '',
                'left' => $is_left ? $matche_left[1] : ''
            ];
        }, $colors);
        return array_filter($colors, function ($color) {
            return strlen($color['name']) > 0 && strlen($color['top']) > 0 && strlen($color['left']) > 0;
        });
    }

    public static function freeCdn($path = "")
    {
        if (Helper::options()->freeAllowCdn == 1) {
            if (Helper::options()->freeCdn) {
                return Helper::options()->freeCdn . $path;
            }
        }
        return __FREEWIND_CDN__ . $path;

    }

    public static function instance()
    {
        if (!self::$helper instanceof self) {
            self::$helper = new self();
        }
        return self::$helper;
    }
}