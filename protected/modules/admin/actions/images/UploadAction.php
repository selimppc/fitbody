<?php



class UploadAction extends CAction {

    public $formClass = 'xupload.models.XUploadForm';

    public $fileAttribute = 'file';

    public $mimeTypeAttribute = 'mime_type';

    public $sizeAttribute = 'size';

    public $displayNameAttribute = 'name';

    public $fileNameAttribute = 'filename';

    public $originalDirectory = 'big';

    public $path;

    public $publicPath;

    public $secureFileNames = true;

    private $_formModel;

    public $systemKey;

    public $imageObject;

    public $thumbnail;

    public $publicThumbnailPath;

    public $imageId;

    public $invokeModel;

    public $afterModelMethod;

    public $itemId;

    public $controllerPath;

    public function init() {

        $this->imageObject = ImageObject::model()->find('`key`="' . $this->systemKey . '"');

        if($this->imageObject === null) {
            throw new CHttpException(500, "Object does not exists.");
        }

        $path = $this->imageObject->path;

        if(substr($path, -1) != '/') {
            $path = $path.'/';
        }

        $this->path = DOCUMENT_ROOT . 'public_html/pub' . (($path[0]=='/') ? $path : '/' . $path );

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


    public function run($method = null, $imageId = null, $itemId = null) {

        Yii::import('application.extensions.images.Thumbnail');
        Yii::import('admin.modules.images.models.ImageObject');
        Yii::import('admin.modules.images.models.Image');
        Yii::import('admin.modules.images.models.ImageSize');

        if($itemId) {
            $this->itemId = (int) $itemId;
        }

        $this->sendHeaders();

        if ($method === 'delete' && $imageId) {
            $this->handleDeleting($imageId);
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

    protected function handleDeleting($imageId) {
        if ($imageId) {

            if(!$Image = Image::model()->findByPk($imageId)) {
                echo json_encode(true);
                return true;
            }

            if (!$imageSizes = ImageSize::model()->findAll('image_object_id="'.$Image->image_object_id.'"')) {
                echo json_encode(true);
                return true;
            }

            $path = $Image->image_object->path;
            if(substr($path,-1) != '/') {
                $path = $path.'/';
            }
            $path = DOCUMENT_ROOT.'public_html/pub'.(($path[0]=='/')?$path:'/'.$path);
            $originPath	= $path.'big/';
            $sizesPath	= $path;
            Yii::app()->files->delFile($originPath, $Image->image_filename);
            foreach($imageSizes as $imageSize) {
                Yii::app()->files->delFile($sizesPath.$imageSize->width.'x'.$imageSize->height.'/',$Image->image_filename);
            }
            Image::model()->deleteByPk($imageId);

        }
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
                    $mark = substr($mark, 11, 11) . substr($mark, 2, 6);
                    $ext = pathinfo($model->{$this->displayNameAttribute}, PATHINFO_EXTENSION);
                    $fileName = strtolower($mark . ((!$ext) ? '' : '.' . $ext));
                    $model->{$this->fileNameAttribute} = $fileName;
                }
                $originalDirectory = ($this->originalDirectory) ? $this->originalDirectory . '/' : '';

                $this->makedir($this->path . $originalDirectory);
                @chmod($originalDirectory, 0777);

                $fullPath = $this->path . $originalDirectory. $model->{$this->fileNameAttribute};
                $model->{$this->fileAttribute}->saveAs($fullPath);
                @chmod($fullPath, 0777);

                $this->create($model->{$this->fileNameAttribute});
                $image = new Image;
                $image->alt = $model->{$this->displayNameAttribute};

                $image->image_filename = $model->{$this->fileNameAttribute};
                $image->image_object_id = $this->imageObject->id;

                if ($image->save()) {
                    $this->imageId = $image->id;

                    if ($this->invokeModel && $this->afterModelMethod) {
                        $invoke = new $this->invokeModel;
                        $invoke->{$this->afterModelMethod}($this->imageId, $this->itemId);
                    }

                    echo json_encode(array(array(
                        "name" => $model->{$this->displayNameAttribute},
                        "method" => ($this->itemId) ? 'upd' : 'add',
                        "image_id" => $this->imageId,
                        "type" => $model->{$this->mimeTypeAttribute},
                        "size" => $model->{$this->sizeAttribute},
                        "url" => $this->getFileUrl($model->{$this->fileNameAttribute}),
                        "thumbnail_url" => $this->getThumbnailUrl($model->{$this->fileNameAttribute}),
                        "delete_url" => rtrim($this->controllerPath, '/') . '/method/delete/image/' . $this->imageId,
                        "delete_type" => "POST"
                    )));
                } else {
                    echo json_encode(array(array("error" => 'file upload Error')));
                    Yii::log("XUploadAction: " . 'fileUpload', CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction");
                }
            } else {
                echo json_encode(array(array("error" => $model->getErrors($this->fileAttribute),)));
                Yii::log("XUploadAction: " . CVarDumper::dumpAsString($model->getErrors()), CLogger::LEVEL_ERROR, "xupload.actions.XUploadAction");
            }
        } else {
            throw new CHttpException(500, "Could not upload file");
        }
    }


    public function create($imageName) {
        if(!$imageSizes = ImageSize::model()->findAll('image_object_id = "' . $this->imageObject->id . '"')) {
            throw new CException('Image size empty.');
        }
        $originalDirectory = ($this->originalDirectory) ? $this->originalDirectory . '/' : '';
        $inputFile = $this->path . $originalDirectory . $imageName;
        $output_name = $imageName;
        foreach($imageSizes as $imageSize) {
            $output_dir = $this->path . $imageSize->width . 'x' . $imageSize->height.'/';
            $options = array(
                'width'  => $imageSize->width,
                'height' => $imageSize->height,
                'method' => $imageSize->type_resize,
                'valign' => 0
            );
            if (!file_exists($output_dir)) {
                Yii::app()->files->makedir($output_dir);
            }
            $this->thumbnail = new Thumbnail();
            $this->thumbnail->output($inputFile, $output_dir . $output_name, $options);
        }
        return true;
    }

    protected function getFileUrl($fileName) {
        return $this->getPublicPath() . $fileName;
    }

    protected function getThumbnailUrl($fileName) {
        return $this->getThumbnailPublicPath() . $fileName;
    }

    protected function getPublicPath() {
        return "{$this->publicPath}/";
    }

    protected function getThumbnailPublicPath() {
        return "{$this->publicThumbnailPath}/";
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
