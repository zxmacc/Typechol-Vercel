<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-25 23:05
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: 路由转发
 */
class Freewind_Router
{
    public function action(Widget_Archive $archive)
    {
        foreach (Config_Router::$router as $path => $router_weight) {
            $pathInfo = $archive->request->getPathInfo();
            $widget_name = $router_weight[0];
            $admin = $router_weight[1];
            if ($path == $pathInfo) {
                if ($admin && !Typecho_Widget::widget('Widget_User')->pass('administrator', true)) {
                    Freewind_Ajax::ajax_error('权限不允许执行操作');
                }
                $widget = new $widget_name();
                $widget->action($archive->request);
                break;
            }
        }
    }


    public static function register()
    {
        foreach (Config_Router::$router as $path => $widget) {
            Helper::addRoute($widget[0], $path, 'Widget_Archive', 'render');
        }
    }

    public static function register_params(Widget_Archive $archive)
    {
        foreach (Config_Router::$params as $param => $param_widget) {
            if ($archive->request->is(__FREEWIND_PARAM__ . '=' . $param)) {
                $admin = $param_widget[1];
                $widget_name = $param_widget[0];
                if ($admin && !Typecho_Widget::widget('Widget_User')->pass('administrator', true)) {
                    Freewind_Ajax::ajax_error('权限不允许执行操作');
                }
                eval('$widget = new ' . $widget_name . '();');
                $widget->action($archive);
                break;
            }
        }

    }
}