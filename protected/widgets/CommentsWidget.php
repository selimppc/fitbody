<?php

class CommentsWidget extends CWidget {

    public $itemId;
    public $modelName;

    public function init() {}

    public function run() {
        $comments = CActiveRecord::model($this->modelName)->getTreeComments($this->itemId);
        $model = new $this->modelName();
        $clearCommentForm = $this->render('comments/commentForm',  array('model' => $model), true);
        if (($post = Yii::app()->request->getPost($this->modelName)) && Yii::app()->user->id) {
            $model->attributes = $post;
            $model->user_id = Yii::app()->user->id;
            $model->material_id = $this->itemId;
            if ($model->save()) {
                $this->controller->refresh(true, '#comment-id-' . $model->id);
            }
        }
        $commentForm = $this->render('comments/commentForm',  array('model' => $model), true);
        $this->render('comments/comments', compact('comments', 'model', 'commentForm', 'clearCommentForm'));
    }

}