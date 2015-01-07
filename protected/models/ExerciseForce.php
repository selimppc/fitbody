<?php

class ExerciseForce extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('force', 'required'),
            array('force', 'length', 'min'=> 2),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'exercise_force';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'force' => 'Тип',
            'status' => 'Статус',
        );
    }

    public function beforeSave() {

        return parent::beforeSave();
    }

}