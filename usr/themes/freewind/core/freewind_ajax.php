<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-24 20:54
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: Ajax返回
 */
class Freewind_Ajax
{

    /**
     * Ajax返回
     * @param false $success 是否成功
     * @param string $msg 消息
     * @param array $data 数据
     */
    function ajax_normail($success = false, $msg = '', $data = [])
    {
        $result = [
            'success' => $success,
            'msg' => $msg,
            'data' => $data
        ];
        self::ajax_return($result);
    }

    /**
     * Ajax返回 错误
     * @param string $msg 错误信息
     */
    public static function ajax_error($msg = '')
    {
        $result = [
            'success' => false,
            'msg' => $msg
        ];
        self::ajax_return($result);
    }

    /**
     * Ajax返回 成功
     * @param string $msg 成功信息
     * @param array $data 数据
     */
    public static function ajax_success($msg = '', $data = [])
    {
        $result = [
            'success' => true,
            'msg' => $msg,
            'data' => $data
        ];
        self::ajax_return($result);
    }


    /**
     * Ajax返回  自定义
     * @param false[] $data
     */
    public static function ajax_return($data = ['success' => false])
    {
        ob_clean();
        echo json_encode($data);
        exit();
    }


    public static function validator_error($error){
        foreach ($error as $k => $v) {
            self::ajax_error($v);
        }
    }
}