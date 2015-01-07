<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class ClubController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'Club',
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
                        'header' => 'Клуб',
                        'name' => 'club',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->club',
                        'align' => 'center',
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'name' => 'is_new_place',
                        'header' => 'Новое место',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '($data->is_new_place) ? "Да": "Нет"',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'select',
                            'source'   => array('0' => 'Нет', '1' => 'Да'),
                            'url'      => $this->createUrl('club/update')
                        )
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
                            'url'      => $this->createUrl('club/update')
                        )
                    ),

                ),
            ),
            'uploadClubImages' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'club_photo',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/club/photo/big/',
                'publicThumbnailPath' => '/pub/club/photo/80x80/',
                'invokeModel' => 'ClubImage',
                'afterModelMethod' => 'addClubImage',
                'controllerPath' => '/admin/club/club/uploadClubImages/'
            ),
            'UploadedMultipleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Club',
                'controllerPath' => '/admin/club/club/uploadClubImages',
                'publicPath' => '/pub/club/photo/big/',
                'publicThumbnailPath' => '/pub/club/photo/80x80/',
            ),
            'uploadClubPrice' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'club_price',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/club_price/images/big',
                'publicThumbnailPath' => '/pub/club_price/images/80x80',
                'invokeModel' => 'Club',
                'afterModelMethod' => 'addClubPrice',
                'controllerPath' => '/admin/club/club/uploadClubPrice/'
            ),
            'UploadedSingleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Club',
                'multiple' => false,
                'relation' => 'priceImage',
                'controllerPath' => '/admin/club/club/uploadClubPrice/',
                'publicPath' => '/pub/club_price/images/big/',
                'publicThumbnailPath' => '/pub/club_price/images/80x80/',
            ),

            'uploadMainImage' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'club_main_photo',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/club/main/photo/big',
                'publicThumbnailPath' => '/pub/club/main/photo/80x80',
                'invokeModel' => 'Club',
                'afterModelMethod' => 'addMainImage',
                'controllerPath' => '/admin/club/club/uploadMainImage/'
            ),
            'UploadedSingleMainImage' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Club',
                'multiple' => false,
                'relation' => 'image',
                'controllerPath' => '/admin/club/club/uploadMainImage/',
                'publicPath' => '/pub/club/main/photo/big/',
                'publicThumbnailPath' => '/pub/club/main/photo/80x80/',
            ),

        ));

    }

    public function actionUpload() {
        Yii::import('application.modules.admin.modules.images.models.Image');
        if(Yii::app()->image->checkUpload('file')) {
            $image_id = Yii::app()->image->save('file','club_description');
            if ($image = Image::model()->findByPk($image_id)) {
                $array = array(
                    'filelink' => '/pub/club_description/images/500x500/' . $image->image_filename
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
            $es = new EditableSaver('Club');
            $es->update();
            Yii::app()->end();
        }

        Yii::import('ext.chosen.Chosen');
        Yii::import( "ext.xupload.models.XUploadForm" );

        $photos = new XUploadForm;
        $chain = new Chain();

        if ($id = Yii::app()->request->getParam('id')) {
            $model = Club::model()->with('destinations', 'addressesRel', 'propertiesRel')->findByPk($id);
            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }

            $model->addresses = $model->addressesRel;
            $model->properties = $model->propertiesRel;

            foreach ($model->addresses as $key => $city) {
                $model->addresses[$key]->phonesArr = $model->addresses[$key]->phones;
                $model->addresses[$key]->worktimesArr = $model->addresses[$key]->worktimes;
            }

            $model->destinations = CHtml::listData($model->destinations, 'destination_id', 'destination_id');

            //set radio
            if(intval($model->chain_id)){
                $model->radio = 1;
            }
        } else {
            $model = new Club();
        }

        
        $destinations = CHtml::listData(ClubDestination::model()->findAll(), 'id', 'destination');
        $cities = CHtml::listData(City::model()->findAll(), 'id', 'city');
        $underground = CHtml::listData(Underground::model()->findAll(array('condition' => 't.status = 1')), 'id', 'title');
        $chains = CHtml::listData(Chain::model()->findAll(array('condition' => 't.status = 1')), 'id', 'title');
        $properties = CHtml::listData(ClubProperty::model()->findAll(), 'id', 'property');
        $uri =  $this->createUrl('list');

        if ($post = Yii::app()->request->getPost('Club')) {
            $model->attributes = $post;
            $model->addresses = array();
            $model->properties = array();

            if(intval($model->radio) == 2){
                $model->chainObject = $chain;
                $model->chainObject->attributes  = Yii::app()->request->getPost('Chain', array());
            } elseif(intval($model->radio) == 0) {
                $model->chain_id = 0;
                $model->chainObject = null;
            } else {
                $model->chainObject = null;
            }


            if ($postProperty = Yii::app()->request->getPost('ClubPropertyLink')) {
                foreach ($postProperty as $property) {
                    $propertyObj = new ClubPropertyLink();
                    $propertyObj->attributes = $property;
                    $model->properties[] = $propertyObj;
                }
            }

            if ($postAddress = Yii::app()->request->getPost('ClubAddress')) {

                $postPhone = Yii::app()->request->getPost('ClubPhone', array());

                $postWorktime = Yii::app()->request->getPost('ClubWorktime', array());


                foreach ($postAddress as $key => $item) {
                    $address = new ClubAddress();
                    $address->attributes = $item;
                    $address->phonesArr = array();
                    
                    foreach ($postPhone as $phone) {
                        if (isset($phone[$key]) && is_array($phone[$key]) && count($phone[$key]) > 0) {
                            $clubPhone = new ClubPhone();
                            if (isset($phone[$key]['phone'])) {
                                $clubPhone->phone = $phone[$key]['phone'];
                                $clubPhone->description = (isset($phone[$key]) ? $phone[$key]['description'] : '');
                                $address->phonesArr[] = $clubPhone;
                            }
                        }
                    }

                    foreach ($postWorktime as $time) {
                        if (isset($time[$key]) && is_array($time[$key]) && count($time[$key]) > 0) {
                            $clubWorktime = new ClubWorktime();
                            $clubWorktime->attributes = $time[$key];
                            $address->worktimesArr[] = $clubWorktime;
                        }
                    }
                    $model->addresses[] = $address;

                }
            }

            if ($model->validate() && $model->validLink && $model->save(false)) {
                $this->redirect($uri);
            }

        }

        $this->render('update', compact('model', 'destinations', 'cities', 'underground','photos', 'properties', 'uri', 'chain','chains'));

    }

}