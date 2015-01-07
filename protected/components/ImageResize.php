<?php
Yii::import('application.helpers.CArray');
Yii::import('application.helpers.FileHelper');

/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/27/12
 * Time: 1:05 PM
 * To change this template use File | Settings | File Templates.
 */
/**
 * Class for work with images as with image set
 *
 * Config file description:
 *  1) every thumb set shoold contain unique name
 *      example: 'profile' => array(profile config here)
 *
 *  2) every thumb set should contain next properties
 *      - basePath [ string ], example: {pub}/profile // folder for locating all set images
 *      - bigPath [string], path from baseBath, example: big
 *      - sizePath [string], path from baseBath, example: sizes
 *      - variablesPath [string], path with variables, example: {user}/{portfolio}
 *
 *      Will next folders:
 *
 *      pub/profile/                        - base image folder
 *      pub/profile/big/                    - big folder
 *      pub/profile/big/{user}/{portfolio}  - folder with vars
 *      pub/profile/sizes/                  - base size folder
 *      pub/profile/sizes/{user}/{portfolio}- size folder for user and portfolio
 *
 *  3) sizes
 *      example:
 *      sizes => array(
 *          '200x100' => array(
 *              //  here you can use next syntax
 *              //  you can call any public method in Image class located
 *              //  in application.extensions.image.Image
 *
 *              //  example: i need resize image and rotate it
 *              //  key === method name of Image object
 *              //  value === method parameters, for past parameters into Image::method used call_user_func_array
 *              'resize' => array(
 *                  200,
 *                  100
 *              ),
 *              'rotate' => array(
 *                  -90
 *              )
 *          )
 *      )
 *
 *  4) config example
 *      return array(
 *          'user' => array(
 *              'basePath' => '{pub}/user',
 *              'bigPath' => 'big',
 *              'sizePath' => '',   //  size paths will created in root of basePath
 *              'sizes' => array(
 *                  //  logo
 *                  '60x60' => array(
 *                      'resize' => array(
 *                          60,
 *                          60
 *                      )
 *                  ),
 *                  '150x150' => array(
 *                      'resize' => array(
 *                          150,
 *                          150
 *                      )
 *                  )
 *              )
 *          ),
 *          'gallery' => array(
 *              'basePath' => '{pub}/gallery',
 *              'bigPath' => 'big',
 *              'sizePath' => 'sizes',
 *              'variablesPath' => '{user_id}/{gallery_id}',           //  i want create url like user_id/gallery_id
 *              'sizes' => array(
 *                  ...
 *              )
 *          )
 *      )
 *
 * ----------------------->
 *
 * PLEASE READ MATERIALS
 *      * http://docs.kohanaphp.com/libraries/image
 *      * http://khnfans.cn/docs/libraries/image
 *
 * For generating new sizes or initializing new sizes use console command iresize
 * Open new console window change directory to project protected directory and type
 *
 * ./yiic help iresize
 *
 * If you get error:  Permission denied
 * try execute chmod u+x yiic and try run command below again
 *
 * ----------------------->
 *
 * SAVING PHOTOS EXAMPLES
 *
 *      * save from $_FILES
 *          ImageResize::processUploadedFile('image_filename', 'gallery');
 *          //  or
 *          ImageResize::getProcessor()->setConfigBySystemKey('gallery')->saveUploadedFile(CUploadedFile::getInstanceByName('image_filename'), true)->process();
 *
 *      * save from string file path
 *          ImageResize::processFile('/home/user/cheburashka.jpg', 'gallery');
 *
 *      * save images from folder see glob function
 *          ImageResize::processFolderFiles('glob pattern', 'gallery'); //  $patternExample = '/home/user/images/*.jpg';
 *
 *      * recompile set size
 *          ImageResize::processSetSize('gallery', '200x300');
 *
 *      * recompile set
 *          ImageResize::processSet('gallery'); //  will recompiled all sizes
 *
 *      * delete image
 *          ImageResize::deleteFile($image_filename);   //  $image_filename like 1565121654.jpg
 *
 * ---------------------->
 *
 * Last image filename you gen get as
 *
 *      ImageResize::getProcessor()->fileName;
 *  // or
 *      Yii::app()->imageResize->processor->fileName;
 *  // or
 *      ImageResize::fileName();
 *
 * EXAMPLE:
 *
 *      ImageResize::processUploadedFile('image_filename', 'gallery');
 *      $forSaveImageFileName = ImageResize::getProcessor()->fileName;
 *      // or
 *      $forSaveImageFileName = ImageResize::fileName();
 *
 */
class ImageResize extends CApplicationComponent {

	/**
	 * @var ImageResize
	 */
	protected static $_instance;

	/**
	 * Check if imageResize was created
	 *
	 * @var bool
	 */
	protected static $_isInited = false;

	/**
	 * Folder for public files
	 *
	 * @var string
	 */
	public $pubFolder = 'pub/image';

	/**
	 * @var string path of alias for config file
	 */
	public $configFile = 'application.config.imageResize';

	/**
	 * @var array resize configuration
	 */
	public $config;

	/**
	 * @var ImageResizeProcessor
	 */
	public $processor = 'ImageResizeProcessor';

	/**
	 * @var CImageComponent
	 */
	public $imageComponent;

	/**
	 * Return new random filename with $extension
	 *
	 * @param string $extension
	 *
	 * @return string
	 */
	public static function getRandName($extension = '') {
		$mark = microtime();
		$mark = substr($mark, 11, 11) . substr($mark, 2, 6);
		return strtolower($mark . '.' . $extension);
	}

	/**
	 * Get configuration by property key
	 * Key can be string or array
	 *
	 * @see CArray::assocSearch method for more
	 *
	 * @param $property
	 * @param null $config
	 * @param bool $exceptionOnError
	 *
	 * @return array|null
	 */
	public static function getConfigProperty($property, $config = null, $exceptionOnError = true) {
		$config = $config === null ? Yii::app()->imageResize->config : $config;
		return CArray::assocSearch($property, $config, $exceptionOnError);
	}

	/**
	 * Copy $file to config folder with system key $systemKey and process current file
	 *
	 * If file not exists or it not a file will return error
	 *
	 * @param $file
	 * @param $systemKey
	 * @param array $variables
	 *
	 * @return bool
	 */
	public static function copyFileAndProcess($file, $systemKey, $variables = array()) {
		if (!file_exists($file) || !is_file($file)) {
			return false;
		}
		return self::getProcessor()
				->setConfigBySystemKey($systemKey)
				->setVariables($variables)
				->saveBigFile($file, true)
				->process();
	}

	/**
	 * Process uploaded file
	 * $name associative array key of uploaded file properties $_FILES [ $name ]
	 * $systemKey - configuration key
	 *
	 * @param $name
	 * @param $systemKey
	 * @param array $variables
	 *
	 * @return bool
	 */
	public static function processUploadedFile($name, $systemKey, $variables = array()) {
		$file = CUploadedFile::getInstanceByName($name);
		if ($file === null) {
			return false;
		}
		return self::getProcessor()
				->setConfigBySystemKey($systemKey)
				->setVariables($variables)
				->saveUploadedFile($file, true)
				->process();
	}

	/**
	 * Scan folder using $pattern see glob function here http://php.net/manual/en/function.glob.php and process all finded files
	 *
	 * @see http://php.net/manual/en/function.glob.php for $pattern
	 *
	 * @param $pattern
	 * @param $systemKey
	 * @param array $variables
	 *
	 *
	 * @return array
	 */
	public static function processFolderFiles($pattern, $systemKey, $variables = array()) {
		$result = array();
		self::getProcessor()->setConfigBySystemKey($systemKey)->setVariables($variables);
		foreach (glob($pattern) as $filename) {
			$result[$filename] = self::getProcessor()->saveBigFile($filename, true)->process();
		}
		return $result;
	}

	/**
	 * @param $filename
	 * @param $systemKey
	 * @param array $variables
	 *
	 * @return bool
	 */
	public static function processFile($filename, $systemKey, $variables = array()) {
		return self::copyFileAndProcess($filename, $systemKey, $variables);
	}

	/**
	 * @param $systemKey
	 * @param $size
	 */
	public static function processSetSize($systemKey, $size) {
		return self::getProcessor()->setConfigBySystemKey($systemKey)->processSetSize($size);
	}


	/**
	 * Will be processed all set sizes from big images
	 *
	 * @param string $systemKey set key
	 */
	public static function processSet($systemKey) {
		self::getProcessor()->setConfigBySystemKey($systemKey);
		foreach (self::getProcessor()->getSizes() as $size => $sizeConfig) {
			self::getProcessor()->processSetSize($size);
		}
	}

	/**
	 * @return string last processd image generated filename
	 */
	public static function fileName() {
		return self::getProcessor()->fileName;
	}

	/**
	 *
	 *
	 * @param $filename
	 * @param $systemKey
	 */
	public static function deleteFile($filename, $systemKey) {
		self::getProcessor()->delete($filename, $systemKey);
	}

	/**
	 * Get last processed image filename
	 *
	 * @param bool $clear
	 */
	public static function getFileName($clear = true) {
		self::$_instance->processor->getFileName($clear);
	}

	/**
	 * @return string of image folder
	 */
	public static function getImagesDirectory() {
		if (self::$_instance === null) {
			Yii::app()->imageResize; //  initialize image resizer
			if (self::$_instance === null) {
				throw new CException('ImageResize: initializing error, into getImagesDirectory');
			}
		}
		return FileHelper::formatPath(Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . self::$_instance->pubFolder);
	}

	/**
	 * @return ImageResizeProcessor
	 */
	public static function getProcessor() {
		if (self::$_instance === null) {
			if (self::$_isInited) {
				throw new CException('ImageResize: can not return process because processor is null');
			}
			Yii::app()->imageResize; //  initialize image resizer
			if (self::$_instance === null) {
				throw new CException('ImageResize: initializing error, into getProcessor');
			}
		}
		return self::$_instance->processor;
	}

	/**
	 * @return ImageResize
	 */
	public static function instance() {
		if (self::$_instance === null) {
			Yii::app()->imageResize; //  initialize image resizer
			if (self::$_instance === null) {
				throw new CException('ImageResize: initializing error, into instance');
			}
		}
		return self::$_instance;
	}

	/**
	 * @param $processor
	 */
	public static function setProcessor($processor) {
		self::$_instance->processor = $processor;
	}

	/**
	 * Update config file using $config as new config
	 *
	 * @param array $config
	 *
	 * @return int
	 */
	public static function updateConfig(array $config) {
		$string = CArray::export($config);
		$file =<<<FILE
<?php
return $string
FILE;
		return file_put_contents(self::instance()->configFile, $file);
	}

	/**
	 * Config configuring
	 */
	protected function prepareConfig() {
		foreach ($this->config as $key => $config) {
			$this->config[$key]['basePath'] = FileHelper::formatPath(str_replace('{pub}', Yii::app()->getBasePath() . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . $this->pubFolder, $config['basePath']));
		}
	}

	/**
	 * @throws CException
	 */
	public function init() {
		parent::init();
		$this->configFile = Yii::getPathOfAlias($this->configFile) . '.php';
		if (!file_exists($this->configFile)) {
			throw new CException('Config file not found');
		}
		$this->config = require_once $this->configFile;
		$this->prepareConfig();
		$this->imageComponent = Yii::app()->image;
		if (is_string($this->processor)) {
			$this->processor = Yii::createComponent(array(
				'class' => $this->processor,
				'imageResize' => $this
			));
		}
		self::$_instance = $this;
		self::$_isInited = true;
	}

	/**
	 * @return array
	 */
	public function getConfig() {
		return $this->config;
	}
}

class ImageResizeProcessor extends CComponent {

	/**
	 * @var ImageResize
	 */
	public $imageResize;

	/**
	 * @var string name of last processed file
	 */
	public $fileName;

	/**
	 * @var Image
	 */
	protected $image;

	/**
	 * @var array
	 */
	protected $config;

	/**
	 * @var array
	 */
	protected $variables = array();

	/**
	 * @param $path
	 *
	 * @return mixed|string
	 */
	protected function resolvePath($path) {
		if (!$path) {
			return '';
		}
		foreach ($this->variables as $key => $value) {
			$path = str_replace('{' . $key . '}', $value, $path);
		}
		return FileHelper::formatPath($path);
	}

	/**
	 * @param Image $image
	 * @param $size
	 *
	 * @return string
	 */
	protected function getImageFilePath(Image $image, $size, $resolvedPath = true) {
		$path = $this->getSizePath($resolvedPath) . $size . '/';
		FileHelper::makedir($path);
		return $path . basename($image->file);
	}

	/**
	 * @param $method
	 * @param $parameters
	 *
	 * @return bool
	 */
	protected function beforeApplyMethod($method, & $parameters) {
		return true;
	}

	/**
	 * @param bool $clear
	 *
	 * @return mixed
	 */
	public function getFileName($clear = true) {
		$temp = $this->fileName;
		if ($clear) {
			$this->fileName = null;
		}
		return $temp;
	}

	/**
	 * $chain is a config of one size
	 *
	 * @example of $chain
	 *          array(
	 *      'resize' => array(
	 *          //  resize parameters
	 *      ),
	 *      'rotate' => array(
	 *          //  rotate parameters
	 *      )
	 * )
	 *
	 * @param array $chain
	 * @param Image $image
	 *
	 * @return void
	 */
	public function applyMethodChain(array $chain, Image $image) {
		foreach ($chain as $method => $parameters) {
			$this->applyMethod($method, $parameters, $image);
		}
	}

	/**
	 * Apply method for current object image or $image
	 *
	 * method can be rotate, resize ...
	 *
	 * @param $method
	 * @param array $parameters
	 * @param Image $image
	 *
	 * @return bool|mixed
	 */
	public function applyMethod($method, array $parameters, Image $image = null) {
		$image = $image === null ? $this->image : $image;
		if (method_exists($image, $method)) {
			if ($this->beforeApplyMethod($method, $parameters)) {
				return call_user_func_array(array($image, $method), $parameters);
			}
		}
		return false;
	}

	/**
	 * @param array $sizes
	 */
	public function processImageSizes(array $sizes) {
		foreach ($sizes as $size => $sizeChain) {
			$_image = clone $this->image;
			$this->applyMethodChain($sizeChain, $_image);
			$_image->save($this->getImageFilePath($_image, $size));
			unset($_image);
		}
	}

	/**
	 * @return array of set sizes configuration
	 */
	public function getSizes() {
		return $this->config['sizes'];
	}

	/**
	 * @param Image $image
	 * @param null $systemKey
	 *
	 *
	 * @return bool
	 */
	public function process(Image $image = null, $systemKey = null) {
		if ($image !== null) {
			$this->setImage($image);
		}
		if ($systemKey !== null) {
			$this->setConfigBySystemKey($systemKey);
		}
		$this->processImageSizes($this->getSizes());
		return true;
	}

	/**
	 * Create processed images from big for $size
	 *
	 * @param string $size key of size for processing
	 */
	public function processSetSize($size) {
		//  get config for new size
		$sizes = $this->getSizes();
		//  recursivelly create folders and processing files to new size
		return $this->_bigToSize($this->getBigPath(), $size, $sizes[$size]);
	}

	/**
	 * @param $bigFolder
	 * @param $size
	 * @param $sizeConfig
	 */
	private function _bigToSize($bigFolder, $size, $sizeConfig) {
		$bigFolder = FileHelper::formatPath($bigFolder);
		$items = scandir($bigFolder);
		foreach ($items as $item) {
			if ($item == '.' || $item == '..') {
				continue;
			}
			if (is_dir($bigFolder . $item)) {
				FileHelper::makedir($this->getSizePath(false) . FileHelper::formatPath(str_replace($this->getBigPath(), '', $bigFolder . $item)));
				$this->_bigToSize($bigFolder . $item, $size, $sizeConfig);
			} else if (is_file($bigFolder . $item)) {
				$this->setImageFromString($bigFolder . $item);
				$_image = clone $this->image;
				$this->applyMethodChain($sizeConfig, $_image);

				//  define new image folder
				//  sizePath + folder in big folders (resolve problem unexisting vars) + size
				$path = $this->getSizePath(false) . FileHelper::formatPath(str_replace($this->getBigPath(), '', $bigFolder)) . $size . '/';
				FileHelper::makedir($path);
				$_image->save($path . basename($_image->file));
				unset($_image);
			}
		}
	}

	/**
	 * Delete image file
	 *
	 * @param string $filename
	 * @param string|null $systemKey
	 */
	public function delete($filename, $systemKey = null) {
		if ($systemKey !== null) {
			$this->setConfigBySystemKey($systemKey);
		}
		FileHelper::deleteFile($this->getBasePath(), $filename);
	}

	/**
	 * Delete image set
	 *
	 * @param string|null $systemKey
	 */
	public function deleteSet($systemKey = null) {
		if ($systemKey) {
			$this->setConfigBySystemKey($systemKey);
		}
		FileHelper::deleteDirectory($this->getBasePath());
	}

	/**
	 * Delete size
	 *
	 * @param string $size
	 * @param string|null $systemKey
	 */
	public function deleteSetSize($size, $systemKey = null) {
		if ($systemKey) {
			$this->setConfigBySystemKey($systemKey);
		}
		$folder = $this->getSizePath(false);
		FileHelper::deleteDirectoryInFolder($folder, $size);
	}

	/**
	 * Save file located in $filename as a big file
	 *
	 * @param string $filename
	 * @param bool $loadFile
	 * @param bool $randomFileName
	 *
	 * @return bool|ImageResizeProcessor|string
	 */
	public function saveBigFile($filename, $loadFile = false, $randomFileName = true) {
		$newFileName = $randomFileName ? $this->imageResize->getRandName(FileHelper::getExtension($filename)) : basename($filename);
		$this->fileName = $newFileName;
		$file = $this->getBigPath() . $newFileName;
		if (copy($filename, $file)) {
			if ($loadFile) {
				return $this->setImageFromString($file);
			}
			return $file;
		}
		return false;
	}

	/**
	 * Save uploaded file as big file
	 *
	 * @param CUploadedFile $file
	 * @param bool $loadFile       if is true file will loaded as image for manipulations
	 * @param bool $randomFileName if is false then filename will not changed
	 *
	 * @return bool|ImageResizeProcessor|string
	 */
	public function saveUploadedFile(CUploadedFile $file, $loadFile = false, $randomFileName = true) {
		if ($randomFileName) {
			$newFileName = $this->imageResize->getRandName($file->getExtensionName());
		} else {
			$newFileName = $file->getName();
		}
		$this->fileName = $newFileName;
		$_file = $this->getBigPath() . $newFileName;
		if ($file->saveAs($_file)) {
			if ($loadFile) {
				return $this->setImageFromString($_file);
			}
			return $_file;
		}
		return false;
	}

	/**
	 * Return image base path
	 *
	 * @return string
	 * @throws CException
	 */
	public function getBasePath() {
		if (!isset($this->config['basePath'])) {
			throw new CException('ImageResizeProcessor: config property basePath not found');
		}
		return $this->config['basePath'];
	}

	/**
	 * Return image big path
	 *
	 * @return mixed
	 * @throws CException
	 */
	public function getBigPath() {
		if (!isset($this->config['bigPath'])) {
			throw new CException('ImageResizeProcessor: config property bigPath not found or config not defined');
		}
		$path = $this->getBasePath() . $this->resolvePath($this->config['bigPath']) . $this->getVariablesPath();
		FileHelper::makedir($path);
		return $path;
	}

	/**
	 * Return image size path
	 *
	 * @return mixed
	 * @throws CException
	 */
	public function getSizePath($resolved = true) {
		if (!isset($this->config['sizePath'])) {
			throw new CException('ImageResizeProcessor: config property sizePath not found');
		}
		if ($resolved) {
			$path = $this->getBasePath() . $this->resolvePath($this->config['sizePath']) . $this->getVariablesPath();
		} else {
			$path = $this->getBasePath() . FileHelper::formatPath($this->config['sizePath']);
		}
		FileHelper::makedir($path);
		return $path;
	}

	public function getVariablesPath() {
		if (isset($this->config['variablesPath'])) {
			return $this->resolvePath($this->config['variablesPath']);
		}
		return '';
	}

	/**
	 * Set config for current image
	 *
	 * @param array $config
	 *
	 * @return ImageResizeProcessor
	 */
	public function setConfig(array $config) {
		$this->config = $config;
		return $this;
	}

	/**
	 * Set config by system key
	 *
	 * @param $systemKey
	 *
	 * @return ImageResizeProcessor
	 */
	public function setConfigBySystemKey($systemKey) {
		$this->config = ImageResize::getConfigProperty($systemKey);
		return $this;
	}

	/**
	 * @param array $variables
	 *
	 * @return ImageResizeProcessor
	 */
	public function setVariables(array $variables) {
		$this->variables = $variables;
		return $this;
	}

	/**
	 * @param string $filename
	 *
	 * @return ImageResizeProcessor
	 */
	public function setImageFromString($filename) {
		$this->image = Image::factory($filename);
		return $this;
	}

	/**
	 * Set image for manipulations
	 *
	 * @param Image $image
	 *
	 * @return ImageResizeProcessor
	 */
	public function setImage(Image $image) {
		$this->image = $image;
		return $this;
	}
}