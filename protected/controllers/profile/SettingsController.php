<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/10/14
 * Time: 1:15 PM
 */
class SettingsController extends AbstractProfileController {
    public function actions()
    {
        return array(
            'upload'=> array(
                'class' => 'application.controllers.profile.actions.UploadAction',
                'systemKey' => 'user_photo',
                'invokeModel' => 'User',
                'afterModelMethod' => 'updatePhoto',
                'publicPath' => '/pub/user_photo/146x146',
                'controllerPath' => '/profile/progress/upload'
            ),
        );
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
                'deny', 'actions' => array('index','changeMainInfo', 'changeProfileInfo', 'changePassword', 'upload'), 'expression' => 'Yii::app()->user->isGuest', 'message' => 'Вы не авторизированы'
            )
        );
    }

    public function actionIndex() {
        $user_id = Yii::app()->user->id;
        if ($post = Yii::app()->request->getPost('DeleteProfile')) {
            if(isset($post['user_id']) && Yii::app()->user->id == $post['user_id'] && User::model()->deleteByPk(Yii::app()->user->id)){
                $this->redirect('/');
                Yii::app()->end();
            }
        }
        $this->renderLayout($user_id);
        $this->active = 'settings';

        $cs = Yii::app()->clientScript
            ->registerScriptFile('/js/front/fileupload/jquery-ui.min.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/ui/datepicker/datepicker.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/ui/datepicker/datepicker_ru.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/ui/datepicker/datepicker.css')
            ->registerScriptFile('/js/front/fileupload/jquery.fileupload.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/fileupload/jquery.iframe-transport.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/ui/datepicker/datepicker.theme.css')
            ->registerCssFile('/js/front/formstyler/jquery.formstyler.css')
            ->registerScriptFile('/js/front/formstyler/jquery.formstyler.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/formstyler/styler_init.js', CClientScript::POS_HEAD)
            ->registerScript('profile_ids','var Variables = {profile_id : '.$this->profile->id.'};', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/profile/settings.js', CClientScript::POS_HEAD);

        $countries = Country::model()->findAll(array('condition' => 'status = :status', 'params' => array(':status' => 1)));

        $uploadModel = new UploadForm;

        $this->render('profile/settings', array(
            'countries' => $countries,
            'uploadModel' => $uploadModel,
        ));
    }

    public function actionChangeMainInfo(){
        $data = Yii::app()->getRequest()->getPost('data');
        $user_id = Yii::app()->user->id;
        $profile_id = Yii::app()->getRequest()->getPost('profile_id');

        $error_flag = false;
        $errors = array();

        $user = User::model()->findByPk($user_id);
        if(!$user){
            echo json_encode(array('status' => false));
            Yii::app()->end();
        }
        $profile = Profile::model()->findByPk($profile_id);
        if(!$profile){
            echo json_encode(array('status' => false));
            Yii::app()->end();
        }

        if($profile->user_id != $user->id){
            echo json_encode(array('status' => false));
            Yii::app()->end();
        }

        $user->attributes = $data;
        $profile->attributes = $data;

        $profile->attachEventHandler('onUpdateProfile', array(new UpdateProfileActivity(), 'addActivity'));
        
        if(!$user->validate()){
            $error_flag = true;
            $errors = array_merge($errors, $user->errors);
        }
        if(!$profile->validate()) {
            $error_flag = true;
            $errors = array_merge($errors, $profile->errors);
        }
        if($error_flag) {
            echo json_encode(array('status' => 'error', 'errors' => $errors));
            Yii::app()->end();
        } else {
            echo json_encode(array('status' => $user->save() && $profile->save()));
            Yii::app()->end();
        }
    }

    public function actionChangeProfileInfo(){
        $data = Yii::app()->getRequest()->getPost('data');
        $user_id = Yii::app()->user->id;
        $profile_id = Yii::app()->getRequest()->getPost('profile_id');

        $user = User::model()->findByPk($user_id);
        if(!$user) {
            echo json_encode(array('status' => false));
            Yii::app()->end();
        }
        $profile = Profile::model()->findByPk($profile_id);
        if(!$profile) {
            echo json_encode(array('status' => false));
            Yii::app()->end();
        }
        if($profile->user_id != $user->id) {
            echo json_encode(array('status' => false));
            Yii::app()->end();
        }
        $profile->attributes = $data;

        $profile->attachEventHandler('onUpdateProfile', array(new UpdateProfileActivity(), 'addActivity'));

        if(!$profile->validate()) {
            echo json_encode(array('status' => 'error', 'errors' => $profile->errors));
            Yii::app()->end();
        } else {
            echo json_encode(array('status' => $profile->save()));
            Yii::app()->end();
        }
    }

    public function actionChangePassword(){
        $password = Yii::app()->getRequest()->getPost('password');
        $user_id = Yii::app()->user->id;
        $profile_id = Yii::app()->getRequest()->getPost('profile_id');

        $user = User::model()->findByPk($user_id);
        if(!$user) {
            echo json_encode(array('status' => false));
            Yii::app()->end();
        }
        $profile = Profile::model()->findByPk($profile_id);
        if(!$profile) {
            echo json_encode(array('status' => false));
            Yii::app()->end();
        }
        if($profile->user_id != $user->id) {
            echo json_encode(array('status' => false));
            Yii::app()->end();
        }
        $user->password = CPasswordHelper::hashPassword($password);
        if(!$user->validate()) {
            echo json_encode(array('status' => 'error', 'errors' => $profile->errors));
            Yii::app()->end();
        } else {
            echo json_encode(array('status' => $user->save()));
            Yii::app()->end();
        }
    }
}