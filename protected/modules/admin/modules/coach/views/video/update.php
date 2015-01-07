

<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить видео: </h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новое видео: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'coachVideoForm',
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
    <?php echo $form->label($model, 'coach_id', array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->dropDownList($model, 'coach_id', CHtml::listData(Coach::model()->findAll(), 'id', 'name'), array('empty' => '- Выберите тренера -', 'class' => 'span5')); ?>
        <?php  echo $form->error($model, 'coach_id', array('class'=>'label label-important')); ?>
    </div>
</div>

<div class="control-group formSep">
    <?php echo $form->label($model, 'title', array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textField($model, 'title', array('class' => 'span5', 'rows' => 15)); ?>
        <?php  echo $form->error($model, 'title', array('class'=>'label label-important')); ?>
    </div>
</div>

<div class="control-group formSep">
    <?php echo $form->label($model, 'code',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textField($model, 'code', array('class' => 'span5', 'rows' => 15)); ?>
        <?php  echo $form->error($model, 'code',array('class'=>'label label-important')); ?>
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