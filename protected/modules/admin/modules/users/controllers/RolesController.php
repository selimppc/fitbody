<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 27.09.13
 * Time: 10:59
 * To change this template use File | Settings | File Templates.
 */
class RolesController extends AdminController {

	public function actions() {
		return CMap::mergeArray(parent::actions(), array(
			'list' => array(
				'class' => 'admin.actions.ListAction',
				'modelName' => 'UserRole',
				'multiple' => false,
				'columns' => array(
					array(
						'name' => 'id',
						'align' => 'center',
						'headerHtmlOptions' => array(
							'width' => 50
						)
					),
					array(
						'name' => 'title'
					),
					array(
						'name' => 'description'
					),
				),
			),
			'update' => array(
				'class' => 'admin.actions.UpdateAction',
				'modelName' => 'UserRole',
			),
			'add' => array(
				'class' => 'admin.actions.UpdateAction',
				'modelName' => 'UserRole',
			)
		));
	}
}