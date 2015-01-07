<?php

class ExerciseEquipment extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('equipment', 'required'),
            array('equipment', 'length', 'min'=> 2),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'exercise_equipment';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'equipment' => 'Тип',
            'status' => 'Статус',
        );
    }

    public function beforeSave() {

        return parent::beforeSave();
    }

}