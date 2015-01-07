<?php

class ReviewWidget extends CWidget {

    public $model; // main model
    public $relation; // relation in main model
    public $itemId;
    public $reviewModel;

    public function init() {}

    public function run() {
        $modelReviews = CActiveRecord::model($this->model)->find(array(
            'condition' => 't.id = :itemId',
            'params' => array(':itemId' => $this->itemId),
            'with' => array(
                $this->relation => array(
                    'condition' => $this->relation. '.status = :status AND '.$this->relation.'.type = :type',
                    'params' => array(':status' => 1,':type' => $this->model),
                    'with' => array(
                        'user' => array(
                            'image'
                        )
                    )
                )
            )
        ));
        $reviews = array();
        if ($modelReviews) {
            foreach ($modelReviews->reviewsRel as $key => $review) {
                $reviews[$key] = $review->attributes;
                $reviews[$key]['user'] = $review->user->getAttributes();
                $reviews[$key]['user']['image'] = $review->user->getPathMainImage();
                $reviews[$key]['user']['urlProfile'] = $review->user->getUrlProfile();
            }
        }
        $model = new $this->reviewModel;

        if (($post = Yii::app()->request->getPost($this->reviewModel)) && !Yii::app()->controller->isGuest) {
            $model->attributes = $post;
            $model->user_id = Yii::app()->user->id;
            $model->material_id = $this->itemId;
            if ($model->save()) {
                Yii::app()->controller->refresh(true, '#review-id-' . $model->id);
            }
        }

        $this->render('review', compact('reviews', 'model'));
    }
}