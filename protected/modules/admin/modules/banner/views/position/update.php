

<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить позицию: <?php echo $model->position; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новую позицию: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'positionForm',
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
    <?php echo $form->label($model,'position',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textField($model,'position'); ?>
        <?php  echo $form->error($model,'position',array('class'=>'label label-important')); ?>
    </div>
</div>
<div class="control-group formSep">
    <?php echo $form->label($model,'width',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textField($model,'width'); ?>
        <?php  echo $form->error($model,'width',array('class'=>'label label-important')); ?>
    </div>
</div>
<div class="control-group formSep">
    <?php echo $form->label($model,'height',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textField($model,'height'); ?>
        <?php  echo $form->error($model,'height',array('class'=>'label label-important')); ?>
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
?>
