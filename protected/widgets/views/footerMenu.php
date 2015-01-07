<nav class="bottom_nav">
	<ul>
        <?php if ($articleCategories): ?>
            <li>
                <a href="<?php echo Yii::app()->createUrl('news/index')?>">Статьи</a>
                <ul class="sub_bottom_nav">
                    <?php foreach($articleCategories as $elem): ?>
                        <li><?php echo CHtml::link(FunctionHelper::upperFirst($elem->category), array('news/category/'.$elem->slug));?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endif; ?>
        <?php if ($exerciseCategories): ?>
            <li>
                <a href="<?php echo Yii::app()->createUrl('exercises/with-weights')?>">База упражнений</a>
                <ul class="sub_bottom_nav">
                    <?php foreach($exerciseCategories as $muscle): ?>
                        <li><?php echo CHtml::link(FunctionHelper::upperFirst($muscle->muscle), array('exercises/with-weights/'.$muscle->slug));?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endif; ?>
        <?php if ($programCategories): ?>
            <li>
                <a href="<?php echo Yii::app()->createUrl('programs/index'); ?>">Программы тренировок</a>
                <ul class="sub_bottom_nav">
                    <?php foreach($programCategories as $programCategory): ?>
                        <li><?php echo CHtml::link(FunctionHelper::upperFirst($programCategory->title), $programCategory->getUrl()); ?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endif; ?>
		<li>
			<a>Полезное</a>
			<ul class="sub_bottom_nav">
				<li><a href="<?php echo Yii::app()->createUrl('calculators/index'); ?>">Калькуляторы</a></li>
				<li><a href="<?php echo Yii::app()->createUrl('books/index'); ?>">Книги</a></li>
			</ul>
		</li>
        <?php if ($coachCategories): ?>
            <li class="y_color">
                <?php echo CHtml::link('Персональные тренеры', array('coaches/index')); ?>
                <ul class="sub_bottom_nav">
                    <?php foreach($coachCategories as $coachCategory): ?>
                        <li><?php //echo CHtml::link(FunctionHelper::upperFirst($coachCategory->title), $coachCategory->getCoachCategoryUrl()); ?></li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php endif; ?>
        <li class="y_color"><?php echo CHtml::link('Фитнес клубы', array('club/list')); ?></li>
		<li class="y_color"><?php echo CHtml::link('Спортивные магазины', array('shop/list')); ?></li>
	</ul>
</nav>