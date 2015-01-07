<div class="tab active">
    <h2>
        Добавление нового прогресса
        <a href="<?php echo Yii::app()->createUrl('profile/'.Yii::app()->user->id.'/progress'); ?>" class="cancel_link fl_r">Отменить добавление</a>
    </h2>
    <div class="progress_edit_block ov">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id' => 'progressForm',
            'htmlOptions'=>array('enctype'=>'multipart/form-data', 'data-id' => ''),
            'enableClientValidation' => false,
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
                'validateOnChange'=>true,
                'validateOnType'=>true
            )
        )); ?>
            <div class="row">
                <label>
                    <span class="label">Заголовок для вашего прогресса</span>
                    <?php echo $form->textField($model,'title'); ?>
                </label>
            </div>
            <div class="img_before fl_l">
                <span class="label">Фотография “было”</span>
                <div class="img_cont <?php if ($form->error($model,'before_image_id') != "") echo "error"; ?>" id="image_before">
                    <?php if($model->before_image_id) echo Yii::app()->image->getImageTag($model->before_image_id,369,369, array('data-image' => $model->before_image_id)); ?>
                    <?php echo $form->hiddenField($model,'before_image_id'); ?>
                    <label id="label_before" for="ProfileProgress_before_main" <?php if(!$model->before_image_id) echo 'style="display:none"' ?>>
                        <?php echo $form->checkBox($model,'before_main'); ?>
                        Сделать главной фотографией “Было”
                    </label>
                </div>

                <div class="upload_link">
                    Загрузить фотографию
                    <?php echo CHtml::activeFileField($uploadModel, 'file', array('id' => 'fileupload_before','data-url' => "/profile/progress/upload")); ?>
                </div>
                <label class="fl_r datepicker_holder">
                    <?php echo $form->textField($model,'before_date', array('class' => 'datepicker')); ?>
                </label>
                <div class="clear">&nbsp;</div>
                <label><?php echo $form->textArea($model,'before_description', array('rows' => 10, 'cols' => 30, 'placeholder' => 'Комментарий к фотографии')); ?></label>
            </div>
            <div class="img_after fl_r">
                <span class="label">Фотография “стало”</span>
                <div class="img_cont <?php if ($form->error($model,'now_image_id') != "") echo "error"; ?>" id="image_now" >
                    <?php if($model->now_image_id) echo Yii::app()->image->getImageTag($model->now_image_id,369,369, array('data-image' => $model->now_image_id)); ?>
                    <?php echo $form->hiddenField($model,'now_image_id'); ?>
                    <label id="label_now" for="ProfileProgress_now_main" <?php if(!$model->now_image_id) echo 'style="display:none"' ?>>
                        <?php echo $form->checkBox($model,'now_main'); ?>
                        Сделать главной фотографией “Стало”
                    </label>
                </div>

                <div class="upload_link">
                    Загрузить фотографию
                    <?php echo CHtml::activeFileField($uploadModel, 'file', array('id' => 'fileupload_now','data-url' => "/profile/progress/upload")); ?>
                </div>
                <label class="fl_r datepicker_holder">
                    <?php echo $form->textField($model,'now_date', array('class' => 'datepicker')); ?>
                </label>
                <div class="clear">&nbsp;</div>
                <label><?php echo $form->textArea($model,'now_description', array('rows' => 10, 'cols' => 30, 'placeholder' => 'Комментарий к фотографии')); ?></label>
            </div>
            <div class="clear">&nbsp;</div>
            <div class="row bordered">
                <div class="row_inline">
                    <label>
                        <a href="javascript:void(0);" id="progressForm_save" class="color_btn blue">Сохранить</a>
                    </label>
                </div>
                <div class="row_inline">
                    <label><?php echo $form->checkBox($model,'status'); ?>Прогресс будет доступен всем пользователям</label>
                </div>

            </div>
        <?php $this->endWidget(); ?>
    </div>
</div>