<?php
/**
 *
 * @var $this Grid
 * @var $form CActiveForm
 */
?>
<div class="row-fluid">
	<div class="span<?php echo $this->size;?>">
		<?php if ($this->caption) {?>
            <h3 class="heading"><?php echo Yii::t('admin', $this->caption);?></h3>
		<?php }?>
        <div class="dataTables_wrapper form-inline" id="<?php echo $this->getId();?>">
            <div class="row">
                <div class="span12">
		            <?php $this->renderRecordsPerPage();?>
                </div>
            </div>
			<?php $this->renderGrid();?>
	        <div class="row">
		        <div class="span5">
			        <?php if(in_array('add',$this->actions)): ?>
			            <a class="btn btn-mini" onclick="var event = arguments[0] || window.event; event.stopPropagation(); grid.get('<?php echo $this->getId();?>').addRow('<?php echo $this->addUri; ?>',$(this)); return false;" href=""><i class="icon-plus"></i> Add new</a>
			        <?php
			            endif;
			            if(in_array('delete',$this->actions)):
			        ?>
			        <a class="btn btn-mini" href="javascript:;" onClick="grid.deleteSelected('<?php echo $this->getId();?>');"><i class="icon-trash"></i> Delete</a>
			        <?php endif; ?>
		        </div>
<!--				<div class="span2"><div class="dataTables_info" id="dt_a_info">--><?php //$this->renderSummary();?><!--</div></div>-->
                <div class="span7"><?php $this->renderPager();?></div>
	        </div>
        </div>
	</div>
</div>