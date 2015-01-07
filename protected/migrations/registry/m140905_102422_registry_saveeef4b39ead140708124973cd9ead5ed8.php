<?php

class m140905_102422_registry_saveeef4b39ead140708124973cd9ead5ed8 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '8'; 
		$model->title = 'Название'; 
		$model->key = 'title'; 
		$model->type = 'text'; 
		$model->create_date = ''; 
		$model->parent_category_id = '3'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102422_registry_saveeef4b39ead140708124973cd9ead5ed8 does not support migration down.\n";
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
