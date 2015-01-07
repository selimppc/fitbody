<?php

class ClubAddress extends CActiveRecord {

    public $phonesArr = array();
    public $valid = true;
    public $worktimesArr = array();

    public $distance;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('city_id, address', 'required'),
            array('lat, lon','required','message' => 'Отметьте объект на карте'),
            array('city_id, underground_id', 'numerical', 'integerOnly' => true),
            array('city_id', 'exist', 'className' => 'City', 'attributeName' => 'id'),
            array('underground_id', 'exist', 'className' => 'Underground', 'attributeName' => 'id'),
            array('parking','length','min' => 2),
            array('distance', 'safe'),
        );
    }

    public function tableName() {
        return 'club_address';
    }

    public function relations() {
        return array(
            'phones'  => array(self::HAS_MANY, 'ClubPhone', array('club_address_id' => 'id')),
            'city'=>array(self::HAS_ONE, 'City', array('id' => 'city_id')),
            'worktimes'  => array(self::HAS_MANY, 'ClubWorktime', array('club_address_id' => 'id')),
        );
    }

    public function attributeLabels() {
        return array(
            'city_id' => 'Город',
            'address' => 'Адрес',
            'parking' => 'Парковка',
            'underground_id'=>'Метро',
        );
    }

    public function beforeSave() {

        return parent::beforeSave();
    }

    public function afterValidate() {
        if (!Yii::app()->request->isAjaxRequest) {

            if (is_array($this->phonesArr) && count($this->phonesArr) > 0) {
                foreach ($this->phonesArr as $phone) {
                    if (!$phone->validate()) {
                        $this->valid = false;
                    }
                }
            }

            if (is_array($this->worktimesArr) && count($this->worktimesArr) > 0) {
                foreach ($this->worktimesArr as $worktime) {
                    if (!$worktime->validate()) {
                        $this->valid = false;
                    }
                }
            }
        }
        return parent::afterValidate();
    }

    public function afterSave() {
        if (!Yii::app()->request->isAjaxRequest) {
            if (is_array($this->phonesArr) && count($this->phonesArr) > 0) {
                foreach ($this->phonesArr as $phone) {
                    $phone->club_address_id = $this->id;
                    $phone->save(false);
                }
            }
            if (is_array($this->worktimesArr) && count($this->worktimesArr) > 0) {
                foreach ($this->worktimesArr as $worktime) {
                    $worktime->club_address_id = $this->id;
                    $worktime->save(false);
                }
            }
        }
        return parent::afterSave();
    }
}