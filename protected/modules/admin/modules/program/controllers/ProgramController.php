<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class ProgramController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'Program',
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
                        'header' => 'Название',
                        'name' => 'title',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->title',
                        'align' => 'center',
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
                            'source'   => array('0' => 'Неактивный', '1' => 'Активный'),
                            'url'      => $this->createUrl('program/update')
                        )
                    ),
                    array(
                        'header' => 'Дата создания',
                        'name' => 'created_at',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->created_at',
                        'align' => 'center',
                    ),
                    array(
                        'header' => 'Дата обновления',
                        'name' => 'updated_at',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->updated_at',
                        'align' => 'center',
                    ),
                    array(
                        'name' => 'image_id',
                        'class' => $this->imageColumnClass,
                        'imageId' => '$data->image_id'
                    ),

                ),
            ),
            'uploadProgramImage' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'program_main_photo',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/program/main/photo/big',
                'publicThumbnailPath' => '/pub/program/main/photo/80x80',
                'invokeModel' => 'Program',
                'afterModelMethod' => 'addProgramPhoto',
                'controllerPath' => '/admin/program/program/uploadProgramImage/'
            ),
            'UploadedSingleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Program',
                'multiple' => false,
                'relation' => 'image',
                'controllerPath' => '/admin/program/program/uploadProgramImage/',
                'publicPath' => '/pub/program/main/photo/big/',
                'publicThumbnailPath' => '/pub/program/main/photo/80x80/',
            ),
        ));

    }

    public function actionUpdate() {
        if(Yii::app()->request->isAjaxRequest && isset($_POST['pk'])) {
            Yii::import('ext.editable.EditableSaver');
            $es = new EditableSaver('Program');
            $es->update();
            exit();
        }

        $listUri = preg_replace(array('/\/$/','/\/update.*/','/\/add.*/'),'', Yii::app()->request->getRequestUri()).'/list';

        if ($id = Yii::app()->request->getParam('id')) {
            $model = Program::model()->findByPk($id);
            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }
            $programDays = ProgramDay::model()->scopeProgram($id)->with('exercisesRel')->findAll();
            foreach ($programDays as $item) {
                // for search by hash week_day in view
                $model->days[$item->day_of_week] = $item;
                $model->days[$item->day_of_week]->exercises = $model->days[$item->day_of_week]->exercisesRel;
            }
        } else {
            $model = new Program();
        }

        if ($postProgram = Yii::app()->request->getPost('Program')) {
            
            $model->attributes = $postProgram;
            $model->days = array();
            if (($postProgramDay = Yii::app()->request->getPost('ProgramDay')) && isset($postProgramDay['daysOfWeek']) && is_array($postProgramDay['daysOfWeek']) && isset($postProgramDay['day']) && is_array($postProgramDay['day'])) {
                foreach ($postProgramDay['daysOfWeek'] as $key => $dayOfWeek) {
                    if (isset($dayOfWeek['day_of_week']) && $dayOfWeek['day_of_week']) {
                        foreach ($postProgramDay['day'] as $keyDay => $item) {
                            if ((int) $key === (int) $keyDay) {
                                $day = new ProgramDay();
                                $day->attributes = $item;
                                $day->day_of_week = $key;
                                if ($postExercise = Yii::app()->request->getPost('ProgramDayExercise')) {
                                    foreach ($postExercise as $dayExercise => $exerciseItem) {
                                        if ((int) $dayExercise === (int) $keyDay) {
                                            foreach ($exerciseItem as $val) {
                                                $dayExercise = new ProgramDayExercise();
                                                $dayExercise->attributes = $val;
                                                $day->exercises[] = $dayExercise;
                                            }
                                        }
                                    }
                                }
                                $model->days[$keyDay] = $day;
                            }
                        }
                        
                    }
                }
            }

            if ($model->validate() && $model->valid && $model->save(false)) {
                $this->redirect($listUri);
            }
        }


        //TODO:: ref
        $counter = 0;
        $day = new ProgramDay();
        $exercise = new ProgramDayExercise();
        $exercises = CHtml::listData(Exercise::model()->findAll(), 'id', 'title');
        $daysOfWeek = ProgramDay::model()->getDaysOfWeek();

        $categories = ProgramCategory::model()->categoriesListData();

        Yii::import( "ext.xupload.models.XUploadForm" );
        $photos = new XUploadForm;

        $this->render('update', compact('model', 'photos', 'categories', 'listUri', 'day', 'exercise', 'exercises', 'counter', 'daysOfWeek'));
    }

    public function actionAdd() {
        $this->actionUpdate();
    }

    public function actionUpload() {
        if(Yii::app()->image->checkUpload('file')) {
            $image_id = Yii::app()->image->save('file','program_photo');
            if ($image = Image::model()->findByPk($image_id)) {
                $array = array(
                    'filelink' => '/pub/program/photo/500x500/' . $image->image_filename
                );
                echo stripslashes(json_encode($array));
                exit();
            }

        }
    }

}