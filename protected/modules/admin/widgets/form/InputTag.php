<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/26/12
 * Time: 3:37 PM
 * To change this template use File | Settings | File Templates.
 */
class InputTag extends Input {

	protected $registerPackages = array(
		'inputTag'
	);

	public $tags;

	public $selectedTags;

	public $ulHtmlOptions = array();

	public $handlerOptions = array();

	public static function resolveTags($tags) {
		if (is_array($tags)) {
			return $tags;
		} else if ($tags === null) {
			return array();
		} else if (is_string($tags)) {
			return array_map('trim', explode(',', $tags));
		}
	}

	public static function renderUl($htmlOptions) {
		echo Html::openTag('ul', $htmlOptions);
		echo Html::closeTag('ul');
	}

	public static $i = 0;

	public static function bind($selector, $tagHandlerOptions = array()) {
		if (!isset($tagHandlerOptions['afterAdd'])) {
			$tagHandlerOptions['afterAdd'] = "
			function () {

			}";
		}
		if (!isset($tagHandlerOptions['afterDelete'])) {
			$tagHandlerOptions['afterDelete'] = "
			function () {

			}";
		}
		Yii::app()->clientScript->registerScript('inputTag' . self::$i, "$('{$selector}').tagHandler(" . CJSON::encode($tagHandlerOptions) . ")");
	}

	public function _run() {
		//  render hidden field
		$id = 'inputTag' . self::$i;
		$this->htmlOptions['id'] = $id;
		if ($this->model) {
			echo Html::activeHiddenField($this->model, $this->attribute, $this->htmlOptions);
		} else {
			echo Html::hiddenField($this->attribute, self::resolveTags($this->selectedTags), $this->htmlOptions);
		}
		$this->ulHtmlOptions['element-id'] = $id;
		if (!isset($this->ulHtmlOptions['id'])) {
			$this->ulHtmlOptions['id'] = 'inputTagUl' . self::$i;
		}
		self::renderUl($this->ulHtmlOptions);
		self::bind('ss', $this->handlerOptions);
	}
}
