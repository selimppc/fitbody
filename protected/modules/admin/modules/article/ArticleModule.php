<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/27/13
 * Time: 4:00 PM
 * To change this template use File | Settings | File Templates.
 */
class ArticleModule extends AdminModule {

    public $defaultController = 'article';

	public $icon = 'icon-briefcase';
    public $title = 'Статьи';

	public $menuItems = array(
        array(
            'title' => 'Статьи',
            'header' => true
        ),
        array(
            'title' => 'Категории',
            'url' => 'category'
        ),
		array(
			'title' => 'Подкатегории',
			'url' => 'subcategory'
		),
        array(
            'title' => 'Статьи',
            'url' => 'article'
        ),
		array(
			'title' => 'Комментарии',
			'url' => 'comment'
		),
    );
}