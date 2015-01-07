<?php

class EmailNotificationManager {

    public static function sendEmailRegisterUser($event) {
        $user = $event->sender;
        self::sendEmail(array('name' => $user->nickname, 'hash' => $user->hash), 'RegisterNewUser', Yii::app()->name.' Подтверждение регистрации.', $user->email);

    }

    public static function sendEmailForgotPassword($email) {
        if ($user = User::model()->find('email = :email AND status = :status', array(':email' => $email, ':status' => User::STATUS_ACTIVE))) {
            $hash = User::generateHash($email);
            $user->hash = $hash;
            $user->hash_created_at = User::generateTimeExpiration();
            $user->update();
            $baseUrl = Yii::app()->getBaseUrl(true);
            $url = $baseUrl .Yii::app()->createUrl('authorization/loginByHash', array('hash' => $user->hash, 'redirect' => urlencode($baseUrl . Yii::app()->createUrl('profile/settings/index'))));
            self::sendEmail(array('url' => $url, 'baseUrl' => $baseUrl), 'ForgotPassword', 'Change password', $email);
        }
    }

    public function sendEmail($params, $view, $subject, $emailTo = null){
        Yii::import('ext.yii-mail.YiiMailMessage');
        $message = new YiiMailMessage;
        $message->view = $view;
        $message->subject = $subject;
        $message->setBody($params, 'text/html');
        if ($emailTo != null)
            $message->addTo($emailTo);
        else
            $message->addTo(Yii::app()->user->user->email);
        $message->from = Yii::app()->params['adminEmail'];
        Yii::app()->mail->send($message);
    }

}