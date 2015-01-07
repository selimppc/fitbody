<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 9/24/14
 * Time: 3:18 PM
 */
class CompanyNewsWidget extends CWidget {

    const NEWS_COUNT = 4;

    public function init() {
    }

    public function run(){

        $this->render('companyNews',array(
            'companyNews'      => FunctionHelper::mergeAndOrderByDate(ClubNews::getLastNews(), ShopNews::getLastNews(), self::NEWS_COUNT),
        ));
    }
}