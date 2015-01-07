<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/27/13
 * Time: 4:00 PM
 * To change this template use File | Settings | File Templates.
 */
class ClubModule extends AdminModule {

	public $icon = 'icon-lock';
    public $title = 'Клубы';

	public $menuItems = array(
        array(
            'title' => 'Клубы',
            'header' => true
        ),
        array(
            'title' => 'Сети клубов',
            'url' => 'chain'
        ),
        array(
            'title' => 'Категории',
            'url' => 'destination'
        ),
        array(
            'title' => 'Направления',
            'url' => 'property'
        ),
        array(
            'title' => 'Клубы',
            'url' => 'club'
        ),
        array(
            'title' => 'Новости',
            'url' => 'news'
        ),
        array(
            'title' => 'Отзывы',
            'url' => 'review'
        ),
//		array(
//			'title' => 'Комментарии',
//			'url' => 'ClubComment'
//		),

    );
}