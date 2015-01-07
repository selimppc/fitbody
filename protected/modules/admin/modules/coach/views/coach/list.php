<div class="row-fluid">
<h3 class="heading">Тренеры</h3>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'grid_list',
    'selectionChanged'=>'function() {
        location.href ="/admin/coach/coach/update.html?id=" + $.fn.yiiGridView.getSelection("grid_list");
     }',
    'dataProvider'  => $dataProvider,
    'afterAjaxUpdate' => 'function(id, data) {
            if($(".cbox_single").length) {
               gebo_colorbox_single.init();
            }
    }',
    'pagerCssClass' => 'pagination',
    'pager'=>array(
        'class' => 'admin.widgets.grid.PagerRus'
    ),
    'enableSorting' => false,
    'htmlOptions' => array('class' => 'dataTables_wrapper form-inline'),
    'itemsCssClass' => 'table table-striped table-bordered dTableR dataTable items',
    'enablePagination'=> true,
    'columns'       => array(
        array(
            'id' => 'autoId',
            'class' => 'CCheckBoxColumn',
            'selectableRows'=> 2,
            'value' => '$data->id',
            'checkBoxHtmlOptions' => array(
                'name'=>'autoId[]',
                'onchange'=>'if($(this).attr("checked") == "checked") $(this).parents("tr").addClass("checked"); else $(this).parents("tr").removeClass("checked");'
            ),
            'headerHtmlOptions' => array(
                'width' => 10
            ),
            'htmlOptions' => array(
                'class' => 'group-checkbox-column',
            ),
        ),
        array(
            'name'  => 'position',
            'value' => '$data->position',
            'htmlOptions' => array(
                'width' => 60,
                'align' => 'center'
            ),
        ),
        array(
            'name'  => 'id',
            'header' => 'ID',
            'htmlOptions' => array(
                'width' => 60,
                'align' => 'center'
            ),
        ),
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'name',
            'htmlOptions' => array(
                'width' => 200,
                'align' => 'center'
            ),
        ),
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'short_description',
            'headerHtmlOptions' => array(
                'width' => 400
            ),
            'editable' => array(
                'type'  => 'textarea'
            ),

        ),
        array(
            'name' => 'image_id',
            'class' => $this->imageColumnClass,
            'imageId' => '$data->image_id'
        ),
        array(
            'class'=>'CButtonColumn',
            'header' => 'Вверх/Вниз',
            'template'=>'{up} {down}',
            'htmlOptions' => array('style' => 'width: 100px; text-align:center; vertical-align: middle;', 'class' => 'up_down'),
            'buttons'=>array(
                'up' => array(
                    'label'=>'Up element',
                    'imageUrl'=>'/images/admin/arrow-up.png',
                    'click' => "function() {
                        $.fn.yiiGridView.update('grid_list', {
                            type: 'POST',
                            url: $(this).attr('href'),
                            success: function(data) {
                                if (data) {
                                    $.fn.yiiGridView.update('grid_list');
                                }
                            }
                        })
                        return false;
                    }",
                    'url'=>'Yii::app()->controller->createUrl("/admin/coach/coach/position", array("model" => "Coach", "direction" => "up", "position" => $data->position))',
                ),
                'down' => array(
                    'label'=>'Down element',
                    'imageUrl'=>'/images/admin/arrow-down.png',
                    'click' => "function() {
                        $.fn.yiiGridView.update('grid_list', {
                            type: 'POST',
                            url: $(this).attr('href'),
                            success: function(data) {
                                if (data) {
                                    $.fn.yiiGridView.update('grid_list');
                                }
                            }
                        })
                        return false;
                    }",
                    'url'=>'Yii::app()->controller->createUrl("/admin/coach/coach/position", array("model" => "Coach", "direction" => "down", "position" => $data->position))',
                )
            )
        ),
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'is_recommended',
            'value' => '($data->is_recommended) ? "Да" : "Нет"',
            'editable' => array(
                'type'     => 'select',
                'source'   => array(Coach::IS_RECOMMENDED => 'Да', Coach::IS_NOT_RECOMMENDED => 'Нет'),
                'url'      => $this->createUrl('/admin/coach/coach/update')
            ),
            'htmlOptions' => array(
                'align' => 'center'
            ),
        ),
        array(
            'class' => 'ext.editable.EditableColumn',
            'name' => 'status',
            'value' => '($data->status) ? "Активный" : "Неактвный"',
            'editable' => array(
                'type'     => 'select',
                'source'   => array(Coach::STATUS_INACTIVE => 'Неактвный', Coach::STATUS_ACTIVE => 'Активный'),
                'url'      => $this->createUrl('/admin/coach/coach/update')
            ),
            'htmlOptions' => array(
                'align' => 'center'
            ),
        )
    )
)); ?>

<div id="controll-button">
    <?php echo CHtml::linkButton('<i class="icon-plus"></i> Добавить',array(
        'submit'=>'/admin/coach/coach/add.html',
        'class' => 'btn btn-mini'
    )); ?>
    <?php echo CHtml::link('<i class="icon-trash"></i> Удалить',
        array('controller/action', 'param1'=>'value1'), array('class'=>'btn btn-mini', 'id' => "deleteSelectedRowsButton")); ?>

</div>
<style type="text/css">
    .summary {
        height: 40px;
        text-align: right;
    }
    tbody tr {
        cursor: pointer;
    }
    .down, .up {
        padding: 5px;
    }
    .down:hover, .up:hover {
        background: #eee;
    }
    .down:active, .up:active {
        background: #aaa;
    }
    #controll-button {
        position: relative;
        width: 30%;
    }
</style>
<script>
    $(document).on('click','.cbox_single, .up_down, .group-checkbox-column', function(e) {
        e.stopPropagation();
    });

    $(document).on('click', '#deleteSelectedRowsButton', function(e) {
        e.preventDefault();
        var rows = $('#grid_list tbody .group-checkbox-column input:checked');
        var myArray = rows.map(function(){
            return $(this).val();
        }).get();
        if (myArray.length > 0) {
            var data = {
                'ids' : JSON.stringify(myArray),
                'rt' : 'deleteSelected',
                'ajax': true
            };
            $.ajax({
                "type": "post",
                "url": '/admin/coach/coach/deleteCoach',
                "dataType" : "json",
                "data": data,
                success: function (data, textStatus) {
                    $.fn.yiiGridView.update("grid_list");
                }
            });
        }
    })
</script>

