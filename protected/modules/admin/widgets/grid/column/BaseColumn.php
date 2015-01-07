<?php
//  Import superclass
Yii::import('zii.widgets.grid.CGridColumn');

/**
 * Created by JetBrains PhpStorm.
 * User: once
 * Date: 11/15/12
 * Time: 1:26 PM
 * To change this template use File | Settings | File Templates.
 */
/**
 * Admin module base column class
 */
abstract class BaseColumn extends CGridColumn {

	/**
	 * @var Grid
	 */
	public $grid;

	/**
	 * @var string the attribute name of the data model. Used for column sorting, filtering and to render the corresponding
	 * attribute value in each data cell. If {@link value} is specified it will be used to rendered the data cell instead of the attribute value.
	 * @see value
	 * @see sortable
	 */
	public $name;
	/**
	 * @var string a PHP expression that will be evaluated for every data cell and whose result will be rendered
	 * as the content of the data cells. In this expression, the variable
	 * <code>$html</code> the html number (zero-based); <code>$data</code> the data model for the html;
	 * and <code>$this</code> the column object.
	 */
	public $value;

	/**
	 * @var string the type of the attribute value. This determines how the attribute value is formatted for display.
	 * Valid values include those recognizable by {@link CGridView::formatter}, such as: raw, text, ntext, html, date, time,
	 * datetime, boolean, number, email, image, url. For more details, please refer to {@link CFormatter}.
	 * Defaults to 'text' which means the attribute value will be HTML-encoded.
	 */
	public $type='text';

	/**
	 * @var boolean whether the column is sortable. If so, the header cell will contain a link that may trigger the sorting.
	 * Defaults to true. Note that if {@link name} is not set, or if {@link name} is not allowed by {@link CSort},
	 * this property will be treated as false.
	 * @see name
	 */
	public $sortable=true;

	/**
	 * @var bool
	 */
	public $useMultiSort = true;

	/**
	 * @var string if column is sortable html th element will contain this class
	 */
	public $sortCssClass = 'sorting';

	/**
	 * @var string if sortable enabled and column sorted in asc order, html element th will contain current class
	 */
	public $sortCssAscClass = 'sorting_asc';

	/**
	 * @var string if sortable enabled and column sorted in desc order, html element th will contain current class
	 */
	public $sortCssDescClass = 'sorting_desc';

	/**
	 * @var boolean If is true, header will be output
	 */
	public $renderHeader = true;

	/**
	 * @var bool
	 */
	protected $isSorted = false;

	/**
	 * @var string default sort order
	 */
	protected $sortOrder = 'asc';

	/**
	 * @param $order set column class as sorted with $order
	 */
	public function sortedWithOrder($order) {
		$this->isSorted = true;
		$this->sortOrder = $order;
	}

	public function renderHeaderCell() {
		if ($this->sortable) {
			$sortClass = $this->sortCssClass;
		}
		if (!isset($this->headerHtmlOptions['class'])) {
			$this->headerHtmlOptions['class'] = $sortClass;
		}
		if ($this->isSorted) {
			$sortClass = ($this->sortOrder == 'asc' ? $this->sortCssAscClass : $this->sortCssDescClass);
			if (preg_match('/(' . $this->sortCssClass . '|' . $this->sortCssDescClass . '|' . $this->sortCssAscClass . ')/', $this->headerHtmlOptions['class'], $m)) {
				$this->headerHtmlOptions['class'] = str_replace($m[0], $sortClass, $this->headerHtmlOptions['class']);
			} else {
				$this->headerHtmlOptions['class'] .= ' ' . $sortClass;
			}
		}
		parent::renderHeaderCell();
	}

	/**
	 * Output header
	 */
	protected function renderHeaderCellContent() {
		if ($this->renderHeader) {
			echo $this->name;
		}
	}

	/**
	 * Renders a data cell.
	 * @param integer $row the html number (zero-based)
	 */
	public function renderDataCell($row) {
		$data=$this->grid->dataProvider->data[$row];
		$options=$this->htmlOptions;
		if($this->cssClassExpression!==null) {
			$class=$this->evaluateExpression($this->cssClassExpression,array('html'=>$row,'data'=>$data));
			if(!empty($class))
			{
				if(isset($options['class']))
					$options['class'].=' '.$class;
				else
					$options['class']=$class;
			}
		}
		$options['grid-td-id'] = $this->id . '_' . $row;
		echo CHtml::openTag('td',$options);
		$this->renderDataCellContent($row,$data);
		echo '</td>';
	}

	/**
	 * @param int $row
	 * @param mixed $data
	 */
	protected function renderDataCellContent($row, $data) {
		echo $data[$this->name];
	}
}
