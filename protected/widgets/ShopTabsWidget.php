<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/26/14
 * Time: 3:33 PM
 */
class ShopTabsWidget extends CWidget {
    public $active = false;
    public $slug   = '';
    public $shop_id;

    public function init() {}

    public function run(){
        $count = ShopReview::countReviews($this->shop_id);

        $this->render('shopTabs',array(
            'slug'     => $this->slug,
            'active'   => $this->active,
            'shop_id'  => $this->shop_id,
            'count'    => $count,
        ));
    }
}