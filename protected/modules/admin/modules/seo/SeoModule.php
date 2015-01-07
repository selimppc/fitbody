<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 14.10.13
 * Time: 16:36
 * To change this template use File | Settings | File Templates.
 */
class SeoModule extends AdminModule {

    public $icon = 'icon-cog';

    public $menuItems = array(
        array(
            'title' => 'Страницы',
            'url' => 'pages'
        ),
        array(
            'title' => 'Изображения',
            'url' => 'images'
        )
    );
}