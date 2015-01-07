<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/7/14
 * Time: 1:58 PM
 */
class CountryController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'Country',
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
                        'header' => 'Страна',
                        'name' => 'title',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->title',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'text',
                            'url'      => $this->createUrl('country/update'),
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
                            'url'      => $this->createUrl('country/update')
                        )
                    ),

                ),
            ),
            'add' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'Country'
            ),
            'update' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'Country'
            ),
            'uploadCountryIcon' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'country_icon',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/country/icon/big',
                'publicThumbnailPath' => '/pub/country/icon/16x11',
                'invokeModel' => 'Country',
                'afterModelMethod' => 'addCountryIcon',
                'controllerPath' => '/admin/place/country/uploadCountryIcon/'
            ),
            'UploadedSingleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Country',
                'multiple' => false,
                'relation' => 'image',
                'controllerPath' => '/admin/place/country/uploadCountryIcon/',
                'publicPath' => '/pub/country/icon/big/',
                'publicThumbnailPath' => '/pub/country/icon/16x11/',
            ),
        ));

    }

    public function actionUpload() {
        if(Yii::app()->image->checkUpload('file')) {
            $image_id = Yii::app()->image->save('file','country_icon');
            if ($image = Image::model()->findByPk($image_id)) {
                $array = array(
                    'filelink' => '/pub/country/icon/16x11/' . $image->image_filename
                );
                echo stripslashes(json_encode($array));
                exit();
            }

        }
    }

}