<div class="login_block">
    <p>Войти как пользователь Fitbody</p>
    <span>У вас еще нет аккаунта? <?php echo CHtml::link('Зарегистрироваться!', array('authorization/register')); ?></span>
    <span>Забыли пароль? <a href="<?php echo Yii::app()->createUrl('authorization/forgotPassword'); ?>">Восстановить</a></span>

    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'loginForm',
        'action' => (Yii::app()->createUrl('authorization/login') . '?redirect='. Yii::app()->request->getUrl() . (($target && !is_array($target)) ? ('&target=' . $target) : '')),
        'htmlOptions'=>array()
    )); ?>
    <div class="row">
        <div class="row_inline"><?php echo $form->textField($model, 'email', array('placeholder' => 'Email или никнейм')); ?></div>
        <div class="row_inline"><?php echo $form->passwordField($model, 'password', array('placeholder' => 'Пароль')); ?></div>
    </div>
    <?php if ($model->hasErrors()): ?>
        <ul class="error_cause">
            <?php
            $content = '';
            foreach($model->getErrors() as $errors) {
                foreach($errors as $error) {
                    if($error != '') {
                        $content .= "<li>- $error</li>\n";
                    }
                }
            }
            echo $content;
            ?>
        </ul>
    <?php endif; ?>
    <div class="row">
        <div class="row_inline"><input type="submit" value="Войти" class="h_34"></div>
        <div class="row_inline">
            <label>
                <?php echo $form->checkBox($model, 'rememberMe'); ?>
                Запомнить меня
            </label>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
<div class="login_block">
    <p>Войти через социальные сети</p>
    <ul class="social_btns_login">
        <li><a href="<?php echo $vkUrl; ?>" class="vk_btn"></a></li>
        <li><a href="<?php echo $facebookUrl; ?>" class="fb_btn"></a></li>
    </ul>
</div>