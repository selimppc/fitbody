<?php

class SocialButtons extends CWidget {

    public $host;
    public $share = false;

    public function init() {
        $this->host = $_SERVER['HTTP_HOST'];
    }

    public function run() {

        $facebookAppId = $this->getFacebookAppId();
        $vkAppId = $this->getVkAppId();
        $twitter = $this->getTwitterName();

        if ($vkAppId) {
            Yii::app()->getClientScript()
                ->registerScriptFile('//vk.com/js/api/openapi.js?115');
        }

        $this->render('socialButtons', array('facebookAppId' => $facebookAppId, 'vkAppId' => $vkAppId, 'twitter' => $twitter, 'share' => $this->share));
    }

    protected function getFacebookAppId() {
        $facebookAppId = (isset(Yii::app()->params->facebook[$this->host]['appId'])) ? Yii::app()->params->facebook[$this->host]['appId'] : '';
        return $facebookAppId;
    }

    protected function getVkAppId() {
        $vkAppId = (isset(Yii::app()->params->vk[$this->host]['appId'])) ? Yii::app()->params->vk[$this->host]['appId'] : '';
        return $vkAppId;
    }
    protected function getTwitterName() {
        $name = (isset(Yii::app()->params->twitter)) ? Yii::app()->params->twitter : '';
        return $name;
    }


}