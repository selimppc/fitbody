<?php



class UploadAction extends CAction {

    public $formClass = 'xupload.models.XUploadForm';

    public $fileAttribute = 'file';

    public $mimeTypeAttribute = 'mime_type';

    public $sizeAttribute = 'size';

    public $displayNameAttribute = 'name';

    public $fileNameAttribute = 'filename';

    public $path;

    public $publicPath = '/pub/video/';

    public $secureFileNames = true;

    private $_formModel;

    public $invokeModel;

    public $afterModelMethod;

    public $itemId;

    public $videoId;

    public $controllerPath;

    public function init() {

        $this->path = DOCUMENT_ROOT . 'public_html' . $this->publicPath;

        if(!is_dir($this->path)) {
            mkdir( $this->path, 0777, true );
            chmod ( $this->path, 0777 );
            //throw new CHttpException(500, "{$this->path} does not exists.");
        } else if( !is_writable($this->path) ) {
            chmod($this->path, 0777);
            //throw new CHttpException(500, "{$this->path} is not writable.");
        }

        if( !isset($this->_formModel)) {
            $this->formModel = Yii::createComponent(array('class' => $this->formClass));
        }

    }


    public function run($method = null, $filename = null) {
        $this->init();
        $this->sendHeaders();
        if ($method === 'delete' && $filename) {
            $this->handleDeleting($filename);
        } else {
            $this->handleUploading();
        }

    }

    protected function sendHeaders() {
        header('Vary: Accept');
        if (isset($_SERVER['HTTP_ACCEPT']) && (strpos($_SERVER['HTTP_ACCEPT'], 'application/json') !== false)) {
            header('Content-type: application/json');
        } else {
            header('Content-type: text/plain');
        }
    }

    protected function handleDeleting($filename) {
        Yii::app()->files->delFile($this->path, $filename);
        echo json_encode(true);
        return true;
    }

    protected function handleUploading() {
        $this->init();

        $model = $this->formModel;

        $model->{$this->fileAttribute} = CUploadedFile::getInstance($model, $this->fileAttribute);

        if ($model->{$this->fileAttribute} !== null) {
            $model->{$this->mimeTypeAttribute} = $model->{$this->fileAttribute}->getType();
            $model->{$this->sizeAttribute} = $model->{$this->fileAttribute}->getSize();
            $model->{$this->displayNameAttribute} = $model->{$this->fileAttribute}->getName();
            $model->{$this->fileNameAttribute} = $model->{$this->displayNameAttribute};

            if ($model->validate()) {
                if ($this->secureFileNames) {
                    $mark = microtime();
                    $mark = rand(1, 9999) . substr($mark, 11, 11) . substr($mark, 2, 6) . rand(1, 9999);
                    $ext = pathinfo($model->{$this->displayNameAttribute}, PATHINFO_EXTENSION);
                    $fileName = strtolower($mark . ((!$ext) ? '' : '.' . $ext));
                    $model->{$this->fileNameAttribute} = $fileName;
                }

                $this->makedir($this->path);
                @chmod($this->path, 0777);

                $fullPath = $this->path . '/' . $model->{$this->fileNameAttribute};
                $model->{$this->fileAttribute}->saveAs($fullPath);
                @chmod($fullPath, 0777);

                $video = new Video();

                $video->filename = $model->{$this->fileNameAttribute};
                $video->filename_real = $model->{$this->displayNameAttribute};

                echo json_encode(array(array(
                    "name" => $model->{$this->displayNameAttribute},
                    "method" => ($this->videoId) ? 'upd' : 'add',
                    "file" => $model->{$this->fileNameAttribute},
                    "type" => $model->{$this->mimeTypeAttribute},
                    "size" => $model->{$this->sizeAttribute},
                    "url" => $this->getFileUrl($model->{$this->fileNameAttribute}),
                    "delete_url" => rtrim($this->controllerPath, '/') . '/method/delete/filename/' . $model->{$this->fileNameAttribute},
                    "delete_type" => "POST"
                )));

            } else {
                echo json_encode(array(array("error" => $model->getErrors($this->fileAttribute),)));
                Yii::log("XUploadAction: " . CVarDumper::dumpAsString($model->getErrors()), CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction");
            }
        } else {
            throw new CHttpException(500, "Could not upload file");
        }
    }

    protected function getFileUrl($fileName) {
        return $this->getPublicPath() . $fileName;
    }

    protected function getPublicPath() {
        return "{$this->publicPath}/";
    }

    public function setFormModel($model) {
        $this->_formModel = $model;
    }

    public function getFormModel() {
        return $this->_formModel;
    }

    protected function fileExists($file) {
        return is_file( $file['path'] );
    }

    protected function deleteFile($file) {
        return unlink($file['path']);
    }


    public function makedir($dir) {
        if (is_dir($dir)) {
            return;
        }
        $path = '/';
        $dir_arr = explode('/', $dir);
        if (sizeof($dir_arr) > 0) {
            foreach ($dir_arr as $part) {
                if (strlen($part) > 0) {
                    if (isset($part[1]) && $part[1] == ':') {
                        $path = $part . '/';
                    }
                    else {
                        $path .= $part . '/';
                    }
                    if (!file_exists($path)) {
                        mkdir($path);
                    }
                }
            }
        }
    }
}
