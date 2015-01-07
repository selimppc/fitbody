<?php

class m140905_102750_registry_save4e13903a27b3c9e5ada1bc2d7ebe8f07 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '15'; 
		$model->title = 'Текст'; 
		$model->key = 'content'; 
		$model->type = 'redactor'; 
		$model->create_date = ''; 
		$model->parent_category_id = '7'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102750_registry_save4e13903a27b3c9e5ada1bc2d7ebe8f07 does not support migration down.\n";
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
