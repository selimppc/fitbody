<?php

class ClubNews extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;
    const COUNT_LAST_NEWS = 4;

    private $_url;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, short_description, news, club_id, image_id', 'required'),
            array('title, short_description, news', 'length', 'min'=> 2),
            //array('status', 'numerical', 'integerOnly' => true),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('status','in','range'=>array(self::STATUS_ACTIVE, self::STATUS_INACTIVE)),
        );
    }

    public function tableName() {
        return 'club_news';
    }

    public function relations() {
        return array(
            'club'  => array(self::HAS_ONE, 'Club', array('id' => 'club_id')),
            'image'=>array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Заголовок',
            'short_description' => 'Короткое описание',
            'news' => 'Новость',
            'image_id' => 'Изображение',
            'club_id' => 'Клуб',
            'status' => 'Статус',
        );
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
        );
    }

    public function beforeValidate() {
        Yii::import('application.modules.admin.modules.images.models.Image');
        return parent::beforeValidate();
    }

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->created_at = new CDbExpression('NOW()');
        }
        $this->updated_at = new CDbExpression('NOW()');
        return parent::beforeSave();
    }


    public function addNewsPhoto($imageId, $itemId = null) {
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

    public static function getClubNews($id){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status AND t.club_id = :club_id";
        $criteria->params = array(':status' => 1, ':club_id' => $id);
        $criteria->with = array('image');

        return ClubNews::model()->findAll($criteria);
    }

    public static function getArticleBySlug($slug){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status AND t.slug = :slug";
        $criteria->params = array(':status' => 1, ':slug' => $slug);

        return ClubNews::model()->find($criteria);
    }


    public function getUrl($clubSlug) {
        if ($this->_url === null) {
            $this->_url = Yii::app()->createUrl('club/' . $clubSlug . '/article/' . $this->slug);
        }
        return $this->_url;
    }

    public static function getLastNews() {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.status = :status';
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        $criteria->with = array('club' => array('condition' => 'club.status = :statusRelation', 'params' => array(':statusRelation' => self::STATUS_ACTIVE)));
        $criteria->order = 'created_at DESC';
        $criteria->limit = self::COUNT_LAST_NEWS;
        return static::model()->findAll($criteria);
    }
}