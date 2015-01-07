<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/19/14
 * Time: 4:04 PM
 */
class Underground extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title', 'required'),
            array('title', 'length', 'min'=> 2),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'underground';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Название',
            'status' => 'Статус',
        );
    }

    public function beforeSave() {

        return parent::beforeSave();
    }

}