<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/1/14
 * Time: 4:44 PM
 */
class ProfileTabsWidget extends CWidget {
    public $active = false;
    public $profile_id;

    public $show_profile;
    public $show_photo;
    public $show_progress;
    public $show_program;
    public $show_goals;

    public function init() {}

    public function run(){

        $this->render('profileTabs',array(
            'active'     => $this->active,
            'profile_id' => $this->profile_id,
        ));
    }
}