<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class ShopController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'Shop',
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
                            'url'      => $this->createUrl('shop/update')
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
                            'url'      => $this->createUrl('shop/update')
                        )
                    ),
                ),
            ),
            'uploadShopImages' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'shop_images',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/shop/images/big/',
                'publicThumbnailPath' => '/pub/shop/images/80x80/',
                'invokeModel' => 'ShopImage',
                'afterModelMethod' => 'addShopImage',
                'controllerPath' => '/admin/shop/shop/uploadShopImages/',
            ),
            'UploadedMultipleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Shop',
                'controllerPath' => '/admin/shop/shop/uploadShopImages',
                'publicPath' => '/pub/shop/images/big/',
                'publicThumbnailPath' => '/pub/shop/images/80x80/',
            ),
            'uploadMainImage' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'shop_main',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/shop/main/big',
                'publicThumbnailPath' => '/pub/shop/main/80x80',
                'invokeModel' => 'Shop',
                'afterModelMethod' => 'addMainImage',
                'controllerPath' => '/admin/shop/shop/uploadMainImage/',
            ),
            'UploadedSingleMainImage' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Shop',
                'multiple' => false,
                'relation' => 'image',
                'controllerPath' => '/admin/shop/shop/uploadMainImage/',
                'publicPath' => '/pub/shop/main/big/',
                'publicThumbnailPath' => '/pub/shop/main/80x80/',
            ),
        ));

    }

    public function actionUpload() {
        if(Yii::app()->image->checkUpload('file')) {
            $image_id = Yii::app()->image->save('file','shop_description');
            if ($image = Image::model()->findByPk($image_id)) {
                $array = array(
                    'filelink' => '/pub/shop_description/images/500x500/' . $image->image_filename
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
//        Debug::print_die($_POST);

        if (Yii::app()->request->isAjaxRequest && isset($_POST['pk'])) {
            Yii::import('ext.editable.EditableSaver');
            $es = new EditableSaver('Shop');
            $es->update();
            Yii::app()->end();
        }

        Yii::import('ext.chosen.Chosen');
        Yii::import( "ext.xupload.models.XUploadForm" );

        $photos = new XUploadForm;
        $chain = new ShopChain();

        if ($id = Yii::app()->request->getParam('id')) {
            $model = Shop::model()->with('categories', 'addressesRel')->findByPk($id);
            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }

            $model->addresses = $model->addressesRel;

            foreach ($model->addresses as $key => $city) {
                $model->addresses[$key]->phonesArr = $model->addresses[$key]->phones;
                $model->addresses[$key]->worktimesArr = $model->addresses[$key]->worktimes;
            }

            $model->categories = CHtml::listData($model->categories, 'category_id', 'category_id');

            //set radio
            if(intval($model->chain_id)){
                $model->radio = 1;
            }
        } else {
            $model = new Shop();
        }


        $categories = CHtml::listData(ShopCategory::model()->findAll(), 'id', 'title');
        $cities = CHtml::listData(City::model()->findAll(), 'id', 'city');
        $underground = CHtml::listData(Underground::model()->findAll(array('condition' => 't.status = 1')), 'id', 'title');
        $chains = CHtml::listData(ShopChain::model()->findAll(array('condition' => 't.status = 1')), 'id', 'title');
        $uri =  $this->createUrl('list');

        if ($post = Yii::app()->request->getPost('Shop')) {
            $model->attributes = $post;
            $model->addresses = array();

            if(intval($model->radio) == 2){
                $model->chainObject = $chain;
                $model->chainObject->attributes  = Yii::app()->request->getPost('ShopChain', array());
            } elseif(intval($model->radio) == 0) {
                $model->chain_id = 0;
                $model->chainObject = null;
            } else {
                $model->chainObject = null;
            }

            if ($postAddress = Yii::app()->request->getPost('ShopAddress')) {

                $postPhone = Yii::app()->request->getPost('ShopPhone', array());

                $postWorktime = Yii::app()->request->getPost('ShopWorktime', array());


                foreach ($postAddress as $key => $item) {
                    $address = new ShopAddress();
                    $address->attributes = $item;
                    $address->phonesArr = array();

                    foreach ($postPhone as $phone) {
                        if (isset($phone[$key]) && is_array($phone[$key]) && count($phone[$key]) > 0) {
                            $shopPhone = new ShopPhone();
                            if (isset($phone[$key]['phone'])) {
                                $shopPhone->phone = $phone[$key]['phone'];
                                $shopPhone->description = (isset($phone[$key]) ? $phone[$key]['description'] : '');
                                $address->phonesArr[] = $shopPhone;
                            }
                        }
                    }

                    foreach ($postWorktime as $time) {
                        if (isset($time[$key]) && is_array($time[$key]) && count($time[$key]) > 0) {
                            $shopWorktime = new ShopWorktime();
                            $shopWorktime->attributes = $time[$key];
                            $address->worktimesArr[] = $shopWorktime;
                        }
                    }
                    $model->addresses[] = $address;
                }
            }

            if ($model->validate() && $model->validLink && $model->save(false)) {
                $this->redirect($uri);
            }
        }

        $this->render('update', compact('model', 'categories', 'cities', 'underground','photos' , 'uri', 'chain','chains'));

    }

}