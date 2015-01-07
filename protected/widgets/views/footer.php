<footer><!--open footer-->
	<div class="box">
		<?php $this->widget('application.widgets.FooterMenuWidget'); ?>
		<div class="footer_bottom">
			<div class="copy">&copy; 2014 Fitbody.by. Все права защищены.</div>
			<div class="design fl_r">Дизайн и разработка - <a href="http://pixelplex.by/">Pixelplex</a></div>
			<div class="footer_nav">
				<ul>
					<li><?php echo CHtml::link('О проекте', array('/about')); ?></li>
					<li><?php echo CHtml::link('Реклама на сайте', array('/advertising')); ?></li>
					<li><?php echo CHtml::link('Партнерам', array('/partnership')); ?></li>
					<li><?php echo CHtml::link('Контакты', array('/contacts')); ?></li>
				</ul>
			</div>
		</div>
	</div>
</footer><!--close footer-->