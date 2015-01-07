<?php

class m140915_143650_registry_save4d99fb9d6eaaab900d27a7529ac5acd0 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '17'; 
		$model->language = 'ru'; 
		$model->value = 'A5jK43dEE'; 
		$model->create_date = '2014-09-15 16:36:49'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140915_143650_registry_save4d99fb9d6eaaab900d27a7529ac5acd0 does not support migration down.\n";
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
