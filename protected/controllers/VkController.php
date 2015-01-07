<?php

Yii::setPathOfAlias('vk', Yii::getPathOfAlias('application.extensions.vk'));
Yii::import('vk.classes.VkPhpSdk');
Yii::import('vk.classes.VkApiException');
Yii::import('vk.classes.Oauth2Proxy');


class VkController extends CController {
    
    public function actionVkLogin() {
        $host = $_SERVER['HTTP_HOST'];
        $redirect = Yii::app()->request->getParam('redirect', '/');
        $vkAppId = (isset(Yii::app()->params->vk[$host]['appId'])) ? Yii::app()->params->vk[$host]['appId'] : '';
        $vkAppSecret = (isset(Yii::app()->params->vk[$host]['appSecret'])) ? Yii::app()->params->vk[$host]['appSecret'] : '';
        if ($vkAppId && $vkAppSecret) {
            // Init OAuth 2.0 proxy
            $oauth2Proxy = new Oauth2Proxy(
                $vkAppId, // client id
                $vkAppSecret, // client secret
                'https://oauth.vk.com/access_token', // access token url
                'https://oauth.vk.com/authorize', // dialog uri
                'code', // response type
                Yii::app()->getBaseUrl(true). '/vk-login?redirect=' . urlencode($redirect), // redirect url
                'email, first_name, last_name' // scope
            );

            if ($oauth2Proxy->authorize() === true) {
                $email = $oauth2Proxy->getEmail();
                $vkId = $oauth2Proxy->getUserId();
                if ($email && $vkId) {
                    $vkPhpSdk = new VkPhpSdk();
                    $vkPhpSdk->setAccessToken($oauth2Proxy->getAccessToken());
                    $vkPhpSdk->setUserId($oauth2Proxy->getUserId());
                    $result = $vkPhpSdk->api('getProfiles', array(
                        'uids' => $vkPhpSdk->getUserId(),
                        'fields' => 'uid, sex, first_name, last_name, screen_name, photo_big', //, nickname
                    ));
                    $user = User::model()->find('email= :email OR vk_id= :vk_id', array(':email' => $email, ':vk_id' => $vkId));
                        if (!$user) {
                            $user = new User('vk');
                            $user->vk_id = $vkId;
                            $user->first_name = $this->getProperty('first_name', $result);
                            $user->last_name = $this->getProperty('last_name', $result);
//                            $user->nickname = (Yii::app()->name . '_' . time() . rand(0, 99));
                            $user->nickname = $email;
                            $user->email = $email;
                            $user->status = User::STATUS_ACTIVE;
                            $gender = $this->getProperty('gender', $result);
                            if (in_array($gender, array(1, 2))) {
                                if ($gender === 1) {
                                    $user->gender = User::GENDER_MALE;
                                }
                                if ($gender === 2) {
                                    $user->gender = User::GENDER_FEMALE;
                                }
                            }
                            $user->role_id = UserRole::USER_ROLE_USER;
                            if ($user->save()) {
                                EmailNotificationManager::sendEmailRegisterUser($user);
                            } else {
                                $this->redirect('/');
                            }
                        } else if ($user->email) {
                            $user->vk_id = $vkId;
                            $user->update();
                        }

                        $loginForm = new LoginForm('socialLogin');
                        $loginForm->email = $user->email;
                        if ($loginForm->validate()) {
                            $loginForm->login(null, true);
                            $this->redirect($redirect);
                        }
                }
                
            }
        }
    }

    protected function getProperty($property, $response) {
        return (isset($response['response'][0][$property])) ? $response['response'][0][$property] : '';
    }

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
                'deny', 'actions' => array('vkLogin'), 'expression' => '!Yii::app()->user->isGuest', 'message' => 'Вы уже вошли в систему'
            )
        );
    }


}