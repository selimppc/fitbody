<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/12/12
 * Time: 11:08 AM
 * To change this template use File | Settings | File Templates.
 *
 * @property BaseController controller
 */
class NavRightMenu extends CWidget {

	/**
	 * @var array
	 */
	public $structure = array();

	/**
	 * Parsed structure string
	 *
	 * @var string
	 */
	protected $_structure = '';

	/**
	 * @param $tag
	 * @param array $htmlOptions
	 */
	protected function open($tag, $htmlOptions = array()) {
		$this->_structure .= CHtml::openTag($tag, $htmlOptions);
	}

	/**
	 * @param $tag
	 */
	protected function close($tag) {
		$this->_structure .= CHtml::closeTag($tag);
	}

	/**
	 * @param $tag
	 * @param $htmlOptions
	 * @param bool $content
	 * @param bool $closeTag
	 */
	protected function tag($tag, $htmlOptions, $content = false, $closeTag = true) {
		$this->_structure .= CHtml::tag($tag, $htmlOptions, $content, $closeTag);
	}

	/**
	 * @param array $a
	 * @param $dropDownExists
	 */
	protected function parseA(array $a, $dropDownExists) {
		$a['class'] = isset($a['class']) ? $a['class'] : '';
		//  if dropdown add class
		if ($dropDownExists) {
			$a['class'] .= ' dropdown-toggle';
			$a['data-toggle'] = 'dropdown';
		}
		//  get image
		$img = isset($a['img']) ? $a['img'] : null;
		//  get icon
		$i = isset($a['i']) ? $a['i'] : null;
		if ($i) {
			//  unset icon from a attributes
			unset($a['i']);
		}
		if ($img) {
			unset($a['img']);
		}
		$titleExists = isset($a['title']);
		$title = $titleExists ? $a['title'] : 'Not defined';
		$title = Yii::t('admin', $title);

		if (isset($a['createUrl']) && is_array($a['createUrl'])) {
			$a['href'] = Yii::app()->createUrl(
				$a['createUrl']['route'],
				isset($a['createUrl']['params']) ? $a['createUrl']['params'] : array(),
				isset($a['createUrl']['ampersand']) ? $a['createUrl']['ampersand'] : '&'
			);
			unset($a['createUrl']);
		} else if (!isset($a['href'])) {
			$a['href'] = 'javascript:;';
		}

		//  create a
		$this->open('a', $a);
		if ($img) {
			if (isset($img['assetUrl']) && $img['assetUrl']) {
				$img['src'] = $this->controller->assetUrl . $img['src'];
			}
			//  insert image
			$this->tag('img', $img);
			if ($titleExists) {
				$this->_structure .= ' ' . $title;
			}
		} else if ($i) {
			//  insert icon
			$this->tag('i', $i, '');
			if ($titleExists) {
				$this->_structure .= ' ' . $title;
			}
		} else {
			//  set title
			$this->_structure .= $title;
		}
		if ($dropDownExists) {
			$this->tag('b', array(
				'class' => 'caret'
			), '');
		}
		//  close
		$this->close('a');
	}

	protected function parseDropDown(array $dropDown) {
		$this->open('ul', array(
			'class' => 'dropdown-menu'
		));
		foreach ($dropDown as $item) {
			$this->parseItem($item);
		}
		$this->close('ul');
	}

	/**
	 * Algoritm:
	 *
	 * parse li
	 *      -> parse a
	 *      -> parse ul (dropdown)
	 *
	 *
	 * @param array $item
	 */
	protected function parseItem(array $item) {
		$liOptions = array_key_exists('li', $item) ? $item['li'] : array();
		if (array_key_exists('divider', $item) && $item['divider']) {
			$this->tag('li', array(
				'class' => 'divider'
			), '');
			return;
		}
		$dropDownExists = array_key_exists('dropDown', $item) && !empty($item['dropDown']);
		if ($dropDownExists) {
			$liOptions['class'] = isset($liOptions['class']) ? $liOptions['class'] : '';
			$liOptions['class'] .= ' dropdown';
		}
		$this->open('li', $liOptions);
		$aOptions = isset($item['a']) ? $item['a'] : array(
			'title' => 'a: not not defined'
		);
		$this->parseA($aOptions, $dropDownExists);
		if ($dropDownExists) {
			$this->parseDropDown($item['dropDown']);
		}
		$this->close('li');
	}

	/**
	 *
	 */
	protected function insertDivider() {
		$this->tag('li', array(
			'class' => 'divider-vertical'
		));
	}

	/**
	 *
	 */
	protected function parseStructure() {
		if (empty($this->structure)) {
			return;
		}
		$count = count($this->structure);
		$i = 0;
		foreach ($this->structure as $item) {
			$i++;
			$this->parseItem($item);
			if ($count > $i) {
				$this->insertDivider();
			}
		}
		//CVarDumper::dump($this->structure, 10 , true);
	}

	protected function output() {
		echo $this->_structure;
	}

	/**
	 *
	 */
	public function run() {
		$this->parseStructure();
		$this->output();
	}
}
