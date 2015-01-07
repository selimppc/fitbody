<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 16.07.14
 * Time: 11:21
 * Comment: Yep, it's magic
 */

class CoachNews extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    const COUNT_LAST_NEWS = 8;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, short_description, news, coach_id, image_id', 'required'),
            array('title, short_description, news', 'length', 'min'=> 2),
            array('title', 'unique'),
            array('image_id', 'exist', 'className' => 'Image', 'attributeName' => 'id'),
            array('coach_id', 'exist', 'className' => 'Coach', 'attributeName' => 'id'),
            array('status','in','range'=>array(self::STATUS_ACTIVE, self::STATUS_INACTIVE)),
        );
    }

    public function tableName() {
        return 'coach_news';
    }

    public function relations() {
        return array(
            'coach'  => array(self::HAS_ONE, 'Coach', array('id' => 'coach_id')),
            'image'=>array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'id' => 'ID',
            'title' => 'Заголовок',
            'short_description' => 'Краткое описание',
            'news' => 'Новость',
            'image_id' => 'Изображение',
            'coach_id' => 'Тренер',
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

    public function statusCondition($status = self::STATUS_ACTIVE) {
        $this->getDbCriteria()->mergeWith(array(
            'condition'=> 't.status = :status',
            'params' => array(':status' => $status)
        ));
        return $this;
    }

    public function fetchOneNews($slug) {
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status AND t.slug = :slug";
        $criteria->params = array(':status' => CoachNews::STATUS_ACTIVE, ':slug' => $slug);
        return $this->find($criteria);
    }

    public function fetchAllCoachNews($coachId) {
        $criteria = new CDbCriteria();
        $criteria->condition = "t.status = :status AND t.coach_id = :coach_id";
        $criteria->params = array(':status' => CoachNews::STATUS_ACTIVE, ':coach_id' => $coachId);
        return new CActiveDataProvider(__CLASS__, array(
            'criteria' => $criteria,
            // TODO::pagination???
//            'pagination' => array(
//                'pageSize' => self::PAGINATION_COUNT_NEWS,
//                'pageVar' => 'page'
//            )
        ));
    }

    public function getMainImagePath() {
        if ($this->image) {
            return '/pub/coach/news/photo/200x150/' . $this->image->image_filename;
        }
        return '/images/blank/200x150.gif';
    }

    public function getNewsUrl($coachSlug) {
        return Yii::app()->createUrl('coach/' . $coachSlug . '/news/' . $this->slug);
    }


    public function fetchLastNews() {
        $criteria = new CDbCriteria();
        $criteria->condition = 't.status = :status';
        $criteria->params = array(':status' => self::STATUS_ACTIVE);
        $criteria->addInCondition('t.coach_id', Coach::getCoachIdsFromActiveCats());
        $criteria->with = array('coach' => array('condition' => 'coach.status = :statusRelation', 'params' => array(':statusRelation' => self::STATUS_ACTIVE)));
        $criteria->order = 'created_at DESC';
        $criteria->limit = self::COUNT_LAST_NEWS;
        return static::model()->findAll($criteria);
    }

//    public static function getClubNews($id){
//        $criteria = new CDbCriteria();
//        $criteria->condition = "t.status = :status AND t.club_id = :club_id";
//        $criteria->params = array(':status' => 1, ':club_id' => $id);
//        $criteria->with = array('image');
//
//        return ClubNews::model()->findAll($criteria);
//    }
//
//    public static function getArticleBySlug($slug){
//        $criteria = new CDbCriteria();
//        $criteria->condition = "t.status = :status AND t.slug = :slug";
//        $criteria->params = array(':status' => 1, ':slug' => $slug);
//
//        return ClubNews::model()->find($criteria);
//    }


}