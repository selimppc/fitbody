<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 11/27/13
 * Time: 5:23 PM
 * To change this template use File | Settings | File Templates.
 */
class RegistryValue extends CActiveRecord {

	/**
	 * Returns the static model of the specified AR class.
	 *
	 * @param string $className active record class name.
	 *
	 * @return RegistryValue the static model class
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName() {
		return 'registry_value';
	}

	public function rules()
	{
		return array(
			array('registry_id,language','required'),
			array('registry_id','exist','className'=>'Registry','allowEmpty'=>false,'attributeName'=>'id'),
//			array('language','in','range'=>Yii::app()->params['languages']),
		);
	}

	public function relations()
	{
		return array(
			'registry' => array(self::HAS_ONE,'Registry','registry_id')
		);
	}

	public function save($runValidation=true,$attributes=null,$create_migration=true)
	{
		if($this->isNewRecord) {
			$old = $this->findAll('old=0 AND language = :lang AND registry_id = :registry_id', array(
				':lang' => $this->language,
				':registry_id' => $this->registry_id
			));
			foreach($old as $val) {
				$val->old = 1;
				$val->save(false,null,false);
			}
		}
		$result = parent::save($runValidation,$attributes);
		if($create_migration && $result) {
			$params = array(
				'id' => null,
				'registry_id' => $this->registry_id,
				'language' => $this->language,
				'value' => $this->value,
				'create_date' => $this->create_date,
				'old' => $this->old,
				'is_new' => true
			);
			Yii::app()->static->createUpdateOrAddMigration('RegistryValue',$params);
		}
		return $result;
	}

	public function get($elementId, $lang = null)
	{
		$lang = ($lang === null) ? Yii::app()->language:$lang;
		$data = $this->find('registry_id = :registry_id AND language = :lang AND old = 0', array(
			':registry_id' => $elementId,
			':lang' => $lang
		));
		if($data == null)
			return '';
		return $data->value;
	}
}