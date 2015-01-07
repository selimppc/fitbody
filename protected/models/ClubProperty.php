<?php

class ClubProperty extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('property', 'required'),
            array('property', 'length', 'min'=> 1),
            array('is_main', 'numerical', 'integerOnly' => true),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
        );
    }

    public function tableName() {
        return 'club_property';
    }

    public function relations() {
        return array(
            'image'=>array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'property' => 'Название',
            'is_main' => 'Статус',
            'image_id' => 'Изображение',
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