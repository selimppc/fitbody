<?php
/**
 * Created by PhpStorm.
 * User: shumer
 * Date: 7/11/14
 * Time: 12:04 PM
 */
class BodyPartController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'BodyPart',
                'multiple' => true,
                'updateUri' => '/admin/exercise/body-part/update.html',
                'addUri' => '/admin/exercise/body-part/add.html',
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
                        'header' => 'Название',
                        'name' => 'title',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->title',
                        'align' => 'center',
                        'editable' => array(
                            'type' => 'text',
                            'url' => $this->createUrl('body-part/update'),
                            'placement' => 'right'
                        )
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'header' => 'Родительный падеж',
                        'name' => 'genitive',
                        'headerHtmlOptions' => array(
                            'width' => 200
                        ),
                        'value' => '$data->genitive',
                        'align' => 'center',
                        'editable' => array(
                            'type' => 'text',
                            'url' => $this->createUrl('body-part/update'),
                            'placement' => 'right'
                        )
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
                            'url'      => $this->createUrl('body-part/update')
                        )
                    ),
                ),
            ),
            'add' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'BodyPart'
            ),
            'update' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'BodyPart'
            ),
        ));

    }
}