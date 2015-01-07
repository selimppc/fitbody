<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/6/12
 * Time: 11:32 AM
 * To change this template use File | Settings | File Templates.
 * @property boolean $isGuest Whether the current application user is a guest.
 * @property int $role
 */
class WebUser extends CWebUser {

    private $_model = null;

    private $user;

    private function getModel(){
        if (!$this->isGuest && $this->_model === null){
            $this->_model = User::model()->findByPk($this->id);
        }
        return $this->_model;
    }

    public function getRole() {
        $user = User::model()->findByPk($this->id);
        if($user === null)
            return 0;
        if($user->status != User::STATUS_ACTIVE)
            return 0;
        return $user->role_id;
    }

    public function getIsGuest() {
        if(parent::getIsGuest()) {
            return true;
        }
        return User::model()->findByPk($this->id) === null;
    }

    public function getUser()
    {
        if($this->isGuest)
            return null;
        if(!$this->user)
            $this->user = User::model()->findByPk($this->id);
        return $this->user;
    }

}