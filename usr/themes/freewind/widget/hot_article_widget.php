<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-12-07 19:39
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class Hot_Article_Widget extends Widget_Abstract_Contents
{
    public function __construct($request, $response, $params = NULL)
    {
        parent::__construct($request, $response, $params);
        $this->parameter->setDefault(array('pageSize' => $this->options->postsListSize, 'parentId' => 0, 'ignoreAuthor' => false));
    }

    public function execute()
    {
        $select = $this->select()->from('table.contents')
            ->join('table.fields', "table.contents.cid = table.fields.cid and table.fields.name = 'postType'")
            ->where("table.contents.password IS NULL OR table.contents.password = ''")
            ->where('table.contents.status = ?', __POST_STATUS_PUBLISH)
            ->where('table.contents.type = ?', 'post')
            ->where("table.fields.str_value = ?", __POST_TYPE_ARTICLE)
            ->limit($this->parameter->pageSize)
            ->order('table.contents.views', Typecho_Db::SORT_DESC);
        $this->db->fetchAll($select, array($this, 'push'));
    }
}