<?php
/**
 * Created by JetBrains PhpStorm.
 * User: viktor
 * Date: 23.09.13
 * Time: 12:24
 * To change this template use File | Settings | File Templates.
 */
class MechanicController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'ExerciseMechanic',
                'updateUri' => '/admin/exercise/mechanic/update.html',
                'addUri' => '/admin/exercise/mechanic/add.html',
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

                        'header' => 'Движения',
                        'name' => 'mechanic',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->mechanic',
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
                            'url'      => $this->createUrl('mechanic/update')
                        )
                    ),

                ),
            ),
            'add' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'ExerciseMechanic'
            ),
            'update' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'ExerciseMechanic'
            )
        ));

    }

}