<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-28 16:20
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class Freewind_Article
{
    public static function support($cid)
    {
        $db = Typecho_Db::get();
        $prefix = $db->getPrefix();
        if (!array_key_exists('support', $db->fetchRow($db->select()->from('table.contents')))) {
            $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `support` INT(10) DEFAULT 0;');
            return [
                'icon' => 'icon-xin',
                'count' => 0,
                'text' => '点赞'
            ];
        }
        $row = $db->fetchRow($db->select('support')->from('table.contents')->where('cid = ?', $cid));
        $support = Freewind_Cookie::get('extend_contents_support');
        if (empty($support)) {
            $support = array();
        } else {
            $support = explode(',', $support);
        }
        if (!in_array($cid, $support)) {
            return [
                'icon' => 'icon-xin',
                'count' => $row['support'],
                'text' => '点赞'
            ];
        } else {
            return [
                'icon' => 'icon-theheart-fill',
                'count' => $row['support'],
                'text' => '已赞'
            ];
        }
    }

    public static function shuo_pic_list($article)
    {
        if ($article->fields->postShuoPic) {
            $lines = explode("\n", $article->fields->postShuoPic);
            return array_filter($lines, function ($line) {
                return trim($line);
            });
        }
        return [];
    }

    public static function _content($article)
    {
        if ($article->fields->postType == 2)
            return $article->content;
        elseif ($article->fields->postType == 3) {
            return self::photo_list($article);
        } else {
            $content = $article->content;
            if (strpos($content, '{fwcline') !== false) {
                $content = preg_replace('/{fwcline[ ]*start="([\s\S]*?)"[ ]*end="([\s\S]*?)"[ ]*}[\s\S]*?{\/fwcline[ ]*}/', '<div class="fwcline" style="background-image: linear-gradient(-45deg,transparent 0,transparent 10%,$2 0,$2 40%,transparent 0,transparent 60%,$1 0,$1 90%,transparent 0,transparent 100%);"></div>', $content);
            }
            if (strpos($content, '{fwbili') !== false) {
                $content = preg_replace('/{fwbili[ ]*bvid="([\s\S]*?)"[ ]*bvnu="([\s\S]*?)"[ ]*}([\s\S]*?){\/fwbili[ ]*}/', '<iframe class="fwbili" src="//player.bilibili.com/player.html?bvid=$1&page=$2"></iframe>', $content);
            }
            if (strpos($content, '{fwmusic') !== false) {
                $content = preg_replace('/{fwmusic[ ]*source="([\s\S]*?)"[ ]*type="([\s\S]*?)"[ ]*id="([\s\S]*?)"[ ]*}[\s\S]*?{\/fwmusic[ ]*}/', '<meting-js
	server="$1"
	type="$2"
	id="$3">
</meting-js>', $content);
            }
            if (strpos($content, '{fwcode') !== false) {
                $content = preg_replace('/{fwcode[ ]*type="([\s\S]*?)"[ ]*}(<br>)?([\s\S]*?)(<br>)?{\/fwcode[ ]*}/', '<div class="fwcode fwcode-$1">$3</div>', $content);
            }
            if (strpos($content, '{fwalert') !== false) {
                $content = preg_replace('/{fwalert[ ]*type="([\s\S]*?)"[ ]*}(<br>)*([\s\S]*?)(<br>)*{\/fwalert[ ]*}/', '<div class="fwalert fwalert-$1">$3</div>', $content);
            }
            if (strpos($content, '{fwbtn') !== false) {
                $content = preg_replace('/{fwbtn[ ]*type="(.*?)"[ ]*url="(.*?)"[ ]*}(<br>)*{icon="(.*?)"[ ]*}(.*?)(<br>)*{\/fwbtn[ ]*}/', '<a class="fwbtn fwbtn-$1" href="$2" target="_blank"><i class="fa $4"></i>$5</a>', $content);
            }
            if (strpos($content, '{fwgroup') !== false) {
                $content = preg_replace('/{fwgroup[ ]*title="(.*?)"[ ]*}(<br>)?([\s\S]*?)(<br>)?{\/fwgroup[ ]*}/', '<div class="fwgroup pos-rlt"><div class="fwgroup-title pos-abs">$1</div>$3</div>', $content);
            }
            if (strpos($content, '{fwtab') !== false) {
                $content = preg_replace('/{fwthead[ ]*target="(.*?)"[ ]*}(<br>)*([\s\S]*?)(<br>)*{\/fwthead[ ]*}/', '<div class="fwthead" data-target="$1">$3</div>', $content);
                $content = preg_replace('/{fwtbody[ ]*target="(.*?)"[ ]*}(<br>)?([\s\S]*?)(<br>)?{\/fwtbody[ ]*}/', '<div class="fwtbody fwtbody-$1">$3</div>', $content);
                $content = preg_replace('/{fwh[ ]*}(<br>)*([\s\S]*?)(<br>)*{\/fwh[ ]*}/', '<div class="fwh">$2</div>', $content);
                $content = preg_replace('/{fwb[ ]*}(<br>)*([\s\S]*?)(<br>)*{\/fwb[ ]*}/', '<div class="fwb">$2</div>', $content);
                $content = preg_replace('/{fwtab[ ]*}(<br>)*([\s\S]*?)(<br>)*{\/fwtab[ ]*}/', '<div class="fwtab">$2</div>', $content);
            }
            return $content;
        }
    }

    public static function keywords($article)
    {
        if ($article->fields->postKeywords) return $article->fields->postKeywords;
        $tag = array_map(function ($tag) {
            return $tag['name'];
        }, $article->tags);
        return implode(',', $tag);
    }

    public static function summery($post, $strlen = 70)
    {
        if ($post->fields->postDesc) return $post->fields->postDesc;
        if ($post->fields->postType == 3) {
            return '发布了相册《' . $post->title . '》';
        } else {
            return mb_substr(preg_replace("/[{<](.|\n)+?[>}]/", '', $post->content), 0, $strlen) . "...";
        }
    }

    public static function meta_last_modify($mid)
    {
        $db = Typecho_Db::get();
        $prefix = $db->getPrefix();
        $sql = "SELECT FROM_UNIXTIME(`modified`,'%Y-%m-%d') as time FROM `" . $prefix . "contents` WHERE `cid` IN (SELECT `cid` FROM `" . $prefix . "relationships` WHERE mid = " . $mid . ") ORDER BY time DESC LIMIT 1";
        $res = $db->fetchRow($db->query($sql));
        return $res ? $res['time'] : '暂无更新';
    }


    public static function views($archive)
    {
        $cid = $archive->cid;
        $db = Typecho_Db::get();
        $prefix = $db->getPrefix();
        if (!array_key_exists('views', $db->fetchRow($db->select()->from('table.contents')))) {
            $db->query('ALTER TABLE `' . $prefix . 'contents` ADD `views` INT(10) DEFAULT 0;');
            echo 0;
            return;
        }
        $row = $db->fetchRow($db->select('views')->from('table.contents')->where('cid = ?', $cid));
        if ($archive->is('single')) {
            $views = Freewind_Cookie::get('extend_contents_views');
            if (empty($views)) {
                $views = array();
            } else {
                $views = explode(',', $views);
            }
            if (!in_array($cid, $views)) {
                $db->query($db->update('table.contents')->rows(array('views' => (int)$row['views'] + 1))->where('cid = ?', $cid));
                array_push($views, $cid);
                $views = implode(',', $views);
                Freewind_Cookie::set('extend_contents_views', $views); //记录查看cookie
            }
        }
        return $row['views'];
    }

    public static function enclosure($post, $fileType)
    {
        //"1" => "关闭",
        //"2" => "开启",
        //"3" => "开启登录可见",
        //"4" => "开启回复可见",
        //"5" => "开启登录回复可见",
        $user = Typecho_Widget::widget('Widget_User');
        if ($user->group == 'administrator') {
            return true;
        }

        if ($fileType == 2) {
            return true;
        } else if ($fileType == 3) {
            if ($user->hasLogin()) {
                return true;
            }
        } else if ($fileType == 4) {
            $comments = Freewind_Cookie::get('extend_contents_comments');
            if (!empty($comments)) {
                $comments = explode(',', $comments);
                if (in_array($post->cid, $comments)) {
                    return true;
                }
            }
        } else if ($fileType == 5) {
            $comments = Freewind_Cookie::get('extend_contents_comments');
            if (empty($comments)) {
                $comments = [];
            } else {
                $comments = explode(',', $comments);
            }
            if ($user->hasLogin() && in_array($post->cid, $comments)) {
                return true;
            }
        }
        return false;
    }

    public static function metas_count($type = 'category', $limit = 6)
    {
        $db = Typecho_Db::get();
        $select = $db->select("name,count")
            ->from('table.metas')
            ->where('type = ?', $type)
            ->where('parent = ?', 0)
            ->order('count', Typecho_Db::SORT_DESC)
            ->limit($limit);


        $rows = $db->fetchAll($select);

        $category_rows = [];
        foreach ($rows as $row) {
            $category_rows[] = [
                'name' => $row['name'],
                'count' => (int)$row['count']
            ];
        }
        return $category_rows;
    }


    public static function article_count($dateArray)
    {
        $db = Typecho_Db::get();
        $select = $db->select("DATE_FORMAT(FROM_UNIXTIME(created),'%Y-%m') as time,count(1) as number")
            ->from('table.contents')
            ->where('type = ?', 'post')
            ->group('time')
            ->order('time', Typecho_Db::SORT_DESC)
            ->limit(6);
        $rows = $db->fetchAll($select);

        $article_rows = [];
        foreach ($rows as $row) {
            $article_rows[$row['time']] = $row['number'];
        }
        $article_count = [];
        foreach ($dateArray as $date) {
            $article_count[] = [
                'time' => $date,
                'count' => $article_rows[$date] ? (int)$article_rows[$date] : 0
            ];
        }
        return $article_count;
    }

    public static function photo_list($post)
    {
        $lines = explode("\n", $post->text);
        $lines = array_filter($lines, function ($line) {
            return $line;
        });
        return array_map(function ($line) {
            $line = trim($line);
            $items = explode(";", $line);
            if (count($items) > 1) {
                return [
                    'caption' => $items[1],
                    'src' => $items[0]
                ];
            }
            return [
                "src" => trim($line)
            ];
        }, $lines);
    }

    public static function photos_index($post, $len = 0)
    {
        if ($post->password)
            return [
                'photos' => [
                    [
                        'src' => "https://imagebed-1252410096.cos.ap-nanjing.myqcloud.com/203306/527a1120a35f42049558fe9bce8984a2.png"
                    ]
                ],
                'count' => 0
            ];
        $lines = self::photo_list($post);
        $count = count($lines);
        $len = $len < 0 ? count($lines) : $len;
        $len = count($lines) > $len ? $len : count($lines);
        return
            [
                'photos' => array_splice($lines, 0, $len),
                'count' => $count
            ];
    }

    public static function get_statistics()
    {
        $db = Typecho_Db::get();
        $contents = $db->fetchRow($db->select('COUNT(1) as count')->from('table.contents')->where('type = ?', 'post'));
        $category = $db->fetchRow($db->select('COUNT(1) as count')->from('table.metas')->where('type = ?', 'category'));
        $comment = $db->fetchRow($db->select('COUNT(1) as count')->from('table.comments')->where('type = ?', 'comment'));
        return [
            'contents' => $contents['count'],
            'category' => $category['count'],
            'comment' => $comment['count'],
        ];
    }

    public static function get_comment_by_cid($cid, $len = 4)
    {
        $db = Typecho_Db::get();
        $select = $db->select('author,authorId,ownerId,mail,text,created')
            ->from('table.comments')
            ->where('cid = ?', $cid)
            ->order('created', Typecho_Db::SORT_DESC)
            ->limit($len);
        return $db->fetchAll($select);
    }

    public static function parseFile($data)
    {
        $lines = explode("||", $data);
        $lines = array_filter($lines, function ($line) {
            return trim($line);
        });
        if (count($lines) == 0) {
            return false;
        }
        return count($lines) > 1 ? ['url' => $lines[0], 'pwd' => $lines[1]] : ['url' => $lines[0], 'pwd' => ''];

    }

}