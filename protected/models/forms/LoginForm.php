<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/6/12
 * Time: 12:32 PM
 * To change this template use File | Settings | File Templates.
 */
class LoginForm extends CFormModel {

    public $email;
    public $password;
    public $rememberMe;
    public $target;

    const TARGET_POPUP = 'popup';
    const TARGET_MENU = 'menu';


    private $_identity;

    /**
     * Declares the validation rules.
     * The rules state that email and password are required,
     * and password needs to be authenticated.
     */
    public function rules() {
        return array(
            //login
            array('email, password', 'required', 'on' => 'login'),
            array('password', 'length', 'min' => 5, 'on' => 'login'),
            array('rememberMe', 'boolean', 'on' => 'login'),
            array('password', 'authenticate', 'on' => 'login'),
            array('email', 'email', 'on' => 'login'),
            array('email, password', 'filter', 'filter' => 'trim', 'on' => 'login'),
            array('email, password', 'filter', 'filter' => 'trim', 'on' => 'login'),
            array('target', 'in', 'range' => array(self::TARGET_MENU, self::TARGET_POPUP)),

            //social login
            array('email', 'email', 'on' => 'socialLogin'),

            array('email', 'email', 'on' => 'activate'),
        );
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels() {
        return array(
            'email' => 'Email',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить меня'
        );
    }

    /**
     * Authenticates the password.
     * This is the 'authenticate' validator as declared in rules().
     */
    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            if (!$this->_identity->authenticate()) {
                $this->addError('email', Yii::t('admin', 'Неправильно введен электронная почта или пароль.'));
            }
        }
    }

    /**
     * Logs in the user using the given email and password in the model.
     * @return boolean whether login is successful
     */
    public function login($hash = null, $withoutPassword = null) {

        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->email, $this->password);
            if ($hash) {
                $this->_identity->authenticateByHash($hash);
            } else {
                $this->_identity->authenticate(!$withoutPassword);
            }
        }

        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            $duration = $this->rememberMe ? 3600 * 24 * 30 : 0; // 30 days
            Yii::app()->user->login($this->_identity, $duration);
            return true;
        } else
            return false;

    }

}
