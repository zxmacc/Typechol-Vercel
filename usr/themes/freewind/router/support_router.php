<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-28 16:14
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class Support_Router implements Router_Interface
{

    function action($request)
    {
        $cid = $request->get('cid');
        if (!$cid) {
            Freewind_Ajax::ajax_error('文章不能为空');
        }
        $db = Typecho_Db::get();
        $row = $db->fetchRow($db->select('support')->from('table.contents')->where('cid = ?', $cid));
        $support = Freewind_Cookie::get('extend_contents_support');
        if (empty($support)) {
            $support = array();
        } else {
            $support = explode(',', $support);
        }
        if (!in_array($cid, $support)) {
            $db->query($db->update('table.contents')->rows(array('support' => (int)$row['support'] + 1))->where('cid = ?', $cid));
            array_push($support, $cid);
            $support = implode(',', $support);
            Freewind_Cookie::set('extend_contents_support', $support, 3600 * 12); // 每篇文章每人12小时可以点赞一次
            Freewind_Ajax::ajax_success('点赞成功', $row['support'] + 1);
        } else {
            Freewind_Ajax::ajax_error('该文章您已点过赞啦');
        }
    }

}