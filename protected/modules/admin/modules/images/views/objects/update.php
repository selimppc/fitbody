<?php
/**
 * @var $form CActiveForm
 * @var $model ImageObject::model()
 */
?>
<?php
$form=$this->beginWidget('CActiveForm', array(
	'htmlOptions'=>array('class'=>'form-horizontal'),
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'errorCssClass' => 'f_error',
		'validateOnSubmit'=>true,
		'validateOnChange'=>false,
		'validateOnType'=>false
	)
)); ?>
<?php if(isset($model->id)): ?>
	<h3 class="mb_30">Update object: <?php echo $model->title; ?></h3>
<?php else: ?>
	<h3 class="mb_30">Add new Image Object</h3>
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
		<?php echo $form->label($model,'title',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'title') ?>
			<?php  echo $form->error($model,'title',array('class'=>'label label-important')); ?>
		</div>
	</div>
	<div class="control-group formSep">
		<?php echo $form->label($model,'key',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'key') ?>
			<?php  echo $form->error($model,'key',array('class'=>'label label-important')); ?>
		</div>
	</div>
	<div class="control-group formSep">
		<?php echo $form->label($model,'path',array('class'=>'control-label')); ?>

		<div class="controls">
			<?php echo $form->textField($model,'path') ?>
			<?php  echo $form->error($model,'path',array('class'=>'label label-important')); ?>
			<br>
			<small>Example: user/avatar</small>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<?php echo CHtml::submitButton('Save', array('class'=>'btn btn-gebo')); ?>
			<a href="<?php echo $listUri; ?>" class="btn">Close</a>
		</div>
	</div>
<?php $this->endWidget(); ?>

<?php if(isset($model->id)): ?>
<?php
	$criteria = new CDbCriteria();
	$criteria->condition = 'image_object_id = "'.$model->id.'"';
	$dataProvider = new CActiveDataProvider('ImageSize',  array('criteria' => $criteria));
?>
<?php $this->widget('admin.widgets.grid.Grid', array(
		'model'         => new ImageSize('search'),
		'dataProvider'  => $dataProvider,
		'editable'      => true,
		'addUri'        => $this->createUrl('objects/addSize'),
		'toRequest'     => array(
			'image_object_id'   => $model->id
		),
		'columns'       => array(
			array(
				'align' => 'center',
				'name'  => 'id'
			),
			array(
				'name'  => 'title',
				'class' => 'ext.editable.EditableColumn',
				'editable'  => array(
					'url'   => $this->createUrl('objects/addSize')
				)
			),
			array(
				'name'  => 'width',
				'class' => 'ext.editable.EditableColumn',
				'align' => 'center',
				'editable'  => array(
					'url'   => $this->createUrl('objects/addSize')
				)
			),
			array(
				'name'  => 'height',
				'class' => 'ext.editable.EditableColumn',
				'align' => 'center',
				'editable'  => array(
					'url'   => $this->createUrl('objects/addSize')
				)
			),
			array(
				'name'  => 'type_resize',
				'class' => 'ext.editable.EditableColumn',
				'value' => 'Yii::app()->image->typeResize[$data->type_resize]',
				'editable'  => array(
					'type'  => 'select',
					'source' => Yii::app()->image->typeResize,
					'url'   => $this->createUrl('objects/addSize')
				)
			)
		)
)); ?>

<?php endif; ?>