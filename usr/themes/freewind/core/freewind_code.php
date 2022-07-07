<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-24 20:39
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description: 验证码
 */
class Freewind_Code
{
    private $width;
    private $height;
    private $str;
    private $im;
    private $strColor;

    function __construct($session_name = 'freewind_code', $length = 4, $width = 80, $height = 20)
    {
        $vcode = 'qwertyupasdfghjkzxcvbnmQWERTYUPASDFGHJKZXCVBNM23456789';
        $this->str = "";
        for ($i = 0; $i < $length; $i++) {
            $this->str .= $vcode[rand(0, strlen($vcode))];
        }
        session_start();
        $_SESSION[$session_name] = $this->str;
        $this->width = $width;
        $this->height = $height;
        $this->createImage();
    }

    function createImage()
    {
        $this->im = imagecreate($this->width, $this->height);//创建画布
        imagecolorallocate($this->im, 200, 200, 200);//为画布添加颜色
        for ($i = 0; $i < 4; $i++) {//循环输出四个数字
            $this->strColor = imagecolorallocate($this->im, rand(0, 100), rand(0, 100), rand(0, 100));
            imagestring($this->im, rand(3, 5), $this->width / 4 * $i + rand(5, 10), rand(2, 5), $this->str[$i], $this->strColor);
        }
        for ($i = 0; $i < 200; $i++) {//循环输出200个像素点
            $this->strColor = imagecolorallocate($this->im, rand(0, 255), rand(0, 255), rand(0, 255));
            imagesetpixel($this->im, rand(0, $this->width), rand(0, $this->height), $this->strColor);
        }
    }

    function show()
    {//
        header('content-type:image/png');//定义输出为图像类型
        imagepng($this->im);//生成图像
        imagedestroy($this->im);//销毁图像释放内存
    }


    /**
     * 生成验证码
     */
    public static function create_code()
    {
        ob_clean();
        $image = new self();//将类实例化为对象
        $image->show();//调用函数
    }

    /**
     * 检查验证码
     * @param string $user_code 用户输入的验证码
     * @return bool 是否成功
     */
    public static function check_code($user_code)
    {
        session_start();
        $code = strtolower($_SESSION["freewind_code"]);
        $_SESSION["freewind_code"] = Typecho_Common::randString(4);
        $user_code = strtolower($user_code);
        return $code == $user_code;
    }
}