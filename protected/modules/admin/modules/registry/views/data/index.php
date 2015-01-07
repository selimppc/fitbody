<?php 
/**
 * @var $tree  array
 * @var $folderForm FolderForm()
 *
 */ 
?>

<div <?php if((!Yii::app()->static->isExistsNew || Yii::app()->user->role < Yii::app()->static->accessLevelEdit) && !$tree) echo 'style="display:none;"' ?> class="span4">
	<?php $this->beginContent('_migration_btns'); $this->endContent(); ?>
	<div <?php if(!$tree) echo 'style="display:none;"' ?> class="chat_sidebar">
		<?php echo $tree; ?>
	</div>
</div>

<div class="span9 form-horizontal">

	<div class="span5">
		<?php if(Yii::app()->user->role >= Yii::app()->static->accessLevelEdit): ?>
		<div  data-toggle="buttons-checkbox" class="btn-group">
			<button id="add_new_folder_button" class="btn btn-info active">Add folder</button>
		</div>
		<div>
			<?php $this->beginContent('folder_form',array(
				'folderForm' => $folderForm,
				'showForm' => true
			)); $this->endContent(); ?>
		</div>
		<?php endif; ?>
	</div>
</div>