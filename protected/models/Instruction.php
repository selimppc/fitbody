<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/16/14
 * Time: 1:09 PM
 */
class Instruction extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('exercise_id, title', 'required'),
            array('title', 'length', 'min'=> 2),
            array('exercise_id', 'numerical', 'integerOnly' => true),
            array('exercise_id', 'exist', 'className' => 'Exercise', 'attributeName' => 'id'),
        );
    }

    public function tableName() {
        return 'instruction';
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Инструкция',
        );
    }

}