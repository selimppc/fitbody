<?php $form=$this->beginWidget('CActiveForm', array(
    'errorMessageCssClass' => 'review_error_cause',
    'htmlOptions' => array(),
    'action' => Yii::app()->request->getUrl() . '#comment-form'
)); ?>
<?php echo $form->hiddenField($model, 'parent_id', array('class' => 'comment_parent_id')); ?>
<?php if (!$this->controller->isGuest): ?>
    <a class="comment_photo fl_l" href="<?php echo Yii::app()->user->getState('urlProfile'); ?>">
        <?php echo CHtml::image(Yii::app()->user->getState('image'), Yii::app()->user->getState('nickname')); ?>
    </a>
<?php endif; ?>
    <div class="row with_pict">
        <?php echo $form->textArea($model, 'text', array('cols' => '30', 'rows' => '10', 'placeholder' => 'Ваш комментарий')); ?>
    </div>
    <div class="row with_pict">
        <input type="submit" value="Отправить комментарий" class="color_btn blue must-login" />
    </div>
<?php  echo $form->error($model, "text"); ?>
<?php $this->endWidget(); ?>