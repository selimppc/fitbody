<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 25.07.14
 * Time: 15:13
 * Comment: Yep, it's magic
 */

class ActivityUser {

    const TYPE_REGISTER = 'Register';
    const TYPE_ADD_COMMENT = 'AddComment';
    const TYPE_ADD_PROGRESS = 'AddProgress';
    const TYPE_ADD_GOAL = 'AddGoal';
    const TYPE_REACHED_GOAL = 'ReachedGoal';
    const TYPE_ADD_PHOTO = 'AddPhoto';
    const TYPE_UPDATE_PROFILE = 'UpdateProfile';
    const TYPE_ADD_PROGRAM = 'AddProgram';

    public $userId;

    public function __construct($userId) {
        $this->userId = $userId;
    }

    public function register() {

    }





}