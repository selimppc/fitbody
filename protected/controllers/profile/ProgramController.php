<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/10/14
 * Time: 6:19 PM
 */
class ProgramController extends AbstractProfileController {

    public $days = array(
        1 => array('en' => 'monday', 'ru' => 'Понедельник'),
        2 => array('en' => 'tuesday', 'ru' => 'Вторник'),
        3 => array('en' => 'wednesday','ru' => 'Среда'),
        4 => array('en' => 'thursday', 'ru' => 'Четверг'),
        5 => array('en' => 'friday', 'ru' => 'Пятница'),
        6 => array('en' => 'saturday', 'ru' => 'Суббота'),
        7 => array('en' => 'sunday', 'ru' => 'Воскресенье'),
    );

    public function filters()
    {
        return array(
            'accessControl',
        );
    }

    public function accessRules() {
        return array(
            array(
                'deny', 'actions' => array('edit, changeDate, editNote'), 'expression' => 'Yii::app()->user->isGuest', 'message' => 'Вы не авторизированы', 'deniedCallback' => array($this, 'redirectToLogin')
            )
        );
    }


    public function actionIndex($user_id) {
        $this->renderLayout($user_id);
        $this->owner = ($user_id == Yii::app()->user->id);
        $this->active = 'program';

        $program = ProfileProgram::getCurrentProgram($this->profile->id, $this->days);

        $cs = Yii::app()->clientScript
            ->registerScriptFile('/js/front/ui/datepicker/datepicker.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/ui/datepicker/datepicker_ru.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/ui/datepicker/datepicker.css')
            ->registerCssFile('/js/front/ui/datepicker/datepicker.theme.css')
            ->registerScript('profile_ids','var Variables = {profile_id : '.$this->profile->id.', program_id : '.($program ? $program->id : 'undefined').'};', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/profile/program.js', CClientScript::POS_HEAD);


        $days = $this->days;
        $this->render('profile/program/list', compact('program','days'));
    }

    public function actionEdit($program_id = null) {
        $edit = (bool)$program_id;

        $user_id = Yii::app()->user->id;
        if(!$user_id){
            throw new CHttpException(404,'You are not authorized.');
        }
        $this->renderLayout($user_id);
        $this->active = 'program';

        $cs = Yii::app()->clientScript
            ->registerScriptFile('/js/front/ui/datepicker/datepicker.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/ui/datepicker/datepicker_ru.js', CClientScript::POS_HEAD)
            ->registerCssFile('/js/front/ui/datepicker/datepicker.css')
            ->registerCssFile('/js/front/ui/datepicker/datepicker.theme.css')
            ->registerScriptFile('/js/front/popup.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/popup_init.js', CClientScript::POS_HEAD)
            ->registerScriptFile('/js/front/profile/program.add.js', CClientScript::POS_HEAD);

        $url = Yii::app()->createUrl('profile/'.$user_id.'/program');

        $exercise = Exercise::getAll();
        $typeSelect = CHtml::listData(array(array('id' => 1,'title' => 'С отягощениями'), array('id' => '2', 'title' => 'TRX')), 'id', 'title');
        $muscleSelect = CHtml::listData(Muscle::getAll(), 'id', 'accusative');

        if($program_id){
            $model = ProfileProgram::model()->findByPk((int)$program_id);
            if(!$model){
                $model = new ProfileProgram();
                $model->initDefault();
            } else {
                $model->initExisted($this->days);
                $model->changeDates();
            }
        } else {
            $model = new ProfileProgram();
            $model->initDefault();
        }
        $edit ? $model->setScenario('edit') : $model->setScenario('create');
        $model->profile_id = $this->profile->id;

        if ($post = Yii::app()->request->getPost('ProfileProgram')) {
            $model->attributes = $post;
            $model->attachEventHandler('onAfterAddProgram', array(new AddProgramActivity(), 'addActivity'));
            if ($model->save()) {
                $this->redirect($url);
            }
        }

        $this->render('profile/program/edit', compact('muscleSelect', 'typeSelect', 'exercise','model', 'edit'));
    }

    public function actionChangeDate(){
        $profile_id = Yii::app()->getRequest()->getPost('profile_id');
        $date = Yii::app()->getRequest()->getPost('date');
        $profile = Profile::model()->findByPk($profile_id);
        $this->owner = ($profile->user_id == Yii::app()->user->id);


        $program = ProfileProgram::getCurrentProgram($profile_id, $this->days, $date);

        $days = $this->days;
        echo json_encode(array(
            'html' => $this->renderPartial('//content/profile/tpl/program', compact('program','days'), true),
            'program_id' => $program ? $program->id : null
        ));
        Yii::app()->end();
    }

    public function actionEditNote(){
        $success = true;
        $program_id = Yii::app()->getRequest()->getPost('program_id');
        if(!$program_id){
            $success = false;
        } else {
            $program = ProfileProgram::model()->findByPk($program_id);
            if(!$program){
                $success = false;
            } else {
                $profile = Profile::model()->findByPk($program->profile_id);
                if(!$profile){
                    $success = false;
                } else {
                    if($profile->user_id != Yii::app()->user->id){
                        $success = false;
                    } else {
                        $date = Yii::app()->getRequest()->getPost('date');
                        $model = ProfileProgramNote::getEditNote($program_id, $date);
                        $model->program_id = $program_id;
                        $model->meal = Yii::app()->getRequest()->getPost('meal');
                        $model->pharmacology = Yii::app()->getRequest()->getPost('pharmacology');
                        $model->note = Yii::app()->getRequest()->getPost('note');
                        $model->date = $date;
                        if($model->save()){
                            $success = true;
                        } else {
                            $success = false;
                        }
                    }
                }
            }
        }


        echo json_encode(array(
            'success' => $success
        ));
        Yii::app()->end();
    }

}