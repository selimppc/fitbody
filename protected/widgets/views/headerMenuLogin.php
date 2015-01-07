<a class="personal_link fl_r" href="">
    <?php echo CHtml::image(Yii::app()->user->getState('image'), Yii::app()->user->getState('nickname')); ?>
    <span class="name"><?php echo CHtml::encode($name); ?></span>
</a>
<div class="personal_block">
    <ul>
        <li><a href="<?php echo Yii::app()->createUrl('profile/'.$profile_id); ?>">Профиль</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('profile/'.$profile_id.'/goals'); ?>">Цели</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('profile/'.$profile_id.'/progress'); ?>">Прогресс</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('profile/'.$profile_id.'/program'); ?>">Программа тренировок</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('profile/'.$profile_id.'/photo'); ?>">Фотографии</a></li>
        <li><a href="<?php echo Yii::app()->createUrl('profile/settings'); ?>">Настройки</a></li>
        <li class="logout"><a href="<?php echo Yii::app()->createUrl('authorization/logout'); ?>">Выйти</a></li>
    </ul>
</div>