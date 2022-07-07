<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-25 15:31
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class Article_Widget
{
    private $page;

    private $postType;


    public function __construct(Widget_Archive $archive, $postType = '')
    {
        $this->page = $archive->request->is('page') ? $archive->request->get('page') : 1;
        $this->postType = $postType;
    }

    private function pageSize()
    {
        return Helper::options()->pageSize;
    }

    private function db()
    {
        return Typecho_Db::get();
    }


    private function select()
    {
        return $this->db()->select('table.contents.cid', 'table.contents.title', 'table.contents.slug', 'table.contents.created', 'table.contents.authorId',
            'table.contents.modified', 'table.contents.type', 'table.contents.status', 'table.contents.text', 'table.contents.commentsNum', 'table.contents.order',
            'table.contents.template', 'table.contents.password', 'table.contents.allowComment', 'table.contents.allowPing', 'table.contents.allowFeed',
            'table.contents.parent')->from('table.contents')
            ->join('table.fields', "table.contents.cid = table.fields.cid and table.fields.name = 'postType'");
    }

    public function total()
    {
        $query = $this->db()->select('COUNT(1) as count')->from('table.contents')->join('table.fields', "table.contents.cid = table.fields.cid and table.fields.name = 'postType'");
        $query = $this->execute($query);
        $result = $this->db()->fetchRow($query);
        $count = $result['count'];
        return ceil($count / $this->pageSize());
    }

    /**
     * @param Typecho_Db_Query $query
     * @return Typecho_Db_Query
     */
    public function execute(Typecho_Db_Query $query)
    {
        $query = $query->where('table.contents.status = ?', 'publish')
            ->where('table.contents.type = ?', 'post');
        if ($this->postType)
            $query = $query->where("table.fields.str_value= ?", $this->postType);
        return $query->order('table.contents.created', Typecho_Db::SORT_DESC);
    }

    /**
     * @throws Typecho_Exception
     */
    public function articles()
    {
        $query = $this->select();
        $query = $this->execute($query)->page($this->page, $this->pageSize());
        $result = $this->db()->fetchAll($query);
        Typecho_Widget::widget('Widget_Abstract_Contents')->to($article);
        foreach ($result as $value) {
            $article->push($value);
        }
        return $article;
    }

    public function pageNav()
    {
        if ($this->total() == 1) return;
        $output = '<ol class="page-navigator">';
        if ($this->page > 1) {
            $output .= '<li class="next"><a href="?page=1"><i class="iconfont icon-angle-left"></i></a></li>';
        }
        for ($pageNo = 1; $pageNo <= $this->total(); $pageNo++) {
            $output .= '<li class="' . ($pageNo == $this->page ? 'current' : '') . '"><a href="?page=' . $pageNo . '">' . $pageNo . '</a></li>';
        }
        if ($this->page < $this->total()) {
            $output .= '<li class="next"><a href="?page=' . $this->total() . '"><i class="iconfont icon-angle-right"></i></a></li>';
        }
        $output .= '</ol>';
        echo $output;
    }
}