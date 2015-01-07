<?php
Yii::import('application.helpers.CArray');
Yii::import('application.helpers.FileHelper');
Yii::import('application.extensions.image.CImageComponent');
Yii::import('application.components.ImageResize');
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/28/12
 * Time: 2:59 PM
 * To change this template use File | Settings | File Templates.
 */
class IresizeCommand extends BaseCommand {

	/**
	 * hello    world
	 * @var string set key in resize config
	 */
	public $setKey = null;

	/**
	 * Initialize imageResize component
	 *  - create needed folders
	 */
	public function actionInit() {
		FileHelper::makedir(Yii::app()->imageResize->getImagesDirectory());
		foreach (Yii::app()->imageResize->config as $systemKey => $config) {
			ImageResize::getProcessor()->setConfig($config);
			FileHelper::makedir(ImageResize::getProcessor()->getBasePath());
			if (ImageResize::getProcessor()->getVariablesPath() == '') {
				FileHelper::makedir(ImageResize::getProcessor()->getBigPath());
				FileHelper::makedir(ImageResize::getProcessor()->getSizePath());
				foreach ($config['sizes'] as $size => $sizeConfig) {
					FileHelper::makedir(ImageResize::getProcessor()->getSizePath() . $size);
				}
			}
		}
		return 0;
	}

	/**
	 * If $size not defined system delete all set data
	 *
	 * NOTICE: set configuration will deleted from config file, is size exists will deleted only size
	 *
	 * @param string $size if size presented then system delete all folders in set with system key $setKey for current size
	 *
	 * @return int
	 */
	public function actionRm($size = '') {
		$setKey = $this->getGlobalProperty('setKey');
		if ($size) {
			$sizes = ImageResize::getProcessor()->setConfigBySystemKey($setKey)->getSizes();
			if (array_key_exists($size, $sizes)) {
				ImageResize::getProcessor()->deleteSetSize($size);
				$config = ImageResize::instance()->config;
				unset($config[$setKey]['sizes'][$size]);
				ImageResize::updateConfig($config);
			} else {
				$this->error(sprintf("Can not delete image size '%s' because size is unknown", $size));
			}
		} else {
			ImageResize::getProcessor()->deleteSet($setKey);
			$config = ImageResize::instance()->config;
			unset($config[$setKey]);
			ImageResize::updateConfig($config);
		}
		return 0;
	}

	/**
	 * If size is defined then will be created images only for current size using config with set key $setKey and image
	 * processor configuration for presented size
	 *
	 * If size not defined will be recompiled all images for presented set key $setKey
	 *
	 * @param string $size
	 * @return int command status
	 */
	public function actionBuild($size = '') {
		if ($this->setKey === null) {
			$this->error('ERROR: setKey property not defined: use --setKey=system key of the set');
			return 1;
		}
		if ($size) {
			$sizes = ImageResize::getProcessor()->setConfigBySystemKey($this->setKey)->getSizes();
			if (!array_key_exists($size, $sizes)) {
				$this->error("ERROR: undefine size {$size} of set with key {$this->setKey}, see config file in " . Yii::app()->imageResize->configFile);
				return 1;
			}
			ImageResize::processSetSize($this->setKey, $size);
		} else {
			ImageResize::processSet($this->setKey);
		}
		return 0;
	}

	/**
	 * Action repeat build actions
	 *
	 * @see action build
	 * @param string $size
	 */
	public function actionRebuild($size = '') {
		$this->actionBuild($size);
	}
}
