<?php

class ClubDestination extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    private $_url;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('destination, description', 'required'),
            array('destination', 'unique'),
            array('destination, description', 'length', 'min'=> 1),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'club_destination';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'destination' => 'Название',
            'description' => 'Описание',
            'status' => 'Статус',
        );
    }

    public function behaviors() {
        return array(
            'sluggable' => array(
                'class'=>'ext.behaviors.SluggableBehavior.SluggableBehavior',
                'columns' => array('destination'),
                'unique' => true,
                'update' => true,
                'translit' => true
            ),
        );
    }



    public function beforeSave() {

        return parent::beforeSave();
    }

    public function afterSave() {
        Club::changeCacheTag();
        parent::afterSave();
    }

    public static function getDestinations(){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status";
        $criteria->params = array(":status" => 1);
        return ClubDestination::model()->findAll($criteria);
    }

    public static function getDestinationId($slug){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status AND t.slug = :slug";
        $criteria->params = array(":status" => 1, ":slug" => $slug);
        $arr = ClubDestination::model()->find($criteria);
        return !empty($arr) ? $arr->id : false;
    }

    public function fetchRootDestinations() {
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status";
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        $criteria->order = "t.destination ASC";
        return static::model()->findAll($criteria);
    }

    public function getUrl() {
        if ($this->_url === null) {
            $this->_url = Yii::app()->createUrl('club/list/' . $this->slug);
        }
        return $this->_url;
    }


}