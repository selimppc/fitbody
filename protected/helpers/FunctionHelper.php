<?php

class FunctionHelper {

    public static function upperFirst($str) {
        return mb_substr(mb_strtoupper($str, 'utf-8'), 0, 1, 'utf-8') . mb_substr($str, 1, mb_strlen($str)-1, 'utf-8');
    }

    public static function getTreeMenuCategory(&$categories, $slug, $children = false, $titleField = 'title', $url = '') {
        $lis = array();
        $votedParent = false;

        foreach ($categories as $category) {

            $voted = false;
            $childrenAppend = ($category['children']) ? static::getTreeMenuCategory($category['children'], $slug, true, $titleField, $url) : '';

            if ($category['slug'] === $slug || (isset($childrenAppend['votedParent']) && $childrenAppend['votedParent'])) {
                $votedParent = true;
                $voted = true;
            }

            $lis[] = CHtml::tag('li', (($voted) ? array('class' => 'active') : array()) , CHtml::link(
                CHtml::tag('span', array(), $category[$titleField])
                , Yii::app()->createUrl($url . '/' . $category['slug']), array('title' => $category['description']))
            . ((isset($childrenAppend['html']) && $voted) ? $childrenAppend['html'] : ''));
        }

        $html = ($children) ? '<ul class="sub_main_nav_list" style="display: block;">' : '<ul class="main_nav_list">';
        $html .= implode('', $lis) . '</ul>';

        return ($children) ? array('html' => $html, 'votedParent' => $votedParent) : $html;
    }


    public static function buildTreeArray(&$data, $rootId = 0, $parentField = 'parent_id') {
        $tree = array();
        foreach ($data as $id => $node) {
            $node = $node->attributes;
            if ($node[$parentField] == $rootId) {
                unset($data[$id]);
                $node['children'] = self::buildTreeArray($data, $node['id']);
                $tree[] = $node;
            }
        }
        return $tree;
    }




//    public static function getChildrenCatId($cats) {
//        $ids = array();
//        foreach ($cats as $cat) {
//            $ids[] = $cat->id;
//            if ($cat->children) {
//                $ids = array_merge($ids, static::getChildrenCatId($cat->children));
//            }
//        }
//        return $ids;
//    }



    public static function getBuildComments($comments, $commentForm = '', $errorParentId = null, $children = false, $parent = null) {

        $html = '';
        foreach ($comments as $comment) {

            $html .= '<li class="comment' . (($children) ? ' replied' : '') . '" id="comment-id-' . $comment->id . '"data-comment-id="' . $comment->id . '">';

            $html .= '<a class="img_box fl_l" href="' . ($comment->user->getUrlProfile()) . '">' . CHtml::image($comment->user->getPathMainImage(), $comment->user->nickname) . '</a>';
            $html .= '<span class="comments_info fl_l">';

            $html .= '<span class="fl_r comment_link"><a href="" class="must-login answer-link" data-reply="'. (($errorParentId && $comment->id == $errorParentId) ? 'close' : 'open' ).'">ответить</a></span>';
            $html .= '<span class="name"><a href="' . ($comment->user->getUrlProfile()) . '">' . (CHtml::encode(($comment->user->nickname) ?  $comment->user->nickname : ($comment->user->first_name . ' ' . $comment->user->last_name))) . ' </a></span> ';

            if ($children) {
                $html .= '<span class="date">' . DateHelper::convertDate($comment->created_at) . ' ответил</span>';
				$html .= '<span class="name"><a href="' . ($comment->user->getUrlProfile()) . '">' . (CHtml::encode(($comment->user->nickname) ?  $comment->user->nickname : ($comment->user->first_name . ' ' . $comment->user->last_name))) . ' </a></span> ';
            } else {
                $html .= '<span class="date">' . DateHelper::convertDate($comment->created_at) . '</span>';
            }

            $html .= '<span class="comment_body">' . CHtml::encode($comment->text) . '</span>';

            $html .= '</span>';

            $html .= '</li>';

            if ($errorParentId && $comment->id == $errorParentId) {
                $html .= '<li class="comment_edit">';
                $html .= $commentForm;
                $html .= '</li>';
            }

            if ($comment->children) {
                $html .= static::getBuildComments($comment->children, $commentForm, $errorParentId, true, $comment);
            }

        }
        return $html;
    }

    public static function getTerm($days){
        $month = $days/30;
        $year = $month/12;
        if($month < 1){
            switch(true){
                case $days > 4 && $days < 21:
                    return $days.' дней';
                case $days%10 == 1:
                    return $days.' день';
                case $days%10 == 2:
                case $days%10 == 3:
                case $days%10 == 4:
                    return $days.' дня';
                default:
                    return $days.' дней';
            }
        } elseif ($month < 12) {
            $month_part = $month - (int)$month;
            $month = (int)$month . ($month_part > 0.5 ? ',5' : '');
            switch(true){
                case $month > 4 && $month < 21:
                    return $month.' месяцев';
                case $month%10 == 1 && $month_part > 0.5:
                    return $month.' месяца';
                case $month%10 == 1:
                    return $month.' месяц';
                case $month%10 == 2:
                case $month%10 == 3:
                case $month%10 == 4:
                    return $month.' месяца';
                default:
                    return $month.' месяцев';
            }
        } else {
            $year_part = $year - (int)$year;
            $year = (int)$year . ($year_part > 0.5 ? ',5' : '');
            switch(true){
                case $year > 4 && $year < 21:
                    return $year.' лет';
                case $year%10 == 1 && $year_part > 0.5:
                    return $year.' года';
                case $year%10 == 1:
                    return $year.' год';
                case $year%10 <= 2:
                case $year%10 == 3:
                case $year%10 == 4:
                    return $year.' года';
                default:
                    return $year.' лет';
            }
        }
    }

    public static function mergeAndOrderByDate($array1, $array2, $limit = 4){

        if(count($array1) <= 0)
            return $array2;
        if(count($array2) <= 0)
            return $array1;

        $array = array();

        foreach($array1 as $elem1){
            foreach($array2 as $elem2){
                if(strtotime($elem2->created_at) > strtotime($array1[0]->created_at)){
                    array_push($array, array_shift($array2));
                }
            }
            array_push($array, array_shift($array1));
        }

        if(count($array) <= $limit)
            return $array;
        else
            return array_slice($array, 0, $limit);
    }
}