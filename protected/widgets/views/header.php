<header><!--open header-->
	<div class="box">
		<a href="<?php echo Yii::app()->getBaseUrl(true);  ?>" class="logo">fitbody</a>
		<div class="search_box">
            <?php echo CHtml::beginForm(array('search/index'), 'get'); ?>
            <?php echo CHtml::textField('q', Yii::app()->request->getParam('q', ''), array('placeholder' => 'Поиск на сайте')); ?>
            <?php echo CHtml::submitButton('', array('name' => '')); ?>
            <?php echo CHtml::endForm(); ?>
		</div>
		<div class="social_network">
			<span>Мы в соцсетях:</span>
			<ul>
				<li class="google"><a href="https://plus.google.com/u/0/b/105455302128585929368/105455302128585929368/posts"></a></li>
				<li class="fb"><a href="https://www.facebook.com/fitbody.by"></a></li>
				<li class="vk"><a href="https://vk.com/fitbodyby"></a></li>
				<li class="twitter"><a href="https://twitter.com/FitBodyBy"></a></li>
			</ul>
		</div>
        <?php if (Yii::app()->controller->isGuest): ?>
            <?php $this->widget('application.widgets.HeaderMenuNotLoginWidget'); ?>
        <?php else: ?>
            <?php $this->widget('application.widgets.HeaderMenuLoginWidget'); ?>
        <?php endif; ?>
	</div>
</header><!--close header-->