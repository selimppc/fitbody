<?php
/**
 * @var $element Registry
 * @var $language string
 */
?>
<div class="control-group formSep">
	<div class="controls">

	</div>
</div>


<?php foreach(Yii::app()->params['languages'] as $key => $lang): ?>
		<?php $exists = false; ?>
		<?php foreach($element->value as $val): ?>
			<?php if($val->language == $key): ?>
				<?php $exists=true; ?>
				<div <?php if($language!=$key) echo 'style="display:none;"' ?> data-language="<?php echo $key; ?>" class="control-group formSep">
					<?php if(Yii::app()->user->role >= Yii::app()->static->accessLevelEdit): ?>
						<span class="help-block"><?php echo $element->path; ?></span>
					<?php endif; ?>
					<div class="control-label">
						<label for="<?php echo $element->key . $key; ?>"><?php echo $element->title; ?></label>
					</div>
					<div class="controls">
						<?php if(Yii::app()->user->role >= Yii::app()->static->accessLevelEdit): ?>
							<a href="#" data-id="<?php echo $element->id; ?>" title="delete" class="btn btn-mini pull-right delete_element"><i class="icon-remove"></i></a>
						<?php endif; ?>
						<div data-provides="fileupload" class="fileupload <?php echo ($val->value) ? 'fileupload-exists' : 'fileupload-new'; ?> ">
							<input value='<?php echo $val->value; ?>' type="hidden" id="<?php echo $element->key.$key; ?>" name="elementData[<?php echo $element->id ?>][<?php echo $key; ?>]"/>
							<input type="hidden">
							<div style="max-width: 150px; max-height: 150px; margin-right: 10px;" class="fileupload-preview fileupload-exists thumbnail">
								<?php echo ($val->value) ? Yii::app()->image->getImageTag($val->value,150,150) : ''; ?>
							</div>
							<span class="btn btn-file">
								<span class="fileupload-new">Select image</span>
								<span class="fileupload-exists">Change</span>
								<input  id="<?php echo $element->key.$key; ?>" type="file" name="elementData_<?php echo $element->id ?>_<?php echo $key; ?>">
							</span>
						</div>
					</div>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
		<?php if(!$exists): ?>
		<div <?php if($language!=$key) echo 'style="display:none;"' ?> data-language="<?php echo $key; ?>" class="control-group formSep">
			<?php if(Yii::app()->user->role >= Yii::app()->static->accessLevelEdit): ?>
				<span class="help-block"><?php echo $element->path; ?></span>
			<?php endif; ?>
			<div class="control-label">
				<label for="<?php echo $element->key . $key; ?>"><?php echo $element->title; ?></label>
			</div>
			<div class="controls">
				<div data-provides="fileupload" class="fileupload fileupload-new">
					<input type="hidden" id="<?php echo $element->key.$key; ?>" name="elementData[<?php echo $element->id ?>][<?php echo $key; ?>]"/>
					<input type="hidden">
					<div style="max-width: 150px; max-height: 150px; margin-right: 10px;" class="fileupload-preview fileupload-exists thumbnail"></div>
						<span class="btn btn-file">
							<span class="fileupload-new">Select image</span>
							<span class="fileupload-exists">Change</span>
							<input id="<?php echo $element->key.$key; ?>" type="file" name="elementData_<?php echo $element->id ?>_<?php echo $key; ?>">
						</span>
				</div>
				<?php if(Yii::app()->user->role >= Yii::app()->static->accessLevelEdit): ?>
					<a href="#" data-id="<?php echo $element->id; ?>" title="delete" class="btn btn-mini pull-right delete_element"><i class="icon-remove"></i></a>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
<?php endforeach; ?>
