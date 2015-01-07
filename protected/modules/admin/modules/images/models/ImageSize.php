<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 16.10.13
 * Time: 15:41
 * To change this template use File | Settings | File Templates.
 */
class ImageSize extends CActiveRecord {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'image_size';
	}

	public function rules() {
		return array(
			array('title,width,height,type_resize,image_object_id', 'required'),
			array('width,height','numerical','integerOnly'=>true)
		);
	}

	public function relations() {
		return array(
			'image_object'  => array(self::HAS_ONE,'ImageObject',array('id'=>'image_object_id'))
		);
	}

	public function save($runValidation=true,$attributes=null) {
		if(!parent::save())
			return false;
		Yii::app()->image->createNewImageSize($this->id);
		return true;
	}

	public function deleteByPk($pk,$condition='',$params=array()) {
		$imageSizeArray = ImageSize::model()->findAllByPk($pk);
		foreach($imageSizeArray as $ImageSize) {
			$Image = ImageObject::model()->findByPk($ImageSize->image_object_id);
			$path = DOCUMENT_ROOT.'public_html/pub'.(($Image->path[0]=='/')?$Image->path:'/'.$Image->path);
			Yii::app()->files->delDirInPath($path,$ImageSize->width.'x'.$ImageSize->height);
		}
		return parent::deleteByPk($pk,$condition='',$params=array());
	}
}