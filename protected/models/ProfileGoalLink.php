<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/11/14
 * Time: 4:47 PM
 */
class ProfileGoalLink extends CActiveRecord {

    const TYPE_FAT = 'fat';
    const TYPE_SIZE = 'size';
    const TYPE_WEIGHT = 'weight';
    const LIMIT_LAST_ROWS = 4;

    public $goal;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('profile_id, goal_id, type', 'required'),
            array('type', 'in', 'range' => array(self::TYPE_FAT, self::TYPE_SIZE, self::TYPE_WEIGHT)),
            array('profile_id, goal_id', 'numerical', 'on' => 'register'),
            array('profile_id', 'exist', 'className' => 'Profile', 'attributeName' => 'id'),
        );
    }

    public function tableName() {
        return 'profile_goal_link';
    }

    public function relations() {
        return array(
            //'Profile'=>array(self::HAS_ONE, 'Profile', array('id' => 'profile_id')),
            'Profile'=>array(self::BELONGS_TO, 'Profile', array('profile_id' => 'id')),
        );
    }

    public function attributeLabels() {
        return array(
        );
    }

    public static function getLast($profile_id){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.profile_id = :profile_id";
        $criteria->params = array(':profile_id' => $profile_id);
        $criteria->limit = self::LIMIT_LAST_ROWS;
        $criteria->order = "t.id DESC";
        return ProfileGoalLink::model()->findAll($criteria);
    }

    public static function getByTypeGoalId($type, $goal_id){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.type = :type AND t.goal_id = :goal_id";
        $criteria->params = array(':type' => $type, ':goal_id' => $goal_id);
        return static::model()->find($criteria);
    }

    public function afterSave() {
        if($this->hasEventHandler('onAfterAddGoal')) {
            $event = new CModelEvent($this);
            $this->onAfterAddGoal($event);
        }
        return parent::afterSave();
    }

    public function onAfterAddGoal($event) {
        $this->raiseEvent('onAfterAddGoal', $event);
    }

    public function fetchArrayGoalsByIds($array) {
        $goals = $this->fetchGoalsByIds($array);
        $goalsArray = array();
        foreach ($goals as $image) {
            $goalsArray[$image->id] = $image->attributes;
        }
        return $goalsArray;
    }

    public function fetchGoalsByIds($array) {
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $array);
        return $this->findAll($criteria);
    }

}