<?php
/**
 * @var $this GridPopup
 */
?>
<div id="edit_popup_<?php echo $this->id; ?>" class="popover editable-popover fade in">
	<div class="arrow"></div>
	<div class="popover-inner">
		<h3 class="popover-title">Add new record</h3>
		<div class="popover-content">
			<div class="label label-important" id="<?php echo $this->id; ?>_popup_error" style="display: none;"></div>
			<div class="editable-loading" style="display: none;"></div>
			<?php
			$form=$this->beginWidget('CActiveForm', array(
				'htmlOptions'=>array('class'=>'form-horizontal'),
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'errorCssClass' => 'f_error',
					'validateOnSubmit'=>true,
					'validateOnChange'=>true,
					'validateOnType'=>false,
					'validationUrl' => Yii::app()->createUrl("admin/FormValidation" ),
					'ajaxVar' => 'ajax',
					'afterValidate' => "js:
						function(form, data, hasError) {
                            if (! hasError) {
                                $.ajax({
                                    type: 'POST',
                                    url: '".$this->addUri."',
                                    data: $(form).serialize(),
                                    success: function(response) {
                                        grid.popup.close('{$this->id}');
                                        grid.get('{$this->id}').updateGrid();
                                    },
                                    error: function(error) {
                                        AdminNotification.showError(error.responseText);
                                    }
                                });
                            }
							return false;
                        }"
				)
			)); ?>
			<?php echo $this->getFields($form); ?>
			<div style="padding-top: 15px;">
				<?php echo CHtml::submitButton('Save', array('class'=>'btn btn-gebo')); ?>
				<a href="" onclick="grid.popup.close('<?php echo $this->id; ?>'); return false;" class="btn">Close</a>
			</div>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>