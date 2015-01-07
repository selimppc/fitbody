<div class="aside_item banner_main">
    <div class="banner_main_info">
        <h5>Bodyspace</h5>
        <span>
            Онлайн органайзер для спортсменов с полезными функциями и удобным дизайном
        </span>
    </div>
    <?php if ($isGuest): ?>
        <a href="<?php echo Yii::app()->createUrl('authorization/register'); ?>" class="color_btn_large">Бесплатная регистрация</a>
    <?php else: ?>
        <a href="<?php echo Yii::app()->createUrl('profile/profile/index', array('user_id' => Yii::app()->user->id)); ?>" class="color_btn_large">Профиль</a>
    <?php endif; ?>
</div>