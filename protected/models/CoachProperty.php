<?php

class CoachProperty extends CActiveRecord {


    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('property', 'required'),
            array('property', 'length', 'max'=> 255),
            array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_INACTIVE)),
        );
    }

    public function tableName() {
        return 'coach_property';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'property' => 'Название',
            'status' => 'Статус',
        );
    }



    public function beforeSave() {

        return parent::beforeSave();
    }

    public function addPropertyPhoto($imageId, $itemId = null) {
        if ($itemId) {
            if ($item = self::findByPk($itemId)) {
                if($item->image_id) {
                    Yii::app()->image->delete($item->image_id);
                }
                $item->image_id = (int) $imageId;
                $item->update();
            }
        }
    }



}