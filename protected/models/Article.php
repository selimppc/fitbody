<?php

class Article extends CActiveRecord {

    const ARTICLES_PER_PAGE = 12;

    const INDEX_SLIDER_ARTICLES_COUNT = 4;
    const INDEX_ARTICLES_COUNT = 7;

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const SHOW_TRUE = 1;
    const SHOW_FALSE = 0;

    private $_urlArticle;
    private $_mainImageUrlArticle;
    private $_mainImageThumbnailUrlArticle;
    private $_mainImageListUrlArticle;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, introduction, article, article_subcategory_id, image_id', 'required'),
            array('article','length', 'min'=> 2),
            array('title, introduction','length', 'min'=> 2,'max' => 254),
            array('status, article_subcategory_id, show, pick', 'numerical', 'integerOnly' => true),
            array('article_subcategory_id', 'exist', 'className' => 'ArticleSubcategory', 'attributeName' => 'id'),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('end_at', 'date', 'format'=>'yyyy-MM-dd', 'allowEmpty' => false),
        );
    }

    public function tableName() {
        return 'article';
    }

    public function relations() {
        return array(
            'image'=>array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
	        'category'=>array(self::BELONGS_TO, 'ArticleSubcategory', 'article_subcategory_id'),
            'subcategory' => array(self::BELONGS_TO, 'ArticleSubcategory', 'article_subcategory_id'),
        );
    }

    public function attributeLabels() {
        return array(
            'article' => 'Статья',
            'title' => 'Заголовок',
            'end_at' => 'Отображать до',
            'image_id' => 'Изображение',
            'article_subcategory_id' => 'Категория',
            'introduction' => 'Вступление',
            'status' => 'Статус',
            'show'  => 'Отображать на главной',
            'pick'  => 'Выделить пост',
        );
    }

    public function beforeSave() {
        return parent::beforeSave();
    }

    public  function afterFind () {
        $this->end_at = date('Y-m-d', strtotime($this->end_at));
        return parent::afterFind();
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
                'setUpdateOnCreate' => true,
            )
        );
    }

    public function addArticlePhoto($imageId, $itemId = null) {
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

	public function deleteByPk($pk, $condition = '', $params = array()){
		$items = self::findAllByPk($pk);
		foreach($items as $item){
			if($item->image_id){
				Yii::app()->image->delete($item->image_id);
			}
		}
		return parent::deleteByPk($pk,$condition,$params);
	}

    public function getUrlArticle() {
        if ($this->_urlArticle === null) {
            $this->_urlArticle = Yii::app()->createUrl('article/' . $this->slug);
        }
        return $this->_urlArticle;
    }


    public function getMainImageUrlArticle() {
        if ($this->_mainImageUrlArticle === null) {
            if ($this->image_id) {
                $this->_mainImageUrlArticle = '/pub/article/photo/430x300/' . $this->image->image_filename;
            } else {
                $this->_mainImageUrlArticle = '/images/blank/430x300.gif';
            }
        }
        return $this->_mainImageUrlArticle;
    }

    public function getMainImageThumbnailUrlArticle() {
        if ($this->_mainImageThumbnailUrlArticle === null) {
            if ($this->image_id) {
                $this->_mainImageThumbnailUrlArticle = '/pub/article/photo/195x130/' . $this->image->image_filename;
            } else {
                $this->_mainImageThumbnailUrlArticle = '/images/blank/195x130.gif';
            }
        }
        return $this->_mainImageThumbnailUrlArticle;
    }

    public function getMainImageListUrlArticle() {
        if ($this->_mainImageListUrlArticle === null) {
            if ($this->image_id) {
                $this->_mainImageListUrlArticle = '/pub/article/photo/200x150/' . $this->image->image_filename;
            } else {
                $this->_mainImageListUrlArticle = '/images/blank/200x150.gif';
            }
        }
        return $this->_mainImageListUrlArticle;
    }

    public static function getNews($category_id = null, $subcategory_id = null){
        $criteria = new CDbCriteria();
        $criteria->condition = 'category.status = :status AND subcategory.status = :status AND t.status = :status AND t.end_at > NOW()';
        $criteria->params    = array(':status' => self::STATUS_ACTIVE);
        if($category_id){
            $criteria->addCondition('category.id = :category_id');
            $criteria->params[':category_id'] = $category_id;
        }
        if($subcategory_id){
            $criteria->addCondition('subcategory.id = :subcategory_id');
            $criteria->params[':subcategory_id'] = $subcategory_id;
        }
        $criteria->order     = 't.pick DESC, t.updated_at DESC';
        $criteria->with      = array('image', 'subcategory' => array('with' => 'category'));

        return new CActiveDataProvider(__CLASS__, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::ARTICLES_PER_PAGE,
                'pageVar' => 'page'
            )
        ));
    }

    public static function getOther($category_id, $id, $limit = 4){
        $criteria = new CDbCriteria();
        $criteria->condition = 'subcategory.status = :status AND t.status = :status AND category.id = :category_id AND t.id != :id AND t.end_at > NOW()';
        $criteria->params    = array(':status' => self::STATUS_ACTIVE, ':category_id' => $category_id, ':id' => $id);
        $criteria->order = 't.created_at DESC';
        $criteria->limit = $limit;
        $criteria->with = array('subcategory' => array('with' => 'category'),'image' => array());
        return static::model()->findAll($criteria);
    }

    public static function getArticle($slug){
        $criteria = new CDbCriteria();
        $criteria->condition = 't.slug = :slug AND t.status = :status';
        $criteria->params    = array(':slug' => $slug, ':status' => self::STATUS_ACTIVE);
        return static::model()->with('image', array('subcategory' => array('with' => 'category')))->find($criteria);
    }

    public static function getMostViewed($limit){
        $criteria = new CDbCriteria();
        $criteria->condition = 'category.status = :status AND subcategory.status = :status AND t.status = :status AND (t.created_at BETWEEN ADDDATE(NOW(), interval -7 Day) AND NOW()) AND t.end_at > NOW()';
        $criteria->params    = array(':status' => self::STATUS_ACTIVE);
        $criteria->order     = 't.count DESC';
        $criteria->limit     = $limit;
        $criteria->with      = array('subcategory' => array('with' => 'category'));
        return static::model()->findAll($criteria);
    }

    public static function getIndexNews($show, $limit){
        $criteria = new CDbCriteria();
        $criteria->condition = "subcategory.status = :status AND category.status = :status AND t.status = :status AND t.show = :show AND t.end_at > NOW()";
        $criteria->params = array(':status' => self::STATUS_ACTIVE,':show' => ($show ? self::SHOW_TRUE : self::SHOW_FALSE));
        $criteria->order = "t.created_at DESC";
        $limit ? $criteria->limit = self::INDEX_ARTICLES_COUNT : $criteria->limit = self::INDEX_SLIDER_ARTICLES_COUNT;
        $criteria->with = array('image', 'subcategory' => array('with' => array('category')));
        return static::model()->findAll($criteria);
    }

}