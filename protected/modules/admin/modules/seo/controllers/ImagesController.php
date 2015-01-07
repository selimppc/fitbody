<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 17.10.13
 * Time: 16:44
 * To change this template use File | Settings | File Templates.
 */
Yii::import('admin.modules.images.models.Image');
class ImagesController extends AdminController {

	public function actions() {
		return CMap::mergeArray(parent::actions(),array(
			'list'  => array(
				'class' => 'admin.actions.ListAction',
				'modelName' => 'Image',
				'actions' => array('edit'),
				'multiple' => false,
				'editable' => true,
				'columns' => array(
					array(
						'class' => $this->editableClass,
						'name'  => 'alt',
						'editable' => array()
					),
					array(
						'name'  => 'image_filename'
					),
					array(
						'name'  => 'image',
						'class' => $this->imageColumnClass,
						'imageId'   => '$data->id'
					)
				)
			),
			'update' => array(
				'class' => $this->updateActionClass,
				'modelName' => 'Image'
			)
		));
	}
}