<?php

class CoachesController extends FrontController {

    public function actionIndex($categorySlug = null) {

        $categoryId = null;
        $categoryTitle = null;
        if ($categorySlug) {

            $category = CoachCategory::model()->slugCondition($categorySlug)->statusCondition()->find();
            if ($category) {
                $categoryId = $category->id;
                $categoryTitle = $category->title;
            }
        }

        Yii::app()->clientScript
            ->registerScriptFile('/js/front/rating/rating_readonly_init.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/rating/jquery.rating-2.0.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/rating/jquery.rating.css');

        $coaches = Coach::model()->fetchCoaches($categoryId); //be careful!! grouped by id
        $lastNews = CoachNews::model()->fetchLastNews();

        $this->render('coaches', compact('categoryId', 'categorySlug', 'coaches', 'categoryTitle', 'lastNews'));
    }

}