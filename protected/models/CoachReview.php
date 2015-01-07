<?php

class CoachReview extends Review
{
    // совпадает с префиксом подкласса комментария и именем класса сущности
    const TYPE_OF_REVIEW = 'Coach';

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function __construct($scenario='insert') {
        $this->type_of_review = self::TYPE_OF_REVIEW;
        parent::__construct($scenario);
    }


    public function relations() {
        return array_merge(parent::relations(), array(
            'material' => array(self::BELONGS_TO, self::TYPE_OF_REVIEW, 'material_id'),
        ));
    }


    public function attributeLabels()
    {
        return array_merge(parent::attributeLabels(), array(
           'material_id' => 'Тренер',
           'user_id' => 'Пользователь'
        ));
    }

    public function countReviews($coachId) {
        return self::model()->count('status = :status AND material_id = :coachId', array(':status' => Review::STATUS_ACTIVE, ':coachId' => $coachId));
    }

    public function afterSave() {
        $reviews = static::model()->findAll(array('condition' => 't.status = :status AND material_id = :coachId', 'params' => array(':status' => self::STATUS_ACTIVE, ':coachId' => $this->material_id)));
        $count = static::model()->count(array('condition' => 't.status = :status AND material_id = :coachId', 'params' => array(':status' => self::STATUS_ACTIVE, ':coachId' => $this->material_id)));
        $sum = 0;
        foreach ($reviews as $review) {
            $sum += $review->rating;
        }
        $coachRating = ($sum > 0) ? ($sum / $count) : 0;
        Coach::model()->updateByPk($this->material_id, array('rating' => $coachRating, 'count_reviews' => $count));
        return parent::afterSave();
    }
}