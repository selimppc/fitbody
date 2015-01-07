<?php

class m140905_102705_registry_savee902887df0af44aa062ae48e2dc765a2 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '12'; 
		$model->title = 'Название'; 
		$model->key = 'title'; 
		$model->type = 'text'; 
		$model->create_date = ''; 
		$model->parent_category_id = '6'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102705_registry_savee902887df0af44aa062ae48e2dc765a2 does not support migration down.\n";
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
