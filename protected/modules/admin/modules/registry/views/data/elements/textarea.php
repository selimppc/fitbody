<?php
/**
 * @var $element Registry
 * @var $language string
 */
?>
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
						<textarea name="elementData[<?php echo $element->id ?>][<?php echo $key; ?>]" id="<?php echo $element->key.$key; ?>" rows="5"><?php echo $val->value; ?></textarea>
						<?php if(Yii::app()->user->role >= Yii::app()->static->accessLevelEdit): ?>
							<a href="#" data-id="<?php echo $element->id; ?>" title="delete" class="btn btn-mini pull-right delete_element"><i class="icon-remove"></i></a>
						<?php endif; ?>
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
				<textarea name="elementData[<?php echo $element->id ?>][<?php echo $key; ?>]" id="<?php echo $element->key.$key; ?>"  rows="5"></textarea>
				<?php if(Yii::app()->user->role >= Yii::app()->static->accessLevelEdit): ?>
					<a href="#" data-id="<?php echo $element->id; ?>" title="delete" class="btn btn-mini pull-right delete_element"><i class="icon-remove"></i></a>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
<?php endforeach; ?>
