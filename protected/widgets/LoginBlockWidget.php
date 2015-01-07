<?php

class LoginBlockWidget extends CWidget {

    public $target;

    public function init() {}

    public function run() {

        $model = new LoginForm('login');

        if (Yii::app()->user->hasFlash('loginErrors')) {
            $model->addErrors(Yii::app()->user->getFlash('loginErrors', null, false));
        }

        if (Yii::app()->user->hasFlash('loginAttributes')) {
            $model->attributes = Yii::app()->user->getFlash('loginAttributes', null, false);
        }

        $facebookUrl = Yii::app()->getBaseUrl(true) .'/facebook-login?redirect='. Yii::app()->request->getUrl();
        $vkUrl = Yii::app()->getBaseUrl(true) .'/vk-login?redirect='. Yii::app()->request->getUrl();
        $target = $this->target;
        $this->render('loginBlock', compact('model', 'facebookUrl', 'vkUrl', 'target'));
    }


}