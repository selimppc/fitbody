<?php
/**
 * @var $folderForm FolderForm()
 * @var $form CActiveForm
 * @var $showForm bool
 */
?>

		<?php
		$form=$this->beginWidget('CActiveForm', array(
			'htmlOptions'=>array('class'=>'form-horizontal well span6 add_new_folder_form','enctype'=>'multipart/form-data','style'=>"margin-top: 20px; margin-left: 0; ".(($showForm)?'':'display: none')),
			'enableClientValidation'=>true,
			'method' => 'POST',
			'clientOptions'=>array(
				'errorCssClass' => 'f_error',
				'validateOnSubmit'=>true,
				'validateOnChange'=>false,
				'validateOnType'=>false
			)
		)); ?>
			<fieldset>
				<p class="f_legend">Add new folder <a id="hide_form_add_new_folder" class="pull-right" href="">hide form</a></p>
				<input type="hidden" name="FolderForm[parent_category_id]" id="FolderForm_parent_category_id" value=""/>
				<div class="control-group formSep">
					<?php echo $form->label($folderForm,'title',array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->textField($folderForm,'title') ?>
						<?php echo $form->error($folderForm,'title',array('class'=>'label label-important')); ?>
					</div>
				</div>
				<div class="control-group formSep">
					<?php echo $form->label($folderForm,'key',array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->textField($folderForm,'key') ?>
						<?php echo $form->error($folderForm,'key',array('class'=>'label label-important')); ?>
					</div>
				</div>

				<div class="control-group">
					<div class="controls">
						<button id="add_folder_button" class="btn" type="submit">Add</button>
					</div>
				</div>
			</fieldset>
		<?php $this->endWidget(); ?>

<style type="text/css">
	.controls {
		margin-left: 80px !important;
	}
	.form-horizontal .control-label {
		width: 70px !important;
	}
</style>
