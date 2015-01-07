<?php if (!$voted):?>
    <?php $form = $this->beginWidget('CActiveForm', array(
            'id' => 'rating-form',
            'errorMessageCssClass' => 'review_error_cause',
            'htmlOptions' => array(),
            'action' => (Yii::app()->request->getUrl() . '#voted-rating-success')
        )); ?>
        <label for="rating_select">Оценка</label>
        <?php echo $form->dropDownList($model, "rating", array_combine(range(1,10), range(1,10)), array('class' => 'styler', 'id' => 'rating_select')); ?>
        <input type="submit" value="Оценить" class="must-login">
    <?php $this->endWidget(); ?>
<?php else: ?>
    <span id="voted-rating-success"class="rated">Ваша оценка принята</span>
<?php endif; ?>