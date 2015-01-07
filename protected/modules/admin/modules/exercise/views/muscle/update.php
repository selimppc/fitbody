

<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить мышцу: <?php echo $model->muscle; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новую Мышцу: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'muscleForm',
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
    <?php echo $form->label($model,'muscle',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textField($model,'muscle'); ?>
        <?php  echo $form->error($model,'muscle',array('class'=>'label label-important')); ?>
    </div>
</div>

<div class="control-group formSep">
    <?php echo $form->label($model,'accusative',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textField($model,'accusative'); ?>
        <?php  echo $form->error($model,'accusative',array('class'=>'label label-important')); ?>
    </div>
</div>

<div class="control-group formSep">
    <?php echo $form->label($model,'slug',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textField($model,'slug'); ?>
        <?php  echo $form->error($model,'slug',array('class'=>'label label-important')); ?>
    </div>
</div>

<div class="control-group formSep">
    <?php echo $form->label($model,'description',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textArea($model,'description',array('rows' => 10, 'cols' => 50, 'style' => 'width:500px')); ?>
        <?php  echo $form->error($model,'description',array('class'=>'label label-important')); ?>
    </div>
</div>


<div class="control-group formSep">
    <?php echo $form->label($model,'image_id',array('class'=>'control-label')); ?>
    <?php echo $form->hiddenField($model,'image_id', array('class' => 'images')) ?>
    <div class="controls well">
        <?php  echo $form->error($model,'image_id',array('class'=>'label label-important', 'style' => 'margin: 10px 0px;')); ?>
        <?php
        $id = ($model->id) ? $model->id : '';
        Yii::import( "ext.xupload.models.XUploadForm" );
        $this->widget('ext.xupload.XUpload', array(
                'url' => Yii::app( )->createUrl( "/admin/exercise/muscle/uploadMuscleImage/item/" . $id),
                'model' => new XUploadForm,
                'htmlOptions' => array('id'=>'muscleForm'),
                'attribute' => 'file',
                'multiple' => false,
                'formView' => 'ext.xupload.views.form',
                'showForm' => false,
                'options' => array(
                    'submit' => "js:function (e, data) {
                                    //$('#Muscle_image_id').attr('value','');
                                    //data.formData = {itemId: " . $id . "};
                                    return true;
                                 }",
                    'success'=>"js:function(result, textStatus, jqXHR) {
                                    $('#Muscle_image_id').attr('value', result[0].image_id);
                                }",
                    'added' => "js:function() {
                                    $('#muscleForm .fileinput-button').addClass('disabled');
                                    $('#XUploadForm_file').prop('disabled', true);
                                }",
                    'destroyed' => "js: function() {
                                    $('#Muscle_image_id').attr('value','');
                                }",
                    'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                ),
            )
        );
        ?>
    </div>
</div>

<div class="control-group formSep">
    <?php echo $form->label($model, "status", array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->checkBox($model, "status"); ?>
        <?php  echo $form->error($model, "status", array('class'=>'label label-important')); ?>
    </div>
</div>

<div class="control-group">
    <div class="controls">
        <button id="save_btn" class="btn btn-info" name="save_and_close">Сохранить</button>
        <a href="<?php echo $listUri; ?>" class="btn">Закрыть</a>
    </div>
</div>

<?php $this->endWidget(); ?>


<?php
    $cs = Yii::app()->clientScript;
    $cs->registerScriptFile('/js/admin/uploader.js', CClientScript::POS_END);
    $cs->registerScriptFile('/js/admin/muscle.js', CClientScript::POS_END);
?>

