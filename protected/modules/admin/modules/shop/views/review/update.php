

<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить отзыв: <?php echo $model->review; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новый отзыв: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'shopReviewForm',
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
    <?php echo $form->label($model,'material_id',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->dropDownList($model, "material_id", $materials, array('empty' => '- Выберите клуб -', 'class' => 'span6')); ?>
        <?php  echo $form->error($model,'material_id',array('class'=>'label label-important')); ?>
    </div>
</div>

<div class="control-group formSep">
    <?php echo $form->label($model,'user_id',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->dropDownList($model, "user_id", $users, array('empty' => '- Выберите ползователя -', 'class' => 'span6')); ?>
        <?php  echo $form->error($model,'user_id',array('class'=>'label label-important')); ?>
    </div>
</div>

<div class="control-group formSep">
    <?php echo $form->label($model, 'review',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textArea($model, 'review', array('class' => 'span6', 'rows' => 15)); ?>
        <?php  echo $form->error($model, 'review',array('class'=>'label label-important')); ?>
    </div>
</div>


<div class="control-group formSep">
    <?php echo $form->label($model,'rating',array('class'=>'control-label', 'for' => 'exercise')); ?>
    <div class="controls">
        <div class="jRatingExercise" data-average="<?php echo ($model->rating) ? $model->rating : 0 ;?>"></div>
        <?php echo $form->hiddenField($model, 'rating', array('class' => 'jRatingExerciseHidden')); ?>
        <?php  echo $form->error($model,'rating',array('class'=>'label label-important')); ?>
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
$cs = Yii::app()->clientScript
    ->registerScriptFile(
        $this->assetUrl . '/lib/jRating/jRating.jquery.min.js',
        CClientScript::POS_HEAD
    )
    ->registerCssFile(
        $this->assetUrl . '/lib/jRating/jRating.jquery.css'
    )
    ->registerScriptFile('/js/admin/uploader.js', CClientScript::POS_END)
    ->registerScriptFile('/js/admin/property.js', CClientScript::POS_END)
    ->registerScriptFile('/js/admin/coach_review.js', CClientScript::POS_END);
?>

