<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 16.10.13
 * Time: 14:14
 * To change this template use File | Settings | File Templates.
 */
Yii::import('application.extensions.images.Thumbnail');
Yii::import('admin.modules.images.models.ImageObject');
Yii::import('admin.modules.images.models.ImageSize');
Yii::import('admin.modules.images.models.Image');
class ImageComponent extends CApplicationComponent {

    /**
     * @var Thumbnail
     */
    protected $Thumbnail;

    const TYPE_RESIZE_SCALE_MAX	= 0;
    const TYPE_RESIZE_SCALE_MIN	= 1;
    const TYPE_RESIZE_CROP		= 2;
    const TYPE_RESIZE_FIT		= 3;

    public function getTypeResize() {
        return array(
            self::TYPE_RESIZE_SCALE_MAX => Yii::t('image','Scale max'),
            self::TYPE_RESIZE_SCALE_MIN => Yii::t('image','Scale min'),
            self::TYPE_RESIZE_CROP => Yii::t('image','Crop'),
            self::TYPE_RESIZE_FIT => Yii::t('image','Fit'),
        );
    }

    public function __construct() {
        $this->Thumbnail = new Thumbnail();
    }

    /**
     * Create an image folder and if $head = true - the folder "big" inside it
     *
     * @param $path
     * @param bool $head
     * @return bool
     * @throws CException
     */
    public function addNewObject($path,$head=true) {
        if($path[0] == '/')
            $path = substr($path,1);
        if(substr($path,-1) != '/')
            $path = $path.'/';
        $path = DOCUMENT_ROOT.'public_html/pub/'.$path.'big/';
        return Yii::app()->files->makedir($path);
    }

    public function createNewImageSize($id) {
        $imageSizeData = ImageSize::model()->findByPk($id);
        $path = $imageSizeData->image_object->path;
        if(substr($path,-1) != '/')
            $path = $path.'/';
        $path = DOCUMENT_ROOT.'public_html/pub'.(($path[0]=='/')?$path:'/'.$path);
        $bigPath = $path.'big/';
        $this->processNewSize($bigPath, $path, $imageSizeData->width, $imageSizeData->height, $imageSizeData->type_resize);
    }

    public function processNewSize($bigPath, $outPath, $width, $height, $typeResize) {
        $item = scandir($bigPath);
        $cnt = count($item);
        $options = array(
            'width'		=> $width,
            'height'	=> $height,
            'method'	=> $typeResize,
            'valign'	=> 0,
        );
        for ($i = 0; $i < $cnt; $i++) {
            if ($item[$i] == '.' || $item[$i] == '..') {
                continue;
            }
            if (is_file($bigPath . $item[$i])) {
                $outFolder = $outPath . $width . 'x' . $height . '/';
                Yii::app()->files->makedir($outFolder);
                $this->Thumbnail->output($bigPath . $item[$i], $outFolder . $item[$i], $options);
            } else {
                Yii::app()->files->makedir($outPath . $item[$i]);
                $this->processNewSize($bigPath . $item[$i], $outPath . $item[$i], $width, $height, $typeResize);
            }
        }
    }

    /**
     * Check whether the file is loaded
     *
     * @param $name
     * @return bool
     */
    public function checkUpload($name) {
        return (isset($_FILES[$name])&&$_FILES[$name]['size']>0);
    }

    public function save($filesVarName,$systemKey,$alt='',$originalName=false) {
        $imageData = ImageObject::model()->find('`key`="'.$systemKey.'"');
        if($imageData === null)
            return null;
        $path = $imageData->path;
        if(substr($path,-1) != '/')
            $path = $path.'/';
        $path = DOCUMENT_ROOT.'public_html/pub'.(($path[0]=='/')?$path:'/'.$path);
        $fileName = $this->saveFile($filesVarName,$path.'big/',$originalName);
        if ($fileName) {
            $this->create($systemKey,$fileName,$imageData,$path);
            $alt = ($alt == '') ? $_FILES[$filesVarName]['name'] : $alt;
            $image = new Image();
            $image->alt = $alt;
            $image->image_filename = $fileName;
            $image->image_object_id = $imageData->id;
            if(!$image->save())
                return null;
            return $image->id;

        }
        return null;
    }

    public function saveFromFile($file,$systemKey,$alt='',$originalName=false)
    {
        $imageData = ImageObject::model()->find('`key`="'.$systemKey.'"');
        if($imageData === null)
            return null;
        $path = $imageData->path;
        if(substr($path,-1) != '/')
            $path = $path.'/';
        $path = DOCUMENT_ROOT.'public_html/pub'.(($path[0]=='/')?$path:'/'.$path);

        $fileName = preg_replace('/\/([A-z0-9\.]*)$/','$1',$file);
        if ($originalName) {
            $file_name = strtolower($fileName);
        }
        else {
            $mark = microtime();
            $mark = substr($mark,11,11).substr($mark,2,6);
            $ext = strrchr($fileName, '.');
            $file_name = strtolower($mark.(($ext===false)?'':$ext));
        }
        if(!copy($file,$path.'big/'.$file_name))
            return null;

        $this->create($systemKey,$file_name,$imageData,$path);
        $image = new Image();
        $image->alt = $alt;
        $image->image_filename = $file_name;
        $image->image_object_id = $imageData->id;
        if(!$image->save())
            return null;
        return $image->id;
    }

    public function delete($imageId) {
        $Image = Image::model()->findByPk($imageId);
        if($Image===null)
            return true;
        $imageSizes = ImageSize::model()->findAll('image_object_id="'.$Image->image_object_id.'"');
        if ($imageSizes == array()) {
            return true;
        }
        $path = $Image->image_object->path;
        if(substr($path,-1) != '/')
            $path = $path.'/';
        $path = DOCUMENT_ROOT.'public_html/pub'.(($path[0]=='/')?$path:'/'.$path);
        $originPath	= $path.'big/';
        $sizesPath	= $path;
        Yii::app()->files->delFile($originPath, $Image->image_filename);
        foreach($imageSizes as $imageSize) {
            Yii::app()->files->delFile($sizesPath.$imageSize->width.'x'.$imageSize->height.'/',$Image->image_filename);
        }
        Image::model()->deleteByPk($imageId);
        return true;
    }

    /**
     * Save file to folder
     * @param string $filesVarName
     * @param string $folder
     * @param bool $originalName
     * @param bool $fullFolder
     * @return string|false
     */
    public static function saveFile($filesVarName, $folder, $originalName = false, $fullFolder = true) {
        if (!$fullFolder) {
            if ( (substr($folder, -1) != '/') && (substr($folder, -1) != '\\') ) {
                $folder .= '/';
            }
            global $FilePath;
            $folder = $FilePath . $folder;
        }
        Yii::app()->files->makedir($folder);
        if ($originalName) {
            $file_name = strtolower($_FILES[$filesVarName]['name']);
        }
        else {
            $mark = microtime();
            $mark = substr($mark,11,11).substr($mark,2,6);
            $ext = strrchr($_FILES[$filesVarName]['name'], '.');
            $file_name = strtolower($mark.(($ext===false)?'':$ext));
        }
        if (file_exists($folder . $file_name)) {
            @chmod($folder . $file_name, 0777);
            @unlink($folder . $file_name);
        }
        if (@move_uploaded_file($_FILES[$filesVarName]['tmp_name'], $folder . $file_name)) {
            return $file_name;
        }
        else {
            return false;
        }
    }

    /**
     * Create thumbnail set
     *
     * @param $systemKey
     * @param $imageName
     * @param $imageData
     * @param $path
     * @return bool
     * @throws CException
     */
    public function create($systemKey, $imageName, $imageData, $path) {
        $imageSizes = ImageSize::model()->findAll('image_object_id="'.$imageData->id.'"');
        if($imageSizes == array())
            throw new CException('Image size empty or internal error');

        $input_file		= $path.'big/'.$imageName;
        $output_name	= $imageName;
        foreach($imageSizes as $imageSize) {
            $output_dir = $path.$imageSize->width.'x'.$imageSize->height.'/';
            $options = array(
                'width'  => $imageSize->width,
                'height' => $imageSize->height,
                'method' => $imageSize->type_resize,
                'valign' => 0
            );
            if (!file_exists($output_dir)) {
                Yii::app()->files->makedir($output_dir);
            }
            $this->Thumbnail->output($input_file, $output_dir . $output_name, $options);
        }
        return true;
    }

    public function getImageTag($imageId,$width,$height,$htmlOptions=array()) {
        $image = $this->getImage($imageId);
        if($image === null)
            return '';
        $path = $image->image_object->path;

        $exists = false;
        foreach($image->image_object->image_size as $size) {
            if($size->width == $width && $size->height == $height)
                $exists = true;
        }

        if(substr($path,-1) != '/')
            $path = $path.'/';
        $path = '/pub'.(($path[0]=='/')?$path:'/'.$path).(($exists)?$width.'x'.$height:'big').'/'.$image->image_filename;
        $html = '<img alt="'.$image->alt.'" src="'.$path.'" ';
        if(!$exists) {
            $htmlOptions['width'] = $width;
            $htmlOptions['height'] = $height;
        }
        if(is_array($htmlOptions)) {
            foreach($htmlOptions as $key=>$val)
                $html .= $key.'="'.$val.'" ';
        }
        $html .= '/>';
        return $html;
    }

    public function getImage($imageId)
    {
        return Image::model()->findByPk($imageId);
    }
}