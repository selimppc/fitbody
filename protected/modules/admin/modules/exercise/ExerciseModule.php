<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/27/13
 * Time: 4:00 PM
 * To change this template use File | Settings | File Templates.
 */
class ExerciseModule extends AdminModule {

	public $icon = 'icon-folder-open';
    public $title = 'Упражнения';

	public $menuItems = array(
        array(
            'title' => 'Словари',
            'header' => true
        ),
        array(
            'title' => 'Тип мышц',
            'url' => 'muscle'
        ),
        array(
            'title' => 'Части тела',
            'url' => 'body-part'
        ),
//        array(
//            'title' => 'Тип упражнений',
//            'url' => 'type'
//        ),
//        array(
//            'title' => 'Тип оборудования',
//            'url' => 'equipment'
//        ),
//        array(
//            'title' => 'Тип силы',
//            'url' => 'force'
//        ),
//        array(
//            'title' => 'Тип подготовки',
//            'url' => 'level'
//        ),
//        array(
//            'title' => 'Тип движений',
//            'url' => 'mechanic'
//        ),

        array(
            'title' => 'Упражнения',
            'header' => true
        ),
        array(
            'title' => 'Упражнения',
            'url' => 'exercise'
        ),

    );
}