<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class ExerciseController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'Exercise',
                'updateUri' => '/admin/exercise/exercise/update.html',
                'addUri' => '/admin/exercise/exercise/add.html',
                'multiple' => true,
                'columns' => array(
                    array(
                        'name' => 'id',
                        'align' => 'center',
                        'headerHtmlOptions' => array(
                            'width' => 50
                        )
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'header' => 'Название',
                        'name' => 'title',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->title',
                        'align' => 'center',
                        'editable' => array(
                            'type' => 'text',
                            'url' => $this->createUrl('exercise/update'),
                            'placement' => 'right'
                        )
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'header' => 'Основная группа мышц',
                        'name' => 'muscle_id',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->muscle->muscle',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'select',
                            'source'   => CHtml::listData(Muscle::getAll(),'id','muscle'),
                            'url'      => $this->createUrl('exercise/update'),
                        )
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'name' => 'type',
                        'header' => 'Тип упражнения',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '($data->type == $data::TYPE_TRX) ? "TRX": "С отягощениями"',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'select',
                            'source'   => array(Exercise::TYPE_WITH_WEIGHT => 'С отягощениями', Exercise::TYPE_TRX => 'TRX'),
                            'url'      => $this->createUrl('exercise/update')
                        )
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'name' => 'status',
                        'header' => 'Статус',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '($data->status) ? "Активный": "Неактивный"',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'select',
                            'source'   => array(Exercise::STATUS_INACTIVE => 'Неактивный', Exercise::STATUS_ACTIVE => 'Активный'),
                            'url'      => $this->createUrl('exercise/update')
                        )
                    ),

                ),
            ),
	        'uploadMuscleImages' => array(
		        'class' => 'admin.actions.images.UploadAction',
		        'systemKey' => 'muscle_image',
		        'formClass' => 'ext.xupload.models.XUploadForm',
		        'publicPath' => '/pub/muscle/image/big/',
		        'publicThumbnailPath' => '/pub/muscle/image/80x80/',
		        'controllerPath' => '/admin/exercise/exercise/uploadMuscleImages/'
	        ),
	        'UploadedMuscleImages' => array(
		        'class' => 'admin.actions.images.UploadedImages',
		        'model' => 'Exercise',
		        'controllerPath' => '/admin/exercise/exercise/UploadedMuscleImages',
		        'publicPath' => '/pub/muscle/image/big/',
		        'publicThumbnailPath' => '/pub/muscle/image/80x80/',
	        ),

            'uploadExerciseImages' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'exercise_photo',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/exercise/photo/big/',
                'publicThumbnailPath' => '/pub/exercise/photo/80x80/',
                'invokeModel' => 'ExerciseImage',
                'afterModelMethod' => 'addExerciseImage',
                'controllerPath' => '/admin/exercise/exercise/uploadExerciseImages/'
            ),
            'UploadedMultipleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Exercise',
                'controllerPath' => '/admin/exercise/exercise/uploadExerciseImages',
                'publicPath' => '/pub/exercise/photo/big/',
                'publicThumbnailPath' => '/pub/exercise/photo/80x80/',
            ),

            'uploadExerciseVideos' => array(
                'class' => 'admin.actions.videos.UploadAction',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'invokeModel' => 'ExerciseVideo',
                'publicPath' => '/pub/exercise/video', //without slash
                'afterModelMethod' => 'addExerciseVideo',
                'controllerPath' => '/admin/exercise/exercise/uploadExerciseVideos/'
            ),
            'UploadedMultipleVideos' => array(
                'class' => 'admin.actions.videos.UploadedVideos',
                'model' => 'Exercise',
                'controllerPath' => '/admin/exercise/exercise/uploadExerciseVideos',
                'publicPath' => '/pub/exercise/video', //without slash
            ),
        ));

    }

    public function actionUpload() {
        if(Yii::app()->image->checkUpload('file')) {
            $image_id = Yii::app()->image->save('file','exercise_photo');
            if ($image = Image::model()->findByPk($image_id)) {
                $array = array(
                    'filelink' => '/pub/exercise/photo/500x500/' . $image->image_filename
                );
                echo stripslashes(json_encode($array));
                exit();
            }

        }
    }

    public function actionUpdate() {

        if(Yii::app()->request->isAjaxRequest && isset($_POST['pk'])) {
            Yii::import('ext.editable.EditableSaver');
            $es = new EditableSaver('Exercise');
            $es->update();
            exit();
        }

        Yii::import( "ext.xupload.models.XUploadForm" );

        $photos = new XUploadForm;

        $listUri = preg_replace(array('/\/$/','/\/update.*/','/\/add.*/'),'', Yii::app()->request->getRequestUri()).'/list';


        if ($id = Yii::app()->request->getParam('id')) {
            $model = Exercise::model()->with('images','muscles','instructions')->findByPk($id);
            $model->instructionArray = $model->instructions;
            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }
        } else {
            $model = new Exercise();
        }

        if ($postExercise = Yii::app()->request->getPost('Exercise')) {
            $model->attributes = $postExercise;

            if($postInstruction = Yii::app()->request->getPost('Instruction')){
                $model->instructionArray = array();
                foreach ($postInstruction as $elem) {
                    $newInstruction = new Instruction();
                    $newInstruction->attributes = $elem;
                    $model->instructionArray[] = $newInstruction;
                }
            } else {
                $model->instructionArray = array();
            }
            if ($model->validate() && $model->validInstruction && $model->save(false)) {
                $this->redirect($listUri);
            }
        }

        $dataType = array($model::TYPE_WITH_WEIGHT => 'С отягощением', $model::TYPE_TRX => 'TRX');
        $dataMuscle = Muscle::getAll();

        $this->render('update', compact('model', 'photos', 'listUri','dataType','dataMuscle'));
    }

    public function actionAdd() {
        $this->actionUpdate();
    }

}