<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 12/2/13
 * Time: 10:52 AM
 * To change this template use File | Settings | File Templates.
 */
class MigrateController extends RegistryController {

	public function init()
	{
		parent::init();
		Yii::import('admin.modules.registry.models.*');
	}

	public function actionIndex($r)
	{
		Yii::app()->static->migrate();
		parent::redirect($r);
	}

	public function actionMake($r)
	{
		Yii::app()->static->makeMigrations();
		parent::redirect($r);
	}
}