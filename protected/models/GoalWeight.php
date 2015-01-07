<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/11/14
 * Time: 4:26 PM
 */
class GoalWeight extends CActiveRecord {
    public $profile_id;
    public $type = 'weight';

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('weight_start, weight_end, end_date, title', 'required'),
            array('weight_start, weight_current, weight_end', 'match', 'pattern'=>'/^[0-9]+((\.|\,)[0-9]+)?$/'),

            array('start_date, end_date', 'date', 'format'=>'d.M.yyyy','on' => 'insert'),

            array('end_date', 'date', 'format'=>'d.M.yyyy','on' => 'edit'),
        );
    }

    public function tableName() {
        return 'goal_weight';
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

    public function beforeSave() {
        $this->weight_start = str_replace(',','.',$this->weight_start);
        $this->weight_end = str_replace(',','.',$this->weight_end);

        if($this->end_date) {
            $date = DateTime::createFromFormat('d.m.Y', $this->end_date);
            if($date) $this->end_date = $date->format('Y-m-d');

            if ($this->isNewRecord) {
                $this->start_date = new CDbExpression('NOW()');
                $this->weight_current = $this->weight_start;
            }
        }
        return parent::beforeSave();
    }

    public function afterSave(){
        $model = new ProfileGoalLink();
        $model->profile_id = $this->profile_id;
        $model->goal_id = $this->id;
        $model->type = $this->type;
        $model->attachEventHandler('onAfterAddGoal', array(new AddGoalActivity(), 'addActivity'));
        $model->save();
        parent::afterSave();
    }

    public static function getByIds($array){
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id',$array);
        return GoalWeight::model()->findAll($criteria);
    }
}