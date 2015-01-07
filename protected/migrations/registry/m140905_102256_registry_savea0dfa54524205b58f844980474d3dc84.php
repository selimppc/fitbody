<?php

class m140905_102256_registry_savea0dfa54524205b58f844980474d3dc84 extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new Registry();
		
		$model->id = '6'; 
		$model->title = 'Контакты'; 
		$model->key = 'contacts'; 
		$model->type = 'folder'; 
		$model->create_date = '2014-09-05 13:22:56'; 
		$model->parent_category_id = ''; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140905_102256_registry_savea0dfa54524205b58f844980474d3dc84 does not support migration down.\n";
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
