<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class CityController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'City',
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
                        'class' => 'ext.editable.EditableColumn',
                        'header' => 'Город',
                        'name' => 'city',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->city',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'text',
                            'url'      => $this->createUrl('city/update'),
                        )
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
                            'url'      => $this->createUrl('city/update')
                        )
                    ),

                ),
            ),
            'add' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'City'
            ),
            'update' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'City'
            )
        ));

    }

}