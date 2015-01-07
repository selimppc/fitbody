<ul>
    <li class="<?php if($active == 'about')   echo 'active'; ?>"><?php echo CHtml::link('<span class="va_middle_in">О центре</span>','/shop/'.$slug.'/about.html', array('class' => 'va_middle_out')); ?></li>
    <li class="<?php if($active == 'news')    echo 'active'; ?>"><?php echo CHtml::link('<span class="va_middle_in">Новости</span>','/shop/'.$slug.'/news.html', array('class' => 'va_middle_out')); ?></li>
    <li class="<?php if($active == 'reviews') echo 'active'; ?>"><?php echo CHtml::link('<span class="va_middle_in">Отзывы ('.$count.')</span>','/shop/'.$slug.'/reviews.html', array('class' => 'va_middle_out')); ?></li>
</ul>