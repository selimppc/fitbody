<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/27/13
 * Time: 4:00 PM
 * To change this template use File | Settings | File Templates.
 */
class PlaceModule extends AdminModule {

	public $icon = 'icon-home';
    public $title = 'Расположение';

	public $menuItems = array(
        array(
            'title' => 'Словари',
            'header' => true
        ),
        array(
            'title' => 'Страны',
            'url' => 'country'
        ),
        array(
            'title' => 'Города',
            'url' => 'city'
        ),
        array(
            'title' => 'Метро',
            'url' => 'underground'
        ),
    );
}