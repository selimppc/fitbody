<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить программу: <?php echo $model->title; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новую программу: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'programForm',
    'htmlOptions'=>array('class'=>'form-horizontal ','enctype'=>'multipart/form-data', 'data-id' => ($model->id) ? $model->id : ''),
    'enableClientValidation' => false,
    'clientOptions'=>array(
        'errorCssClass' => 'f_error',
        'validateOnSubmit'=>true,
        'validateOnChange'=>true,
        'validateOnType'=>true
    )
)); ?>


<div class="control-group formSep">
    <?php echo $form->label($model,'title',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textField($model,'title'); ?>
        <?php  echo $form->error($model,'title',array('class'=>'label label-important')); ?>
    </div>
</div>
<div class="control-group formSep">
    <?php echo $form->label($model,'category_id',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->dropDownList($model, 'category_id', ProgramCategory::model()->categoriesListData(), array('empty' => ' -- Выберите категорию -- ')); ?>
        <?php  echo $form->error($model,'category_id',array('class'=>'label label-important')); ?>
    </div>
</div>
<div class="control-group formSep">
    <?php echo $form->label($model,'short_description',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textArea($model,'short_description'); ?>
        <?php  echo $form->error($model,'short_description',array('class'=>'label label-important')); ?>
    </div>
</div>



<h3 class="heading">Главное изображение</h3>
<div class="control-group formSep">
    <?php echo $form->label($model,'image_id',array('class'=>'control-label')); ?>
    <?php echo $form->hiddenField($model,'image_id', array('class' => 'images')) ?>
    <div class="controls well">
        <?php  echo $form->error($model,'image_id',array('class'=>'label label-important', 'style' => 'margin: 10px 0px;')); ?>
        <?php
        $id = ($model->id) ? $model->id : '';
        $this->widget('ext.xupload.XUpload', array(
                'url' => Yii::app( )->createUrl( "/admin/program/program/uploadProgramImage/item/" . $id),
                'model' => new XUploadForm,
                'htmlOptions' => array('id'=>'programForm'),
                'attribute' => 'file',
                'multiple' => false,
                'formView' => 'ext.xupload.views.form',
                'showForm' => false,
                'options' => array(
                    'submit' => "js:function (e, data) {
                                    //$('#Program_image_id').attr('value','');
                                    //data.formData = {itemId: " . $id . "};
                                    return true;
                                 }",
                    'success'=>"js:function(result, textStatus, jqXHR) {
                                    $('#Program_image_id').attr('value', result[0].image_id);
                                }",
                    'added' => "js:function() {
                                    $('#programForm .fileinput-button').addClass('disabled');
                                    $('#XUploadForm_file').prop('disabled', true);
                                }",
                    'destroyed' => "js: function() {
                                    $('#Program_image_id').attr('value','');
                                }",
                    'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                ),
            )
        );
        ?>
    </div>
</div>



<div class="control-group formSep">
    <?php Yii::import('ext.imperavi.ImperaviRedactorWidget');
        $this->widget('ImperaviRedactorWidget', array(
            'model' => $model,
            'attribute' => 'description',
            'options' => array(
                'lang' => 'ru',
                "imageUpload" => "/admin/program/program/upload",
                "minHeight" => 300
            ),
        ));
    ?>
</div>

<div class="control-group">
    <?php echo $form->label($model, "status", array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->checkBox($model, "status"); ?>
        <?php  echo $form->error($model, "status", array('class'=>'label label-important')); ?>
    </div>
</div>
<h3 class="heading">Программа тренировки</h3>

<table class="table table-striped" id="days-of-week-table">
    <caption><h2 class="muted heading">Дни недели</h2></caption>
    <tbody>
        <tr class="info">
            <?php foreach ($daysOfWeek as $dayNum => $dayName): ?>
                <td>
                    <?php if (isset($model->days[$dayNum])): ?>
                        <?php echo $form->checkBox($day, "[daysOfWeek][$dayNum]day_of_week", array('data-day' => $dayNum, 'checked' => 'checked')); ?>
                    <?php else: ?>
                        <?php echo $form->checkBox($day, "[daysOfWeek][$dayNum]day_of_week", array('data-day' => $dayNum)); ?>
                    <?php endif; ?>
                </td>
                <td>
                    <?php echo $dayName; ?>
                </td>
            <?php endforeach; ?>
        </tr>
    </tbody>
</table>
<!--TPL -->
<script id="table-tpl" type="program/tpl">
    <table>
        <tbody>
            <tr>
                <td class="text-center span5">
                    <?php echo $form->dropDownList($exercise,"[<%weekDay%>][<%counter%>]exercise_id", $exercises, array('empty' => '-- Выберите упражнение', 'style' => 'width: 100%;')); ?>
                    <?php echo $form->error($exercise, "[<%weekDay%>][<%counter%>]exercise_id", array('class'=>'label label-important')); ?>
                </td>
                <td class="text-center">
                    <?php echo $form->textArea($exercise, "[<%weekDay%>][<%counter%>]description", array('style' => 'width: 100%;')); ?>
                    <?php echo $form->error($exercise, "[<%weekDay%>][<%counter%>]description", array('class'=>'label label-important')); ?>
                </td>
                <td class="text-center span2">
                    <a href="#" class="btn btn-small btn-warning btn-remove-row">Удалить</a>
                </td>
            </tr>
        </tbody>
    </table>
</script>
<!-- end TPL -->


<?php foreach ($daysOfWeek as $day => $dayName):
    if (isset($model->days[$day]) && $model->days[$day]) {
        $tempArray = ($model->days[$day]->exercises) ? $model->days[$day]->exercises: array();
        $class = '';
    } else {
        $tempArray = array();
        $class = 'hidden';
    }

?>

    <div data-day="<?php echo $day; ?>" class="day-container well <?php echo $class; ?>">
        <h2><?php echo $dayName; ?></h2>
        <table class="table table-hover description-of-day-table" data-day="<?php echo $day; ?>">
            <caption><h3 class="muted">Описание</h3></caption>
            <tbody>
                <tr>
                    <td>
                        <?php echo $form->textArea((isset($model->days[$day])) ? $model->days[$day] : new ProgramDay(), "[day][$day]description", array('style' => 'width: 100%;')); ?>
                        <?php echo $form->error((isset($model->days[$day])) ? $model->days[$day] : new ProgramDay(), "[day][$day]description", array('class'=>'label label-important')); ?>
                        <?php echo $form->error((isset($model->days[$day])) ? $model->days[$day] : new ProgramDay(), "[day][$day]day_of_week", array('class'=>'label label-important')); ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <table class="table table-hover program-of-day-table" data-day="<?php echo $day; ?>">
            <caption><h3 class="muted">Упражнения</h3></caption>
            <thead>
                <tr>
                    <th class="text-center span5">Упражнение</th>
                    <th>Описание</th>
                    <th class="text-center span2">Действие</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tempArray as $item): ?>
                    <tr>
                        <td class="text-center span5">
                            <?php echo $form->dropDownList($item,"[$day][$counter]exercise_id", $exercises, array('empty' => '-- Выберите упражнение', 'style' => 'width: 100%;')); ?>
                            <?php echo $form->error($item, "exercise_id", array('class'=>'label label-important')); ?>
                        </td>
                        <td class="text-center">
                            <?php echo $form->textArea($item, "[$day][$counter]description", array('style' => 'width: 100%;')); ?>
                            <?php echo $form->error($item, "description", array('class'=>'label label-important')); ?>
                        </td>
                        <td class="text-center span2">
                            <a href="#" class="btn btn-small btn-warning btn-remove-row">Удалить</a>
                        </td>
                    </tr>
                    <?php $counter++; ?>
                <?php endforeach;?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="2"></td>
                    <td class="text-center span2">
                        <a href="#" class="btn btn-small btn-info btn-add-row">Добавить</a>
                    </td>
                </tr>
            </tfoot>
        </table>
    </div>
<?php endforeach; ?>

<input value="<?php echo $counter; ?>" type="hidden" id="general-count" />

<hr />
<div class="control-group formSep">
    <?php Yii::import('ext.imperavi.ImperaviRedactorWidget');
    $this->widget('ImperaviRedactorWidget', array(
        'model' => $model,
        'attribute' => 'description_after',
        'options' => array(
            'lang' => 'ru',
            "imageUpload" => "/admin/program/program/upload",
            "minHeight" => 300
        ),
    ));
    ?>
</div>
<div class="control-group">
    <div class="controls">
        <button id="save_btn" class="btn btn-info" name="save_and_close">Сохранить</button>
        <a href="<?php echo $listUri; ?>" class="btn">Закрыть</a>
    </div>
</div>

<?php $this->endWidget(); ?>

<?php
    Yii::app()->clientScript
        ->registerScriptFile('/js/admin/uploader.js', CClientScript::POS_END)
        ->registerScriptFile('/js/admin/program.js', CClientScript::POS_END);

?>

