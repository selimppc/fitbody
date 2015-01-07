<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/7/14
 * Time: 2:40 PM
 */
class Country extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, image_id', 'required'),
            array('title', 'length', 'min'=> 2),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('status', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'country';
    }

    public function relations() {
        return array(
            'image'=>array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Название',
            'status' => 'Статус',
            'image_id' => 'Изображение',
        );
    }

    public function addCountryIcon($imageId, $itemId = null) {
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

    public function beforeSave() {

        return parent::beforeSave();
    }

}