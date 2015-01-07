<?php
Yii::import('zii.widgets.CBreadcrumbs');

class Breadcrumbs extends CBreadcrumbs {

    public $homeLink = '<a href="/"> Главная </a>';
    public $separator = ' — ';
    public $inactiveLinkTemplate = '<a>{label}</a>';
    public $activeLinkTemplate = '<a href="{url}">{label}</a>';
    public $htmlOptions = array('class' => 'breadcrumbs');

    public function run() {
        parent::run();
    }
}