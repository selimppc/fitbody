<?php

Yii::setPathOfAlias('Facebook', Yii::getPathOfAlias('application.extensions.facebook'));
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\FacebookSDKException; // use in FacebookRedirectLoginHelper
use Facebook\FacebookRedirectLoginHelper;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;

class FacebookController extends CController {

    public function actionFacebookLogin() {
        $redirect = Yii::app()->request->getParam('redirect', '/');
        $host = $_SERVER['HTTP_HOST'];
        $facebookAppId = (isset(Yii::app()->params->facebook[$host]['appId'])) ? Yii::app()->params->facebook[$host]['appId'] : '';
        $facebookAppSecret = (isset(Yii::app()->params->facebook[$host]['appSecret'])) ? Yii::app()->params->facebook[$host]['appSecret'] : '';
        if ($facebookAppId && $facebookAppSecret) {
            FacebookSession::setDefaultApplication($facebookAppId, $facebookAppSecret);
            $helper = new FacebookRedirectLoginHelper((Yii::app()->getBaseUrl(true) . '/facebook-login?redirect=' . urlencode($redirect)));
            $session = null;
            try {
                $session = $helper->getSessionFromRedirect();
            } catch(FacebookRequestException $ex) {
                // When Facebook returns an error
            } catch(\Exception $ex) {
                // When validation fails or other local issues
            }
            if ($session) {
                try {
                    $userProfile = (new FacebookRequest(
                        $session, 'GET', '/me'
                    ))->execute()->getGraphObject(GraphUser::className());
                    
                    if ($userProfile) {
                        $facebookId = $userProfile->getProperty('id');
                        $email = $userProfile->getProperty('email');
                        $user = User::model()->find('email= :email OR facebook_id= :facebook_id', array(':email' => $email, ':facebook_id' => $facebookId));
                        if (!$user) {
                            $user = new User('facebook');
                            $user->facebook_id = $facebookId;
                            $user->first_name = $userProfile->getProperty('first_name');
                            $user->last_name = $userProfile->getProperty('last_name');
                            //$user->nickname = (Yii::app()->name . '_' . time() . rand(0, 99));
                            $user->nickname = $email;
                            $user->email = $email;
                            $user->status = User::STATUS_ACTIVE;
                            $gender = $userProfile->getProperty('gender');
                            if (in_array($gender, array(User::GENDER_MALE, User::GENDER_FEMALE))) {
                                $user->gender = $gender;
                            }
                            $user->role_id = UserRole::USER_ROLE_USER;
                            if ($user->save()) {
                                EmailNotificationManager::sendEmailRegisterUser($user);
                            } else {
                                Yii::app()->end();
                            }
                        } else if ($user->email) {
                            $user->facebook_id = $facebookId;
                            $user->update();
                        }

                        $loginForm = new LoginForm('socialLogin');
                        $loginForm->email = $user->email;
                        if ($loginForm->validate()) {
                            $loginForm->login(null, true);
                            $this->redirect($redirect);
                        }
                    }

                } catch(FacebookRequestException $e) {

                    echo "Exception occured, code: " . $e->getCode();
                    echo " with message: " . $e->getMessage();

                }
            } else {
                $facebookUrl = $helper->getLoginUrl(array('scope' => 'email'));
                $this->redirect($facebookUrl);
            }
        }
    }

//    public function actionFacebookUrl() {
//        $redirect = Yii::app()->request->getParam('redirect', '/');
//        $host = $_SERVER['HTTP_HOST'];
//        $facebookAppId = (isset(Yii::app()->params->facebook[$host]['appId'])) ? Yii::app()->params->facebook[$host]['appId'] : '';
//        $facebookAppSecret = (isset(Yii::app()->params->facebook[$host]['appSecret'])) ? Yii::app()->params->facebook[$host]['appSecret'] : '';
//        if ($facebookAppId && $facebookAppSecret) {
//            FacebookSession::setDefaultApplication($facebookAppId, $facebookAppSecret);
//            $helper = new FacebookRedirectLoginHelper((Yii::app()->getBaseUrl(true) . '/facebook-login?redirect=' . urlencode($redirect)));
//            $facebookUrl = $helper->getLoginUrl(array('scope' => 'email'));
//            $this->redirect($facebookUrl);
//        }
//    }

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'deny', 'actions' => array('logout'), 'expression' => 'Yii::app()->user->isGuest', 'message' => 'Вы не авторизированы'
            ),
            array(
                'deny', 'actions' => array('facebookLogin'), 'expression' => '!Yii::app()->user->isGuest', 'message' => 'Вы уже вошли в систему'
            )
        );
    }
}