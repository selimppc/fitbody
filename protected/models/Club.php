<?php

class Club extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const PLACE_NEW = 1;
    const PLACE_NOT_NEW = 0;

    const CLUB_CACHE_TAG = 'clubDestinationCacheTag';

    const PAGINATION_COUNT_CLUBS = 12;

    public $validLink = true;
    public $addresses = array();
    public $properties = array();

    public $radio = 0;
    public $chainObject;
    private $_urlAbout;
    private $_mainImageUrl;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('club, description, short_description, destinations, addresses, image_id', 'required'),
            array('club', 'unique'),
            array('club, description, price_description, short_description, site', 'length', 'min'=> 2),
            array('status, pick, chain_id, price_image_id', 'numerical', 'integerOnly' => true),
            array('price_image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('radio','safe'),
            array('status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_INACTIVE)),
            array('is_new_place', 'in', 'range'=>array(self::PLACE_NEW, self::PLACE_NOT_NEW)),
        );
    }

    public function tableName() {
        return 'club';
    }

    public function relations() {
        return array(
            'image'=>array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
            'destinations'  => array(self::HAS_MANY, 'ClubDestinationLink', array('club_id' => 'id')),
            'destinationsMM'  => array(self::MANY_MANY, 'ClubDestination', 'club_destination_link(club_id, destination_id)'),
            'propertiesRel'  => array(self::HAS_MANY, 'ClubPropertyLink', array('club_id' => 'id')),
            'props'=>array(self::MANY_MANY, 'ClubProperty', 'club_property_link(club_id, club_property_id)'),
            'coaches'=>array(self::MANY_MANY, 'Coach', 'coach_club_link(club_id, club_property_id)'),
            'addressesRel'  => array(self::HAS_MANY, 'ClubAddress', array('club_id' => 'id')),
            'images'=>array(self::MANY_MANY, 'Image', 'club_image(club_id, image_id)'),
            'price'=>array(self::HAS_ONE, 'Image', array('id' => 'price_image_id')),
            'reviewsRel'  => array(self::HAS_MANY, 'ClubReview', array('material_id' => 'id')),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'site' => 'Сайт',
            'club' => 'Клуб',
            'destinations' => 'Категории',
            'addresses' => 'Адреса',
            'short_description' => 'Краткое описание',
            'description' => 'Описание',
            'status' => 'Статус',
            'pick'  => 'Выделить',
            'radio' => 'Сеть',
            'chain_id' => 'Название сети',
            'price_description' => 'Прайс-лист',
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
                'columns' => array('club'),
                'unique' => true,
                'update' => true,
                'translit' => true
            ),
        );
    }

    public function afterValidate () {
        if (!Yii::app()->request->isAjaxRequest) {
            if (is_array($this->destinations) && count($this->destinations)) {
                foreach ($this->destinations as $item) {
                    $clubDestinationLink = new ClubDestinationLink();
                    $clubDestinationLink->destination_id = $item;
                    if (!$clubDestinationLink->validate()) {
                        $this->validLink = false;
                        $this->addError('destinations', 'Одно из выбранных направлений отсутствует');
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

            if (is_array($this->properties) && count($this->properties)) {
                foreach ($this->properties as $item) {
                    if (!$item->validate()) {
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
            ClubDestinationLink::model()->deleteAll('club_id = :club_id', array(':club_id' => $this->id));
            if (is_array($this->destinations) && count($this->destinations)) {
                foreach ($this->destinations as $item) {
                    $clubDestinationLink = new ClubDestinationLink();
                    $clubDestinationLink->destination_id = $item;
                    $clubDestinationLink->club_id = $this->id;
                    $clubDestinationLink->save(false);
                }
            }

            ClubAddress::model()->deleteAll('club_id = :club_id', array(':club_id' => $this->id));
            if (is_array($this->addresses) && count($this->addresses)) {
                foreach ($this->addresses as $item) {
                    $item->club_id = $this->id;
                    $item->save(false);
                }
            }

            ClubPropertyLink::model()->deleteAll('club_id = :club_id', array(':club_id' => $this->id));
            if (is_array($this->properties) && count($this->properties)) {
                foreach ($this->properties as $item) {
                    $item->club_id = $this->id;
                    $item->save(false);
                }
            }

        }
        Club::changeCacheTag();
        return parent::afterSave();
    }

    public static function changeCacheTag() {
        if (Yii::app()->cache)
            Yii::app()->cache->set(self::CLUB_CACHE_TAG, microtime(true), 0);
    }

    public function addClubPrice($imageId, $itemId = null) {
        if ($itemId) {
            if ($item = self::findByPk($itemId)) {
                if($item->price_image_id) {
                    Yii::app()->image->delete($item->price_image_id);
                }
                $item->price_image_id = (int) $imageId;
                $item->update();
            }
        }
    }

    public static function getPickedClubs($destination_id){
        $criteria = new CDbCriteria();
        if($destination_id){
            $criteria->condition = "t.status = :status AND t.pick = :pick AND destinations.destination_id = :destination_id";
            $criteria->params = array(':status' => self::STATUS_ACTIVE, ':pick' => self::STATUS_ACTIVE, ':destination_id' => intval($destination_id));
        } else {
            $criteria->condition = "t.status = :status AND t.pick = :pick";
            $criteria->params = array(':status' => self::STATUS_ACTIVE, ':pick' => self::STATUS_ACTIVE);
        }
        $criteria->order = 't.club';
        $criteria->with = array('images','props','destinations','addressesRel' => array('with' => array('city','phones','worktimes')));

        return Club::model()->findAll($criteria);
    }

    public function getClubs($destination_id, $hidePicked = false){
        $criteria = new CDbCriteria();

        if(intval($destination_id)){
            $criteria->with = array('images','props','destinations' => array('together'=>true, 'condition' => "destinations.destination_id = :destination_id", 'params' => array(':destination_id' => intval($destination_id))),'addressesRel' => array('with' => array('city','phones','worktimes')));
        } else {
            $criteria->with = array('images','props','destinations','addressesRel' => array('with' => array('city','phones','worktimes')));
        }
        $criteria->condition = "t.status = :status".($hidePicked ? ' AND t.pick = :pick' : '');
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        $hidePicked ? ($criteria->params[':pick'] = self::STATUS_INACTIVE) : null;
        $criteria->order = 't.club';

        return new CActiveDataProvider(__CLASS__, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGINATION_COUNT_CLUBS,
                'pageVar' => 'page'
            )
        ));
    }

    public static function getClubsInRange($lat, $lon, $range, $destination_id = false){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status";
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        $criteria->order = 'distance DESC';
        $criteria->with = array(
            'images',
            'props',
            'destinations' => $destination_id ? array('together'=>true, 'condition' => "destinations.destination_id = :destination_id", 'params' => array(':destination_id' => $destination_id)) : array(),
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

        return Club::model()->findAll($criteria);
    }

    public static function getClubBySlug($slug){

        $criteria = new CDbCriteria();
        $criteria->condition = 't.slug = :slug AND t.status = :status';
        $criteria->params    = array(':slug' => $slug, ':status' => self::STATUS_ACTIVE);
        $criteria->with      = array(
            'images',
            'propertiesRel' => array(
                'with' => array('property'),
            ),
            'addressesRel' => array(
                'with' => array(
                    'city',
                    'phones',
                    'worktimes'
                ),
            ),
        );

        return Club::model()->find($criteria);
    }

    public function statusCondition($status = self::STATUS_ACTIVE) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=> 't.status = :status',
            'params' => array(':status' => $status)
        ));
        return $this;
    }

    public function fetchNewPlaces() {
        $criteria = new CDbCriteria();
        $criteria->with = array('image', 'addressesRel', 'destinationsMM' => array('condition' => 'destinationsMM.status = :destinationStatus', 'params' => array(':destinationStatus' => ClubDestination::STATUS_ACTIVE)));
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

    public function getNewPlaceImagePath() {
        if ($this->image) {
            return '/pub/club/main/photo/230x160/' . $this->image->image_filename;
        }
        return '/images/blank/230x160.gif';
    }

    public function getUrlAbout() {
        if ($this->_urlAbout === null) {
            $this->_urlAbout = Yii::app()->createUrl('club/' . $this->slug . '/about');
        }
        return $this->_urlAbout;
    }

    public function getMainImageUrlSearch() {
        if ($this->_mainImageUrl === null) {
            if ($this->image) {
                $this->_mainImageUrl = '/pub/club/main/photo/130x90/' . $this->image->image_filename;
            } else {
                $this->_mainImageUrl = '/images/blank/130x90.gif';
            }
        }
        return $this->_mainImageUrl;
    }

    public function fetchClubsByIds($ids = array()) {
        $criteria = new CDbCriteria();
        $criteria->addInCondition('club_id', $ids);
        $criteria->with = array('image', 'addressesRel' => array('with' => array('phones')));
        return Club::model()->findAll($criteria);
    }



}