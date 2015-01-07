<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shumer
 * Date: 5/29/14
 * Time: 1:54 PM
 * To change this template use File | Settings | File Templates.
 */
class SubcategoryController extends AdminController {

	public function actions() {
		return CMap::mergeArray(parent::actions(), array(
			'list' => array(
				'class' => 'admin.actions.ListAction',
				'modelName' => 'ArticleSubcategory',
				'multiple' => true,
				'columns' => array(
					array(
						'name' => 'id',
						'align' => 'center',
						'headerHtmlOptions' => array(
							'width' => 50
						)
					),
					array(
						'header' => 'Название',
						'name' => 'title',
						'headerHtmlOptions' => array(
							'width' => 250
						),
						'value' => '$data->title',
						'align' => 'center',
					),
					array(
						'header' => 'Категория',
						'name' => 'category_id',
						'headerHtmlOptions' => array(
							'width' => 250
						),
						'value' => '$data->category->category',
						'align' => 'center',
					),
					array(
						'class' => 'ext.editable.EditableColumn',
						'name' => 'status',
						'header' => 'Статус',
						'headerHtmlOptions' => array(
							'width' => 250
						),
						'value' => '($data->status) ? "Активный": "Неактивный"',
						'align' => 'center',
						'editable' => array(
							'type'     => 'select',
							'source'   => array('0' => 'Неактивный', '1' => 'Активный'),
							'url'      => $this->createUrl('subcategory/update')
						)
					),
				),
			),
			'add' => array(
				'class' => 'admin.actions.UpdateAction',
				'modelName' => 'ArticleSubcategory'
			),
			'update' => array(
				'class' => 'admin.actions.UpdateAction',
				'modelName' => 'ArticleSubcategory'
			)
		));

	}

}