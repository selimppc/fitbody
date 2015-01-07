<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/25/14
 * Time: 10:54 AM
 */
class ShopChain extends CActiveRecord {


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
        return 'shop_chain';
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