<ul>
    <li class="<?php if($active == 'about')   echo 'active'; ?>"><?php echo CHtml::link('<span class="va_middle_in">О центре</span>','/club/'.$slug.'/about.html', array('class' => 'va_middle_out')); ?></li>
    <li class="<?php if($active == 'price')   echo 'active'; ?>"><?php echo CHtml::link('<span class="va_middle_in">Прайс-лист</span>','/club/'.$slug.'/price.html', array('class' => 'va_middle_out')); ?></li>
    <li class="<?php if($active == 'coaches') echo 'active'; ?>"><?php echo CHtml::link('<span class="va_middle_in">Тренеры</span>','/club/'.$slug.'/coaches.html', array('class' => 'va_middle_out')); ?></li>
    <li class="<?php if($active == 'news')    echo 'active'; ?>"><?php echo CHtml::link('<span class="va_middle_in">Новости</span>','/club/'.$slug.'/news.html', array('class' => 'va_middle_out')); ?></li>
    <li class="<?php if($active == 'reviews') echo 'active'; ?>"><?php echo CHtml::link('<span class="va_middle_in">Отзывы ('.$count.')</span>','/club/'.$slug.'/reviews.html', array('class' => 'va_middle_out')); ?></li>
</ul>