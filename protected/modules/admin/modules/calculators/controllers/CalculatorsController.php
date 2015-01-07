<?php

class CalculatorsController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'Calculator',
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
                            'url'      => $this->createUrl('calculators/update')
                        )
                    ),
                    array(
                        'name' => 'image_id',
                        'class' => $this->imageColumnClass,
                        'imageId' => '$data->image_id'
                    ),

                ),
            ),
            'uploadCalculatorImage' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'calculator_main_photo',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/calculator/main/photo/big',
                'publicThumbnailPath' => '/pub/calculator/main/photo/80x80',
                'invokeModel' => 'Calculator',
                'afterModelMethod' => 'addCalculatorPhoto',
                'controllerPath' => '/admin/calculators/calculators/uploadCalculatorImage/'
            ),
            'UploadedSingleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Calculator',
                'multiple' => false,
                'relation' => 'image',
                'controllerPath' => '/admin/calculators/calculators/uploadCalculatorImage/',
                'publicPath' => '/pub/calculator/main/photo/big/',
                'publicThumbnailPath' => '/pub/calculator/main/photo/80x80/',
            ),
        ));

    }

    public function actionUpdate() {
        if(Yii::app()->request->isAjaxRequest && isset($_POST['pk'])) {
            Yii::import('ext.editable.EditableSaver');
            $es = new EditableSaver('Calculator');
            $es->update();
            exit();
        }

        $listUri = preg_replace(array('/\/$/','/\/update.*/','/\/add.*/'),'', Yii::app()->request->getRequestUri()).'/list';

        if ($id = Yii::app()->request->getParam('id')) {
            $model = Calculator::model()->findByPk($id);
            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }
        } else {
            $model = new Calculator();
        }

        if ($postCalculator = Yii::app()->request->getPost('Calculator')) {
            
            $model->attributes = $postCalculator;

            if ($model->validate() && $model->save(false)) {
                $this->redirect($listUri);
            }
        }
        Yii::import( "ext.xupload.models.XUploadForm" );
        $photos = new XUploadForm;

        $this->render('update', compact('model', 'photos', 'listUri'));
    }

    public function actionAdd() {
        $this->actionUpdate();
    }

    public function actionUpload() {
        if(Yii::app()->image->checkUpload('file')) {
            $image_id = Yii::app()->image->save('file','calculator_photo');
            if ($image = Image::model()->findByPk($image_id)) {
                $array = array(
                    'filelink' => '/pub/calculator/photo/500x500/' . $image->image_filename
                );
                echo stripslashes(json_encode($array));
                exit();
            }

        }
    }

}