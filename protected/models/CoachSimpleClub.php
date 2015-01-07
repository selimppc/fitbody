<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 9/12/14
 * Time: 12:54 PM
 */
class CoachSimpleClub extends CActiveRecord {


    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title', 'required'),
            array('title', 'length', 'min' => 1, 'max'=> 255),
            array('coach_id', 'exist', 'className' => 'Coach', 'attributeName' => 'id'),
        );
    }

    public function tableName() {
        return 'coach_simple_club';
    }

    public function relations() {
        return array();
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Название',
            'coach_id' => 'ID тренера',
        );
    }

    public function beforeSave() {
        return parent::beforeSave();
    }
}