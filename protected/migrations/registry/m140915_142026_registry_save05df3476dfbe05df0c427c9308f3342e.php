<?php

class m140915_142026_registry_save05df3476dfbe05df0c427c9308f3342e extends CDbMigration
{
	public function up()
	{
		Yii::import("application.modules.admin.modules.registry.models.*");
		$model =  new RegistryValue();
		
		$model->id = ''; 
		$model->registry_id = '17'; 
		$model->language = 'ru'; 
		$model->value = 'A5jK43dE'; 
		$model->create_date = '2014-09-15 16:20:26'; 
		$model->old = '0'; 
		$model->save(true,null,false);

	}

	public function down()
	{
		echo "m140915_142026_registry_save05df3476dfbe05df0c427c9308f3342e does not support migration down.\n";
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
