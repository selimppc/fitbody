<?php

class CalculatorsModule extends AdminModule {

    public $defaultController = 'calculators';

	public $icon = 'icon-retweet';
    public $title = 'Калькуляторы';

	public $menuItems = array(
        array(
            'title' => 'Калькуляторы',
            'header' => true
        ),
        array(
            'title' => 'Калькуляторы',
            'url' => 'calculators'
        ),
    );
}