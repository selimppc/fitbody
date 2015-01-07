<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/11/14
 * Time: 11:59 AM
 */
class BodyPart extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, genitive', 'required'),
            array('title, genitive', 'length', 'min'=> 2),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'body_part';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Часть тела',
            'status' => 'Статус',
            'genitive'  => 'Родительный падеж'
        );
    }

    public function beforeSave() {

        return parent::beforeSave();
    }

    public static function getActive(){
        return BodyPart::model()->findAll(array('condition' => 'status = :status', 'params' => array(':status' => 1)));
    }

}