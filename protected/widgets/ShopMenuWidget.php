<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/26/14
 * Time: 2:23 PM
 */
class ShopMenuWidget extends CWidget {
    public $active = false;

    public function init() {}

    public function run(){

        $this->render('shopMenu',array(
            'category' => ShopCategory::getCategories(),
            'active'   => $this->active,
        ));
    }
}