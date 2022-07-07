<?php

/**
 * Author: Mr丶冷文
 * Date: 2021-11-28 21:06
 * Email: kevinlu98@qq.com
 * Blog: https://kevinlu98.cn
 * Description:
 */
class Report_Router implements Router_Interface
{

    function action($request)
    {
        ob_clean();
        $dateArray = self::create_date_array();
        Freewind_Ajax::ajax_success('', [
            'article' => Freewind_Article::article_count($dateArray),
            'category' => Freewind_Article::metas_count(),
            'tag' => Freewind_Article::metas_count('tag'),
        ]);
    }

    private static function create_date_array($ago = 5)
    {
        $start = new DateTime('first day of ' . $ago . ' month ago');
        $end = new DateTime();
        $interval = DateInterval::createFromDateString('1 month');
        $period = new DatePeriod($start, $interval, $end);
        $dateArray = [];
        foreach ($period as $dt) {
            $dateArray[] = $dt->format("Y-m");
        }
        return $dateArray;
    }
}