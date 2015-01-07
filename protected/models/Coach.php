<?php

class Coach extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const IS_RECOMMENDED = 1;
    const IS_NOT_RECOMMENDED = 0;

    const PAGINATION_COUNT_COACHES = 12;
    const LIMIT_POSITIONS_COACHES = 3;

    const COACH_CACHE_TAG = 'coachCategoryCacheTag';

    public $validLink = true;
    public $phonesArr = array();
    public $simpleClubsArr = array();
    public $categoryMainVar;
    public $properties = array();
    public $clubs = array();

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('name, short_description, about', 'required'),
            array('name, short_description, about', 'length', 'min'=> 3),
            array('website, skype', 'length', 'max'=> 255),
            array('website', 'url'),
            array('show', 'numerical', 'integerOnly' => true),
            array('is_recommended, status', 'in', 'range'=>array(self::STATUS_ACTIVE, self::STATUS_INACTIVE)),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('categories, clubs, cost', 'safe'),
            array('email', 'email')
        );
    }

    public function tableName() {
        return 'coach';
    }

    public function relations() {
        return array(
            'image'=>array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
            'categoryMain'=>array(self::HAS_ONE, 'CoachCategoryLink', array('coach_id' => 'id'), 'condition' =>'categoryMain.is_main = :is_main', 'params' => array(':is_main' => CoachCategoryLink::MAIN_CATEGORY)),
            'categories'  => array(self::HAS_MANY, 'CoachCategoryLink', array('coach_id' => 'id')),
            'categoriesMM'  => array(self::MANY_MANY, 'CoachCategory', 'coach_category_link(coach_id, category_id)'),
            'phones'  => array(self::HAS_MANY, 'CoachPhone', array('coach_id' => 'id')),
            'propertiesRel'  => array(self::HAS_MANY, 'CoachPropertyLink', array('coach_id' => 'id')),
            'reviewsRel'  => array(self::HAS_MANY, 'CoachReview', array('material_id' => 'id')),
            'images' => array(self::MANY_MANY, 'Image', 'coach_image(coach_id, image_id)'),
            'clubsRel' => array(self::MANY_MANY, 'Club', 'coach_club_link(coach_id, club_id)'),
            'simpleClubs' => array(self::HAS_MANY, 'CoachSimpleClub', array('coach_id' => 'id')),
        );
    }

    public function attributeLabels() {
        return array(
            'name' => 'Имя',
            'about' => 'Информация',
            'categories' => 'Доп. категории',
            'clubs' => 'Клубы',
            'status' => 'Статус',
            'short_description' => 'Краткое описание',
            'image_id' => 'Изображение',
            'website' => 'Веб-сайт',
            'cost' => 'Стоимость',
            'is_recommended' => 'Рекомендуем',
            'position' => 'Позиция',
        );
    }

    public function behaviors() {
        return array(
            'sluggable' => array(
                'class'=>'ext.behaviors.SluggableBehavior.SluggableBehavior',
                'columns' => array('name'),
                'unique' => true,
                'update' => true,
                'translit' => true
            ),
        );
    }

    public function afterValidate () {
        if (!Yii::app()->request->isAjaxRequest) {


            if (is_array($this->phonesArr) && count($this->phonesArr) > 0) {
                foreach ($this->phonesArr as $phone) {
                    if (!$phone->validate()) {
                        $this->validLink = false;
                    }
                }
            }

            if (is_array($this->simpleClubsArr) && count($this->simpleClubsArr) > 0) {
                foreach ($this->simpleClubsArr as $simpleClub) {
                    if (!$simpleClub->validate()) {
                        $this->validLink = false;
                    }
                }
            }


            if (is_array($this->categories) && count($this->categories)) {
                foreach ($this->categories as $key => $item) {
                    if ((int) $item !== (int) $this->categoryMainVar->category_id) {
                        $coachCategoryLink = new CoachCategoryLink();
                        $coachCategoryLink->category_id = $item;
                        if (!$coachCategoryLink->validate()) {
                            $this->validLink = false;
                            $this->addError('categories', 'Одна из выбранных категорий отсутствует');
                            break;
                        }
                    }

                }
            }

            if (is_array($this->clubs) && count($this->clubs)) {
                foreach ($this->clubs as $key => $item) {
                    $coachClubLink = new CoachClubLink();
                    $coachClubLink->club_id = $item;
                    if (!$coachClubLink->validate()) {
                        $this->validLink = false;
                        $this->addError('categories', 'Один из выбранных клубов отсутствует');
                        break;
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

            if (!$this->categoryMainVar->validate()) {
                $this->validLink = false;
                $this->categoryMainVar->addError('category_id', 'Выбранная категория отсутствует');
            }

        }

        return parent::afterValidate();
    }

    public function beforeSave() {
        if($this->isNewRecord) {
            $criteria = new CDbCriteria;
            $criteria->select='max(position) AS position';
            $this->position = ($pos = Coach::model()->find($criteria)) ? $pos['position'] + 1 : 1;
        }
        return parent::beforeSave();
    }

    public function afterSave() {
        if (!Yii::app()->request->isAjaxRequest) {

            CoachPhone::model()->deleteAll('coach_id = :coach_id', array(':coach_id' => $this->id));
            if (is_array($this->phonesArr) && count($this->phonesArr)) {
                foreach ($this->phonesArr as $item) {
                    $item->coach_id = $this->id;
                    $item->save(false);
                }
            }

            CoachSimpleClub::model()->deleteAll('coach_id = :coach_id', array(':coach_id' => $this->id));
            if (is_array($this->simpleClubsArr) && count($this->simpleClubsArr)) {
                foreach ($this->simpleClubsArr as $item) {
                    $item->coach_id = $this->id;
                    $item->save(false);
                }
            }

            CoachCategoryLink::model()->deleteAll('coach_id = :coach_id', array(':coach_id' => $this->id));

            if (is_array($this->categories) && count($this->categories)) {
                foreach ($this->categories as $item) {
                    if ((int) $item !== (int) $this->categoryMainVar->category_id) {
                        $coachCategoryLink = new CoachCategoryLink();
                        $coachCategoryLink->category_id = $item;
                        $coachCategoryLink->coach_id = $this->id;
                        $coachCategoryLink->save();
                    }
                }
            }

            CoachClubLink::model()->deleteAll('coach_id = :coach_id', array(':coach_id' => $this->id));

            if (is_array($this->clubs) && count($this->clubs)) {
                foreach ($this->clubs as $item) {
                    $coachClubLink = new CoachClubLink();
                    $coachClubLink->club_id = $item;
                    $coachClubLink->coach_id = $this->id;
                    $coachClubLink->save(false);
                }
            }


            CoachPropertyLink::model()->deleteAll('coach_id = :coach_id', array(':coach_id' => $this->id));
            if (is_array($this->properties) && count($this->properties)) {
                foreach ($this->properties as $item) {
                    $item->coach_id = $this->id;
                    $item->save(false);
                }
            }

            $this->categoryMainVar->coach_id = $this->id;
            $this->categoryMainVar->save(false);

        }
        Coach::changeCacheTag();
        return parent::afterSave();
    }

    public static function changeCacheTag() {
        if (Yii::app()->cache)
            Yii::app()->cache->set(self::COACH_CACHE_TAG, microtime(true), 0);
    }

    public function addCoachPhoto($imageId, $itemId = null) {
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

    public function fetchCoaches($categoryId = null) {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.status = :status';
        if ($categoryId) {
            $criteria->with = array(
                'image',
                'categoriesMM' => array(
                    'together' => true,
                    'condition' => 'categoriesMM.status = :categoryStatus AND categoriesMM.id = :category_id',
                    'params' => array(':categoryStatus' => CoachCategory::STATUS_ACTIVE, ':category_id' => $categoryId)
                )
            );
        } else {
            $criteria->with = array(
                'image',
                'categoriesMM' => array(
                    'together' => true,
                    'condition' => 'categoriesMM.status = :categoryStatus',
                    'params' => array(':categoryStatus' => CoachCategory::STATUS_ACTIVE)
                )
            );
        }
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        $criteria->order = "t.position";
        $criteria->group = "t.id";

        return new CActiveDataProvider(__CLASS__, array(
            'criteria' => $criteria,
            'pagination' => array(
                'pageSize' => self::PAGINATION_COUNT_COACHES,
                'pageVar' => 'page'
            )
        ));
    }

    public static function getCoachIdsFromActiveCats($limit = null){
        $criteria = new CDbCriteria();
        $criteria->with = array('categoriesMM' => array(
            'together' => true,
            'joinType' => 'INNER JOIN',
            'condition' => 'categoriesMM.status = :categoryStatus',
            'params' => array(':categoryStatus' => CoachCategory::STATUS_ACTIVE)
        ));
        $criteria->condition = 't.status = :status';
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
//        $criteria->order = 't.position ASC';
//        $limit ? ($criteria->limit = $limit) : null;
        $criteria->group = "t.id";

        $array = static::model()->findAll($criteria);
        $ids = array();
        foreach($array as $value){
            array_push($ids, $value->id);
        }
        return $ids;
    }

    public function fetchRecommendedCoaches() {
        $criteria = new CDbCriteria();
        $criteria->with = array('categoriesMM');
        $criteria->addInCondition('t.id', self::getCoachIdsFromActiveCats());
        $criteria->addCondition('t.is_recommended = '.self::IS_RECOMMENDED);
        $criteria->order = 'RAND()';
        $criteria->limit = self::LIMIT_POSITIONS_COACHES;

        return $this->findAll($criteria);
    }


    public function fetchCoach($coachSlug) {
        $criteria = new CDbCriteria();
        $criteria->with = array(
            'clubsRel' => array('together' => false, 'with' => array('destinationsMM' => array('condition' => 'destinationsMM.status = :destinationStatus', 'params' => array(':destinationStatus' => ClubDestination::STATUS_ACTIVE))), 'condition' => 'clubsRel.status = :clubStatus', 'params' => array(":clubStatus" => Club::STATUS_ACTIVE)),
            'images', 'propertiesRel' => array('with' => 'property'),
            'phones',
            'categories' => array('order' => 'is_main DESC', 'together' => true, 'with' => array('category' => array('condition' => 'category.status = :categoryStatus', 'params' => array(':categoryStatus' => CoachCategory::STATUS_ACTIVE)))),
            'simpleClubs'
        );
        $criteria->condition = 't.status = :status AND t.slug = :slug';
        $criteria->params = array(':status' => Coach::STATUS_ACTIVE, ':slug' => $coachSlug);
        return static::model()->find($criteria);
    }

    private $_urlCoachAllNews;
    private $_urlCoachAllReviews;
    private $_urlCoachAllAbout;
    private $_urlCoachAllVideo;

    public function getUrlCoachAllNews() {
        if ($this->_urlCoachAllNews === null) {
            $this->_urlCoachAllNews = Yii::app()->createUrl('coach/news', array('coachSlug' => $this->slug));
        }
        return $this->_urlCoachAllNews;
    }

    public function getUrlCoachAllReviews() {
        if ($this->_urlCoachAllReviews === null) {
            $this->_urlCoachAllReviews = Yii::app()->createUrl('coach/reviews', array('coachSlug' => $this->slug));
        }
        return $this->_urlCoachAllReviews;
    }

    public function getUrlCoachAllAbout() {
        if ($this->_urlCoachAllAbout === null) {
            $this->_urlCoachAllAbout = Yii::app()->createUrl('coach/index', array('coachSlug' => $this->slug));
        }
        return $this->_urlCoachAllAbout;
    }

    public function getUrlCoachAllVideo() {
        if ($this->_urlCoachAllVideo === null) {
            $this->_urlCoachAllVideo = Yii::app()->createUrl('coach/video', array('coachSlug' => $this->slug));
        }
        return $this->_urlCoachAllVideo;
    }

    public function getImageCoachesPath() {
        if ($this->image_id) {
            return '/pub/coach/main/photo/200x150/' . $this->image->image_filename;
        }
        return '/images/blank/200x150.gif';
    }

    public function getImageThumbnailCoachesPath() {
        if ($this->image_id) {
            return '/pub/coach/main/photo/80x80/' . $this->image->image_filename;
        }
        return '/images/blank/80x80.gif';
    }

    public function changePosition($direction, $position) {
        $criteria = new CDbCriteria;
        $criteria->select='max(position) AS position';
        $maxPosition = static::model()->find($criteria);
        $current = static::model()->find('position=:position', array(':position' => $position));
        if ($direction == "up" && $position > 1) {
            $prev = static::model()->find('position=:position', array(':position' => $position - 1));
            $prev->position = $position--;
            $current->position = $position;
            $prev->update();
            $current->update();
            return 'up';
        }
        if ($position < $maxPosition['position'] && $direction == "down") {
            $next = static::model()->find('position=:position', array(':position' => $position + 1));
            $next->position = $position++;
            $current->position = $position;
            $current->update();
            $next->update();
            return 'down';
        }
    }

    public function deleteAndUpdatePosition($ids) {
        //$criteriaUpd = new CDbCriteria;
        //$criteriaUpd->addInCondition('course_city_id', $ids) ;
        //Element::model()->updateAll(array('position' => NULL), $criteriaUpd);
        if (self::model()->deleteByPk($ids)) {
            //set position - 1, 2, 3,..
            $criteria = new CDbCriteria();
            $criteria->order = 'position ASC';
            $coaches = self::model()->findAll($criteria);
            if ($coaches) {
                $i = 1;
                foreach ($coaches as $coach) {
                    $coach->position = $i;
                    $coach->update();
                    $i++;
                }
            }
            return true;
        }
        return false;
    }

}