<?php

class ItremClass {

    public static $pagesize = 10;

    /**
     * 排序的配置
     */
    public static $sort = [
            ['name' => '最新揭晓', 'sort' => 'win', 'orderBy' => ['asc'], 'class' => 'ui-block-a'],
            ['name' => '人气', 'sort' => 'hot', 'orderBy' => ['desc'], 'class' => 'ui-block-b'],
            ['name' => '价格',  'sort' => 'price', 'orderBy' => ['asc', 'desc'], 'class' => 'ui-block-c'],
            ['name' => '最新', 'sort' => 'new', 'orderBy' => ['desc'], 'class' => 'ui-block-d'],
        ];

    /**
     * 数据库别名
     */
    public static $dbAlias = ['win' => 'remain', 'hot' => 'hots', 'price' => 'cost', 'new' => 'item_created_at'];


    /**
     * 生成排序链接
     *
     * @TODO: 样式结构提取
     *
     * @param $url     原始url
     * @param $sort   排序字段
     * @param $orderBy 顺序倒序
     *
     * @return string
     */
    public function createSort($url, $sort, $orderBy = '') {

        $sortList = '';
        foreach(self::$sort as $item) {
            $sortOrderBy = (!empty($orderBy) && count($item['orderBy']) == 2) ? ( $orderBy == 'desc' ? 'desc' : 'asc' ) : $item['orderBy'][0];
            $active = ($sort == $item['sort']) ? 'ui-btn-active' : '';
            $sortList .= "<li class='{$item['class']}'>".
                         "<a href='javascript:;' data-url='{$url}?sort={$item['sort']}&orderBy={$orderBy}' class='item-list ui-link ui-btn {$active}'>{$item['name']}</a>".
                         "</li>";

        }

        return $sortList;
    }

}
