<?php

class m140905_102733_registry_savef2b97942dc20e36e45c66b38d063a71f extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '14'; 
		$model->title = 'Название'; 
		$model->key = 'title'; 
		$model->type = 'text'; 
		$model->create_date = ''; 
		$model->parent_category_id = '7'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102733_registry_savef2b97942dc20e36e45c66b38d063a71f does not support migration down.\n";
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
