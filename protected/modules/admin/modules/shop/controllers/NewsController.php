<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/25/14
 * Time: 11:26 AM
 */
class NewsController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'ShopNews',
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
                        'name' => 'shop_id',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => 'FunctionHelper::upperFirst($data->shop->title)',
                        'align' => 'center',
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'header' => 'Заголовок',
                        'name' => 'title',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => 'FunctionHelper::upperFirst($data->title)',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'text',
                            'url'      => $this->createUrl('news/update'),
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
                            'url'      => $this->createUrl('news/update')
                        )
                    ),

                ),
            ),
            'uploadNewsImage' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'shop_news',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/shop/news/big',
                'publicThumbnailPath' => '/pub/shop/news/80x80',
                'invokeModel' => 'ShopNews',
                'afterModelMethod' => 'addNewsPhoto',
                'controllerPath' => '/admin/shop/news/uploadNewsImage/'
            ),
            'UploadedSingleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'ShopNews',
                'multiple' => false,
                'relation' => 'image',
                'controllerPath' => '/admin/shop/news/uploadNewsImage/',
                'publicPath' => '/pub/shop/news/big/',
                'publicThumbnailPath' => '/pub/shop/news/80x80/',
            ),
        ));

    }

    public function actionUpload() {
        Yii::import('application.modules.admin.modules.images.models.Image');
        if(Yii::app()->image->checkUpload('file')) {
            $image_id = Yii::app()->image->save('file','news_photo');
            if ($image = Image::model()->findByPk($image_id)) {
                $array = array(
                    'filelink' => '/pub/news/photo/500x500/' . $image->image_filename
                );
                echo stripslashes(json_encode($array));
                exit();
            }

        }
    }

    public function actionAdd() {
        $this->actionUpdate();
    }

    public function actionUpdate() {

        if (Yii::app()->request->isAjaxRequest && isset($_POST['pk'])) {
            Yii::import('ext.editable.EditableSaver');
            $es = new EditableSaver('ShopNews');
            $es->update();
            Yii::app()->end();
        }

        Yii::import('ext.chosen.Chosen');
        Yii::import( "ext.xupload.models.XUploadForm" );

        $photos = new XUploadForm;

        if ($id = Yii::app()->request->getParam('id')) {
            $model = ShopNews::model()->findByPk($id);
            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }

        } else {
            $model = new ShopNews();
        }

        $shops = CHtml::listData(Shop::model()->findAll(), 'id', 'title');
        $uri =  $this->createUrl('list');

        if ($post = Yii::app()->request->getPost('ShopNews')) {
            $model->attributes = $post;

            if ($model->save()) {
                $this->redirect($uri);
            }

        }

        $this->render('update', compact('model', 'photos', 'shops', 'uri'));

    }

}