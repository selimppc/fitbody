<?php

class m140905_102718_registry_save922eaca17a5fcae979dac081df8a3b73 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '13'; 
		$model->title = 'Текст'; 
		$model->key = 'content'; 
		$model->type = 'redactor'; 
		$model->create_date = ''; 
		$model->parent_category_id = '6'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102718_registry_save922eaca17a5fcae979dac081df8a3b73 does not support migration down.\n";
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
