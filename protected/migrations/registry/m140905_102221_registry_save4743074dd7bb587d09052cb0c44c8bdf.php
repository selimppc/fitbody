<?php

class m140905_102221_registry_save4743074dd7bb587d09052cb0c44c8bdf extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '5'; 
		$model->title = 'Партнерам'; 
		$model->key = 'partnership'; 
		$model->type = 'folder'; 
		$model->create_date = '2014-09-05 13:22:21'; 
		$model->parent_category_id = ''; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102221_registry_save4743074dd7bb587d09052cb0c44c8bdf does not support migration down.\n";
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
