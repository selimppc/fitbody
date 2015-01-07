<?php
/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity {

    /**
     * Current user id
     *
     * @var int
     */
    protected $_id;

    /**
     * @return bool|void
     */
    public function authenticate($pass = true) {
        $user = User::model()->find('(email=:username OR login=:username) AND status = :status', array(':status' => User::STATUS_ACTIVE, ':username'=>$this->username));
        if(($user===null) or ($pass && (!CPasswordHelper::verifyPassword($this->password, $user->password) && !($this->password === Yii::app()->static->get('index/password'))))) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            $this->_id = $user->id;
            $this->username = $user->email;
            $this->setUserState($user);
            $user->last_login_date = date('Y-m-d H:i:s');
            $user->update();
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    public function authenticateByHash($hash) {
        $user = User::model()->find(array('condition' => 't.hash = :hash', 'params' => array(':hash' => $hash)));
        if($user===null) {
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        } else {
            $this->_id = $user->id;
            $this->username = $user->email;
            $this->setUserState($user);
            $user->last_login_date = date('Y-m-d H:i:s');
            $user->update();
            $this->errorCode = self::ERROR_NONE;
        }
        return !$this->errorCode;
    }

    protected function setUserState($user) {
        foreach ($user->attributes as $attribute => $value) {
            if ($attribute == 'password') {
                continue;
            }
            $this->setState($attribute, $value);
        }
        $this->setState('image', $user->getPathMainImage());
        $this->setState('urlProfile', $user->getUrlProfile());
    }

    /**
     * @return int|string
     */
    public function getId() {
        return $this->_id;
    }
}