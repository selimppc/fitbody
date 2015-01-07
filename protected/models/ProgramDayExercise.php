<?php

class ProgramDayExercise extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }


    public function rules() {
        return array(
            array('exercise_id', 'required'),
            array('description', 'length', 'min' => 3),
            array('exercise_id', 'numerical', 'integerOnly' => true),
            array('exercise_id', 'exist', 'className' => 'Exercise', 'attributeName' => 'id'),
        );
    }

    public function tableName() {
        return 'program_day_exercise';
    }

    public function relations() {
        return array(
            'exerciseRel' => array(self::HAS_ONE, 'Exercise', array('id' => 'exercise_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'exercise_id' => 'Упражнение',
            'description' => 'Текст описания',
        );
    }

    public function beforeSave() {

        return parent::beforeSave();
    }




}