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
                'modelName' => 'CoachProperty',
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
                        'header' => 'Свойство',
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
                            'url'      => $this->createUrl('property/update')
                        )
                    ),
                ),
            )
        ));

    }

    public function actionAdd() {
        $this->actionUpdate();
    }

    public function actionUpdate() {

        if (Yii::app()->request->isAjaxRequest && isset($_POST['pk'])) {
            Yii::import('ext.editable.EditableSaver');
            $es = new EditableSaver('CoachProperty');
            $es->update();
            Yii::app()->end();
        }
        Yii::import( "ext.xupload.models.XUploadForm" );

        $photos = new XUploadForm;

        if ($id = Yii::app()->request->getParam('id')) {
            $model = CoachProperty::model()->findByPk($id);

            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }

        } else {
            $model = new CoachProperty();
        }

        $uri =  $this->createUrl('list');

        if ($post = Yii::app()->request->getPost('CoachProperty')) {
            $model->attributes = $post;

            if ($model->save()) {
                $this->redirect($uri);
            }

        }

        $this->render('update', compact('model', 'photos', 'uri'));

    }

}