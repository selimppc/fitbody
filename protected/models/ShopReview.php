<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/25/14
 * Time: 11:09 AM
 */
class ShopReview extends Review
{
    // совпадает с префиксом подкласса комментария и именем класса сущности
    const TYPE_OF_REVIEW = 'Shop';

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
            'material_id' => 'Магазин',
            'user_id' => 'Пользователь'
        ));
    }

    public static function countReviews($shop_id) {
        return self::model()->count('status = :status AND material_id = :shop_id', array(':status' => Review::STATUS_ACTIVE, ':shop_id' => $shop_id));
    }

    public function afterSave() {
        $reviews = static::model()->findAll(array('condition' => 't.status = :status AND material_id = :shop_id', 'params' => array(':status' => self::STATUS_ACTIVE, ':shop_id' => $this->material_id)));
        $count = static::model()->count(array('condition' => 't.status = :status AND material_id = :shop_id', 'params' => array(':status' => self::STATUS_ACTIVE, ':shop_id' => $this->material_id)));
        $sum = 0;
        foreach ($reviews as $review) {
            $sum += $review->rating;
        }
        $coachRating = ($sum > 0) ? ($sum / $count) : 0;
        Shop::model()->updateByPk($this->material_id, array('rating' => $coachRating, 'count_reviews' => $count));
        return parent::afterSave();
    }
}