<?php

class m140905_095245_registry_save3ecbaa72eae26184cef9b72ae5f5d0e9 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '2'; 
		$model->title = 'asd'; 
		$model->key = 'asd'; 
		$model->type = 'folder'; 
		$model->create_date = '2014-09-05 12:52:45'; 
		$model->parent_category_id = ''; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_095245_registry_save3ecbaa72eae26184cef9b72ae5f5d0e9 does not support migration down.\n";
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
