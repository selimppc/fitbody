<?php

class BookController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'Book',
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
                            'url'      => $this->createUrl('book/update')
                        )
                    ),
                    array(
                        'name' => 'image_id',
                        'class' => $this->imageColumnClass,
                        'imageId' => '$data->image_id'
                    ),

                ),
            ),
            'uploadBookFile' => array(
                'class' => 'admin.actions.files.UploadAction',
                'formClass' => 'ext.xupload.models.XUploadFormFile',
                'publicPath' => '/pub/books',
                'controllerPath' => '/admin/book/book/uploadBookFile/'
            ),
            'uploadBookImage' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'book_main_photo',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/book/main/photo/big',
                'publicThumbnailPath' => '/pub/book/main/photo/80x80',
                'invokeModel' => 'Book',
                'afterModelMethod' => 'addBookPhoto',
                'controllerPath' => '/admin/book/book/uploadBookImage/'
            ),
            'UploadedSingleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Book',
                'multiple' => false,
                'relation' => 'image',
                'controllerPath' => '/admin/book/book/uploadBookImage/',
                'publicPath' => '/pub/book/main/photo/big/',
                'publicThumbnailPath' => '/pub/book/main/photo/80x80/',
            ),
        ));

    }

    public function actionUpdate() {
        if(Yii::app()->request->isAjaxRequest && isset($_POST['pk'])) {
            Yii::import('ext.editable.EditableSaver');
            $es = new EditableSaver('Book');
            $es->update();
            exit();
        }

        $listUri = preg_replace(array('/\/$/','/\/update.*/','/\/add.*/'),'', Yii::app()->request->getRequestUri()).'/list';

        if ($id = Yii::app()->request->getParam('id')) {
            $model = Book::model()->findByPk($id);
            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }
        } else {
            $model = new Book();
        }

        if ($postBook = Yii::app()->request->getPost('Book')) {
            
            $model->attributes = $postBook;

            if ($model->validate() && $model->save(false)) {
                $this->redirect($listUri);
            }
        }
        Yii::import( "ext.xupload.models.XUploadForm" );
        Yii::import( "ext.xupload.models.XUploadFormFile" );
        $photos = new XUploadForm;

        $categories = BookCategory::model()->findAll();
        $categories = CHtml::listData($categories, 'id', 'title');
        $this->render('update', compact('model', 'photos', 'listUri', 'categories'));
    }

    public function actionAdd() {
        $this->actionUpdate();
    }

}