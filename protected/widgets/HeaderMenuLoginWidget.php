<?php

class HeaderMenuLoginWidget extends CWidget {

    public function init() {}

    public function run() {
        $first_name = Yii::app()->user->getState('first_name');
        $last_name = Yii::app()->user->getState('last_name');
        $nickname = Yii::app()->user->getState('nickname');
        $profile_id = Yii::app()->user->getState('id');

        $name = ($first_name || $last_name) ? ($first_name . ' ' . $last_name) : $nickname;

        $this->render('headerMenuLogin', compact('name', 'profile_id'));
    }


}