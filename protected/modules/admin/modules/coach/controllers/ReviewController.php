<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class ReviewController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'CoachReview',
                'multiple' => true,
                'actions' => array('update','delete'),
                'columns' => array(
                    array(
                        'name' => 'id',
                        'align' => 'center',
                        'headerHtmlOptions' => array(
                            'width' => 50
                        )
                    ),
                    array(
                        'name' => 'Coach id',
                        'value' => '$data->material_id',
                        'align' => 'center',
                        'headerHtmlOptions' => array(
                            'width' => 50
                        )
                    ),
//                    array(
//                        'class' => 'ext.editable.EditableColumn',
//                        'header' => 'Отзыв',
//                        'name' => 'review',
//                        'headerHtmlOptions' => array(
//                            'width' => 250
//                        ),
//                        'value' => '$data->review',
//                        'align' => 'center',
//                        'editable' => array(
//                            'type' => 'textarea',
//                            'url' => $this->createUrl('review/update'),
//                            'placement' => 'right'
//                        )
//                    ),
                    array(
                        'header' => 'Отзыв',
                        'name' => 'review',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->review',
                        'align' => 'center',

                    ),
                    array(
                        'header' => 'Создан',
                        'name' => 'created_at',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->created_at',
                        'align' => 'center',

                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'name' => 'status',
                        'header' => 'Статус',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '($data->status) ? "Отображен": "Скрыт"',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'select',
                            'source'   => array('0' => 'Скрыт', '1' => 'Отображен'),
                            'url'      => $this->createUrl('review/update')
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
            $es = new EditableSaver('CoachReview');
            $es->update();
            Yii::app()->end();
        }
        Yii::import( "ext.xupload.models.XUploadForm" );

        $photos = new XUploadForm;

        if ($id = Yii::app()->request->getParam('id')) {
            $model = CoachReview::model()->findByPk($id);

            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }

        } else {
            $model = new CoachReview();
        }

        $model->setScenario('adminScenario');
        $coaches = Coach::model()->findAll();
        $coaches = CHtml::listData($coaches, 'id', 'name');

        $users = User::model()->findAll();
        $users = CHtml::listData($users, 'id', function ($user) {
            return ($user->first_name || $user->last_name) ? ($user->first_name . ' ' . $user->last_name) : $user->nickname;
        });

        $uri =  $this->createUrl('list');

        if ($post = Yii::app()->request->getPost('CoachReview')) {
            $model->attributes = $post;
            $model->type = CoachReview::TYPE_OF_REVIEW;
            if ($model->save()) {
                $this->redirect($uri);
            }

        }

        $this->render('update', compact('model', 'coaches', 'users', 'photos', 'uri'));

    }

}