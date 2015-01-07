<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 14.10.13
 * Time: 16:45
 * To change this template use File | Settings | File Templates.
 */
class PagesController extends AdminController {

	public function actions() {
		return CMap::mergeArray(parent::actions(), array(
			'list' => array(
				'class' => 'admin.actions.ListAction',
				'modelName' => 'Seo',
				'multiple' => false,
				'editable' => true,
				'columns' => array(
					array(
						'name' => 'id',
						'align' => 'center',
						'headerHtmlOptions' => array(
							'width' => 50
						)
					),
					array(
						'class' => 'ext.editable.EditableColumn',
						'name' => 'title',
						'editable' => array(
							'type'  => 'text'
						),
						'headerHtmlOptions' => array(
							'width' => 250
						)
					),
					array(
						'class' => 'ext.editable.EditableColumn',
						'name' => 'keywords',
						'editable' => array(
							'type'  => 'textarea'
						)
					),
					array(
						'class' => 'ext.editable.EditableColumn',
						'name' => 'description',
						'editable' => array(
							'type'  => 'textarea'
						)
					),
					array(
						'class' => 'ext.editable.EditableColumn',
						'name' => 'uri',
						'editable' => array(
							'type'  => 'text'
						)
					),
					array(
						'class' => 'ext.editable.EditableColumn',
						'name' => 'is_auto_generation',
						'editable' => array(
							'type'  => 'select',
							'source' => array(1=>'Yes',2=>'No')
						),
						'align' => 'center',
						'headerHtmlOptions' => array(
							'width' => 150
						)
					),
				),
			),
			'update' => array(
				'class' => 'admin.actions.UpdateAction',
				'modelName' => 'Seo',
			),
			'add' => array(
				'class' => 'admin.actions.UpdateAction',
				'modelName' => 'Seo',
			)
		));
	}
}