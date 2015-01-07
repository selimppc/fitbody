<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/21/14
 * Time: 3:03 PM
 */
class ProfilePhoto extends CActiveRecord {



    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    public function rules() {
        return array(
            array('profile_id, image_id', 'required'),
        );
    }

    public function tableName() {
        return 'profile_photo';
    }

    public function relations() {
        return array(
            'image'=>array(self::HAS_ONE, 'Image', array('id' => 'image_id')),
            'profile'=>array(self::BELONGS_TO, 'Profile', array('profile_id' => 'id')),
            'comment' => array(self::HAS_MANY, 'PhotoComment', array('material_id' => 'image_id')),
        );
    }

    public function attributeLabels() {
        return array(
        );
    }

    public function addImage($image_id){

        $user_id = Yii::app()->user->id;
        $profile = Profile::model()->find('t.user_id = :user_id',array(':user_id' => $user_id));

        if($profile){
            $this->profile_id = $profile->id;
            $this->image_id = $image_id;

            $this->attachEventHandler('onAfterAddImage', array(new AddImageActivity(), 'addActivity'));
            $event = new CModelEvent($this);
            $this->onAfterAddImage($event);

            return $this->save();
        } else {
            return false;
        }
    }

    public function onAfterAddImage($event) {
        $this->raiseEvent('onAfterAddImage', $event);
    }

    public static function getPhoto($profile_id, $limit = 0){
        $criteria = new CDbCriteria();
        $criteria->condition = "t.profile_id  = :profile_id";
        $criteria->params = array(':profile_id' => $profile_id);
        if($limit) $criteria->limit = $limit;
        $criteria->order = "t.id DESC";
        $criteria->with = array('image','comment' => array(
            'select' => 'comment.type, comment.material_id, count(comment.id) as cnt',
            'condition' => 'comment.type = :type',
            'params' => array(':type' => "Photo"),
            'group' => 'comment.material_id',
            'together' => false
        ));
//        Debug::print_die(ProfilePhoto::model()->findAll($criteria));
        return ProfilePhoto::model()->findAll($criteria);
    }

}