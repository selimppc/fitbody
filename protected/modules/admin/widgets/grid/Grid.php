<?php
Yii::import('zii.widgets.grid.CGridView');
Yii::import('zii.widgets.grid.CDataColumn');
Yii::import('zii.widgets.grid.CLinkColumn');
Yii::import('zii.widgets.grid.CButtonColumn');
Yii::import('zii.widgets.grid.CCheckBoxColumn');

/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/14/12
 * Time: 10:56 AM
 * To change this template use File | Settings | File Templates.
 */
class Grid extends CGridView {

	/**
	 * @var link
	 */
	protected $gridSession;

	public $multiple = true;

	public $showSelectPerPage = true;

	/**
	 * @var string grid title, displayed on top left position if defined
	 */
	public $caption;

	/**
	 * @var object
	 */
	public $aggregator;

	/**
	 * @var int
	 */
	public $size;

	/**
	 * @var CDbCommand
	 */
	public $command;

	/**
	 * @var CDbCommand
	 */
	public $countCommand;

	/**
	 * If $command not defined system will be use $model for constructing
	 * command
	 *
	 * @var CActiveRecord
	 */
	public $model;

	/**
	 * Grid template
	 *
	 * @var string
	 */
	public $template = 'admin.widgets.views.grid.template';

	/**
	 * @var CPagination
	 */
	public $pagination;

	/**
	 * Widget class
	 *
	 * @var CBasePager
	 */
	public $pager   = 'admin.widgets.grid.Pager';

	/**
	 * Widget pager options for configuration
	 *
	 * @var array
	 */
	public $pagerOptions = array();

	/**
	 * @var string
	 */
	public $pagerCssClass = 'adminPager';

	/**
	 * @var bool
	 */
	public $enableFilter = false;

	/**
	 * Filter widget class
	 *
	 * @var string
	 */
	public $filter  = 'admin.widgets.Filter';

	/**
	 * Options will be configure filter widget
	 * See widget class
	 *
	 * @var array
	 */
	public $filterOptions = array();

	/**
	 * Count rows in grid
	 *
	 * @var int
	 */
	public $pageSize = 15;

	/**
	 * @var string tells what object will be process records per page
	 */
	public $recordsPerPageClass = 'admin.widgets.grid.GridRecordsPerPage';

	/**
	 * @var array define variants of items on page
	 * @example array(10, 15, 20, 40, 60, 70)
	 *
	 * @default see GridRecordsPerPage::$recordsPerPage
	 */
	public $recordsPerPage;


	public $storeLastPage = true;

	public $storePageSize = true;

	public $storeSorting = true;

	public $updateUri;

	public $addUri;

	public $primaryKey = 'id';

	public $toRequest = array();

	/**
	 * @var string
	 */
	public $recordsPerPageVar = 'onpage_{id}';

	/**
	 * @var bool if is true $defaultSorting config will be merged with current client sort config
	 */
	public $mergeDefaultSorting = false;

	/**
	 * Default sorting configuration
	 *
	 * IF string:
	 *      -   id asc, title desc
	 * IF array:
	 *      - assoc : array('id' => 'asc', 'title' => 'desc')
	 *      - list : array('id asc', 'title desc')
	 *
	 *
	 * @var string|array
	 */
	public $defaultSorting = '';

	/**
	 * @var bool if is true than user can sort data with couple fields, else only one
	 */
	public $useMultiSort = false;

	/**
	 * @var string define $_GET page var for transfering sort configuration
	 */
	public $sortPageVar = 'sort{id}';

	public $editable = false;

	public $tableAttributes = array(
		'class' => 'table table-striped table-bordered dTableR dataTable',
		//'class' => 'table table-striped',
		'hidden-id' => 'dt_a',
		'id' => null
	);

	public $actions = array(
		'add','edit','delete'
	);

	/**
	 * @var GridEditConfig contain edit options as key(option name), value (option value)
	 * If you use any Input column you can set url and action for update row value
	 */
	public $editConfig;

	/**
	 * @var mixed valid callable call PHP
	 * @see call_user_func
	 */
	public $onDeleteSelected;

	/**
	 * Find and return property in current object or in aggregator object
	 *
	 * Aggregator object it object who init current grid
	 *
	 * @param string $key
	 *
	 * @return mixed|null
	 */
	public function getParam($key) {
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
	 * Set param into session for current grid
	 *
	 * @param $key
	 * @param $value
	 * @param bool $rewrite
	 *
	 * @return bool
	 */
	protected function sessionSet($key, $value, $rewrite = true) {
		$keyExists = array_key_exists($key, $this->gridSession);
		if (!$keyExists || ($keyExists && $rewrite)) {
			$this->gridSession[$key] = $value;
			return true;
		}
		return false;
	}

	/**
	 * Get params from session for current grid
	 *
	 * @param $key
	 * @param null $default
	 *
	 * @return null
	 */
	protected function sessionGet($key, $default = null) {
		return array_key_exists($key, $this->gridSession) ? $this->gridSession[$key] : $default;
	}

	/**
	 * @var string
	 */
	protected $sessionId;

	/**
	 * @return string id of session
	 */
	protected function createSessionId() {
		$this->sessionId = md5(Yii::app()->controller->module->getId() . Yii::app()->controller->getId() . $this->getId());
		return $this->sessionId;
	}

	/**
	 * Create CDbCommand object
	 * and fill select table where
	 */
	protected function createCommand() {
		$this->command = Yii::app()->db->createCommand();
		$columns = array();
		foreach ($this->getParam('columns') as $key => $column) {
			if (is_object($column)) {
				$columns[] = $column->name;
			} else {
				$chunk = explode(':', !is_array($column) ? $column : $column['name']);
				$columns[] = $chunk[0];
			}
		}
		$this->command->select($columns)->from($this->getParam('model')->tableName());
	}

	/**
	 * Create data provider
	 */
	protected function createDataProvider() {
//		$this->command->setJoin('
//								LEFT JOIN admin_user_role ON admin_user_role.backend_user_id = backend_user.backend_user_id
//								LEFT JOIN role ON role.role_id = backend_user_role.role_id'
//		);
//		Debug::print_die($this->command);
//		$this->dataProvider = new CArrayDataProvider($this->command->queryAll());

	}

	/**
	 * @return int|mixed|null
	 */
	public function getPageSize() {
		$pageSize = $this->getParam('pageSize');
		if (isset($_GET[$this->recordsPerPageVar])) {
			$pageSize = $_GET[$this->recordsPerPageVar];
			if ($this->storePageSize) {
				$this->sessionSet('pageSize', $pageSize);
			}
		}
		return $this->storePageSize ? $this->sessionGet('pageSize', $pageSize) : $pageSize;
	}

	/**
	 * Build count command.
	 * Set pagination item count
	 * Set command limit
	 */
	protected function setCommandLimit() {
		$this->countCommand = clone $this->command;
		$this->countCommand->select('COUNT(*) AS count');
		$this->getPagination()->setItemCount($this->getItemCount());
		$limit = $this->getPageSize();
		$this->command->limit($limit, $this->getPagination()->getCurrentPage() * $limit);
	}

	protected function setCommandConditions() {

	}

	/**
	 * Count all items without limits
	 *
	 * @var int
	 */
	protected $itemCount;

	/**
	 * Return total items
	 *
	 * If exists countCommand than execute it and get total items count, else will be used
	 * dataProvider method getTotalItemCount
	 *
	 * @return int|mixed
	 */
	protected function getItemCount() {
		if ($this->itemCount === null) {
			$this->itemCount = $this->countCommand ? $this->countCommand->queryScalar() : $this->getDataProvider()->getTotalItemCount();
		}
		return $this->itemCount;
	}

	/**
	 * @return CDataProvider
	 */
	protected function getDataProvider() {
		if ($this->getParam('command') === null) {
			if ($this->getParam('model') === null) {
				throw new CException(Yii::t('admin', 'Widget grid: dataProvider, command and table is null'));
			}
			$this->createCommand();
		}
		$this->setCommandLimit();
		$this->setCommandSort();
		$this->setCommandConditions();
		$this->createDataProvider();
		$this->dataProvider->setPagination($this->getPagination());
		return $this->dataProvider;
	}

	/**
	 * @return CPagination
	 */
	protected function getPagination() {
		if ($this->pagination === null) {
			$this->pagination = new Pagination();
			$this->pagination->pageVar = 'page' . $this->getId();
			$this->pagination->pageSize = $this->getPageSize();
			if ($this->storeLastPage) {
				if (isset($_GET[$this->pagination->pageVar])) {
					$this->sessionSet('page', $_GET[$this->pagination->pageVar]);
				} else if ($page = $this->sessionGet('page')) {
					$_GET[$this->pagination->pageVar] = $page;
				}
			}
		}
		return $this->pagination;
	}

	protected function setCommandSort() {
		$order = $this->getSortOrder();
		if ($order) {
			$this->command->setOrder($order);
		}
	}

	/**
	 * @var array contain sort configuration, structured as associated array, key is column name, value is sort order
	 */
	protected $sortDetails = array();

	/**
	 * @return string make sort string from $_GET parameters and default parameter
	 */
	protected function getSortOrder() {
		$sortConfig = $this->defaultSorting;
		$empty = empty($sortConfig);
		if (is_string($sortConfig) && !$empty) {
			$tmp = array();
			$chunk = explode(',', $sortConfig);
			foreach ($chunk as $part) {
				$chunk2 = explode(' ', trim($part));
				$tmp[$chunk2[0]] = isset($chunk2[1]) ? $chunk2[1] : 'asc';
			}
			$sortConfig = $tmp;
			unset($tmp);
		} else if (is_array($sortConfig) && !$empty) {
			$tmp = array();
			foreach ($sortConfig as $key => $value) {
				if (is_numeric($key)) {
					$chunk2 = explode(' ', trim($value));
					$tmp[$chunk2[0]] = isset($chunk2[1]) ? $chunk2[1] : 'asc';
				} else {
					$tmp[$key] = $value;
				}
			}
			$sortConfig = $tmp;
			unset($tmp);
		}
		if (isset($_GET[$this->sortPageVar])) {
			$clientSorting = $this->parseSortConfiguration($_GET[$this->sortPageVar]);
			$sortConfig = $this->mergeDefaultSorting ? array_merge($sortConfig, $clientSorting) : $clientSorting;
		} else if ($this->storeSorting) {
			$sortConfig = $this->sessionGet('sorting', $sortConfig);
		}
		if (!$sortConfig) {
			return false;
		}
		if ($this->storeSorting) {
			$this->sessionSet('sorting', $sortConfig);
		}
		$result = array();
		foreach ((array)$sortConfig as $field => $order) {
			$this->sortDetails[$field] = $order;
			$result[] = $field . ' ' . $order;
		}
		return implode(', ', $result);
	}

	/**
	 * Parse client sort parameters and return associated array
	 * parameter was transfered as "field1[order1],field2[order2]"
	 *
	 * @param $sortString
	 *
	 * @return array
	 */
	protected function parseSortConfiguration($sortString) {
		if (preg_match_all('/([\w]+)\[(asc|desc)\]/', $sortString, $matches)) {
			$assoc = array();
			foreach ($matches[1] as $key => $field) {
				$assoc[$field] = $matches[2][$key];
			}
			return $assoc;
		}
		return array();
	}

	protected function makeSortUrl(BaseColumn $column) {
		static $isSortingActive;
		if ($isSortingActive === null) {
			$isSortingActive = array_key_exists($this->sortPageVar, $_GET);
		}
		$parameters = $_GET;
		$currentOrder = array_key_exists($column->name, $this->sortDetails) ? $this->sortDetails[$column->name] : false;
		$order = $currentOrder == 'asc' ? 'desc' : 'asc';
		if ($this->useMultiSort && $column->useMultiSort) {
			if ($currentOrder) {
				$parameters[$this->sortPageVar] = str_replace(sprintf('%s[%s]', $column->name, $currentOrder), sprintf('%s[%s]', $column->name, $order), $parameters[$this->sortPageVar]);
			} else {
				if ($isSortingActive) {
					$parameters[$this->sortPageVar] .= sprintf(',%s[%s]', $column->name, $order);
				} else {
					$parameters[$this->sortPageVar] = sprintf('%s[%s]', $column->name, $order);
				}
			}
		} else {
			$parameters[$this->sortPageVar] = sprintf('%s[%s]', $column->name, $order);
		}
		$url = Yii::app()->createUrl(Yii::app()->controller->module->getId() . '/' . Yii::app()->controller->getId() . '/' . Yii::app()->controller->getAction()->getId(), $parameters);
		return $url;
	}

	/**
	 * Creating grid url for $parameters
	 *
	 * @param $parameters
	 *
	 * @return string
	 */
	public function createUrl($parameters) {
		return Yii::app()->createUrl(Yii::app()->controller->module->getId() . '/' . Yii::app()->controller->getId() . '/' . Yii::app()->controller->getAction()->getId(), $parameters);
	}

	public function createEditUrl() {
		$this->updateUri = ($this->updateUri) ? $this->updateUri : Yii::app()->createUrl(Yii::app()->controller->module->getId() . '/' . Yii::app()->controller->getId() . '/update', array());
		$this->addUri = ($this->addUri) ? $this->addUri : Yii::app()->createUrl(Yii::app()->controller->module->getId() . '/' . Yii::app()->controller->getId() . '/add', array());
	}

	public function registerClientScript() {
		$this->beforeAjaxUpdate = new CJavaScriptExpression("
function (id, options) {
	grid.get(id).yiiGridBeforeUpdate(id, options);
}
		");

		$this->afterAjaxUpdate = new CJavaScriptExpression("
function (id, options) {
	grid.get(id).yiiGridAfterUpdate(id, options);
	setEventsOnGridRows();
}
		");
		Yii::app()->clientScript->registerPackage('grid');
		Yii::app()->clientScript->registerScript($this->id . 'inputColumn', "
			grid.create({$this->fetchClientGridInputConfig()});
		");
		$onclickCallBack = "
		function setEventsOnGridRows() {
			$('#table{$this->id} input[name^=autoId]').each(function () {
				$(this).click(function (e) {
					e = e || window.event;
					var el = e.target || e.srcElement;
					if($(el).parents('td').hasClass('group-checkbox-column')) e.stopPropagation();
				});
			});
		}
		setEventsOnGridRows();
		";
		Yii::app()->clientScript->registerScript($this->id . 'checkedRows', $onclickCallBack);
	}

	protected function registerEditableScript() {
		$this->widget('admin.widgets.grid.GridPopup',array(
			'id'        => $this->id,
			'addUri'    => $this->addUri,
			'columns'   => $this->columns,
			'model'     => $this->model,
			'toRequest' => $this->toRequest
		));
	}

	protected function fetchClientGridInputConfig() {
		$config = array(
			//  set grid id
			'id' => $this->id,
			//  set table id
			'tid' => $this->tableAttributes['id'],
			//  set model
			'model' => get_class($this->model),
			//  set url
			'url' => '',
			// set multiple flag
			'multiple' => (int)$this->multiple,
			// set update uri
			'update_uri' => $this->updateUri,
			// set editable
			'editable' => $this->editable,
			// set flag edit_action
			'edit_action' => (int)in_array('edit',$this->actions)
		);
		return CJavaScript::encode($config);
	}

	/**
	 * Draw grid
	 */
	public function renderGrid() {
		$this->openTable();
		$this->renderTableHeader();
		$this->renderTableBody();
		$this->renderTableFooter();
		$this->closeTable();
	}

	/**
	 * Renders the table header.
	 */
	public function renderTableHeader() {
		if(!$this->hideHeader) {
			echo "<thead>\n";
			if($this->filterPosition===self::FILTER_POS_HEADER) {
				$this->renderFilter();
			}
			echo "<tr>\n";
			foreach($this->columns as $i => $column) {
				$column->headerHtmlOptions['column'] = $column->name;
				if (array_key_exists($column->name, $this->sortDetails)) {
					if (method_exists($column, 'sortedWithOrder')) {
						$column->sortedWithOrder($this->sortDetails[$column->name]);
					}
				}
				if ($column instanceof BaseColumn && $column->sortable) {
					$this->updateSelector .= sprintf(', #%s #%s', $this->getId(), $column->id);
					$column->headerHtmlOptions['href'] = $this->makeSortUrl($column);
				}
				$column->renderHeaderCell();
			}
			echo "</tr>\n";
			if($this->filterPosition===self::FILTER_POS_BODY) {
				$this->renderFilter();
			}
			echo "</thead>\n";
		} else if ($this->filter!==null && ($this->filterPosition===self::FILTER_POS_HEADER || $this->filterPosition===self::FILTER_POS_BODY)) {
			echo "<thead>\n";
			$this->renderFilter();
			echo "</thead>\n";
		}
	}

	/**
	 * Renders a table body html.
	 * @param integer $row the html number (zero-based).
	 */
	public function renderTableRow($row) {
		if($this->rowHtmlOptionsExpression!==null) {
			$data=$this->dataProvider->data[$row];
			$class=$this->evaluateExpression($this->rowHtmlOptionsExpression,array('html'=>$row,'data'=>$data));
		} else if(is_array($this->rowCssClass) && ($n=count($this->rowCssClass))>0) {
			$class=$this->rowCssClass[$row%$n];
		} else {
			$class='';
		}
		$class = $class.' rowlink';
		$trOptions = array(
			'class'     => $class,
			'onclick'   => '
				var event = arguments[0] || window.event;
				var el = event.target || event.srcElement;
				if($(el).attr("stoppropagation")==undefined && !$(el).parents("*[stoppropagation=true]").length)
					grid.get("'.$this->id.'").selectRow(this);'
		);
		if (isset($this->dataProvider->data[$row]['id'])) {
			$trOptions['data-id'] = $this->dataProvider->data[$row]['id'];
		}
		echo CHtml::openTag('tr', $trOptions);
		foreach($this->columns as $column) {
			$column->renderDataCell($row);
		}
		echo CHtml::closeTag('tr') . "\n";
	}

	/**
	 * Open table tag
	 */
	protected function openTable() {
		if (!isset($this->tableAttributes['id']) || !$this->tableAttributes['id']) {
			$this->tableAttributes['id'] = 'table' . $this->getId();
		}
		$this->tableAttributes['class'] .= ' ' . $this->itemsCssClass;
		echo CHtml::openTag('table', $this->tableAttributes);
	}

	/**
	 * Close
	 */
	protected function closeTable() {
		echo CHtml::closeTag('table');
	}

	protected function handleDeleteSelected() {
		if (Yii::app()->request->isAjaxRequest) {
			if (isset($_POST['ajax']) && $_POST['ajax'] == $this->getId()) {
				if (!isset($_POST['rt']) || $_POST['rt'] != 'deleteSelected') {
					return;
				}
				if ($this->onDeleteSelected) {
					$result = call_user_func($this->onDeleteSelected);
				} else {
					$result = array(
						'error' => true,
						'errorMsg' => 'Unknown error'
					);
					if (isset($_POST['ids'])) {
						$ids = CJavaScript::jsonDecode($_POST['ids']);
						if (is_array($ids)) {
							if ($this->model->deleteByPk($ids)) {
								unset($result['error']);
								unset($result['errorMsg']);
							}
						}
					}
					$result = CJSON::encode($result);
				}
				echo $result;
				Yii::app()->end();
			}
		}
	}

	/**
	 * Renders the summary text.
	 */
	public function renderSummary() {
		$this->dataProvider->setTotalItemCount($this->getItemCount());
		parent::renderSummary();
	}

	/**
	 * Draw pager
	 */
	public function renderPager() {
		$this->pagerOptions['dataProvider'] = $this->getParam('dataProvider');
		$this->getPagination()->setItemCount($this->getItemCount());
		$this->pagerOptions['pages'] = $this->getPagination();
		$this->pagerOptions['aggregator'] = $this;
		$this->pagerOptions['class'] = $this->pagerCssClass;
		$this->widget($this->pager, $this->pagerOptions);
	}

	/**
	 * Render items per page variants
	 */
	public function renderRecordsPerPage() {
		$this->widget($this->recordsPerPageClass, array(
			'grid' => $this,
			'recordsPerPage' => $this->recordsPerPage
		));
	}

	/**
	 * Draw filter
	 */
	public function renderFilter() {
		//$this->filterOptions['grid'] = $this;
		//$this->widget($this->filter, $this->filterOptions);
	}


	protected function initGridSession() {
		$_SESSION['grid'][$this->sessionId] = array();
	}

	private $editableColumnsConfig = array();

	protected function initColumns() {
		foreach($this->columns as $key => $val) {
			foreach($val as $confKey => $confVal) {
				if($confKey == 'align') {
					unset($this->columns[$key][$confKey]);
					if(!isset($this->columns[$key]['htmlOptions'])) {
						$this->columns[$key]['htmlOptions'] = array();
					}
					$this->columns[$key]['htmlOptions']['class'] = (isset($this->columns[$key]['htmlOptions']['class'])) ? $this->columns[$key]['htmlOptions']['class'].' text-'.$confVal : 'text-'.$confVal;
				}
			}
		}
		parent::initColumns();
	}

	protected function initAutoIdColumn() {
		array_unshift($this->columns, array(
			'id'=>'autoId',
			'class'=>'CCheckBoxColumn',
			'selectableRows'=>2,
			'value'=>'$data->'.$this->primaryKey,
			'checkBoxHtmlOptions'=>array(
				'name'=>'autoId[]',
				'onchange'=>'if($(this).attr("checked") == "checked") $(this).parents("tr").addClass("checked"); else $(this).parents("tr").removeClass("checked");'
			),
			'headerHtmlOptions' => array(
				'width' => 10,
				'style' => (in_array('delete',$this->actions)) ? '' : 'display:none'
			),
			'htmlOptions'=>array(
				'class'=>'group-checkbox-column',
				'style' => (in_array('delete',$this->actions)) ? '' : 'display:none'
			),
			'visible' => true
		));
	}

	/**
	 * @throws CException
	 */
	public function init() {
		if ($this->id) {
			$this->setId($this->id);
		}
		if (!($this->getParam('model') instanceof CActiveRecord)) {
			throw new CException(Yii::t('admin', 'Widget grid: model not defined or inherited not from CActiveRecord'));
		}
		if (!isset($_SESSION['grid'])) {
			$_SESSION['grid'] = array();
		}
		$this->createSessionId();
		if (!isset($_SESSION['grid'][$this->sessionId])) {
			$this->initGridSession();
		}
		$this->gridSession = & $_SESSION['grid'][$this->sessionId];
		if($this->baseScriptUrl===null) {
			$this->baseScriptUrl=Yii::app()->getAssetManager()->publish(Yii::getPathOfAlias('zii.widgets.assets')).'/gridview';
		}

		$this->createEditUrl();

		$this->registerClientScript();
		$this->initAutoIdColumn();
		$this->initColumns();
		parent::registerClientScript();
		if($this->editable)
			$this->registerEditableScript();

		$this->size = $this->getParam('size');
		if ($this->size === null) {
			$this->size = 12;
		}
		$this->sortPageVar = str_replace('{id}', $this->getId(), $this->sortPageVar);
		$this->recordsPerPageVar = str_replace('{id}', $this->getId(), $this->recordsPerPageVar);
		$this->getDataProvider();
		$this->handleDeleteSelected();
	}

	public function run() {
		$this->renderKeys();
		$this->render($this->template);
	}

	public $groupActions = array();
}
