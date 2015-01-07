<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/27/13
 * Time: 4:00 PM
 * To change this template use File | Settings | File Templates.
 */
class ProgramModule extends AdminModule {

    public $defaultController = 'program';

	public $icon = 'icon-th-list';
    public $title = 'Программы';

	public $menuItems = array(
        array(
            'title' => 'Программы',
            'header' => true
        ),
        array(
            'title' => 'Программы',
            'url' => 'program'
        ),
        array(
            'title' => 'Категории',
            'url' => 'category'
        ),

    );
}