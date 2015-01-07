<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shumer
 * Date: 6/2/14
 * Time: 5:43 PM
 * To change this template use File | Settings | File Templates.
 */
class ClubCommentController extends AdminController {

	public function actions() {
		return CMap::mergeArray(parent::actions(), array(
			'list' => array(
				'class' => 'admin.actions.ListAction',
				'modelName' => 'ClubComment',
				'multiple' => true,
				'actions' => array('update','delete'),
				'columns' => array(
					array(
						'name' => 'id',
						'align' => 'center',
						'headerHtmlOptions' => array(
							'width' => 50
						)
					),
					array(
						'header' => 'Пользователь',
						'name' => 'user',
						'headerHtmlOptions' => array(
							'width' => 200
						),
						'value' => '$data->user->first_name." ".$data->user->last_name',
						'align' => 'center',
					),
					array(
						'class' => 'ext.editable.EditableColumn',
						'name' => 'status',
						'header' => 'Статус',
						'headerHtmlOptions' => array(
							'width' => 50
						),
						'value' => '($data->status) ? "Отображен": "Скрыт"',
						'align' => 'center',
						'editable' => array(
							'type'     => 'select',
							'source'   => array('1' => 'Отображен', '0' => 'Скрыт'),
							'url'      => $this->createUrl('ClubComment/update')
						)
					),
					array(
						'header' => 'Дата создания',
						'name' => 'date',
						'align' => 'center',
						'headerHtmlOptions' => array(
							'width' => 50
						)
					),
				),
			),
			'add' => array(
				'class' => 'admin.actions.UpdateAction',
				'modelName' => 'ArticleComment'
			),
			'update' => array(
				'class' => 'admin.actions.UpdateAction',
				'modelName' => 'ArticleComment'
			),
		));

	}
}