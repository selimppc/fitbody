<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/10/14
 * Time: 3:02 PM
 */
class SideMenuWidget extends CWidget {

    public $subcategory = 0;
    public $category = 0;

    public function init() {}

    public function run() {

        $cat = Yii::app()->controller->getRootCategoriesArticle();

        $this->render('sideMenu',array(
            'subcategory' => $this->subcategory,
            'category' => $this->category,
            'cat' => $cat,
        ));
    }
}