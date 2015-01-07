<?php

class PopupLoginWidget extends CWidget {

    public function init() {}

    public function run() {
        if (Yii::app()->user->hasFlash('activeLoginTarget') && Yii::app()->user->getFlash('activeLoginTarget', null, false) === LoginForm::TARGET_POPUP) {
            Yii::app()->clientScript->registerScript('openPopup', '$(document).trigger("show.loginPopup");');
        }
        $this->render('popupLogin');
    }


}