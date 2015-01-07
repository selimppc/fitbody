<?php

class UploadedImages extends CAction {

    public $model;
    public $multiple = true;
    public $controllerPath;
    public $publicPath;
    public $publicThumbnailPath;
    public $relation = 'images';

    public function init() {}

    public function run($itemId = null) {

        Yii::import('application.modules.admin.modules.images.models.Image');

        if ($itemId) {
            $modelName = $this->model;
            $model = $modelName::model()->with($this->relation)->findByPk($itemId);
            if ($model && count($model->{$this->relation})) {

                if ($this->multiple) {
                    $arrayImagesInfo = array();
                    foreach ($model->{$this->relation} as $image) {
                        $arrayImagesInfo[] = array(
                            "name" => ($image->alt) ? $image->alt : $image->image_filename,
                            "image_id" => $image->id,
                            "url" => $this->publicPath . $image->image_filename,
                            "thumbnail_url" => $this->publicThumbnailPath . $image->image_filename,
                            "delete_url" => rtrim($this->controllerPath, '/') . '/method/delete/image/' . $image->id,
                            "delete_type" => "POST"
                        );
                    }
                    echo json_encode($arrayImagesInfo);
                } else {
                    echo json_encode(array(
                        array(
                            "name" => ($model->{$this->relation}->alt) ? $model->{$this->relation}->alt : $model->{$this->relation}->image_filename,
                            "image_id" => $model->{$this->relation}->id,
                            "url" => $this->publicPath . $model->{$this->relation}->image_filename,
                            "thumbnail_url" => $this->publicThumbnailPath . $model->{$this->relation}->image_filename,
                            "delete_url" => rtrim($this->controllerPath, '/') . '/method/delete/image/' . $model->{$this->relation}->id,
                            "delete_type" => "POST"
                        ),
                    ));
                }
            }

        } elseif(isset($_POST['images']) && count($_POST['images']) > 0) {
            $images = $_POST['images'];
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $images);
            $images = Image::model()->findAll($criteria);
            $arrayImagesInfo = array();
            foreach ($images as $image) {
                $arrayImagesInfo[] = array(
                    "name" => ($image->alt) ? $image->alt : $image->image_filename,
                    "image_id" => $image->id,
                    "url" => $this->publicPath . $image->image_filename,
                    "thumbnail_url" => $this->publicThumbnailPath . $image->image_filename,
                    "delete_url" => rtrim($this->controllerPath, '/') . '/method/delete/image/' . $image->id,
                    "delete_type" => "POST"
                );
            }
            echo json_encode($arrayImagesInfo);
        }
        Yii::app()->end();
    }


}
