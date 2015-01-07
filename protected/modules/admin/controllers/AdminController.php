<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 22.09.13
 * Time: 18:33
 * To change this template use File | Settings | File Templates.
 */
class AdminController extends BaseController {

	protected $editableClass = 'ext.editable.EditableColumn';
	protected $listActionClass = 'admin.actions.ListAction';
	protected $updateActionClass = 'admin.actions.UpdateAction';

	protected $imageColumnClass = 'admin.widgets.grid.column.ImageColumn';

	public $defaultAction = 'list';

	public function actions() {
		return array(
			'list'      => 'admin.actions.ListAction',
			'update'    => 'admin.actions.UpdateAction',
			'add'       => 'admin.actions.UpdateAction',
		);
	}
}