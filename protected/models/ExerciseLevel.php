<?php

class ExerciseLevel extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('level', 'required'),
            array('level', 'length', 'min'=> 2),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'exercise_level';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'level' => 'Уровень',
            'status' => 'Статус',
        );
    }

    public function beforeSave() {

        return parent::beforeSave();
    }

}