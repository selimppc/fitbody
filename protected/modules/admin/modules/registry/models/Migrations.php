<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 12/2/13
 * Time: 10:37 AM
 * To change this template use File | Settings | File Templates.
 */
class Migrations extends CActiveRecord {

	/**
	 * Returns the static model of the specified AR class.
	 *
	 * @param string $className active record class name.
	 *
	 * @return Migrations the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'migrations';
	}
}