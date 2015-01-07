<aside class="fl_r">
    <?php
    $dependency = new CChainedCacheDependency(array(
        //new CTagCacheDependency(Coach::COACH_CACHE_TAG), // coaches
        new CTagCacheDependency(Club::CLUB_CACHE_TAG), // newPlace
        new CDbCacheDependency('SELECT MAX(updated_at) FROM banner LIMIT 1'), // banners
    ));
    if ($this->beginCache(__FILE__.__LINE__, array('duration' => 3600, 'dependency'=> $dependency))): ?>
        <?php $this->widget('application.widgets.SidebarWidget',array(
            'organizer' => true,
            'newPlace' => true,
            'upperRightBanner' => true,
            'bottomRightBanner' => true,
            'coaches'  => false,
        )); ?>
    <?php $this->endCache(); endif; ?>
</aside>

<div class="main inner_page_nosplit clearfix">
    <div class="top_with_bg">
        <h1>Востановление пароля</h1>
        <?php $form = $this->beginWidget('CActiveForm', array(
            'errorMessageCssClass' => 'review_error_cause',
            'htmlOptions' => array()
        )); ?>

            <div class="recovery_block">
                <div class="row_inline">
                    <label>
                        Электронная почта *
                        <?php echo $form->textField($model, 'email', array('placeholder' => 'Введите электронную почту')); ?>
                    </label>
                </div>
            </div>

            <?php if ($model->hasErrors()): ?>
                <div class="row">
                        <ul class="error_cause">
                            <?php
                            $content = '';
                            foreach($model->getErrors() as $errors) {
                                foreach($errors as $error) {
                                    if($error != '') {
                                        $content.="<li>- $error</li>\n";
                                    }
                                }
                            }
                            echo $content;
                            ?>
                        </ul>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="row_inline"><input type="submit" value="Отправить новый пароль" class="h_34"/></div>
            </div>


        <?php $this->endWidget(); ?>

    </div>
</div>