<?php

class UploadedVideos extends CAction {

    public $model;
    public $multiple = true;
    public $controllerPath;
    public $publicPath;
    public $relation = 'videos';

    public function init() {}

    public function run($itemId = null) {

        if ($itemId) {
            $modelName = $this->model;
            $model = $modelName::model()->with($this->relation)->findByPk($itemId);
            if ($model && count($model->{$this->relation})) {

                if ($this->multiple) {
                    $arrayVideosInfo = array();
                    foreach ($model->{$this->relation} as $video) {
                        $arrayVideosInfo[] = array(
                            "name" => ($video->filename_real) ? $video->filename_real : $video->filename,
                            "video_id" => $video->id,
                            "url" => $this->publicPath . '/' . $video->filename,
                            "delete_url" => rtrim($this->controllerPath, '/') . '/method/delete/video/' . $video->id,
                            "delete_type" => "POST"
                        );
                    }
                    echo json_encode($arrayVideosInfo);
                } else {
                    echo json_encode(array(
                        array(
                            "name" => ($model->{$this->relation}->filename_real) ? $model->{$this->relation}->filename_real : $model->{$this->relation}->filename,
                            "image_id" => $model->{$this->relation}->id,
                            "url" => $this->publicPath . '/' . $model->{$this->relation}->filename,
                            "delete_url" => rtrim($this->controllerPath, '/') . '/method/delete/video/' . $model->{$this->relation}->id,
                            "delete_type" => "POST"
                        ),
                    ));
                }
            }

        } elseif(isset($_POST['videos']) && count($_POST['videos']) > 0) {
            $videos = $_POST['videos'];
            $criteria = new CDbCriteria();
            $criteria->addInCondition('id', $videos);
            $videos = Video::model()->findAll($criteria);
            $arrayVideosInfo = array();
            foreach ($videos as $video) {
                $arrayVideosInfo[] = array(
                    "name" => ($video->alt) ? $video->filename_real : $video->filename,
                    "video_id" => $video->id,
                    "url" => $this->publicPath . $video->filename,
                    "delete_url" => rtrim($this->controllerPath, '/') . '/method/delete/video/' . $video->id,
                    "delete_type" => "POST"
                );
            }
            echo json_encode($arrayVideosInfo);
        }
        Yii::app()->end();
    }


}
