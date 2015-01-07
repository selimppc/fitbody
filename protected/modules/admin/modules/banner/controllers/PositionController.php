<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class PositionController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'BannerPosition',
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
                        'header' => 'Позиция',
                        'name' => 'position',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->position',
                        'align' => 'center',
                    ),
                    array(
                        'header' => 'Ширина',
                        'name' => 'width',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->width',
                        'align' => 'center',
                    ),
                    array(
                        'header' => 'Высота',
                        'name' => 'height',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->height',
                        'align' => 'center',
                    ),
                ),
            ),
            'add' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'BannerPosition'
            ),
            'update' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'BannerPosition'
            )
        ));

    }

}