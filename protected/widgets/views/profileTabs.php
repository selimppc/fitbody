<ul>
    <?php if(Yii::app()->user->id == $profile_id): ?>
        <li class="profile_settings"><a href="<?php echo Yii::app()->createUrl('profile/settings'); ?>" class="va_middle_out"><span class="va_middle_in">Настройки</span></a></li>
    <?php endif; ?>
    <li class="<?php echo ($active == 'index'    ? 'active' :''); ?>"><a href="<?php echo Yii::app()->createUrl('profile/'.$profile_id); ?>" class="va_middle_out"><span class="va_middle_in">Профиль</span></a></li>
    <?php if($this->show_goals): ?>
        <li id="goals-tab" class="<?php echo ($active == 'goals'    ? 'active' :''); ?>"><a href="<?php echo Yii::app()->createUrl('profile/'.$profile_id.'/goals'); ?>" class="va_middle_out"><span class="va_middle_in">Цели</span></a></li>
    <?php endif; ?>
    <?php if($this->show_progress): ?>
        <li id="progress-tab" class="<?php echo ($active == 'progress' ? 'active' :''); ?>"><a href="<?php echo Yii::app()->createUrl('profile/'.$profile_id.'/progress'); ?>" class="va_middle_out"><span class="va_middle_in">Прогресс</span></a></li>
    <?php endif; ?>
    <?php if($this->show_program): ?>
        <li id="program-tab" class="<?php echo ($active == 'program'  ? 'active' :''); ?>"><a href="<?php echo Yii::app()->createUrl('profile/'.$profile_id.'/program'); ?>" class="va_middle_out"><span class="va_middle_in">Программа тренировок</span></a></li>
    <?php endif; ?>
    <?php if($this->show_photo): ?>
        <li class="<?php echo ($active == 'photo'    ? 'active' :''); ?>"><a href="<?php echo Yii::app()->createUrl('profile/'.$profile_id.'/photo'); ?>" class="va_middle_out"><span class="va_middle_in">Фото</span></a></li>
    <?php endif; ?>
    <?php if(false): ?>
        <li class="<?php echo ($active == 'activity' ? 'active' :''); ?>"><a href="<?php echo Yii::app()->createUrl('profile/'.$profile_id.'/activity'); ?>" class="va_middle_out"><span class="va_middle_in">Активность</span></a></li>
    <?php endif; ?>
</ul>