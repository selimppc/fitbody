<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/30/14
 * Time: 12:00 PM
 */
class GoalProgress extends CActiveRecord {
    const TYPE_WEIGHT = 1;
    const TYPE_FAT = 2;
    const TYPE_SIZE = 3;
    const TYPE_HEFT = 4;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('type, goal_id, value, date', 'required'),
            array('value', 'match', 'pattern'=>'/^[0-9]+((\.|\,)[0-9]+)?$/'),
            array('goal_id', 'numerical', 'integerOnly' => true),

            array('date', 'date', 'format'=>'d.M.yyyy', 'on' => 'insert'),
            array('date', 'date', 'format'=>'d.M.yyyy','on' => 'edit'),
        );
    }

    public function beforeSave(){
        $this->value = str_replace(',','.',$this->value);
        if ($this->isNewRecord) {
            if($this->date) {
                $date = DateTime::createFromFormat('d.m.Y', $this->date);
                $this->date = $date->format('Y-m-d');
            }
        }
        return parent::beforeSave();
    }

    public function tableName() {
        return 'goal_progress';
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
        );
    }

    public function beforeValidate(){
        return parent::beforeValidate();
    }

    public static function getList($type, $goal_id, $start, $end){
        $return_arr = array();
        $criteria = new CDbCriteria();
        $criteria->condition = "t.type = :type AND t.goal_id = :goal_id";
        $criteria->params = array(':type' => $type, ':goal_id' => $goal_id);
        $criteria->order = "t.date DESC";
        $arr = static::model()->findAll($criteria);
        foreach($arr as $elem){
            if($start > $end){
                if($elem->value < $end)
                    $percentage = 100;
                elseif($elem->value > $start)
                    $percentage = 0;
                else
                    $percentage = ($start - $elem->value)/($start - $end)*100;
            } elseif($start < $end) {
                if($elem->value > $end)
                    $percentage = 100;
                elseif($elem->value < $start)
                    $percentage = 0;
                else
                    $percentage = ($elem->value - $start)/($end - $start)*100;
            } else
                $percentage = 100;
            array_push($return_arr, array(
                'id'      => $elem->id,
                'type'    => $elem->type,
                'goal_id' => $elem->goal_id,
                'value'   => $elem->value,
                'date'    => date('d.m.Y',strtotime($elem->date)),
                'percentage' => $percentage < 0 ? 0 : round($percentage)
            ));
        };
        return $return_arr;
    }

    public static function getLast($type, $goal_id){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.type = :type AND t.goal_id = :goal_id";
        $criteria->params = array(':type' => $type, ':goal_id' => $goal_id);
        $criteria->order = "t.date DESC";
        $criteria->limit = 1;
        return static::model()->find($criteria);
    }
}