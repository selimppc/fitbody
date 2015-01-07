<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 18.07.14
 * Time: 12:00
 * Comment: Yep, it's magic
 */

class OrganizerWidget extends CWidget {

    public function init() {}

    public function run(){
        $isGuest = Yii::app()->controller->isGuest;
        $this->render('organizer', compact('isGuest'));
    }
}