<?php

class m140915_143650_registry_saveda9aeca02585867cb9e4a0b88a9735fc extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '16'; 
		$model->language = 'ru'; 
		$model->value = 'Новое место'; 
		$model->create_date = '2014-09-15 16:36:49'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140915_143650_registry_saveda9aeca02585867cb9e4a0b88a9735fc does not support migration down.\n";
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
