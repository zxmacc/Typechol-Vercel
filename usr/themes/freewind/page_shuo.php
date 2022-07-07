<?php
/**
 * 说说中心
 *
 * @package custom
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('include/header.php');
?>
    <div class="bg-white main-header">
        <h1 class="no-marge">
            <?php if ($this->is('index')) : ?>
                <?php $this->options->title() ?>
            <?php else: ?>
                <?php $this->archiveTitle(array(
                    'category' => _t('分类 %s 下的文章'),
                    'search' => _t('包含关键字 %s 的文章'),
                    'tag' => _t('标签 %s 下的文章'),
                    'author' => _t('%s 发布的文章')
                ), '', ''); ?>
            <?php endif; ?>
        </h1>
        <p class="no-marge"><?php $this->options->description() ?></p>
    </div>
    <div class="blog-content" style="min-height: 100vh">
        <div class="crumbs bottom-shadow">
            <a href="<?php $this->options->siteUrl(); ?>"><i class="iconfont icon-home"></i> 首页</a> <i
                    class="split">/</i>
            <strong><?php $this->archiveTitle(array(
                    'category' => _t('分类 %s 下的文章'),
                    'search' => _t('包含关键字 %s 的文章'),
                    'tag' => _t('标签 %s 下的文章'),
                    'author' => _t('%s 发布的文章')
                ), '', ''); ?></strong>
        </div>
        <div style="margin:10px 20px;">
            <?php Typecho_Plugin::factory('freewind')->contentTop($this); ?>
        </div>
        <div class="blog-list" style="padding-top: 0;">
            <?php $shuo_widget = new Article_Widget($this, 2) ?>
            <?php $shuo = $shuo_widget->articles() ?>
            <?php while ($shuo->next()): ?>
                <div class="blog-item bottom-shadow">
                    <div class="shuo-title pos-rlt">
                        <div class="shuo-avatar pos-abs">
                            <?php echo $shuo->author->gravatar(50); ?>
                        </div>
                        <div class="shuo-info">
                            <p class="author-name"><?php echo $shuo->title(); ?></p>
                            <p class="time"><?php $shuo->date('Y-m-d H:i:s'); ?></p>
                        </div>
                    </div>
                    <div class="shuo-content">
                        <?php echo Freewind_Article::_content($shuo) ?>
                        <?php if ($shuo->fields->postShuoType == 2): ?>
                            <meting-js
                                    list-folded="true"
                                    server="<?php echo $shuo->fields->postShuoMP ?>"
                                    type="<?php echo $shuo->fields->postShuoMT ?>"
                                    id="<?php echo $shuo->fields->postShuoMusic ?>">
                            </meting-js>
                        <?php elseif ($shuo->fields->postShuoType == 3): ?>
                            <iframe class="fwbili" src="//player.bilibili.com/player.html?bvid=<?php echo $shuo->fields->postShuoBvid ?>&page=<?php echo $shuo->fields->postShuoPage ?>"></iframe>
                        <?php else: ?>
                            <?php foreach (Freewind_Article::shuo_pic_list($shuo) as $picture): ?>
                                <img class="lw-shuo-img lazy" data-original="<?php echo $picture ?>" alt="" src="">
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <p class="shuo-footer">
                        <i class="iconfont icon-comment1"> <a
                                    href="<?php $shuo->permalink() ?>"><?php echo $shuo->commentsNum ?> 回复</a> </i>
                        <?php $suport = Freewind_Article::support($shuo->cid) ?>
                        <i class="iconfont <?php echo $suport['icon'] ?>">
                            <a class="post-suport"
                               data-cid="<?php echo $shuo->cid ?>"
                               href="javascript:void (0)">
                                <?php echo '(' . $suport['count'] . ')' . $suport['text'] ?>
                            </a>
                        </i>
                    </p>
                </div>
                <?php $comments = Freewind_Article::get_comment_by_cid($shuo->cid) ?>
                <?php if ($comments): ?>
                    <div class="index-comments bottom-shadow">
                        <ul>
                            <?php foreach ($comments as $comment): ?>
                                <li class="pos-rlt">
                                    <div class="comment-avatar pos-abs">
                                        <img src="<?php echo Freewind_Avatar::get_avatar($comment['mail']) ?>"
                                             alt="">
                                    </div>
                                    <div class="comment-body">
                                        <div class="comment-head"><?php echo $comment['author'] ?>
                                            <?php if ($comment['authorId'] == $comment['ownerId']): ?>
                                                <strong class='admin'>管理员</strong>
                                            <?php endif; ?>
                                            <span><?php echo date('Y-m-d H:i:s', $comment['created']) ?></span>
                                        </div>
                                        <div class="comment-content">
                                            <?php echo preg_replace("/<br>|<p>|<\/p>/", ' ', $comment['text']) ?>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            <?php endwhile; ?>
        </div>
        <?php $shuo_widget->pageNav(); ?>
    </div>
<?php $this->need('include/footer.php'); ?>