<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 12.07.14
 * Time: 14:17
 * Comment: Yep, it's magic
 */


class Book extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const PAGINATION_COUNT_BOOKS = 12;

    private $_imageBookPath;
    private $_downloadUrl;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, description, image_id, category_id', 'required'),
            array('title, description','length', 'min'=> 3),
            array('title','length', 'max'=> 255),
            array('image_id', 'numerical', 'integerOnly' => true),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('category_id', 'exist', 'className' => 'BookCategory', 'attributeName' => 'id'),
            array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_INACTIVE)),
            array('file', 'safe')
        );
    }

    public function tableName() {
        return 'book';
    }

    public function relations() {
        return array(
            'image' => array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
            'categoryRel' => array(self::HAS_ONE, 'BookCategory', array('id' => 'category_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Заголовок',
            'image_id' => 'Изображение',
            'description' => 'Описание',
            'status' => 'Статус',
            'category_id' => 'Категория',
            'file' => 'Файл',
        );
    }

    public function statusCondition($status = self::STATUS_ACTIVE) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=> 'status = :status',
            'params' => array(':status' => $status)
        ));
        return $this;
    }

    public function beforeSave() {
        $this->file_hash = ($this->file) ? hash('sha256', $this->file) : null;
        return parent::beforeSave();
    }

    public function behaviors() {
        return array(
            'sluggable' => array(
                'class'=>'ext.behaviors.SluggableBehavior.SluggableBehavior',
                'columns' => array('title'),
                'unique' => true,
                'update' => true,
                'translit' => true
            ),
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
            )
        );
    }

    public function addBookPhoto($imageId, $itemId = null) {
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

    public function deleteByPk($pk,$condition = '',$params = array()){
        $items = self::findAllByPk($pk);
        foreach($items as $key => $oneItem){
            if($oneItem->image_id){
                Yii::app()->image->delete($oneItem->image_id);
            }
        }
        return parent::deleteByPk($pk,$condition,$params);
    }

    public function fetchBooks($categoryId = null) {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.status = :status';
        $criteria->with = array('image', 'categoryRel' => array('together' => true, 'condition' => 'categoryRel.status = :status', 'params' => array(':status' => BookCategory::STATUS_ACTIVE)));
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        if ($categoryId) {
            $criteria->addCondition('category_id = :categoryId');
            $criteria->params = array(':categoryId' => $categoryId) + $criteria->params;
        }

        return new CActiveDataProvider(__CLASS__, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGINATION_COUNT_BOOKS,
                'pageVar' => 'page'
            )
        ));
    }


    public function getDownloadUrl() {
        if ($this->_downloadUrl === null) {
            $this->_downloadUrl = Yii::app()->createUrl('books/download', array('hash' => $this->file_hash));
        }
        return $this->_downloadUrl;
    }

//    public function getDownloadPath() {
//        return Yii::app()->createUrl('books/download', array('hash' => $this->file_hash));
//    }

    public function getImageBookPath() {
        if ($this->_imageBookPath === null) {
            if ($this->image_id) {
                $this->_imageBookPath = '/pub/book/main/photo/140x140/' . $this->image->image_filename;
            } else {
                $this->_imageBookPath = '/images/blank/140x140.gif';
            }
        }
        return $this->_imageBookPath;
    }

    public function fetchDownloadBook($hash) {
        return $this->find(array('with' => array('categoryRel' => array('condition' => 'categoryRel.status = :categoryStatus', 'params' => array(':categoryStatus' => BookCategory::STATUS_ACTIVE))),'condition' => 't.status = :status AND file_hash = :file_hash', 'params' => array(':file_hash' => $hash, ':status' => Book::STATUS_ACTIVE)));
    }

    public function fetchBooksByIds($ids = array()) {
        $criteria = new CDbCriteria();
        $criteria->addInCondition('t.id', $ids);
        $criteria->with = array('image');
        return Book::model()->findAll($criteria);
    }


}