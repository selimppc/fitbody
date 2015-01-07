<?php

class HeaderMenuNotLoginWidget extends CWidget {

    public function init() {}

    public function run() {
        $active = false;

        if (Yii::app()->user->hasFlash('activeLoginTarget') && Yii::app()->user->getFlash('activeLoginTarget', null, false) === LoginForm::TARGET_MENU) {
            $active = true;
        }

        $this->render('headerMenuNotLogin', compact('active'));
    }


}