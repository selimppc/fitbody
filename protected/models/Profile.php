<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/1/14
 * Time: 4:18 PM
 */
class Profile extends CActiveRecord {

    const SHOW = 1;
    const HIDE = 0;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('user_id', 'required'),
            array('user_id', 'exist', 'className' => 'User', 'attributeName' => 'id'),
            array('biceps, forearm, wrist, thigh, buttocks, shin, neck, chest, waist, height, weight, fat', 'numerical', 'integerOnly' => true),
            array('show_profile, show_photo, show_progress, show_program, show_goals, rss_article, rss_exercise, rss_company_news', 'in', 'range' => array(self::SHOW, self::HIDE)),
        );
    }

    public function tableName() {
        return 'profile';
    }

    public function relations() {
        return array(
            'user'=>array(self::HAS_ONE, 'User', array('id' => 'user_id')),
            'goals'  => array(self::HAS_MANY, 'ProfileGoalLink', array('profile_id' => 'id')),
        );
    }

    public function attributeLabels() {
        return array(
            'weight'    => 'Вес',
            'height'    => 'Рост',
            'fat'       => 'Процент жира',
            'biceps'    => 'Бицепс',
            'neck'      => 'Шея',
            'thigh'     => 'Бедро',
            'forearm'   => 'Предплечье',
            'chest'     => 'Грудь',
            'buttocks'  => 'Ягодицы',
            'wrist'     => 'Запястье',
            'waist'     => 'Талия',
            'shin'      => 'Голень',
        );
    }

    public static function getByUserId($user_id){
        $criteria = new CDbCriteria();
        $criteria->condition = 't.user_id = :user_id';
        $criteria->params = array(':user_id' => $user_id);
        return static::model()->find($criteria);
    }

    public function afterSave() {

        if ($this->hasEventHandler('onUpdateProfile')) {
            $event = new CModelEvent($this);
            $this->onUpdateProfile($event);
        }

        return parent::afterSave();
    }

    public function onUpdateProfile($event) {
        $this->raiseEvent('onUpdateProfile', $event);
    }

}