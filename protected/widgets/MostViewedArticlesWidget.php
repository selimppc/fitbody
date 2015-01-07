<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/11/14
 * Time: 6:12 PM
 */
class MostViewedArticlesWidget extends CWidget {

    const NEWS_COUNT = 4;

    public function init() {}

    public function run(){

        $this->render('mostViewedArticles',array(
            'articles' => Article::getMostViewed(self::NEWS_COUNT),
        ));
    }
}