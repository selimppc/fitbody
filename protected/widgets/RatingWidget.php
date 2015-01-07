<?php

class RatingWidget extends CWidget {

    public $itemId;
    public $modelName;
    public $field;

    public function init() {}

    public function run() {
        $model = new $this->modelName();
        $voted = false;
        if (!Yii::app()->controller->isGuest) {
            $voted = CActiveRecord::model($this->modelName)->find(array('condition' => $this->field . ' = :itemId AND user_id = :userId', 'params' => array(':userId' => Yii::app()->user->id, ':itemId' => $this->itemId)));
            if (($post = Yii::app()->request->getPost($this->modelName)) && !$voted) {
                $model->attributes = $post;
                $model->{$this->field} = $this->itemId;
                $model->user_id = Yii::app()->user->id;
                if ($model->save()) {
                    $this->controller->refresh();
                }
            }
        }
        $this->render('rating', compact('model', 'voted'));
    }


}