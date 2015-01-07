<?php

class m140905_102949_registry_save5cdae8dc0181e833c248ca33102532b1 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '13'; 
		$model->language = 'ru'; 
		$model->value = '<p>йцуц<br></p>'; 
		$model->create_date = '2014-09-05 13:29:49'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102949_registry_save5cdae8dc0181e833c248ca33102532b1 does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}
