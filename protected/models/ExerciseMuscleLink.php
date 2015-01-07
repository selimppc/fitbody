<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shumer
 * Date: 6/1/14
 * Time: 2:44 PM
 * To change this template use File | Settings | File Templates.
 */
class ExerciseMuscleLink extends CActiveRecord {


	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function rules() {
		return array(
			array('exercise_id, muscle_id', 'required'),
			array('exercise_id, muscle_id', 'numerical', 'integerOnly' => true),
			array('exercise_id', 'exist', 'className' => 'Exercise', 'attributeName' => 'id'),
			array('muscle_id', 'exist', 'className' => 'Muscle', 'attributeName' => 'id'),
		);
	}

	public function tableName() {
		return 'exercise_muscle_link';
	}

	public function relations() {
		return array(
			'muscle' => array(self::HAS_ONE, 'Muscle', array('id' => 'muscle_id')),
			'exercise' => array(self::HAS_ONE, 'Exercise', array('id' => 'exercise_id')),
		);
	}

	public function attributeLabels() {
		return array(
			'muscle_id'=> 'Участвующая группы мышц',
		);
	}

	public function beforeSave() {
		return parent::beforeSave();
	}

}