<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 17.10.13
 * Time: 16:53
 * To change this template use File | Settings | File Templates.
 */
Yii::import('admin.modules.images.models.Image');
Yii::import('admin.modules.images.models.ImageObject');
class ImageColumn extends CGridColumn {

	public $name;
	/**
	 * @var string a PHP expression that will be evaluated for every data cell using {@link evaluateExpression} and whose result will be rendered
	 * as the content of the data cell.
	 * In this expression, you can use the following variables:
	 * <ul>
	 *   <li><code>$row</code> the row number (zero-based).</li>
	 *   <li><code>$data</code> the data model for the row.</li>
	 * 	 <li><code>$this</code> the column object.</li>
	 * </ul>
	 * A PHP expression can be any PHP code that has a value. To learn more about what an expression is,
	 * please refer to the {@link http://www.php.net/manual/en/language.expressions.php php manual}.
	 */
	public $value;
	/**
	 * @var boolean whether the column is sortable. If so, the header cell will contain a link that may trigger the sorting.
	 * Defaults to true. Note that if {@link name} is not set, or if {@link name} is not allowed by {@link CSort},
	 * this property will be treated as false.
	 * @see name
	 */
	public $sortable=true;
	/**
	 * @var mixed the HTML code representing a filter input (eg a text field, a dropdown list)
	 * that is used for this data column. This property is effective only when
	 * {@link CGridView::filter} is set.
	 * If this property is not set, a text field will be generated as the filter input;
	 * If this property is an array, a dropdown list will be generated that uses this property value as
	 * the list options.
	 * If you don't want a filter for this data column, set this value to false.
	 * @since 1.1.1
	 */
	public $filter;

	public $imageId;

	/**
	 * The size of the image that will be displayed
	 * @var string
	 */
	public $size = '50x50';

	public $imageClass = '';

	/**
	 * Initializes the column.
	 */
	public function init() {
		parent::init();
		if($this->name===null)
			$this->sortable=false;
		if($this->imageId===null)
			throw new CException(Yii::t('zii','"imageId" must be specified for ImageColumn.'));

		if(!isset($this->headerHtmlOptions['width'])) {
			$size = explode('x',$this->size);
			$this->headerHtmlOptions['width'] = $size[0];
		}
		Yii::app()->clientScript->registerPackage('colorbox');
		Yii::app()->clientScript->registerScript('colorboxInit',"$('a.colorbox_image_grid').colorbox();");
	}

	/**
	 * Renders the filter cell content.
	 * This method will render the {@link filter} as is if it is a string.
	 * If {@link filter} is an array, it is assumed to be a list of options, and a dropdown selector will be rendered.
	 * Otherwise if {@link filter} is not false, a text field is rendered.
	 * @since 1.1.1
	 */
	protected function renderFilterCellContent() {
		if(is_string($this->filter))
			echo $this->filter;
		elseif($this->filter!==false && $this->grid->filter!==null && $this->name!==null && strpos($this->name,'.')===false)
		{
			if(is_array($this->filter))
				echo CHtml::activeDropDownList($this->grid->filter, $this->name, $this->filter, array('id'=>false,'prompt'=>''));
			elseif($this->filter===null)
				echo CHtml::activeTextField($this->grid->filter, $this->name, array('id'=>false));
		}
		else
			parent::renderFilterCellContent();
	}

	/**
	 * Renders the header cell content.
	 * This method will render a link that can trigger the sorting if the column is sortable.
	 */
	protected function renderHeaderCellContent() {
		if($this->grid->enableSorting && $this->sortable && $this->name!==null)
			echo $this->grid->dataProvider->getSort()->link($this->name,$this->header,array('class'=>'sort-link'));
		elseif($this->name!==null && $this->header===null)
		{
			if($this->grid->dataProvider instanceof CActiveDataProvider)
				echo CHtml::encode($this->grid->dataProvider->model->getAttributeLabel($this->name));
			else
				echo CHtml::encode($this->name);
		}
		else
			parent::renderHeaderCellContent();
	}

	/**
	 * Renders the data cell content.
	 * This method evaluates {@link value} or {@link name} and renders the result.
	 * @param integer $row the row number (zero-based)
	 * @param mixed $data the data associated with the row
	 */
	protected function renderDataCellContent($row,$data) {
		$id=$this->evaluateExpression($this->imageId,array('data'=>$data,'row'=>$row));
		$size = explode('x',$this->size);
		$image = Image::model()->findByPk($id);
		if($image===null)
			echo '';
		else {
			$imageHtml = Yii::app()->image->getImageTag($id,$size[0],$size[1],array('class'=> $this->imageClass));
			$html = '<a stoppropagation="true" href="/pub/'.$image->image_object->path.'/big/'.$image->image_filename.'" title="'.$image->alt.'" class="cbox_single thumbnail colorbox_image_grid">'.$imageHtml.'</a>';
			echo $html;
		}
	}
}