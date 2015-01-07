<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class MuscleController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'actions'   => array('edit'),
                'modelName' => 'Muscle',
                'updateUri' => '/admin/exercise/muscle/update.html',
                'addUri' => '/admin/exercise/muscle/add.html',
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
                        'header' => 'Мышца',
                        'name' => 'muscle',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->muscle',
                        'align' => 'center',
                        'editable' => array(
                            'type' => 'text',
                            'url' => $this->createUrl('muscle/update'),
                            'placement' => 'right'
                        )
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'header' => 'Винительный падеж',
                        'name' => 'accusative',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->accusative',
                        'align' => 'center',
                        'editable' => array(
                            'type' => 'text',
                            'url' => $this->createUrl('muscle/update'),
                            'placement' => 'right'
                        )
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'header' => 'Uri',
                        'name' => 'slug',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->slug',
                        'align' => 'center',
                        'editable' => array(
                            'type' => 'text',
                            'url' => $this->createUrl('muscle/update'),
                            'placement' => 'right'
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
                            'source'   => array('0' => 'Неактивный', '1' => 'Активный'),
                            'url'      => $this->createUrl('muscle/update')
                        )
                    ),

                ),
            ),
            'add' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'Muscle'
            ),
            'update' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'Muscle'
            ),
            'uploadMuscleImage' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'muscle_image',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/muscle/image/big',
                'publicThumbnailPath' => '/pub/muscle/image/80x80',
                'invokeModel' => 'Muscle',
                'afterModelMethod' => 'addMusclePhoto',
                'controllerPath' => '/admin/exercise/muscle/uploadMuscleImage/'
            ),
            'UploadedSingleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Muscle',
                'multiple' => false,
                'relation' => 'image',
                'controllerPath' => '/admin/exercise/muscle/uploadMuscleImage/',
                'publicPath' => '/pub/muscle/image/big/',
                'publicThumbnailPath' => '/pub/muscle/image/80x80/',
            ),
        ));

    }

    public function actionUpload() {
        Yii::import('application.modules.admin.modules.images.models.Image');
        if(Yii::app()->image->checkUpload('file')) {
            $image_id = Yii::app()->image->save('file','muscle_image');
            if ($image = Image::model()->findByPk($image_id)) {
                $array = array(
                    'filelink' => '/pub/muscle/image/500x500/' . $image->image_filename
                );
                echo stripslashes(json_encode($array));
                exit();
            }

        }
    }

}