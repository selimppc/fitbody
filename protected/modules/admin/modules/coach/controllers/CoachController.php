<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class CoachController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'uploadCoachImage' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'coach_main_photo',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/coach/main/photo/big',
                'publicThumbnailPath' => '/pub/coach/main/photo/80x80',
                'invokeModel' => 'Coach',
                'afterModelMethod' => 'addCoachPhoto',
                'controllerPath' => '/admin/coach/coach/uploadCoachImage/'
            ),
            'UploadedSingleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Coach',
                'multiple' => false,
                'relation' => 'image',
                'controllerPath' => '/admin/coach/coach/uploadCoachImage/',
                'publicPath' => '/pub/coach/main/photo/big/',
                'publicThumbnailPath' => '/pub/coach/main/photo/80x80/',
            ),

            'uploadCoachImages' => array(
                'class' => 'admin.actions.images.UploadAction',
                'systemKey' => 'coach_photo',
                'formClass' => 'ext.xupload.models.XUploadForm',
                'publicPath' => '/pub/coach/photo/big/',
                'publicThumbnailPath' => '/pub/coach/photo/80x80/',
                'invokeModel' => 'CoachImage',
                'afterModelMethod' => 'addCoachImage',
                'controllerPath' => '/admin/coach/coach/uploadCoachImages/'
            ),
            'UploadedMultipleImages' => array(
                'class' => 'admin.actions.images.UploadedImages',
                'model' => 'Coach',
                'controllerPath' => '/admin/coach/coach/uploadCoachImages',
                'publicPath' => '/pub/coach/photo/big/',
                'publicThumbnailPath' => '/pub/coach/photo/80x80/',
            ),

        ));

    }


    public function actionDeleteCoach() {
        if (isset($_POST['ajax'])) {
            if (!isset($_POST['rt']) || $_POST['rt'] != 'deleteSelected') {
                return;
            }
            $result = array(
                'error' => true,
                'errorMsg' => 'Unknown error'
            );
            if (isset($_POST['ids'])) {
                $ids = CJavaScript::jsonDecode($_POST['ids']);
                if (is_array($ids)) {
                    if (Coach::model()->deleteAndUpdatePosition($ids)) {
                        unset($result['error']);
                        unset($result['errorMsg']);
                    }
                }
            }
            $result = CJSON::encode($result);
            echo $result;
            Yii::app()->end();
        }
    }

    public function actionPosition($model, $direction, $position) {
        echo $model::model()->changePosition($direction, $position);
        Yii::app()->end();
    }

    public function actionList() {
        $numElementsOnPage = 25;
        $criteria = new CDbCriteria();
        $criteria->order = 'position ASC';
        $dataProvider = new CActiveDataProvider('Coach',  array(
                'criteria' => $criteria,
                'pagination' => array(
                    'pageSize'=> $numElementsOnPage,
                )
            )
        );
        $this->render('admin.modules.coach.views.coach.list', array('dataProvider' => $dataProvider));
    }




    public function actionAdd() {
        $this->actionUpdate();
    }

    public function actionUpdate() {

        if (Yii::app()->request->isAjaxRequest && isset($_POST['pk'])) {
            Yii::import('ext.editable.EditableSaver');
            $es = new EditableSaver('Coach');
            $es->update();
            Yii::app()->end();
        }

        if ($id = Yii::app()->request->getParam('id')) {
            $model = Coach::model()->with('categories', 'phones', 'clubsRel')->findByPk($id);
            if (!$model) {
                throw new CHttpException(404, Yii::t('base', 'The specified record cannot be found.'));
            }
            $model->phonesArr = $model->phones;
            $model->simpleClubsArr = $model->simpleClubs;
            $model->properties = $model->propertiesRel;
            $model->clubs = $model->clubsRel;
            $cats = array();
            foreach ($model->categories as $cat) {
                if ((int) $cat->is_main !== (int) CoachCategoryLink::MAIN_CATEGORY) {
                    $cats[] = $cat->category_id;
                }
            }
            $model->categories = $cats;
        } else {
            $model = new Coach();
        }

        $model->categoryMainVar = new CoachCategoryLink();

        if ($model->categoryMain) {
            $model->categoryMainVar = $model->categoryMain;
        }

        $categories = CHtml::listData(CoachCategory::model()->findAll(), 'id', 'title');
        $properties = CHtml::listData(CoachProperty::model()->findAll(), 'id', 'property');
        $clubs = CHtml::listData(Club::model()->findAll(), 'id', 'club');

        $uri =  $this->createUrl('list');

        if ($post = Yii::app()->request->getPost('Coach')) {
            $model->properties = array();
            $model->attributes = $post;

            if ($postPhone = Yii::app()->request->getPost('CoachPhone')) {
                $model->phonesArr = array();
                foreach ($postPhone as $phone) {
                        $coachPhone = new CoachPhone();
                        $coachPhone->attributes = $phone;
                        $model->phonesArr[] = $coachPhone;
                }
            }

            if ($postSimpleClubs = Yii::app()->request->getPost('CoachSimpleClub')) {
                $model->simpleClubsArr = array();
                foreach ($postSimpleClubs as $simpleClub) {
                    $coachSimpleClub = new CoachSimpleClub();
                    $coachSimpleClub->attributes = $simpleClub;
                    $model->simpleClubsArr[] = $coachSimpleClub;
                }
            }

            if ($postMainCategory = Yii::app()->request->getPost('CoachCategoryLink')) {
                $mainCategory = new CoachCategoryLink();
                $mainCategory->attributes = $postMainCategory;
                $mainCategory->is_main = CoachCategoryLink::MAIN_CATEGORY;
                $model->categoryMainVar = $mainCategory;
            }

            if ($postProperty = Yii::app()->request->getPost('CoachPropertyLink')) {

                foreach ($postProperty as $property) {
                    $propertyObj = new CoachPropertyLink();
                    $propertyObj->attributes = $property;
                    $model->properties[] = $propertyObj;
                }
            }

            if ($model->validate() && $model->validLink && $model->save(false)) {
                $this->redirect($uri);
            }
        }

        Yii::import( "ext.xupload.models.XUploadForm" );
        $photos = new XUploadForm;

        Yii::import('ext.chosen.Chosen');

        $this->render('update', compact('model', 'clubs', 'categories', 'properties', 'uri','photos', 'categoryMain'));

    }

    public function actionUpload() {
        if(Yii::app()->image->checkUpload('file')) {
            $image_id = Yii::app()->image->save('file','coach_photo');
            if ($image = Image::model()->findByPk($image_id)) {
                $array = array(
                    'filelink' => '/pub/coach/photo/500x500/' . $image->image_filename
                );
                echo stripslashes(json_encode($array));
                exit();
            }

        }
    }

}