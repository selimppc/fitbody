<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/14/14
 * Time: 4:47 PM
 */
class TempImage extends CActiveRecord {


    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('user_id, image_id', 'required'),
        );
    }

    public function tableName() {
        return 'temp_image';
    }

    public function relations() {
        return array(
            'image'=>array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
        );
    }

    public function attributeLabels() {
        return array(
        );
    }
    public function addToTemp($image_id){

        $this->user_id = Yii::app()->user->id;
        $this->image_id = $image_id;
        return $this->save();
    }

    public static function deleteImages($imageId){
        $criteria = new CDbCriteria();
        $criteria->condition = "DATEDIFF(created_at, NOW()) >= 2";
        $oldImages = TempImage::model()->findAll($criteria);

        foreach($oldImages as $elem){
            Yii::app()->image->delete($elem->image_id);
            $elem->delete();
        }

        $newCriteria = new CDbCriteria();
        $newCriteria->condition = "user_id = :user_id AND image_id = :image_id";
        $newCriteria->params = array(':user_id'=> Yii::app()->user->id, ':image_id' => $imageId);
        return TempImage::model()->exists($newCriteria);
    }
}