<?php

class Banner extends CActiveRecord {

    const STATUS_ACTIVE = 1;
    const STATUS_INACTIVE = 0;

    private $_bannerUrl;
    private $_bannerSourcePath;

    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('title, url, end_at, start_at, position_id, filename, filename_real', 'required', 'on' => 'insert, update'),
            array('filename, filename_real', 'required', 'on' => 'updateFile, update'),
            array('url', 'url'),
            array('title, url', 'length', 'max'=> 255),
            array('filename, filename_real', 'safe', 'on' => 'deleteFiles'),
            array('title', 'length', 'min'=> 1),
            array('status', 'numerical', 'integerOnly' => true)
        );
    }

    public function tableName() {
        return 'banner';
    }

    public function relations() {
        return array(
            'positionRel' => array(self::HAS_ONE, 'BannerPosition', array('id' => 'position_id')),
        );
    }

    public function attributeLabels() {
        return array(
            'title' => 'Название',
            'start_at' => 'Старт',
            'end_at' => 'Заканчивается',
            'image_id' => 'Загрузка Баннера',
            'position_id' => 'Позиция',
            'filename' => 'Файл',
            'position_id' => 'Позиция',
            'status' => 'Статус',
        );
    }


    public function afterFind() {
        if ($this->start_at) {
            $this->start_at = date('Y-m-d', strtotime($this->start_at));
        }
        if ($this->end_at) {
            $this->end_at = date('Y-m-d', strtotime($this->end_at));
        }

        return parent::afterFind();
    }

    public function beforeSave() {
        if ($this->isNewRecord) {
            $this->hash = hash('sha256', $this->title . rand(0, 999999));
        }
        return parent::beforeSave();
    }

    public function fetchBannerByPosition($position) {
        $criteria = new CDbCriteria();
        $criteria->with = array('positionRel');
        $criteria->condition = 'status = :status AND position_id = :positionId AND (NOW() BETWEEN `start_at` AND `end_at`) ';
        $criteria->params = array('positionId' => $position, ':status' => self::STATUS_ACTIVE);
        $criteria->limit = 1;
        $criteria->order = 't.id DESC';
        return $this->find($criteria);
    }

    public function fetchBannerByHash($hash) {
        return $this->find(array('condition' => 'status = :status AND hash = :hash', 'params' => array(':hash' => $hash, ':status' => self::STATUS_ACTIVE)));
    }
    public function getBannerSourcePath() {
        if ($this->_bannerSourcePath === null) {
            $this->_bannerSourcePath = '/pub/banner/' . $this->filename;
        }
        return $this->_bannerSourcePath;
    }

    public function getBannerExtension() {
        return pathinfo($this->filename_real, PATHINFO_EXTENSION);
    }

    public function getBannerUrl() {
        if ($this->_bannerUrl === null) {
            $this->_bannerUrl = Yii::app()->createUrl('banner/' . $this->hash);
        }
        return $this->_bannerUrl;
    }
    public function behaviors(){
        return array(
            'CTimestampBehavior' => array(
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
            )
        );
    }
}