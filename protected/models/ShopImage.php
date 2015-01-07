<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/25/14
 * Time: 10:58 AM
 */
class ShopImage extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('image_id, shop_id', 'required'),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('shop_id', 'exist', 'className' => 'Shop', 'attributeName' => 'id'),
            array('image_id, shop_id', 'numerical', 'integerOnly' => true),
        );
    }

    public function tableName() {
        return 'shop_image';
    }

    public function relations() {
        return array(

        );
    }

    public function attributeLabels() {
        return array(
        );
    }

    public function beforeSave() {
        return parent::beforeSave();
    }

    public function addShopImage($imageId, $itemId = null) {
        if ($itemId) {
            $materialImage = new ShopImage();
            $materialImage->image_id = $imageId;
            $materialImage->shop_id = $itemId;
            $materialImage->save();
        }
    }

}