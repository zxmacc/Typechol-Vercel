<?php error_reporting(0); ?>
<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

//配置
require_once('config/config_constant.php');
require_once('config/config_router.php');
//freewind 核心工具
include_once 'core/freewind_cookie.php';
include_once 'core/freewind_code.php';
include_once 'core/freewind_ajax.php';
include_once 'core/freewind_cache.php';
include_once 'core/freewind_helper.php';
include_once 'core/freewind_http.php';
include_once 'core/freewind_avatar.php';
include_once 'core/freewind_mail.php';
include_once 'core/freewind_article.php';
include_once 'core/freewind_utils.php';
//路由
include_once 'router/router_interface.php';
include_once 'router/color_router.php';
include_once 'router/login_router.php';
include_once 'router/mail_router.php';
include_once 'router/notice_router.php';
include_once 'router/regist_router.php';
include_once 'router/support_router.php';
include_once 'router/update_router.php';
include_once 'router/upload_router.php';
include_once 'router/report_router.php';
include_once 'router/verifycode_router.php';
//组件
include_once 'widget/article_widget.php';
include_once 'widget/comment_widget.php';
include_once 'widget/hot_article_widget.php';
// freewind对后台的扩展
include "plugins/setting_plugin.php";
include "plugins/post_plugin.php";

include_once 'core/freewind_router.php';

/**
 * 初始化
 * @param Widget_Archive $archive
 */
function themeInit(Widget_Archive $archive)
{
    Freewind_Router::register();
    Freewind_Router::register_params($archive);
    Typecho_Plugin::factory('Widget_Archive')->beforeRender = array('Freewind_Router', 'action');
}

/**
 * 后台外观设置
 * @param $form
 */
function themeConfig($form)
{
    include_once 'config/theme_config.php';
}

/**
 * 独立页面与文章设置
 * @param $layout
 */
function themeFields(Typecho_Widget_Helper_Layout $layout)
{
    ?>
    <link rel="stylesheet" href="<?php Helper::options()->themeUrl('static/admin/postwrite.css') ?>">
    <link href="<?php echo Freewind_Helper::freeCdn('plugin/layui/css/layui.min.css') ?>" rel="stylesheet">
    <link rel="stylesheet" href="<?php Helper::options()->themeUrl('static/css/single.css') ?>">
    <?php
    $uri = $_SERVER['REQUEST_URI'];
    if (strstr($uri, "write-page")) {
        ?>
        <style>
            #custom-field textarea {
                width: 100%;
                height: 300px;
                resize: none;
            }


        </style>
        <?php
        $visible = new Typecho_Widget_Helper_Form_Element_Select("visible", [
            "1" => "可见",
            "2" => "不可见"
        ], null, _t("首页页面可见"), _t("即左侧栏点开组成的页面选项是否可见"));
        $icon = new Typecho_Widget_Helper_Form_Element_Text("icon", null, null, _t('导航图标'),
            _t('仅在首页可见时生效<br>详细图标地址<a href="http://www.kevinlu98.cn/page/8.html" target="_blank">Freewind字体图标库</a>'));
        $layout->addItem($visible);
        $layout->addItem($icon);
        $html = new Typecho_Widget_Helper_Form_Element_Textarea(
            "html",
            null, null,
            _t('页面HTML'),
            _t('页面HTML'));
        $css = new Typecho_Widget_Helper_Form_Element_Textarea(
            "css",
            null, null,
            _t('页面css'),
            _t('页面css'));
        $js = new Typecho_Widget_Helper_Form_Element_Textarea(
            "js",
            null, null,
            _t('页面js'),
            _t('页面js'));
        $layout->addItem($html);
        $layout->addItem($css);
        $layout->addItem($js);
        include_once 'plugins/page_plugin.php';
    } elseif (strstr($uri, "write-post")) {
        ?>
        <?php
        Typecho_Plugin::factory('admin/write-post.php')->bottom = ['Post_Plugin', 'render'];
        $postType = new Typecho_Widget_Helper_Form_Element_Select("postType", [
            "1" => "博文",
            "2" => "说说",
            "3" => "相册",
        ], '1', _t("文章类型"), _t(""));
        $layout->addItem($postType);

        $postKeywords = new Typecho_Widget_Helper_Form_Element_Text("postKeywords",
            null, null, _t("关键字(SEO优化)"), _t("多个关键字用 <b>,</b> 隔开"));
        $layout->addItem($postKeywords);
        $postDesc = new Typecho_Widget_Helper_Form_Element_Textarea("postDesc",
            null, null, _t("描述信息(SEO优化)"), _t(""));
        $layout->addItem($postDesc);
        $postShow = new Typecho_Widget_Helper_Form_Element_Select("postShow", [
            "1" => "仅文字",
            "3" => "大图",
        ], '1', _t("展示类型"), _t("列表页面展示的类型"));
        $layout->addItem($postShow);
        $postShowImg = new Typecho_Widget_Helper_Form_Element_Text("postShowImg",
            null, null, _t("列表页展示图片"), _t("列表页展示图片的外链，仅当展示类型为大图时有效"));
        $layout->addItem($postShowImg);
        $postFile = new Typecho_Widget_Helper_Form_Element_Hidden("postFile", null, '1', _t("附件"), _t("是否有附件，当选项至少为开启时展示附件"));
        $layout->addItem($postFile);
        $postFileName = new Typecho_Widget_Helper_Form_Element_Hidden("postFile",
            null, null, _t("附件"));
        $layout->addItem($postFileName);
        $postShuoType = new Typecho_Widget_Helper_Form_Element_Select("postShuoType", [
            "1" => "朋友圈图文",
            "2" => "音乐分享",
            "3" => "B站视频分享",
        ], '1', _t("说说类型"), _t(""));
        $layout->addItem($postShuoType);
        $postShuoPic = new Typecho_Widget_Helper_Form_Element_Textarea("postShuoPic",
            null, null, _t("图片列表"), _t("朋友图样式的图片列表"));
        $layout->addItem($postShuoPic);
        $postShuoMP = new Typecho_Widget_Helper_Form_Element_Select("postShuoMP", [
            "netease" => "网易云音乐",
            "tencent" => "腾讯音乐",
        ], '1', _t("音乐提供方"), _t(""));
        $layout->addItem($postShuoMP);
        $postShuoMT = new Typecho_Widget_Helper_Form_Element_Select("postShuoMT", [
            "playlist" => "歌单",
            "song" => "歌曲",
        ], '1', _t("音乐类型"), _t(""));
        $layout->addItem($postShuoMT);
        $postShuoMusic = new Typecho_Widget_Helper_Form_Element_Text("postShuoMusic",
            null, null, _t("音乐/歌单ID"), _t("网易云/QQ音乐的音乐/歌单ID，获取方式参考"));
        $layout->addItem($postShuoMusic);
        $postShuoBvid = new Typecho_Widget_Helper_Form_Element_Text("postShuoBvid",
            null, null, _t("BvId"), _t("B站视频的BvId"));
        $layout->addItem($postShuoBvid);
        $postShuoPage = new Typecho_Widget_Helper_Form_Element_Text("postShuoPage",
            null, null, _t("剧集数"), _t("B站视频的剧集数"));
        $layout->addItem($postShuoPage);
    }

}

/**
 * 评论设置
 * @param $comments
 * @param $options
 */
function threadedComments($comments, $options)
{
    ?>
    <li>
        <?php $comments->parentAuthor(); ?>

        <div class="pos-abs avatar">
            <img class="lazy" data-original="<?php echo Freewind_Avatar::get_avatar($comments->mail) ?>"
                 alt="">
        </div>
        <div class="commen-body">
            <p class="comm-title">
                <strong><?php echo $comments->author; ?></strong>
                <?php if ($comments->authorId === $comments->ownerId): ?>
                    <i class="identity admin">管理员</i>
                <?php elseif ($comments->authorId != 0): ?>
                    <i class="identity mumber">会员</i>
                <?php else: ?>
                    <i class="identity vistor">游客</i>
                <?php endif; ?>
                <span><?php $comments->date('Y-m-d H:i'); ?></span>
                <a class="replay-btn" href="javascript:void (0);"
                   data-parent="<?php echo $comments->coid ?>"
                   data-pname="<?php echo $comments->author; ?>"
                >回复</a>
            </p>
            <div class="comm-content bottom-shadow">
                <?php $comments->content(); ?>
            </div>
        </div>
    </li>
    <?php
    if ($comments->children) {
        $comments->threadedComments($options);
    } ?>
    <?php
}





