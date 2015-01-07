<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 28.07.14
 * Time: 14:44
 * Comment: Yep, it's magic
 */

class AddImageActivity extends AbstractUserActivity {

    const TYPE_OF_ACTIVITY = 'AddImage';
    const TABLE_OF_RELATION = 'Image';

    public static function model($className=__CLASS__) {
        return parent::model($className);
    }

    public function __construct($scenario = 'insert') {
        $this->type_of_activity = self::TYPE_OF_ACTIVITY;
        parent::__construct($scenario);
    }

    public function rules () {
        return array_merge(parent::rules(), array(
            array('source_id', 'exist', 'className' => self::TABLE_OF_RELATION, 'attributeName' => 'id'),
        ));
    }

    public function relations() {
        return array_merge(parent::relations(), array(
            'material' => array(self::BELONGS_TO, self::TABLE_OF_RELATION, 'source_id'),
        ));
    }

    public function attributeLabels() {
        return array_merge(parent::attributeLabels(), array(
            'source_id' => 'Программа',
            'user_id' => 'Пользователь'
        ));
    }

    public function addActivity($event) {
        $image = $event->sender;
        $uploader = new UploadInner();
        $uploader->systemKey = 'activity_photo';
        if ($imageId = $uploader->run()) {
            $this->user_id = $image->profile->user_id;
            $this->source_id = $imageId;
            $this->save();
        }
    }

    public static function getPathImageByFilename($filename) {
        return '/pub/activity/photo/146x146/' . $filename;
    }

    public static function get50x50Path($filename) {
        return '/pub/activity/photo/50x50/' . $filename;
    }

}