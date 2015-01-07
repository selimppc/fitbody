<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class BannerController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'Banner',
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
                        'header' => 'Позиция',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->positionRel->position',
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
                            'url'      => $this->createUrl('banner/update')
                        )
                    ),

                ),
            ),
            'uploadBannerFiles' => array(
                'class' => 'admin.actions.banners.UploadAction',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'invokeModel' => 'Banner',
                'publicPath' => '/pub/banner', //without slash
                'controllerPath' => '/admin/banner/banner/uploadBannerFiles/'
            ),
            'UploadedBanner' => array(
                'class' => 'admin.actions.banners.UploadedBanners',
                'model' => 'Banner',
                'controllerPath' => '/admin/banner/banner/uploadBannerFiles',
                'publicPath' => '/pub/banner', //without slash
            ),
        ));

    }

    public function actionUpdate() {

        if(Yii::app()->request->isAjaxRequest && isset($_POST['pk'])) {
            Yii::import('ext.editable.EditableSaver');
            $es = new EditableSaver('Banner');
            $es->onBeforeUpdate = function($event) {
                $event->sender->setAttribute('updated_at', null);
            };
            $es->update();
            exit();
        }

        Yii::import( "ext.xupload.models.XUploadForm" );

        $photos = new XUploadForm;

        $listUri = preg_replace(array('/\/$/','/\/update.*/','/\/add.*/'),'', Yii::app()->request->getRequestUri()).'/list';


        if ($id = Yii::app()->request->getParam('id')) {
            $model = Banner::model()->findByPk($id);
            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }
        } else {
            $model = new Banner();
        }

        if ($postBanner = Yii::app()->request->getPost('Banner')) {
            $model->attributes = $postBanner;
            if ($model->save()) {
                $this->redirect($listUri);
            }
        }
        $bannerPositions = BannerPosition::model()->findAll();
        $position = CHtml::listData($bannerPositions, 'id', function ($position) {
            return $position->position . ' (' . $position->width . 'x' . $position->height . ')';
        });
        $this->render('update', compact('model', 'photos', 'position', 'bannerPositions', 'listUri'));
    }

    public function actionAdd() {
        $this->actionUpdate();
    }

}