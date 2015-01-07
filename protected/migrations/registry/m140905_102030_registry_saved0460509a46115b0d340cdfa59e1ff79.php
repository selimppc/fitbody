<?php

class m140905_102030_registry_saved0460509a46115b0d340cdfa59e1ff79 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '3'; 
		$model->title = 'О проекте'; 
		$model->key = 'about'; 
		$model->type = 'folder'; 
		$model->create_date = '2014-09-05 13:20:30'; 
		$model->parent_category_id = ''; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102030_registry_saved0460509a46115b0d340cdfa59e1ff79 does not support migration down.\n";
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
