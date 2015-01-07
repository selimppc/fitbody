<?php

class UploadedBanners extends CAction {

    public $model;
    public $controllerPath;
    public $publicPath;

    public function init() {}

    public function run($itemId = null) {
        if ($itemId) {
            $modelName = $this->model;
            $model = $modelName::model()->findByPk($itemId);
            if ($model && $model->filename && $model->filename_real) {
                echo json_encode(array(
                    array(
                        "name" => ($model->filename_real) ? $model->filename_real : $model->filename,
                        "filename" => $model->filename,
                        "filename_real" => $model->filename_real,
                        "extension" => pathinfo($model->filename_real, PATHINFO_EXTENSION),
                        "url" => $this->publicPath . '/' . $model->filename,
                        "delete_url" => Yii::app()->createUrl($this->controllerPath, array(
                            "method" => "delete",
                            "banner" => $model->id,
                        )),
                        "delete_type" => "POST"
                    ),
                ));
            } else {
                echo json_encode(array());
            }

        } elseif (($data = Yii::app()->request->getPost('data')) && is_array($data) && isset($data['filename']) && isset($data['filename_real'])) {
            echo json_encode(array(
                array(
                    "name" => $data['filename_real'],
                    "filename" => $data['filename'],
                    "filename_real" => $data['filename_real'],
                    "extension" => pathinfo($data['filename_real'], PATHINFO_EXTENSION),
                    "url" => $this->publicPath . '/' . $data['filename'],
                    "delete_url" => Yii::app()->createUrl($this->controllerPath, array(
                        "method" => "delete",
                        "filename" => $data['filename']
                    )),
                    "delete_type" => "POST"
                ),
            ));
        }
        Yii::app()->end();
    }
}
