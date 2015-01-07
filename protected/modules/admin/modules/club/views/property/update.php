

<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить направление: <?php echo $model->property; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новый направление: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'clubForm',
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
    <?php echo $form->label($model,'property',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textField($model,'property'); ?>
        <?php  echo $form->error($model,'property',array('class'=>'label label-important')); ?>
    </div>
</div>

<div class="control-group formSep">
    <?php echo $form->label($model,'image_id',array('class'=>'control-label')); ?>
    <?php echo $form->hiddenField($model,'image_id', array('class' => 'images')) ?>
    <div class="controls well">
        <?php  echo $form->error($model,'image_id',array('class'=>'label label-important', 'style' => 'margin: 10px 0px;')); ?>
        <?php
        $id = ($model->id) ? $model->id : '';
        $this->widget('ext.xupload.XUpload', array(
                'url' => Yii::app( )->createUrl( "/admin/club/property/uploadPropertyImage/item/" . $id),
                'model' => $photos,
                'htmlOptions' => array('id'=>'clubForm'),
                'attribute' => 'file',
                'multiple' => false,
                'formView' => 'ext.xupload.views.form',
                'showForm' => false,
                'options' => array(
                    'submit' => "js:function (e, data) {
                                    return true;
                                 }",
                    'success'=>"js:function(result, textStatus, jqXHR) {
                                    $('#ClubProperty_image_id').attr('value', result[0].image_id);
                                }",
                    'added' => "js:function() {
                                    $('#clubForm .fileinput-button').addClass('disabled');
                                    $('#XUploadForm_file').prop('disabled', true);
                                }",
                    'destroyed' => "js: function() {
                                    $('#ClubProperty_image_id').attr('value','');
                                }",
                    'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                ),
            )
        );
        ?>
    </div>
</div>

<div class="control-group formSep">
    <?php echo $form->label($model, "is_main", array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->checkBox($model, "is_main"); ?>
        <?php  echo $form->error($model, "is_main", array('class'=>'label label-important')); ?>
    </div>
</div>
<div class="control-group">
    <div class="controls">
        <button id="save_btn" class="btn btn-info" name="save_and_close">Сохранить</button>
        <a href="<?php echo $uri; ?>" class="btn">Закрыть</a>
    </div>
</div>

<?php $this->endWidget(); ?>

<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile('/js/admin/uploader.js', CClientScript::POS_END);
$cs->registerScriptFile('/js/admin/property.js', CClientScript::POS_END);
?>

