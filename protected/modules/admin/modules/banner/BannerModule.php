<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/27/13
 * Time: 4:00 PM
 * To change this template use File | Settings | File Templates.
 */
class BannerModule extends AdminModule {

	public $icon = 'icon-picture';
    public $title = 'Баннеры';

	public $menuItems = array(
        array(
            'title' => 'Баннеры',
            'header' => true
        ),
        array(
            'title' => 'Позиции',
            'url' => 'position'
        ),
        array(
            'title' => 'Баннеры',
            'url' => 'banner'
        ),
    );
}