<?php

class m140905_102935_registry_save0923ad6d38cd0f5a6a7ee59fd4558bf9 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '11'; 
		$model->language = 'ru'; 
		$model->value = '<p>фыв<br></p>'; 
		$model->create_date = '2014-09-05 13:29:35'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102935_registry_save0923ad6d38cd0f5a6a7ee59fd4558bf9 does not support migration down.\n";
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
