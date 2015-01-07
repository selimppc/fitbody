<?php
/**
 * @var $form CActiveForm
 * @var $model User::model()
 */
?> 
<?php
	$form=$this->beginWidget('CActiveForm', array(
		'htmlOptions'=>array('class'=>'form-horizontal','enctype'=>'multipart/form-data'),
		'enableClientValidation'=>true,
			'clientOptions'=>array(
				'errorCssClass' => 'f_error',
				'validateOnSubmit'=>true,
				'validateOnChange'=>false,
				'validateOnType'=>false
			)
	)); ?>
	<?php if(isset($model->id)): ?>
		<h3 class="heading">Update user: <?php echo $model->email; ?></h3>
	<?php else: ?>
		<h3 class="heading">Add new User</h3>
	<?php endif; ?>
<?php if(isset($success)&&$success): ?>
	<div class="alert alert-success">
		<a class="close" data-dismiss="alert">×</a>
		<strong>Обновлено</strong>
	</div>
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
        <?php echo $form->label($model,'first_name',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model,'first_name') ?>
            <?php  echo $form->error($model,'first_name',array('class'=>'label label-important')); ?>
        </div>
    </div>
	<div class="control-group formSep">
		<?php echo $form->label($model,'last_name',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->textField($model,'last_name') ?>
			<?php  echo $form->error($model,'last_name',array('class'=>'label label-important')); ?>
		</div>
	</div>
    <div class="control-group formSep">
        <?php echo $form->label($model,'nickname',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model,'nickname') ?>
            <?php  echo $form->error($model,'nickname',array('class'=>'label label-important')); ?>
        </div>
    </div>
    <div class="control-group formSep">
        <?php echo $form->label($model,'birthday',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model,'birthday') ?>
            <?php  echo $form->error($model,'birthday',array('class'=>'label label-important')); ?>
        </div>
    </div>
    <div class="control-group formSep">
        <?php echo $form->label($model,'gender',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'gender', User::model()->getGenderList()); ?>
            <?php  echo $form->error($model,'gender',array('class'=>'label label-important')); ?>
        </div>
    </div>
    <div class="control-group formSep">
        <?php echo $form->label($model, 'country_id',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model, 'country_id', CHtml::listData(Country::model()->findAll(), 'id', 'title')); ?>
            <?php  echo $form->error($model, 'country_id',array('class'=>'label label-important')); ?>
        </div>
    </div>
    <div class="control-group formSep">
        <?php echo $form->label($model, 'city', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'city'); ?>
            <?php  echo $form->error($model, 'city',array('class'=>'label label-important')); ?>
        </div>
    </div>
	<div class="control-group formSep">
		<?php echo $form->label($model,'login',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php  echo $form->textField($model,'login'); ?>
			<?php  echo $form->error($model,'login',array('class'=>'label label-important')); ?>
		</div>
	</div>
	<div class="control-group formSep">
		<?php echo $form->label($model,'email',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->emailField($model,'email'); ?>
			<?php echo $form->error($model,'email',array('class'=>'label label-important')); ?>
		</div>
	</div>
	<div class="control-group formSep">
		<?php echo $form->label($model,'password',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->passwordField($model,'password',array('value'=>'')) ?>
			<?php  echo $form->error($model,'password',array('class'=>'label label-important')); ?>
		</div>
	</div>
	<div class="control-group formSep">
		<?php echo $form->label($model,'role_id',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->dropDownList($model,'role_id',CHtml::listData(UserRole::model()->findAll(),'id','title')) ?>
			<?php echo $form->error($model,'role_id',array('class'=>'label label-important')); ?>
		</div>
	</div>
	<div class="control-group formSep">
		<?php echo $form->label($model,'status',array('class'=>'control-label')); ?>
		<div class="controls">
			<?php echo $form->dropDownList($model,'status',User::model()->statusList) ?>
			<?php echo $form->error($model,'status',array('class'=>'label label-important')); ?>
		</div>
	</div>
	<?php if($model->image_id): ?>
	<div class="control-group formSep">
		<div class="controls">
			<?php echo Yii::app()->image->getImageTag($model->image_id,250,250); ?>
		</div>
	</div>
	<?php endif; ?>
	<div class="control-group formSep">
		<label for="avatar" class="control-label">Avatar</label>
		<div class="controls">
			<div data-provides="fileupload" class="fileupload fileupload-new"><input type="hidden" value="" name="">
				<span class="btn btn-file">
					<span class="fileupload-new">Select file</span>
					<span class="fileupload-exists">Change</span>
					<input type="file" id="avatar" name="avatar" />
				</span>
				<span class="fileupload-preview"></span>
				<a style="float: none" data-dismiss="fileupload" class="close fileupload-exists" href="#">×</a>
			</div>
		</div>
	</div>
	<div class="control-group">
		<div class="controls">
			<?php $this->widget('application.modules.admin.widgets.SaveBtn'); ?>
			<a href="<?php echo $listUri; ?>" class="btn">Close</a>
		</div>
	</div>
<?php $this->endWidget(); ?>