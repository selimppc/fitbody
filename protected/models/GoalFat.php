<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/11/14
 * Time: 12:48 PM
 */
class GoalFat extends CActiveRecord {
    public $profile_id;
    public $type = 'fat';

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('start_weight, current_weight, end_weight, start_fat, current_fat, end_fat', 'match', 'pattern'=>'/^[0-9]+((\.|\,)[0-9]+)?$/'),

            array('start_weight_date, end_weight_date, start_fat_date, end_fat_date', 'date', 'format'=>'d.M.yyyy', 'on' => 'insert'),

            array('end_weight_date, end_fat_date', 'date', 'format'=>'d.M.yyyy','on' => 'edit'),
        );
    }

    public function tableName() {
        return 'goal_fat';
    }

    public function relations() {
        return array(
        );
    }

    public function attributeLabels() {
        return array(
            'city' => 'Город',
            'status' => 'Статус',
        );
    }

    public function beforeValidate(){
        $first_group_empty =
            (empty($this->start_weight) || intval($this->start_weight) == 0) &&
            (empty($this->end_weight) || intval($this->end_weight) == 0) &&
            (empty($this->end_weight_date) || $this->end_weight_date = '0000-00-00');

        $second_group_empty =
            (empty($this->start_fat) || intval($this->start_fat) == 0) &&
            (empty($this->end_fat) || intval($this->end_fat) == 0) &&
            (empty($this->end_fat_date) || $this->end_fat_date = '0000-00-00');

        if($first_group_empty && $second_group_empty){
            foreach($this->attributes as $k => $v)
                $this->addError($k,'Заполните поле');
        } else {
            if(!$first_group_empty) {
                (empty($this->start_weight) || $this->start_weight == 0) ? $this->addError('start_weight','Заполните поле') : false;
                (empty($this->end_weight) || $this->end_weight == 0) ? $this->addError('end_weight','Заполните поле') : false;
                empty($this->end_weight_date) ? $this->addError('end_weight_date','Заполните поле') : false;
            }
            if(!$second_group_empty) {
                (empty($this->start_fat) || $this->start_fat == 0) ? $this->addError('start_fat','Заполните поле') : false;
                (empty($this->end_fat) || $this->end_fat == 0) ? $this->addError('end_fat','Заполните поле') : false;
                empty($this->end_fat_date) ? $this->addError('end_fat_date','Заполните поле') : false;
            }
        }
        return parent::beforeValidate();
    }

    public function beforeSave() {
        $this->start_weight = str_replace(',','.',$this->start_weight);
        $this->end_weight = str_replace(',','.',$this->end_weight);
        $this->start_fat = str_replace(',','.',$this->start_fat);
        $this->end_fat = str_replace(',','.',$this->end_fat);

        if($this->end_weight_date) {
            $date = DateTime::createFromFormat('d.m.Y', $this->end_weight_date);
            if($date) $this->end_weight_date = $date->format('Y-m-d');

            if ($this->isNewRecord) {
                $this->start_weight_date = new CDbExpression('NOW()');
                $this->current_weight = $this->start_weight;
            }
        }
        if($this->end_fat_date) {
            $date = DateTime::createFromFormat('d.m.Y', $this->end_fat_date);
            if($date) $this->end_fat_date = $date->format('Y-m-d');

            if ($this->isNewRecord) {
                $this->start_fat_date = new CDbExpression('NOW()');
                $this->current_fat = $this->start_fat;
            }
        }
        if($this->scenario == 'edit'){
            if((float)$this->start_weight && !(float)$this->current_weight){
                $this->current_weight = $this->start_weight;
                $this->start_weight_date = new CDbExpression('NOW()');
            }
            if((float)$this->start_fat && !(float)$this->current_fat){
                $this->current_fat = $this->start_fat;
                $this->start_fat_date = new CDbExpression('NOW()');
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
        return GoalFat::model()->findAll($criteria);
    }

}