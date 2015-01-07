<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/10/14
 * Time: 6:43 PM
 */
class PhotoController extends AbstractProfileController {
    public function actions()
    {
        return array(
            'upload'=> array(
                'class' => 'application.controllers.profile.actions.UploadAction',
                'systemKey' => 'profile_photo',
                'invokeModel' => 'ProfilePhoto',
                'afterModelMethod' => 'addImage',
                'publicPath' => '/pub/profile_photo/164x164',
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
                'deny', 'actions' => array('add, delete'), 'expression' => 'Yii::app()->user->isGuest', 'message' => 'Вы не авторизированы', 'deniedCallback' => array($this, 'redirectToLogin')
            )
        );
    }

    public function actionIndex($user_id) {
        $this->renderLayout($user_id);
        $this->active = 'photo';

        Yii::app()->clientScript
            ->registerScriptFile('/js/front/fileupload/jquery-ui.min.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/fileupload/jquery.fileupload.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/fileupload/jquery.iframe-transport.js', CClientScript::POS_HEAD)
            ->registerScript('profile_ids','var Variables = {profile_id : '.$user_id.'};', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/profile/photo.js', CClientScript::POS_END);

        $photo = new UploadForm;
        $images = ProfilePhoto::getPhoto($this->profile->id);


        $this->render('profile/photo/list', compact('photo', 'images','user_id'));
    }

    public function actionGallery($user_id) {
        $this->renderLayout($user_id);
        $this->owner = ($user_id == Yii::app()->user->id); //TODO:: та же строчка в AbstractProfileController?
        $this->active = 'photo';

        Yii::app()->clientScript
            ->registerScriptFile('/js/front/owl/owl.carousel.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/owl/profile_photo_inner.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/owl/owl.carousel.css')
            ->registerCssFile('/js/front/owl/owl.theme.css')
            ->registerScriptFile('/js/front/profile/photo.comments.js', CClientScript::POS_HEAD)
            ->registerScript('profile_ids','var Variables = {profile_id : '.$user_id.'};', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/profile/photo.gallery.js', CClientScript::POS_END);;

        $images = ProfilePhoto::getPhoto($this->profile->id);

        $this->render('profile/photo/gallery', compact('images'));
    }

    public function actionDelete($imageId){
        //TODO::check user!!!!
        if($imageId){
            echo json_encode(array('success' => Yii::app()->image->delete($imageId)));
        } else {
            echo json_encode(array('success' => false));
        }
    }

    public function actionComments($image = null){
        $image_id = Yii::app()->request->getPost('image_id');
        if($image)
            $image_id = (int)$image;
        //TODO::check image!!
        $commentWidget = $this->widget('application.widgets.CommentsWidget', array('itemId' => $image_id, 'modelName' => 'PhotoComment'), true);

        echo json_encode(array(
            'html' => $commentWidget,
        ));
        Yii::app()->end();
    }


}