<?php

class ExerciseMechanic extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('mechanic', 'required'),
            array('mechanic', 'length', 'min'=> 2),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'exercise_mechanic';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'mechanic' => 'Тип движений',
            'status' => 'Статус',
        );
    }

    public function beforeSave() {

        return parent::beforeSave();
    }

}