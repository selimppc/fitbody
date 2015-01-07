<?php

class Shop extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const PLACE_NEW = 1;
    const PLACE_NOT_NEW = 0;

    const PAGINATION_COUNT_SHOPS = 12;

    public $validLink = true;
    public $addresses = array();

    public $radio = 0;
    public $chainObject;
    private $_urlAbout;
    private $_mainImageUrl;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, description, short_description, categories, image_id', 'required'),
            array('title', 'unique'),
            array('title, description, short_description, site', 'length', 'min'=> 2),
            array('status, pick, chain_id, image_id', 'numerical', 'integerOnly' => true),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('radio','safe'),
            array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_INACTIVE)),
            array('is_new_place', 'in', 'range'=>array(self::PLACE_NEW, self::PLACE_NOT_NEW)),
        );
    }

    public function tableName() {
        return 'shop';
    }

    public function relations() {
        return array(
            'image'         => array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
            'categories'    => array(self::HAS_MANY, 'ShopCategoryLink', array('shop_id' => 'id')),
            'addressesRel'  => array(self::HAS_MANY, 'ShopAddress', array('shop_id' => 'id')),
            'images'        => array(self::MANY_MANY, 'Image', 'shop_image(shop_id, image_id)'),
            'reviewsRel'    => array(self::HAS_MANY, 'ShopReview', array('material_id' => 'id')),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'site' => 'Сайт',
            'title' => 'Название',
            'categories' => 'Категории',
            'addresses' => 'Адреса',
            'short_description' => 'Краткое описание',
            'description' => 'Описание',
            'status' => 'Статус',
            'pick'  => 'Выделить',
            'radio' => 'Сеть',
            'chain_id' => 'Название сети',
            'image_id' => 'Главное изображение'
        );
    }

    public function beforeSave() {
        if (!Yii::app()->request->isAjaxRequest) {
            if(intval($this->radio) == 2){
                $this->chainObject->save(false);
                if($this->chainObject->id) $this->chain_id = $this->chainObject->id;
            }
        }

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
        );
    }

    public function afterValidate () {
        if (!Yii::app()->request->isAjaxRequest) {
            if (is_array($this->categories) && count($this->categories)) {
                foreach ($this->categories as $item) {
                    $shopCategoriesLink = new ShopCategoryLink();
                    $shopCategoriesLink->category_id = $item;
                    if (!$shopCategoriesLink->validate()) {
                        $this->validLink = false;
                        $this->addError('categories', 'Одна из выбранных категорий отсутствует');
                        break;
                    }
                }
            }

            if (is_array($this->addresses) && count($this->addresses)) {
                foreach ($this->addresses as $item) {
                    if (!$item->validate() || !$item->valid) {
                        $this->validLink = false;
                    }
                }
            }

            if(intval($this->radio) == 2){
                if (!$this->chainObject->validate()) {
                    $this->validLink = false;
                }
            }

        }
        return parent::afterValidate();
    }

    public function afterSave() {
        if (!Yii::app()->request->isAjaxRequest) {
            ShopCategoryLink::model()->deleteAll('shop_id = :shop_id', array(':shop_id' => $this->id));
            if (is_array($this->categories) && count($this->categories)) {
                foreach ($this->categories as $item) {
                    $shopCategoriesLink = new ShopCategoryLink();
                    $shopCategoriesLink->category_id = $item;
                    $shopCategoriesLink->shop_id = $this->id;
                    $shopCategoriesLink->save(false);
                }
            }
            ShopAddress::model()->deleteAll('shop_id = :shop_id', array(':shop_id' => $this->id));
            if (is_array($this->addresses) && count($this->addresses)) {
                foreach ($this->addresses as $item) {
                    $item->shop_id = $this->id;
                    $item->save(false);
                }
            }
        }
        return parent::afterSave();
    }


    public static function getPickedShops($category_id){
        $criteria = new CDbCriteria();
        if($category_id){
            $criteria->condition = "t.status = :status AND t.pick = :pick AND categories.category_id = :category_id";
            $criteria->params = array(':status' => self::STATUS_ACTIVE, ':pick' => self::STATUS_ACTIVE, ':category_id' => intval($category_id));
        } else {
            $criteria->condition = "t.status = :status AND t.pick = :pick";
            $criteria->params = array(':status' => self::STATUS_ACTIVE, ':pick' => self::STATUS_ACTIVE);
        }
        $criteria->order = 't.title';
        $criteria->with = array('images','categories','addressesRel' => array('with' => array('city','phones','worktimes')));

        return Shop::model()->findAll($criteria);
    }

    public function getShops($category_id){
        $criteria = new CDbCriteria();

        if(intval($category_id)){
            $criteria->condition = "t.status = :status AND t.pick = :pick";
            $criteria->params = array(':status' => self::STATUS_ACTIVE , ':category_id' => $category_id, ':pick' => self::STATUS_INACTIVE);
            $criteria->order = 't.title';
            $criteria->with = array('images','categories' => array('together'=>true, 'condition' => "categories.category_id = :category_id", 'params' => array(':category_id' => intval($category_id))),'addressesRel' => array('with' => array('city','phones','worktimes')));
        } else {
            $criteria->condition = "t.status = :status AND t.pick = :pick";
            $criteria->params = array(':status' => self::STATUS_ACTIVE, ':pick' => self::STATUS_INACTIVE);
            $criteria->order = 't.title';
            $criteria->with = array('images','categories','addressesRel' => array('with' => array('city','phones','worktimes')));
        }

        return new CActiveDataProvider(__CLASS__, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGINATION_COUNT_SHOPS,
                'pageVar' => 'page'
            )
        ));
    }

    public static function getShopsInRange($lat, $lon, $range, $category_id = false){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status";
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        $criteria->order = 'distance DESC';
        $criteria->with = array(
            'images',
            'categories' => $category_id ? array('together'=>true, 'condition' => "categories.category_id = :category_id", 'params' => array(':category_id' => $category_id)) : array(),
            'addressesRel' => array(
                'with' => array(
                    'city',
                    'phones',
                    'worktimes'
                ),
                'select' => array(
                    'addressesRel.*',
                    '(6371*1000*acos(cos(RADIANS(addressesRel.lat))*cos(RADIANS(:lat))*cos(RADIANS(:lon)-RADIANS(addressesRel.lon))+sin(RADIANS(addressesRel.lat))*sin(RADIANS(:lat)))) as distance',
                ),
                'params' => array(':lat' => $lat, ':lon' => $lon, ':range' => $range),
                'condition' => '(6371*1000*acos(cos(RADIANS(addressesRel.lat))*cos(RADIANS(:lat))*cos(RADIANS(:lon)-RADIANS(addressesRel.lon))+sin(RADIANS(addressesRel.lat))*sin(RADIANS(:lat)))) < :range',
            ),
        );

        return Shop::model()->findAll($criteria);
    }

    public static function getShopBySlug($slug){

        $criteria = new CDbCriteria();
        $criteria->condition = 't.slug = :slug AND t.status = :status';
        $criteria->params    = array(':slug' => $slug, ':status' => self::STATUS_ACTIVE);
        $criteria->with      = array(
            'images',
            'addressesRel' => array(
                'with' => array(
                    'city',
                    'phones',
                    'worktimes'
                ),
            ),
        );

        return Shop::model()->find($criteria);
    }

    public function statusCondition($status = self::STATUS_ACTIVE) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=> 'status = :status',
            'params' => array(':status' => $status)
        ));
        return $this;
    }

    public function fetchNewPlaces() {
        $criteria = new CDbCriteria();
        $criteria->with = array('image', 'addressesRel');
        $criteria->condition = 't.is_new_place = :isNewPlace';
        $criteria->params = array(':isNewPlace' => self::PLACE_NEW);
        return $this->statusCondition()->findAll($criteria);
    }

    public function addMainImage($imageId, $itemId = null) {
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

    public function getUrlAbout() {
        if ($this->_urlAbout === null) {
            $this->_urlAbout = Yii::app()->createUrl('shop/' . $this->slug . '/about');
        }
        return $this->_urlAbout;
    }

    public function getMainImageUrlSearch() {
        if ($this->_mainImageUrl === null) {
            if ($this->image) {
                $this->_mainImageUrl = '/pub/shop/main/130x90/' . $this->image->image_filename;
            } else {
                $this->_mainImageUrl = '/images/blank/130x90.gif';
            }
        }
        return $this->_mainImageUrl;
    }

    public function fetchShopsByIds($ids = array()) {
        $criteria = new CDbCriteria();
        $criteria->addInCondition('shop_id', $ids);
        $criteria->with = array('image', 'addressesRel' => array('with' => array('phones')));
        return Shop::model()->findAll($criteria);
    }

    public function getNewPlaceImagePath() {
        if ($this->image) {
            return '/pub/shop/main/230x160/' . $this->image->image_filename;
        }
        return '/images/blank/230x160.gif';
    }

}