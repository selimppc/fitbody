<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/13/12
 * Time: 1:32 PM
 * To change this template use File | Settings | File Templates.
 */
class Pager extends CLinkPager {

	/**
	 * @var object
	 */
	public $aggregator;

	/**
	 * @var IDataProvider the data provider for the view.
	 */
	public $dataProvider;

	/**
	 * @var string
	 */
	public $previousPageCssClass = 'prev';

	/**
	 * @var string
	 */
	public $hiddenPageCssClass = 'disabled';

	/**
	 * @var string
	 */
	public $selectedPageCssClass = 'active';

	/**
	 * @var string
	 */
	public $pagerCssClass = 'adminPager';

	/**
	 * @var
	 */
	public $summary;

	/**
	 * Class for main div container
	 *
	 * @var string
	 */
	public $class;

	/**
	 * @var array
	 */
	protected $_buttons;

	protected function getParam($key) {
		if (property_exists($this, $key) && $this->{$key}) {
			return $this->{$key};
		}
		if (is_object($this->aggregator) && property_exists($this->aggregator, $key)) {
			if (property_exists($this, $key)) {
				return $this->$key = $this->aggregator->{$key};
			} else {
				return $this->aggregator->{$key};
			}

		}
		return null;
	}

	/**
	 * Drav header
	 */
	public function drawHeader() {
		echo <<<HTML
<div class="dataTables_paginate paging_bootstrap_alt pagination {$this->class}">
    <ul>
HTML;

	}

	/**
	 * Drav body
	 */
	public function drawBody() {
		echo implode("\n", $this->_buttons);
	}

	/**
	 * Drav footer
	 */
	public function drawFooter() {
		echo <<<HTML
   </ul>
</div>
HTML;

	}

	/**
	 * @param $buttons
	 */
	public function draw($buttons) {
		$this->_buttons = $buttons;
		$this->drawHeader();
		$this->drawBody();
		$this->drawFooter();
	}

	public function run() {
		$this->maxButtonCount = 5;
		$this->nextPageLabel = 'next';
		$this->lastPageLabel = 'last';
		$this->prevPageLabel = 'previous';
		$this->firstPageLabel = 'first';
		$buttons=$this->createPageButtons();
//		Debug::print_die($buttons);
		if(empty($buttons))
			return;

		$this->draw($buttons);
	}
}
