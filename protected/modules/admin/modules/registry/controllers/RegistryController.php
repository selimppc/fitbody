<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 12/3/13
 * Time: 1:10 PM
 * To change this template use File | Settings | File Templates.
 */
class RegistryController extends BaseController {

	public function init()
	{
		Yii::app()->clientScript->registerScriptFile(
			Yii::app()->assetManager->publish(
				Yii::getPathOfAlias('application.modules.admin.modules.registry.assets').'/main.js'
			),
			CClientScript::POS_END
		);
	}
}