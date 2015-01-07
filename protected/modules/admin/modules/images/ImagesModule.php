<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 07.10.13
 * Time: 16:07
 * To change this template use File | Settings | File Templates.
 */
class ImagesModule extends AdminModule {

	public $icon = 'icon-picture';
    public $title = 'Изображения';

	public function init() {
		$this->setImport(array(
			'images.models.*'
		));
	}

	public $menuItems = array(
		array(
            'title' => 'Объекты',
			'url' => 'objects'
		),
	);
}