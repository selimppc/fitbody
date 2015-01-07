<?php

class m140609_151158_registry_saveb93d090d89c17ad116e527866f71f49d extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '1'; 
		$model->title = 'Index'; 
		$model->key = 'index'; 
		$model->type = 'folder'; 
		$model->create_date = '2014-06-09 18:11:58'; 
		$model->parent_category_id = ''; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140609_151158_registry_saveb93d090d89c17ad116e527866f71f49d does not support migration down.\n";
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
