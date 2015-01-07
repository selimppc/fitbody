<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/11/14
 * Time: 3:42 PM
 */
class GoalSize extends CActiveRecord {
    public $profile_id;
    public $type = 'size';

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('start_size, end_size, end_date, body_part_id', 'required'),
            array('start_size, current_size, end_size', 'match', 'pattern'=>'/^[0-9]+((\.|\,)[0-9]+)?$/'),
            array('body_part_id', 'numerical', 'integerOnly' => true),
            array('body_part_id', 'exist', 'className' => 'BodyPart', 'attributeName' => 'id'),

            array('start_date, end_date', 'date', 'format'=>'d.M.yyyy', 'on' => 'insert'),

            array('end_date', 'date', 'format'=>'d.M.yyyy','on' => 'edit'),
        );
    }

    public function tableName() {
        return 'goal_size';
    }

    public function relations() {
        return array(
            'body_part'=>array(self::HAS_ONE, 'BodyPart', array('id' => 'body_part_id')),
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
        $this->start_size = str_replace(',','.',$this->start_size);
        $this->end_size = str_replace(',','.',$this->end_size);

        if($this->end_date) {
            $date = DateTime::createFromFormat('d.m.Y', $this->end_date);
            if($date) $this->end_date = $date->format('Y-m-d');

            if ($this->isNewRecord) {
                $this->start_date = new CDbExpression('NOW()');
                $this->current_size = $this->start_size;
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
        $criteria->addInCondition('t.id',$array);
        return GoalSize::model()->with('body_part')->findAll($criteria);
    }
}