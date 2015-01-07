<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 13:39
 * To change this template use File | Settings | File Templates.
 */
class UserRole extends CActiveRecord {

	const USER_ROLE_DEVELOPER       = 1024;
	const USER_ROLE_GLOBAL_ADMIN    = 512;
	const USER_ROLE_ADMIN           = 100;
	const USER_ROLE_MANAGER         = 50;
	const USER_ROLE_USER            = 10;
	const USER_ROLE_GUEST           = 0;

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function rules() {
		return array(
			array('title,description,id', 'required'),
			array('title', 'unique'),
			array('id','unique','on'=>'update')
		);
	}

	public function tableName() {
		return 'user_role';
	}
}