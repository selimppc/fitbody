<?php

class BookModule extends AdminModule {

    public $defaultController = 'book';

	public $icon = 'icon-tasks';
    public $title = 'Книги';

	public $menuItems = array(
        array(
            'title' => 'Книги',
            'header' => true
        ),
        array(
            'title' => 'Категории Книг',
            'url' => 'category'
        ),
        array(
            'title' => 'Книги',
            'url' => 'book'
        ),

    );
}