<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/10/14
 * Time: 6:01 PM
 */
class ProgressController extends AbstractProfileController {
    public function actions()
    {
        return array(
            'upload'=> array(
                'class' => 'application.controllers.profile.actions.UploadAction',
                'systemKey' => 'profile_progress',
                'invokeModel' => 'TempImage',
                'afterModelMethod' => 'addToTemp',
                'publicPath' => '/pub/profile_progress/photo/369x369',
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
                'deny', 'actions' => array('add, upload, edit'), 'expression' => 'Yii::app()->user->isGuest', 'message' => 'Вы не авторизированы', 'deniedCallback' => array($this, 'redirectToLogin')
            )
        );
    }

    public function actionIndex($user_id) {
        $this->renderLayout($user_id);
        $this->active = 'progress';

        $progress = ProfileProgress::getAll($this->profile->id);

        $now = null;
        $before = null;
        foreach($progress as $elem){
            if($elem->now_main == 1){
                $now = $elem;
            }
            if($elem->before_main == 1){
                $before = $elem;
            }
        }

        $this->render('profile/progress/list',compact('progress','now','before'));
    }

    public function actionAdd() {
        $user_id = Yii::app()->user->id;
        if(!$user_id){
            throw new CHttpException(404,'You are not authorized.');
        }

        Yii::app()->clientScript
            ->registerScriptFile('/js/front/fileupload/jquery-ui.min.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/ui/datepicker/datepicker.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/ui/datepicker/datepicker_ru.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/ui/datepicker/datepicker.css')
            ->registerScriptFile('/js/front/fileupload/jquery.fileupload.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/fileupload/jquery.iframe-transport.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/profile/progress.add.js', CClientScript::POS_END);

        $this->renderLayout($user_id);
        $this->active = 'progress';
        $url = Yii::app()->createUrl('profile/'.$user_id.'/progress');

        $uploadModel = new UploadForm;
        $model = new ProfileProgress();
        if ($post = Yii::app()->request->getPost('ProfileProgress')) {
            $model->attributes = $post;
            $model->profile_id = $this->profile->id;
            $model->attachEventHandler('onAfterAddProgress', array(new AddProgressActivity(), 'addActivity'));
            if ($model->save()) {
                $this->redirect($url);
            }
        }
        $this->render('profile/progress/edit', compact('model', 'uploadModel'));
    }

    public function actionEdit($progress_id) {
        $user_id = Yii::app()->user->id;
        if(!$user_id){
            throw new CHttpException(404,'You are not authorized.');
        }

        Yii::app()->clientScript
            ->registerScriptFile('/js/front/fileupload/jquery-ui.min.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/ui/datepicker/datepicker.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/ui/datepicker/datepicker_ru.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/ui/datepicker/datepicker.css')
            ->registerScriptFile('/js/front/fileupload/jquery.fileupload.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/fileupload/jquery.iframe-transport.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/profile/progress.add.js', CClientScript::POS_END);

        $this->renderLayout($user_id);
        $this->active = 'progress';
        $url = Yii::app()->createUrl('profile/'.$user_id.'/progress');

        $uploadModel = new UploadForm;

        $model = ProfileProgress::model()->findByPk($progress_id);

        $model->changeDateFormat();//TODO:: если нет $model ---> error
//        Debug::print_die($model);

        if(empty($model)){
            $this->redirect(Yii::app()->createUrl('profile/progress/add'));
        }
        if ($post = Yii::app()->request->getPost('ProfileProgress')) {
            $model->attributes = $post;
            $model->profile_id = $this->profile->id;
            if ($model->save()) {
                $this->redirect($url);
            }
        }

        $this->render('profile/progress/edit', compact('model', 'uploadModel'));
    }
}