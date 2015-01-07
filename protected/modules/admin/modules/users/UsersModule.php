<?php
class UsersModule extends AdminModule {

	public $icon = 'icon-user';
    public $title = 'Пользователи';

	public function init() {
		$this->setImport(array(
			'users.models.*'
		));
	}

	public $menuItems = array(
		array(
			'title' => 'Пользователи',
			'url' => 'users'
		),
		array(
			'header' => true,
			'title' => 'Dictionaries'
		),
		array(
			'title' => 'Роли',
			'url'   => 'roles'
		),
	);
}
