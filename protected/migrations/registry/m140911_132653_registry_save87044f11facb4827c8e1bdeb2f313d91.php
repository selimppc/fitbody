<?php

class m140911_132653_registry_save87044f11facb4827c8e1bdeb2f313d91 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '16'; 
		$model->language = 'ru'; 
		$model->value = 'Новые клубы'; 
		$model->create_date = '2014-09-11 15:26:53'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140911_132653_registry_save87044f11facb4827c8e1bdeb2f313d91 does not support migration down.\n";
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
