<?php
/**
 * @var $folderForm FolderForm()
 * @var $form CActiveForm
 * @var $showForm bool
 * @var $folderId int
 * @var $elementForm ElementForm
 */
?>
<div class="span5">
	<div data-toggle="buttons-checkbox" class="btn-group">
		<button id="add_new_element_button" class="btn btn-info <?php echo ($showForm)?'active':''; ?>">Add element</button>
		<button id="create_child_folder" data-current="<?php echo $folderId; ?>" class="btn btn-info ">Create child folder</button>
<!--		<button class="btn btn-danger">Clear folder</button>-->
	</div>
	<div>
		<?php
		$form=$this->beginWidget('CActiveForm', array(
			'htmlOptions'=>array('class'=>'form-horizontal well span6 add_new_element_form','enctype'=>'multipart/form-data','style'=>"margin-top: 20px; margin-left: 0; ".(($showForm)?'':'display: none')),
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
				<p class="f_legend">Add new element <a id="hide_form_add_new_element" class="pull-right" href="">hide form</a></p>
				<input type="hidden" name="ElementForm[parent_category_id]" id="ElementForm_parent_category_id" value="<?php echo $folderId; ?>"/>
				<div class="control-group formSep">
					<?php echo $form->label($elementForm,'title',array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->textField($elementForm,'title') ?>
						<?php echo $form->error($elementForm,'title',array('class'=>'label label-important')); ?>
					</div>
				</div>
				<div class="control-group formSep">
					<?php echo $form->label($elementForm,'key',array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->textField($elementForm,'key') ?>
						<?php echo $form->error($elementForm,'key',array('class'=>'label label-important')); ?>
					</div>
				</div>

				<div class="control-group formSep">
					<?php echo $form->label($elementForm,'type',array('class'=>'control-label')); ?>
					<div class="controls">
						<?php echo $form->dropDownList($elementForm,'type',Registry::model()->getTypeList()) ?>
						<?php echo $form->error($elementForm,'type',array('class'=>'label label-important')); ?>
					</div>
				</div>
				<div class="control-group">
					<div class="controls">
						<button class="btn" type="submit">Add</button>
					</div>
				</div>
			</fieldset>
		<?php $this->endWidget(); ?>

		<?php $this->beginContent('folder_form',array(
			'folderForm' => $folderForm,
			'showForm' => false
		)); $this->endContent(); ?>
	</div>
</div>


<style type="text/css">
	.controls {
		margin-left: 110px !important;
	}
	.form-horizontal .control-label {
		width: 100px !important;
	}
</style>
