<div id="language_div" class="<?php if(($currentLanguage!=Yii::app()->getLanguage())) echo 'alert'; ?> well form-inline control-group ">
	<label class="control-label" for="change_language"><?php echo Yii::t('admin','Language'); ?></label>
	<div class="controls">
		<select name="change_language" data-folder-id="<?php echo $folderId; ?>" id="change_language">
			<?php foreach(Yii::app()->params['languages'] as $key => $lang): ?>
				<option <?php if($key==$currentLanguage) echo 'selected'; ?> value="<?php echo $key; ?>"><?php echo $lang['title']; ?></option>
			<?php endforeach; ?>
		</select>
		<?php if($currentLanguage!=Yii::app()->getLanguage()): ?>
			<span id="other_language_label" class="label label-warning"><?php echo Yii::t('admin','Selected a language other than the selected global language'); ?></span>
		<?php endif; ?>
	</div>

</div>