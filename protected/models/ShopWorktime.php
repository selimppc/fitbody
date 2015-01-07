<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/25/14
 * Time: 11:15 AM
 */
class ShopWorktime extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('from_day, to_day, from_time, to_time', 'required'),
            array('from_day, to_day', 'numerical', 'integerOnly' => true, 'min' => 0, 'max' => 6, 'tooBig' => 'Неправильно задан день', 'tooSmall' => 'Неправильно задан день'),

        );
    }

    public function tableName() {
        return 'shop_worktime';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'Рабочие дни',
            'to_time' => 'Время до которого',
            'from_time' => 'Время с которого',
        );
    }

    public function beforeSave() {
        return parent::beforeSave();
    }

}