<?php
/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/19/12
 * Time: 12:29 PM
 * To change this template use File | Settings | File Templates.
 */
class GridRecordsPerPage extends CWidget {

	/**
	 * Default variants of items on page
	 *
	 * @var array
	 */
	protected $default = array(
		10, 15, 25, 50, 100
	);

	/**
	 * @var string
	 */
	public $id;

	/**
	 * @var array|string
	 */
	public $recordsPerPage;

	/**
	 * @var Grid
	 */
	public $grid;

	/**
	 * @var string Name of $_GET var for transfering count records on page
	 */
	public $pageVar;

	/**
	 * Add htmlAttributes for each option in select
	 *
	 * Attributes:
	 *      - href - needed for ajax reloading grid
	 *
	 * @return array
	 */
	protected function makeOptionsAttributes() {
		$attr = array();
		$parameters = $_GET;
		foreach ($this->recordsPerPage as $onPage) {
			$parameters[$this->pageVar] = $onPage;
			$attr[$onPage] = array(
				'href' => $this->grid->createUrl($parameters)
			);
		}
		return $attr;
	}

	protected function registerClientScript() {
		$script =<<<SC
$(document).on('change', "#{$this->getId()}_select", function () {
	$('#{$this->grid->getId()}').yiiGridView('update', {url: $(this).children("option:selected").attr('href')});
});
SC;
		Yii::app()->clientScript->registerScript($this->getId(), $script);
	}

	public function getId($autoGenerate=true) {
		return $this->id;
	}

	public function renderHeader() {
		$id = $this->getId();
		echo <<<HTML
<div id="{$id}" class="dataTables_length pull-right">
	<label>
HTML;
	}

	public function renderFooter() {
		$string = Yii::t('admin', 'Records per page');
		echo <<<HTML
		&nbsp;$string
	</label>
</div>
HTML;
	}

	public function renderBody() {
		if (is_string($this->recordsPerPage)) {
			$this->recordsPerPage = explode(',', $this->recordsPerPage);
			$this->recordsPerPage = array_map('trim', $this->recordsPerPage);
		}
		$this->recordsPerPage = array_combine($this->recordsPerPage, $this->recordsPerPage);
		$id = $this->getId() . '_select';
		echo CHtml::dropDownList($id, $this->grid->getPageSize(), $this->recordsPerPage, array(
			'size' => 1,
			'aria-controls' => 'dt_a',
			'options' => $this->makeOptionsAttributes()
		));
	}

	public function init() {
		parent::init();
		if (!($this->grid instanceof Grid)) {
			throw new CException(Yii::t('admin', 'Widget GridRecordsPerPage: grid is undefined or not instance of Grid'));
		}
		$this->id = $this->grid->getId() . '_' . parent::getId();
		if (!$this->pageVar) {
			$this->pageVar = 'onpage_' . $this->grid->getId();
		}
	}

	public function run() {
		if (!$this->recordsPerPage) {
			$this->recordsPerPage = $this->default;
		}
		$this->registerClientScript();
		$this->renderHeader();
		$this->renderBody();
		$this->renderFooter();
	}
}
