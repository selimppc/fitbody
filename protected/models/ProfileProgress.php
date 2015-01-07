<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/14/14
 * Time: 11:23 AM
 */
class ProfileProgress extends CActiveRecord {

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, profile_id', 'required'),
            array('now_description, before_description', 'length', 'min'=> 2),
            array('title', 'length', 'min'=> 2, 'max' => 255),
            array('profile_id, now_main, before_main, status, now_image_id, before_image_id', 'numerical', 'integerOnly' => true),
            array('now_date, before_date', 'date', 'format'=>'d.M.yyyy'),
        );
    }

    public function tableName() {
        return 'profile_progress';
    }

    public function relations() {
        return array(
            'before_image' => array(self::HAS_ONE, 'Image', array('id' => 'before_image_id')),
            'now_image' => array(self::HAS_ONE, 'Image', array('id' => 'now_image_id')),
            'profile' => array(self::BELONGS_TO, 'Profile', array('profile_id' => 'id')),
        );
    }

    public function attributeLabels() {
        return array(
        );
    }

    public function beforeValidate(){
        if($this->now_date || $this->now_image_id || $this->now_description || (!$this->now_image_id && !$this->before_image_id)){
            if($this->now_date == ''){
                $this->addError('now_date','Заполните поле даты');
            }
            if(!$this->now_image_id){
                $this->addError('now_image_id','Загрузите фото');
            }
            if($this->now_description  == ''){
                $this->addError('now_description','Заполните поле описания');
            }
        }
        if($this->before_date || $this->before_image_id || $this->before_description || (!$this->now_image_id && !$this->before_image_id)){
            if($this->before_date == ''){
                $this->addError('before_date','Заполните поле даты');
            }
            if(!$this->before_image_id){
                $this->addError('before_image_id','Загрузите фото');
            }
            if($this->before_description  == ''){
                $this->addError('before_description','Заполните поле описания');
            }
        }
        return parent::beforeValidate();
    }

    public function beforeSave() {
        if($this->now_date) {
            $date = DateTime::createFromFormat('d.m.Y', $this->now_date);
            $this->now_date = $date->format('Y-m-d');
        }
        if($this->before_date) {
            $date = DateTime::createFromFormat('d.m.Y', $this->before_date);
            $this->before_date = $date->format('Y-m-d');
        }
        if(!$this->before_image_id){
            $this->before_main = 0;
        }
        if(!$this->now_image_id){
            $this->now_main = 0;
        }
        if((int)$this->before_main){
            ProfileProgress::model()->updateAll(array('before_main' => 0),"profile_id = :profile_id AND before_main = :before_main", array(':profile_id' => $this->profile_id, ':before_main' => 1));
        }
        if((int)$this->now_main){
            ProfileProgress::model()->updateAll(array('now_main' => 0),"profile_id = :profile_id AND now_main = :before_now", array(':profile_id' => $this->profile_id, ':before_now' => 1));
        }
        return parent::beforeSave();
    }

    public function changeDateFormat(){
        if($this->now_date == '0000-00-00'){
            $this->now_date = null;
        }
        if($this->now_date) {
            $this->now_date = date('d.m.Y', strtotime($this->now_date));
        }
        if($this->before_date == '0000-00-00'){
            $this->before_date = null;
        }
        if($this->before_date) {
            $this->before_date = date('d.m.Y', strtotime($this->before_date));
        }
    }

    public function afterSave(){
        if($this->before_image_id){
            TempImage::model()->deleteAllByAttributes(array('user_id' => Yii::app()->user->id, 'image_id' => $this->before_image_id));
        }
        if($this->now_image_id){
            TempImage::model()->deleteAllByAttributes(array('user_id' => Yii::app()->user->id, 'image_id' => $this->now_image_id));
        }

        if($this->hasEventHandler('onAfterAddProgress')) {
            $event = new CModelEvent($this);
            $this->onAfterAddProgress($event);
        }
        return parent::afterSave();
    }

    public function onAfterAddProgress($event) {
        $this->raiseEvent('onAfterAddProgress', $event);
    }

    public static function getAll($profile_id){
        $criteria = new CDbCriteria();
        $criteria->condition = "profile_id = :profile_id";
        $criteria->params = array(':profile_id' => $profile_id);
        $criteria->order = 't.id DESC';
        return ProfileProgress::model()->with(array('before_image','now_image'))->findAll($criteria);
    }

    public static function getAllActive($profile_id){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.profile_id = :profile_id AND t.status = :status";
        $criteria->params = array(':profile_id' => $profile_id, ':status' => 1);
        $criteria->order = 't.id DESC';
        return ProfileProgress::model()->with(array('before_image','now_image'))->findAll($criteria);
    }
}