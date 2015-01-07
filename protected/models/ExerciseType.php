<?php

class ExerciseType extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('type', 'required'),
            array('type', 'length', 'min'=> 2),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'exercise_type';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'type' => 'Тип',
            'status' => 'Статус',
        );
    }

    public function beforeSave() {

        return parent::beforeSave();
    }

}