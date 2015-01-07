<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/19/14
 * Time: 5:10 PM
 */
class Chain extends CActiveRecord {


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
        return 'chain';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Название сети',
            'status' => 'Статус',
        );
    }

    public function beforeSave() {

        return parent::beforeSave();
    }

}