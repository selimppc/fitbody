<?php
/**
 * Created by JetBrains PhpStorm.
 * User: shumer
 * Date: 6/2/14
 * Time: 4:08 PM
 * To change this template use File | Settings | File Templates.
 */
class CommentController extends AdminController {

	public function actions() {
		return CMap::mergeArray(parent::actions(), array(
			'list' => array(
				'class' => 'admin.actions.ListAction',
				'modelName' => 'ArticleComment',
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
//					array(
//						'header' => 'Пользователь',
//						'name' => 'user',
//						'headerHtmlOptions' => array(
//							'width' => 200
//						),
//						'value' => '$data->user->first_name." ".$data->user->last_name',
//						'align' => 'center',
//					),
                    array(
                        'header' => 'Текст',
                        'name' => 'text',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->text',
                        'align' => 'center',

                    ),
                    array(
                        'header' => 'Создан',
                        'name' => 'created_at',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->created_at',
                        'align' => 'center',

                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'name' => 'status',
                        'header' => 'Статус',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '($data->status) ? "Отображен": "Скрыт"',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'select',
                            'source'   => array('0' => 'Скрыт', '1' => 'Отображен'),
                            'url'      => $this->createUrl('comment/update')
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