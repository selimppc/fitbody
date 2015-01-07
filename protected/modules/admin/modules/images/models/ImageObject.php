<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 07.10.13
 * Time: 16:12
 * To change this template use File | Settings | File Templates.
 */
class ImageObject extends CActiveRecord {

	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	public function tableName() {
		return 'image_object';
	}

	public function rules() {
		return array(
			array('title,key,path', 'required'),
			array('key','unique')
		);
	}

	public function save($runValidation=true,$attributes=null) {
		if(!parent::validate()) {
			return false;
		}
		Yii::app()->image->addNewObject($this->path);
		return parent::save();
	}

    public function relations() {
        return array(
            'image_size'  => array(self::HAS_MANY,'ImageSize',array('image_object_id'=>'id'))
        );
    }

	public function deleteByPk($pk,$condition='',$params=array()) {
		$images = ImageObject::model()->findAllByPk($pk);
		foreach($images as $Image) {
			$path = DOCUMENT_ROOT.'public_html/pub'.(($Image->path[0]=='/')?$Image->path:'/'.$Image->path);
			Yii::app()->files->deldir($path);
		}
		return parent::deleteByPk($pk,$condition='',$params=array());
	}
}