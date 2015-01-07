

<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить свойство: <?php echo $model->property; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новое свойство: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'coachPropertyForm',
    'htmlOptions'=>array('class'=>'form-horizontal ', 'data-id' => ($model->id) ? $model->id : ''),
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
        <?php echo $form->textField($model,'property', array('class' => 'span5')); ?>
        <?php  echo $form->error($model,'property',array('class'=>'label label-important')); ?>
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
        <a href="<?php echo $uri; ?>" class="btn">Закрыть</a>
    </div>
</div>

<?php $this->endWidget(); ?>

<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile('/js/admin/uploader.js', CClientScript::POS_END);
$cs->registerScriptFile('/js/admin/property.js', CClientScript::POS_END);
?>

