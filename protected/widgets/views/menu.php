<nav class="top_nav clearfix">
	<ul class="fl_l">
		<li <?php echo ($item == 'news' || $item == 'article') ? 'class="active"' : ''; ?>>
            <a href="<?php echo Yii::app()->createUrl('news/index'); ?>">Статьи</a>
            <?php if ($articleCategories): ?>
                <ul class="sub_top_nav">
                    <?php foreach ($articleCategories as $articleCategory): ?>
                        <li><a href="<?php echo $articleCategory->getUrl(); ?>"><?php echo FunctionHelper::upperFirst($articleCategory->category); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
		<li <?php echo ($item == 'exercises' || $item == 'exercise') ? 'class="active"' : ''; ?>>
            <?php echo CHtml::link('База упражнений', array('exercises/with-weights')); ?>
            <?php if ($exerciseCategories): ?>
                <ul class="sub_top_nav">
                    <?php foreach ($exerciseCategories as $exerciseCategory): ?>
                        <li><a href="<?php echo $exerciseCategory->getUrl(); ?>"><?php echo FunctionHelper::upperFirst($exerciseCategory->muscle); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
		<li <?php echo ($item == 'programs' || $item == 'program') ? 'class="active"' : ''; ?>>
            <?php echo CHtml::link('Программы тренировок', array('programs/index')); ?>
            <?php if ($programCategories): ?>
                <ul class="sub_top_nav">
                    <?php foreach ($programCategories as $programCategory): ?>
                        <li><a href="<?php echo $programCategory->getUrl(); ?>"><?php echo FunctionHelper::upperFirst($programCategory->title); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
		<li <?php echo ($item == 'books' || $item == 'calculators') ? 'class="active"' : ''; ?>>
            <a>Полезное</a>
            <ul class="sub_top_nav">
                <li><a href="<?php echo Yii::app()->createUrl('books/index'); ?>">Книги</a></li>
                <li><a href="<?php echo Yii::app()->createUrl('calculators/index'); ?>">Калькуляторы</a></li>
            </ul>
        </li>
		<li><a href="/forum.html">Форум</a></li>
	</ul>

	<ul class="fl_r y_color">
		<li <?php echo ($item == 'club') ? 'class="active"' : ''; ?>>
            <?php echo CHtml::link('Фитнес клубы', array('club/list'));?>
            <?php if ($clubDestinations): ?>
                <ul class="sub_top_nav">
                    <?php foreach ($clubDestinations as $destination): ?>
                        <li><a href="<?php echo $destination->getUrl(); ?>"><?php echo FunctionHelper::upperFirst($destination->destination); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
		<li <?php echo ($item == 'coaches' || $item == 'coach') ? 'class="active"' : ''; ?>>
            <?php echo CHtml::link('Персональные тренеры', array('coaches/index')); ?>
            <?php if ($coachCategories): ?>
                <ul class="sub_top_nav">
                    <?php foreach ($coachCategories as $coachCategory): ?>
                        <li><a href="<?php echo $coachCategory->getCoachCategoryUrl(); ?>"><?php echo FunctionHelper::upperFirst($coachCategory->title); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
        <li <?php echo ($item == 'shops' || $item == 'shop') ? 'class="active"' : ''; ?>>
            <?php echo CHtml::link('Магазины', array('shop/list')); ?>
            <?php if ($shopCategories): ?>
                <ul class="sub_top_nav">
                    <?php foreach ($shopCategories as $shopCategory): ?>
                        <li><a href="<?php echo $shopCategory->getUrl(); ?>"><?php echo FunctionHelper::upperFirst($shopCategory->title); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </li>
    </ul>
</nav>