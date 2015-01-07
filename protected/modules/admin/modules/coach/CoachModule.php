<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/27/13
 * Time: 4:00 PM
 * To change this template use File | Settings | File Templates.
 */
class CoachModule extends AdminModule {

	public $icon = 'icon-home';
    public $title = 'Тренеры';

	public $menuItems = array(
        array(
            'title' => 'Тренеры',
            'header' => true
        ),
        array(
            'title' => 'Категории Тренеров',
            'url' => 'category'
        ),
        array(
            'title' => 'Свойства',
            'url' => 'property'
        ),
        array(
            'title' => 'Отзывы',
            'url' => 'review'
        ),
        array(
            'title' => 'Тренеры',
            'url' => 'coach'
        ),
        array(
            'title' => 'Новости',
            'url' => 'news'
        ),
        array(
            'title' => 'Видео',
            'url' => 'video'
        ),
    );
}