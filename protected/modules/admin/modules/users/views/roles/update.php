<?php
/**
 * @var $form  CActiveForm
 * @var $model UserRole::model()
 * @var $listUri string
 */
?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'htmlOptions'=>array('class'=>'form-horizontal'),
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'errorCssClass' => 'f_error',
		'validateOnSubmit'=>true,
		'validateOnChange'=>true,
		'validateOnType'=>true
	)
)); ?>
<?php if(isset($model->id)): ?>
	<h3 class="mb_30">Update role: <?php echo $model->title; ?></h3>
<?php else: ?>
	<h3 class="mb_30">Add new role</h3>
<?php endif; ?>
<?php if($errors): ?>
	<div class="alert alert-error mb_30">
		<div class="errorSummary"><p>Please fix the following input errors:</p>
			<ul>
				<?php foreach($errors as $error): ?>
					<?php foreach($error as $errorMsg): ?>
						<li><?php echo $errorMsg; ?></li>
					<?php endforeach; ?>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
<?php endif; ?>
	<div class="control-group formSep">
		<?php echo $form->label($model,'id',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'id') ?>
			<?php  echo $form->error($model,'id',array('class'=>'label label-important')); ?>
		</div>
	</div>
	<div class="control-group formSep">
		<?php echo $form->label($model,'title',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'title') ?>
			<?php  echo $form->error($model,'title',array('class'=>'label label-important')); ?>
		</div>
	</div>
	<div class="control-group formSep">
		<?php echo $form->label($model,'description',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'description') ?>
			<?php  echo $form->error($model,'description',array('class'=>'label label-important')); ?>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<?php echo CHtml::submitButton('Save', array('class'=>'btn btn-gebo')); ?>
			<a href="<?php echo $listUri; ?>" class="btn">Close</a>
		</div>
	</div>

<?php $this->endWidget(); ?>