<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/27/13
 * Time: 4:00 PM
 * To change this template use File | Settings | File Templates.
 */
class ShopModule extends AdminModule {

    public $defaultController = 'shop';

	public $icon = 'icon-shopping-cart';
    public $title = 'Спортивные магазины';

	public $menuItems = array(
        array(
            'title' => 'Категории',
            'url' => 'category'
        ),
        array(
            'title' => 'Сеть магазинов',
            'url' => 'chain'
        ),
        array(
            'title' => 'Спортивные магазины',
            'header' => true
        ),
        array(
            'title' => 'Спортивные магазины',
            'url' => 'shop'
        ),
        array(
            'title' => 'Новости',
            'url' => 'news'
        ),
        array(
            'title' => 'Отзывы',
            'url' => 'review'
        ),
    );
}