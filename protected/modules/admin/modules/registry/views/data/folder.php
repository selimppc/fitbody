<?php
/**
 * @var $tree  array
 * @var $elementForm ElementForm
 * @var $folderId int
 * @var $elements array
 * @var $folderForm FolderForm
 * @var $this DataController
 */
?>

<div class="span4">
	<?php $this->beginContent('_migration_btns'); $this->endContent(); ?>
	<div class="chat_sidebar">
		<?php echo $tree; ?>
	</div>
</div>

<div class="span9 form-horizontal">

	<?php if(Yii::app()->user->role >= Yii::app()->static->accessLevelEdit): ?>
	<?php $this->beginContent('__top_toolbar', array(
		'folderForm' => $folderForm,
		'folderId' => $folderId,
		'elementForm' => $elementForm,
		'showForm' => ($elements == array())
	)); $this->endContent(); ?>
	<?php endif; ?>
	<?php $currentLanguage = (isset($_SESSION['registry_lang'][$folderId]))?$_SESSION['registry_lang'][$folderId]:Yii::app()->getLanguage(); ?>
	<div style="margin-top: 30px;" class="span9">
		<?php if(count($elements) && count(Yii::app()->params['languages'])>1): ?>
			<?php $this->beginContent('_language_select', array(
				'folderId' => $folderId,
				'currentLanguage' => $currentLanguage
			)); $this->endContent(); ?>
		<?php endif; ?>
		<form action="" method="post" enctype="multipart/form-data">
			<?php foreach($elements as $element): ?>
				<?php $this->beginContent('elements/'.$element->type, array(
					'element' => $element,
					'language' => $currentLanguage
				)); $this->endContent(); ?>
			<?php endforeach; ?>
			<?php if(count($elements)): ?>
				<div class="control-group">
					<?php echo CHtml::submitButton('Save', array('class'=>'btn btn-gebo','style'=>'width:150px;')); ?>
				</div>
			<?php endif; ?>
		</form>
	</div>
</div>

<style type="text/css">
	.well {
		padding: 19px !important;
	}
</style>