	<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class ArticleController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'Article',
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
                        'header' => 'Категория',
                        'name' => 'subcategory',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->subcategory->title',
                        'align' => 'center',
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'name' => 'status',
                        'header' => 'Статус',
                        'headerHtmlOptions' => array(
                            'width' => 50
                        ),
                        'value' => '($data->status) ? "Активный": "Неактивный";',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'select',
                            'source'   => array('0' => 'Неактивный', '1' => 'Активный'),
                            'url'      => $this->createUrl('article/update')
                        )
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'name' => 'pick',
                        'header' => 'Выделять',
                        'headerHtmlOptions' => array(
                            'width' => 50
                        ),
                        'value' => '($data->pick) ? "Да": "Нет";',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'select',
                            'source'   => array('0' => 'Нет', '1' => 'Да'),
                            'url'      => $this->createUrl('article/update')
                        )
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'name' => 'show',
                        'header' => 'Отображать на главной',
                        'headerHtmlOptions' => array(
                            'width' => 200
                        ),
                        'value' => '($data->show) ? "Да": "Нет";',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'select',
                            'source'   => array('0' => 'Нет', '1' => 'Да'),
                            'url'      => $this->createUrl('article/update')
                        )
                    ),
                    array(
                        'header' => 'Дата создания',
                        'name' => 'created_at',
                        'headerHtmlOptions' => array(
                            'width' => 150
                        ),
                        'value' => '$data->created_at',
                        'align' => 'center',
                    ),
                    array(
                        'header' => 'Дата обновления',
                        'name' => 'updated_at',
                        'headerHtmlOptions' => array(
                            'width' => 150
                        ),
                        'value' => '$data->updated_at',
                        'align' => 'center',
                    ),
                    array(
                        'header' => 'Отображать до',
                        'name' => 'end_at',
                        'headerHtmlOptions' => array(
                            'width' => 150
                        ),
                        'value' => '$data->end_at',
                        'align' => 'center',
                    ),

                ),
            ),
            'uploadArticleImage' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'article_photo',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/article/photo/big',
                'publicThumbnailPath' => '/pub/article/photo/80x80',
                'invokeModel' => 'Article',
                'afterModelMethod' => 'addArticlePhoto',
                'controllerPath' => '/admin/article/article/uploadArticleImage/'
            ),
            'UploadedSingleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Article',
                'multiple' => false,
                'relation' => 'image',
                'controllerPath' => '/admin/article/article/uploadArticleImage/',
                'publicPath' => '/pub/article/photo/big/',
                'publicThumbnailPath' => '/pub/article/photo/80x80/',
            ),
        ));

    }

    public function actionUpload() {
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
            $es = new EditableSaver('Article');
            $es->update();
            echo json_encode(array('status' => 'success'));
            Yii::app()->end();
        }


        Yii::import( "ext.xupload.models.XUploadForm" );

        $photos = new XUploadForm;

        if ($id = Yii::app()->request->getParam('id')) {
            $model = Article::model()->findByPk($id);
            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }

        } else {
            $model = new Article();
        }

        $uri =  $this->createUrl('list');

        if ($post = Yii::app()->request->getPost('Article')) {
            $model->attributes = $post;
            if ($model->save()) {
                $this->redirect($uri);
            }
        }
        $this->render('update', compact('model', 'photos', 'uri'));
    }

}