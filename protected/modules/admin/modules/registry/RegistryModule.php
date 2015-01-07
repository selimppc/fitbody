<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/27/13
 * Time: 4:00 PM
 * To change this template use File | Settings | File Templates.
 */
class RegistryModule extends AdminModule {

	public $icon = 'icon-book';
    public $title = 'Реестр';

	public function init() {
		$this->defaultController = 'data';
		Yii::import('application.modules.admin.modules.registry.controllers.RegistryController');
	}

	public $menuItems = array();
}