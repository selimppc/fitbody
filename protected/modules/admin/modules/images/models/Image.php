<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 16.10.13
 * Time: 15:03
 * To change this template use File | Settings | File Templates.
 */
class Image extends CActiveRecord {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'image';
	}

	public function rules() {
		return array(
			array('alt','required')
		);
	}

	public function relations() {
		return array(
			'image_object'  => array(self::HAS_ONE,'ImageObject',array('id'=>'image_object_id'))
		);
	}

    public function fetchImagesByIds($array) {
        $criteria = new CDbCriteria();
        $criteria->addInCondition('id', $array);
        return $this->findAll($criteria);
    }

    public function fetchArrayImagesByIds($array) {
        $images = $this->fetchImagesByIds($array);
        $imagesArray = array();
        foreach ($images as $image) {
            $imagesArray[$image->id] = $image->attributes;
        }
        return $imagesArray;
    }
}