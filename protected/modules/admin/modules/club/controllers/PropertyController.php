<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class PropertyController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'ClubProperty',
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
                        'header' => 'Направление',
                        'name' => 'property',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->property',
                        'align' => 'center',
                        'editable' => array(
                            'type' => 'text',
                            'url' => $this->createUrl('property/update'),
                            'placement' => 'right'
                        )
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'name' => 'is_main',
                        'header' => 'Статус',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '($data->is_main) ? "Активный": "Неактивный"',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'select',
                            'source'   => array('0' => 'Неактивный', '1' => 'Активный'),
                            'url'      => $this->createUrl('property/update')
                        )
                    ),
                ),
            ),
            'uploadPropertyImage' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'club_attribute',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/club_attribute/images/big',
                'publicThumbnailPath' => '/pub/club_attribute/images/80x80/',
                'invokeModel' => 'ClubProperty',
                'afterModelMethod' => 'addPropertyPhoto',
                'controllerPath' => '/admin/club/property/uploadPropertyImage/'
            ),
            'UploadedSingleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'ClubProperty',
                'multiple' => false,
                'relation' => 'image',
                'controllerPath' => '/admin/club/property/uploadPropertyImage/',
                'publicPath' => '/pub/club_attribute/images/big/',
                'publicThumbnailPath' => '/pub/club_attribute/images/80x80/',
            ),
        ));

    }

    public function actionAdd() {
        $this->actionUpdate();
    }

    public function actionUpdate() {

        if (Yii::app()->request->isAjaxRequest && isset($_POST['pk'])) {
            Yii::import('ext.editable.EditableSaver');
            $es = new EditableSaver('ClubProperty');
            $es->update();
            Yii::app()->end();
        }
        Yii::import( "ext.xupload.models.XUploadForm" );

        $photos = new XUploadForm;

        if ($id = Yii::app()->request->getParam('id')) {
            $model = ClubProperty::model()->findByPk($id);
//            Debug::print_die($model);
            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }

        } else {
            $model = new ClubProperty();
        }

        $uri =  $this->createUrl('list');

        if ($post = Yii::app()->request->getPost('ClubProperty')) {
            $model->attributes = $post;

            if ($model->save()) {
                $this->redirect($uri);
            }

        }

        $this->render('update', compact('model', 'photos', 'uri'));

    }

}