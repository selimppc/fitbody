<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить сеть: <?php echo $model->title; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новую сеть: </h3>
<?php endif; ?>

    <hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'chainForm',
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