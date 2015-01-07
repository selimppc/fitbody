<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/23/12
 * Time: 3:40 PM
 * To change this template use File | Settings | File Templates.
 */
class Html extends CHtml {

	protected static $_nm = array();

	/**
	 *
	 * @see https://github.com/ioncache/Tag-Handler
	 * @param array $htmlOptions
	 */
	public static function inputTag($attribute, $tags = array(), $htmlOptions = array(), $handlerOptions = array()) {
		return;
		echo self::hiddenField($htmlOptions['name'], '', $htmlOptions);
		echo '<ul id="array_tag_handler"></ul>';
		$handlerOptions['availableTags'] = array(
			'test1',
			'test2',
			'test3',
			'test4',
		);
		self::bindTagHandler($handlerOptions);

	}

	/**
	 * @see https://github.com/ioncache/Tag-Handler
	 */
	public static function bindTagHandler($handlerOptions = array()) {
		Yii::app()->clientScript->registerPackage('inputTag');
		$handlerOptions['autocomplete'] = true;
		echo CJSON::encode($handlerOptions);
		Yii::app()->clientScript->registerScript('aa', '$("#array_tag_handler").tagHandler(' . CJSON::encode($handlerOptions) . ')');
	}

	/**
	 * @param $selector
	 * @param array $wysiwgOptions
	 */
	public static function bindWysiwg($selector = 'textarea', $wysiwgOptions = array()) {
		static $i = 0;
		if ($i > 0) {
			return;
		}
		Yii::app()->clientScript->registerPackage('wysiwg');
		$options = array(
			'mode' => "textareas",
			'theme_advanced_resizing' => false,
			'font_size_style_values' => '8pt,10px,12pt,14pt,18pt,24pt,36pt',
			'plugins' => "autoresize,style,table,advhr,advimage,advlink,emotions,inlinepopups,preview,media,contextmenu,paste,fullscreen,noneditable,xhtmlxtras,template,advlist"
		);
		Yii::app()->clientScript->registerScript("wysiwg" . $i, "tinymce.init(" . CJSON::encode($options) . ");");
	}

	/**
	 * Bind mask for input with javascript $selector and $maskOptions
	 *
	 * $maskOptions you can see here https://github.com/RobinHerbots/jquery.inputmask
	 *
	 * @param $selector
	 * @param array $maskOptions
	 *
	 * @see https://github.com/RobinHerbots/jquery.inputmask
	 */
	public static function bindInputMask($selector, $maskOptions = array()) {
		Yii::app()->clientScript->registerPackage('inputMasked');
		Yii::app()->clientScript->registerScript('inputBind.' . $selector, "
$('$selector').inputmask(" . CJSON::encode($maskOptions) . ");
		");
	}

	/**
	 * Bind spinner for input with $selector and $spinnerOptions
	 *
	 * $spinnerOptions you can see here http://api.jqueryui.com/spinner/
	 *
	 * @param $selector
	 * @param array $spinnerOptions
	 *
	 * @see http://api.jqueryui.com/spinner/
	 */
	public static function bindInputSpinner($selector, $spinnerOptions = array()) {
		Yii::app()->clientScript->registerPackage('inputSpinner');
		Yii::app()->clientScript->registerScript('inputBind.' . $selector, "$('$selector').spinner(" . CJSON::encode($spinnerOptions) . ");");
	}
}
