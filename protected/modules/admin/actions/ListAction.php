<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 22.09.13
 * Time: 19:04
 * To change this template use File | Settings | File Templates.
 */
class ListAction extends AdminAction {


	public $viewParams = array();

	public $columns = array();

	public $caption = '';

	public $dataProvider;

	public $multiple = true;

	public $toRequest = array();

	public $gridClass = 'admin.widgets.grid.Grid';

	/**
	 * Url to redirect the user to update record
	 * Used only if it is false Editable
	 * @var
	 */
	public $updateUri;

	/**
	 * Url to redirect the user to add new record
	 * Used only if it is false Editable
	 * @var string
	 */
	public $addUri;

	/**
	 * primary key in table
	 * @var string
	 */
	public $primaryKey = 'id';

	/**
	 * flag indicating will be editing on the page grid or not
	 * @var bool
	 */
	public $editable = false;

	/**
	 * Determines what action will be included on the page grid
	 * @var array
	 */
	public $actions = array(
		'add','edit','delete'
	);

	public function run() {
		$model = $this->getModel('search');

		if(isset($_GET[$this->modelName]))
			$model->attributes = $_GET[$this->modelName];

		if($this->dataProvider === null) {
			$this->dataProvider = new CActiveDataProvider(get_class($model));
		}

		$viewParams = array(
			'addUri'        => $this->addUri,
			'updateUri'     => $this->updateUri,
			'model'         => $model,
			'dataProvider'  => $this->dataProvider,
			'columns'       => $this->columns,
			'multiple'      => $this->multiple,
			'primaryKey'    => $this->primaryKey,
			'editable'      => $this->editable,
			'caption'       => $this->caption,
			'actions'       => $this->actions,
			'toRequest'     => $this->toRequest
		);
		$this->viewParams = CMap::mergeArray($this->viewParams,$viewParams);

		parent::setView('admin.views.default.list');

		$this->render(array(
			'model'         => $model
		));
	}
}