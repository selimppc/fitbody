<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ideq
 * Date: 22.07.14
 * Time: 11:27
 * Comment: Yep, it's magic
 */

class VideoController extends AdminController {

    public function actions() {
        return CMap::mergeArray(parent::actions(), array(
            'list' => array(
                'class' => 'admin.actions.ListAction',
                'modelName' => 'CoachVideo',
                'dataProvider' => new CActiveDataProvider('CoachVideo', array(
                    'criteria' => new CDbCriteria(array(
                        'with' => array('coach'),
                    ))
                )),
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
                        'header' => 'Тренер',
                        'name' => 'coach_id',
                        'value' => '$data->coach->name',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'align' => 'center',
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'header' => 'Заголовок',
                        'name' => 'title',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->title',
                        'align' => 'center',
                        'editable' => array(
                            'type' => 'textarea',
                            'url' => $this->createUrl('video/update'),
                            'placement' => 'right'
                        )
                    ),
                    array(
                        'class' => 'ext.editable.EditableColumn',
                        'header' => 'Код',
                        'name' => 'code',
                        'headerHtmlOptions' => array(
                            'width' => 250
                        ),
                        'value' => '$data->code',
                        'align' => 'center',
                        'editable' => array(
                            'type' => 'text',
                            'url' => $this->createUrl('video/update'),
                            'placement' => 'right'
                        )
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
                        'value' => '($data->status) ? "Активный": "Неактивный"',
                        'align' => 'center',
                        'editable' => array(
                            'type'     => 'select',
                            'source'   => array('0' => 'Неактивный', '1' => 'Активный'),
                            'url'      => $this->createUrl('video/update')
                        )
                    ),
                ),
            ),
            'add' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'CoachVideo'
            ),
            'update' => array(
                'class' => 'admin.actions.UpdateAction',
                'modelName' => 'CoachVideo'
            )
        ));

    }
}