<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 25.07.14
 * Time: 15:38
 * Comment: Yep, it's magic
 */

abstract class AbstractUserActivity extends CActiveRecord {

    abstract function addActivity($event);

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    protected $type_of_activity = '';
    protected $relationTable = '';

    public function tableName() {
        return 'user_activity';
    }

    protected function instantiate($attributes) {
        $class = $attributes['type'] . 'Activity';
        $model = new $class(null);
        return $model;
    }

    public function rules() {
        return array(
            //array('source_id', 'exist', 'className' => __CLASS__, 'attributeName' => 'id', 'criteria' => array('condition' => 'type = :type', 'params' => array(':type' => $this->type_of_activity))),
        );
    }

    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'user_id'),
        );
    }

    public function type($type) {
        if ($type) {
            $this->getDbCriteria()->mergeWith(array(
                'condition' => 't.type = :type',
                'params' => array(':type' => $type),
            ));
        }
        return $this;
    }

    protected function initType() {
        if (!$this->type)
            $this->type = $this->type_of_activity;
    }

    public function findAllByAttributes($attributes, $condition = '', $params = array()) {
        $this->type($this->type_of_activity);
        return parent::findAllByAttributes($attributes, $condition, $params);
    }

    public function findAll($condition = '', $params=array()) {
        $this->type($this->type_of_activity);
        return parent::findAll($condition, $params);
    }

    public function find($condition = '', $params=array()) {
        $this->type($this->type_of_activity);
        return parent::find($condition, $params);
    }

    public function count($condition = '', $params=array()) {
        $this->type($this->type_of_activity);
        return parent::count($condition, $params);
    }

    public function beforeSave() {
        $this->initType();
        if ($this->isNewRecord) {
            $this->created_at = new CDbExpression('NOW()');
        }
        return parent::beforeSave();
    }

}