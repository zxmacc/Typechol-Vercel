<?php

/**
 * 让崇拜从这里开始，用代码编写人生 - KEVINLU89.CN <br>环境要求：PHP 5.4 ~ 7.4
 * @package  Freewind一一自由之风
 * @author Mr丶冷文
 * @version 1.4
 * @link https://kevinlu98.cn
 */

if (!defined('__TYPECHO_ROOT_DIR__')) exit;
$this->need('include/header.php');
?>

    <div class="bg-white main-header">
        <h1 class="no-marge">
            <?php if ($this->is('index')) : ?>
                <?php $this->options->title() ?>
            <?php else : ?>
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
    <div style="margin:10px 20px;">
        <?php Typecho_Plugin::factory('freewind')->contentTop($this); ?>
    </div>
    <div class="blog-list" style="padding: 0 20px;margin-bottom: 20px;">
        <?php if ($this->is('index')) :
            if ($this->options->freeRecomList) :
                $recommends = json_decode($this->options->freeRecomList);
                $recommends = array_filter($recommends, function ($recommend) {
                    return $recommend->title;
                });
                if (count($recommends) > 0):
                    ?>
                    <div class="swiper" id="swiper-slider">
                        <div class="swiper-wrapper">
                            <?php foreach ($recommends as $recommend) : ?>
                                <div class="swiper-slide recommend pos-rlt border-circular bottom-shadow"
                                     style="background-image:url(<?php echo $recommend->pic ?>);">
                                    <div class="recommend-info pos-abs">
                                        <h3 class="no-marge pos-rlt">
                                            <span>站长推荐</span>
                                            <a href="<?php echo $recommend->url ?>"> <?php echo $recommend->title ?></a>
                                        </h3>
                                        <p class="no-marge hidden-xs"><?php echo $recommend->desc . '...' ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <?php if (count($recommends) > 1): ?>
                            <div class="swiper-pagination"></div>
                            <div class="swiper-button-prev"></div><!--左箭头。如果放置在swiper外面，需要自定义样式。-->
                            <div class="swiper-button-next"></div><!--右箭头。如果放置在swiper外面，需要自定义样式。-->
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>


        <?php while ($this->next()) : ?>
            <?php if ($this->fields->postType == 2) : ?>
                <div class="blog-item bottom-shadow">
                    <div class="shuo-title pos-rlt">
                        <div class="shuo-avatar pos-abs">
                            <?php echo $this->author->gravatar(50); ?>
                        </div>
                        <div class="shuo-info">
                            <p class="author-name">
                                <?php echo $this->title(); ?>
                            </p>
                            <p class="time">
                                <?php $this->date('Y-m-d H:i'); ?>
                            </p>
                        </div>
                    </div>
                    <div class="shuo-content">
                        <?php echo Freewind_Article::_content($this) ?>
                        <?php if ($this->fields->postShuoType == 2): ?>
                            <meting-js
                                    list-folded="true"
                                    server="<?php echo $this->fields->postShuoMP ?>"
                                    type="<?php echo $this->fields->postShuoMT ?>"
                                    id="<?php echo $this->fields->postShuoMusic ?>">
                            </meting-js>
                        <?php elseif ($this->fields->postShuoType == 3): ?>
                            <iframe class="fwbili" src="//player.bilibili.com/player.html?bvid=<?php echo $this->fields->postShuoBvid ?>&page=<?php echo $this->fields->postShuoPage ?>"></iframe>
                        <?php else: ?>
                            <?php foreach (Freewind_Article::shuo_pic_list($this) as $picture): ?>
                                <img class="lw-shuo-img lazy" data-original="<?php echo $picture ?>" alt="" src="">
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <p class="shuo-footer">
                        <i class="iconfont icon-comment1"> <a
                                    href="<?php $this->permalink() ?>"><?php echo $this->commentsNum ?> 回复</a> </i>
                        <?php $suport = Freewind_Article::support($this->cid) ?>
                        <i class="iconfont <?php echo $suport['icon'] ?>">
                            <a class="post-suport" data-cid="<?php echo $this->cid ?>" href="javascript:void (0)">
                                <?php echo '(' . $suport['count'] . ')' . $suport['text'] ?>
                            </a>
                        </i>
                    </p>
                </div>
                <?php $comments = Freewind_Article::get_comment_by_cid($this->cid) ?>
                <?php if ($comments) : ?>
                    <div class="index-comments bottom-shadow">
                        <ul>
                            <?php foreach ($comments as $comment) : ?>
                                <li class="pos-rlt">
                                    <div class="comment-avatar pos-abs">
                                        <img src="<?php echo Freewind_Avatar::get_avatar($comment['mail']) ?>"
                                             alt="">
                                    </div>
                                    <div class="comment-body">
                                        <div class="comment-head"><?php echo $comment['author'] ?>
                                            <?php if ($comment['authorId'] == $comment['ownerId']) : ?>
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
            <?php elseif
            ($this->fields->postType == 3) : ?>
                <div class="blog-item bottom-shadow">
                    <div class="shuo-title pos-rlt">
                        <div class="shuo-avatar pos-abs">
                            <?php echo $this->author->gravatar(50); ?>
                        </div>
                        <div class="shuo-info">
                            <p class="author-name"><?php echo $this->author(); ?>发布了相册《<?php echo $this->title(); ?>
                                》</p>
                            <p class="time"><?php $this->date('Y-m-d H:i:s'); ?></p>
                        </div>
                    </div>
                    <?php $photo_infos = Freewind_Article::photos_index($this, 4) ?>
                    <div class="photo-list">
                        <?php $index = 0; ?>
                        <?php foreach ($photo_infos['photos'] as $photo): ?>
                            <?php if ($index++ < 3): ?>
                                <img class="lw-shuo-img lazy" data-original="<?php echo $photo['src'] ?>"
                                     style="margin-bottom: 5px;"
                                     data-caption="<?php echo $photo['caption'] ?>" alt="">
                            <?php else: ?>
                                <a class="img-more" href="<?php $this->permalink() ?>"
                                   style="margin-bottom: 5px;vertical-align: top">
                                    <div class="bg-more"><span>+<?php echo $photo_infos['count'] - 4 ?></span></div>
                                    <img class="lazy" data-original="<?php echo $photo['src'] ?>"
                                         data-caption="<?php echo $photo['caption'] ?>" alt="">
                                </a>
                            <?php endif; ?>
                        <?php endforeach; ?>
                        <?php if ($this->password) : ?>
                            <p><i class="iconfont icon-mimasuolock"></i>该相册为私密相册，<a href="<?php $this->permalink() ?>">输入密码查看<i
                                            style="margin-left: 5px;" class="fa fa-angle-double-right"></i></a></p>
                        <?php else: ?>
                            <p>共<?php echo $photo_infos['count'] ?>张照片，<a href="<?php $this->permalink() ?>">查看全部<i
                                            style="margin-left: 5px;" class="fa fa-angle-double-right"></i></a></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php else : ?>
                <div class="blog-item bottom-shadow">
                    <?php if ($this->fields->postShow == 3) : ?>
                        <div class="big-img">
                            <img src="<?php $this->fields->postShowImg() ?>" alt="">
                        </div>
                    <?php endif; ?>
                    <h3 class="no-marge">
                        <?php $categories = $this->categories ?>
                        <?php foreach ($categories as $category) : ?>
                            <a class="hide-md hide-sm hidden-xs badge-green"
                               href="<?php echo $category['permalink'] ?>"><?php echo $category['name'] ?></a>
                        <?php endforeach; ?>
                        <a href="<?php $this->permalink() ?>">
                            <?php $this->title() ?>
                            <?php if ($this->password) : ?>
                                <i class="iconfont icon-mimasuolock"></i>
                            <?php endif; ?>
                        </a>
                    </h3>
                    <p class="item-desc"><?php echo Freewind_Article::summery($this) ?> </p>
                    <p class="item-footer no-marge">
                        <?php $suport = Freewind_Article::support($this->cid) ?>
                        <i class="iconfont <?php echo $suport['icon'] ?>">
                            <a class="post-suport" data-cid="<?php echo $this->cid ?>" href="javascript:void (0)">
                                <?php echo '(' . $suport['count'] . ')' . $suport['text'] ?>
                            </a>
                        </i>
                        <i class="iconfont icon-user1"> <?php $this->author(); ?></i>
                        <i class="iconfont icon-clock1"> <?php $this->date('Y-m-d'); ?></i>
                        <i class="iconfont icon-eye"> <?php echo Freewind_Article::views($this) ?></i>
                        <i class="iconfont icon-comment1"> <?php $this->commentsNum('%d条评论'); ?> </i>
                        <i class="iconfont icon-tags-o"><?php $this->tags(""); ?></i>
                    </p>
                </div>
            <?php endif; ?>
        <?php endwhile; ?>
    </div>
<?php $this->pageNav('<i class="iconfont icon-angle-left"></i>', '<i class="iconfont icon-angle-right"></i>', '5', '...'); ?>

<?php $this->need('include/footer.php'); ?>