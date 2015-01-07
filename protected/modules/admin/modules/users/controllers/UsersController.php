<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class UsersController extends AdminController {

	public function actions() {
		return CMap::mergeArray(parent::actions(), array(
			'list' => array(
				'class' => 'admin.actions.ListAction',
				'modelName' => 'User',
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
                        'name' => 'nickname',
                        'value' => '$data->nickname'
                    ),
					array(
						'name' => 'last_name',
						'value' => '$data->last_name." ".$data->first_name'
					),
					array(
						'name' => 'updated_at',
						'headerHtmlOptions' => array(
							'width' => 250
						),
						'value' => 'date("H:i m.d.Y", strtotime($data->updated_at))',
						'align' => 'center'
					),
					array(
						'class' => 'ext.editable.EditableColumn',
						'name' => 'created_at',
						'headerHtmlOptions' => array(
							'width' => 250
						),
						'value' => 'date("Y-m-d",strtotime($data->created_at))',
						'align' => 'center',
						'editable' => array(
							'type'          => 'date',
							'viewformat'    => 'yyyy-mm-dd',
							'format'        => 'yyyy-mm-dd',
							'url'           => $this->createUrl('users/update'),
							'placement'     => 'right',
						)
					),
					array(
						'class' => 'ext.editable.EditableColumn',
						'name' => 'role_id',
						'headerHtmlOptions' => array(
							'width' => 250
						),
						'value' => '$data->user_role->title',
						'align' => 'center',
						'editable' => array(
							'type'     => 'select',
							'url'      => $this->createUrl('users/update'),
							'source'   => CHtml::listData(UserRole::model()->findAll(),'id','title'),
						)
					),
					array(
						'class' => 'ext.editable.EditableColumn',
						'name' => 'status',
						'headerHtmlOptions' => array(
							'width' => 250
						),
						'value' => 'User::model()->statusList[$data->status]',
						'align' => 'center',
						'editable' => array(
							'type'     => 'select',
							'url'      => $this->createUrl('users/update'),
							'source'   => User::model()->statusList,
							'onRender' => 'js: function(e, editable) {
			                      var colors = {1: "green", 2: "blue", 3: "gray", 4: "red"};
			                      $(this).css("color", colors[editable.value]);
			                  }'
						)
					),
					array(
						'name' => 'image',
						'class' => $this->imageColumnClass,
						'imageId' => '$data->image_id'
					)
				),
			),
			'update' => array(
				'class' => 'admin.actions.UpdateAction',
				'modelName' => 'User',
			),
			'add' => array(
				'class' => 'admin.actions.UpdateAction',
				'modelName' => 'User',
			)
		));
	}
}