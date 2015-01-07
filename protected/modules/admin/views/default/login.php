<?php
/**
 * @var $this Controller
 * @var $model LoginForm
 * @var $form CActiveForm
 */
?>
<div class="login_box">
	<?php $form = $this->beginWidget('CActiveForm', array('id' => 'login_form')); ?>
        <div class="top_b"><?php echo Yii::t('admin', 'Sign in to Admin Panel'); ?></div>
		<?php if ($model->hasErrors()) {?>
		    <div class="alert alert-error alert-login">
				<?php echo $form->errorSummary($model); ?>
		    </div>
		<?php } else { ?>
		    <div class="alert alert-info alert-login">
				<?php echo Yii::t('admin', 'Please enter email and password.'); ?>
		    </div>
		<?php }?>
        <div class="cnt_b">
            <div class="formRow">
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-user"></i></span>
	                <?php echo $form->textField($model,'username', array('placeholder' => $model->getAttributeLabel('login')));?>
                </div>
            </div>
            <div class="formRow">
                <div class="input-prepend">
                    <span class="add-on"><i class="icon-lock"></i></span>
	                <?php echo $form->passwordField($model, 'password', array('placeholder' => $model->getAttributeLabel('password')));?>
                </div>
            </div>
            <div class="formRow clearfix">
                <label class="checkbox" for="rememberMe"><?php echo $form->checkBox($model, 'rememberMe', array('id' => 'rememberMe'));?><?php echo $model->getAttributeLabel('rememberMe');?></label>
            </div>
        </div>
        <div class="btm_b clearfix">
	        <?php echo CHtml::submitButton(Yii::t('admin', 'Enter'), array('class' => 'btn btn-inverse pull-right')); ?>
        </div>
    <?php $this->endWidget('CActiveForm');?>
</div>
<script src="<?php echo $this->assetUrl;?>/js/jquery.min.js"></script>
<script src="<?php echo $this->assetUrl;?>/js/jquery.actual.min.js"></script>
<script src="<?php echo $this->assetUrl;?>/lib/validation/jquery.validate.js"></script>
<script src="<?php echo $this->assetUrl;?>/bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo $this->assetUrl;?>/js/jquery.cookie.min.js"></script>
<script src="<?php echo $this->assetUrl;?>/lib/UItoTop/jquery.ui.totop.min.js"></script>
<script>
    $(document).ready(function(){

        //* boxes animation
        form_wrapper = $('.login_box');
        function boxHeight() {
            form_wrapper.animate({ marginTop : ( - ( form_wrapper.height() / 2) - 24) },400);
        };
        form_wrapper.css({ marginTop : ( - ( form_wrapper.height() / 2) - 24) });
        $('.linkform a,.link_reg a').on('click',function(e){
            var target	= $(this).attr('href'),
                    target_height = $(target).actual('height');
            $(form_wrapper).css({
                'height'		: form_wrapper.height()
            });
            $(form_wrapper.find('form:visible')).fadeOut(400,function(){
                form_wrapper.stop().animate({
                    height	 : target_height,
                    marginTop: ( - (target_height/2) - 24)
                },500,function(){
                    $(target).fadeIn(400);
                    $('.links_btm .linkform').toggle();
                    $(form_wrapper).css({
                        'height'		: ''
                    });
                });
            });
            e.preventDefault();
        });

        //* validation
        $('#login_form').validate({
            onkeyup: false,
            errorClass: 'error',
            validClass: 'valid',
            rules: {
                'LoginForm[username]': { required: true, minlength: 3 },
                'LoginForm[password]': { required: true, minlength: 3 }
            },
            highlight: function(element) {
                $(element).closest('div').addClass("f_error");
                setTimeout(function() {
                    boxHeight()
                }, 200)
            },
            unhighlight: function(element) {
                $(element).closest('div').removeClass("f_error");
                setTimeout(function() {
                    boxHeight()
                }, 200)
            },
            errorPlacement: function(error, element) {
                $(element).closest('div').append(error);
            }
        });
    });
</script>