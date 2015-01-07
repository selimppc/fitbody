<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 6/30/14
 * Time: 1:21 PM
 */
class ReviewController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'ShopReview',
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
                        'name' => 'Shop id',
                        'value' => '$data->material_id',
                        'align' => 'center',
                        'headerHtmlOptions' => array(
                            'width' => 50
                        )
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'header' => 'Отзыв',
                        'name' => 'review',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->review',
                        'align' => 'center',
                        'editable' => array(
                            'type' => 'textarea',
                            'url' => $this->createUrl('review/update'),
                            'placement' => 'right'
                        )
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
                        'value' => '($data->status) ? "Активный": "Неактивный"',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'select',
                            'source'   => array('0' => 'Неактивный', '1' => 'Активный'),
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
            $es = new EditableSaver('ShopReview');
            $es->update();
            Yii::app()->end();
        }
        Yii::import( "ext.xupload.models.XUploadForm" );

        $photos = new XUploadForm;

        if ($id = Yii::app()->request->getParam('id')) {
            $model = ShopReview::model()->findByPk($id);

            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }

        } else {
            $model = new ShopReview();
        }

        $model->setScenario('adminScenario');
        $materials = Shop::model()->findAll();
        $materials = CHtml::listData($materials, 'id', 'title');

        $users = User::model()->findAll();
        $users = CHtml::listData($users, 'id', function ($user) {
            return ($user->first_name || $user->last_name) ? ($user->first_name . ' ' . $user->last_name) : $user->nickname;
        });

        $uri =  $this->createUrl('list');

        if ($post = Yii::app()->request->getPost('ShopReview')) {
            $model->attributes = $post;
            $model->type = ShopReview::TYPE_OF_REVIEW;
            if ($model->save()) {
                $this->redirect($uri);
            }

        }

        $this->render('update', compact('model', 'materials', 'users', 'photos', 'uri'));

    }

}