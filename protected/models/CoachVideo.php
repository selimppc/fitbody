<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 22.07.14
 * Time: 11:21
 * Comment: Yep, it's magic
 */

class CoachVideo extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const PAGINATION_COUNT_VIDEO = 2;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, code, coach_id', 'required'),
            array('title', 'unique'),
            array('title, code', 'length', 'max'=> 255),
            array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_INACTIVE)),
        );
    }

    public function tableName() {
        return 'coach_video';
    }

    public function relations() {
        return array(
            'coach' => array(self::HAS_ONE, 'Coach', array('id' => 'coach_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Заголовок',
            'status' => 'Статус',
            'coach_id' => 'Тренер',
        );
    }

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->created_at = new CDbExpression('NOW()');
        }
        return parent::beforeSave();
    }

    public function statusCondition($status = self::STATUS_ACTIVE) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=> 't.status = :status',
            'params' => array(':status' => $status)
        ));
        return $this;
    }

    public function fetchAllCoachVideo($coachId) {
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status AND t.coach_id = :coach_id";
        $criteria->params = array(':status' => CoachNews::STATUS_ACTIVE, ':coach_id' => $coachId);
        return new CActiveDataProvider(__CLASS__, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGINATION_COUNT_VIDEO,
                'pageVar' => 'page'
            )
        ));
    }

}