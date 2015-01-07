

<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить баннер: <?php echo $model->title; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новый баннер: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>

        <?php $form=$this->beginWidget('CActiveForm', array(
            'id' => 'bannerForm',
            'htmlOptions'=>array('class'=>'form-horizontal ','enctype'=>'multipart/form-data', 'data-id' => ($model->id) ? $model->id : ''),
            'enableClientValidation' => false,
            'clientOptions'=>array(
                'errorCssClass' => 'f_error',
                'validateOnSubmit'=>true,
                'validateOnChange'=>true,
                'validateOnType'=>true
            )
        )); ?>
        <div class="control-group formSep">
            <?php echo $form->label($model,'title',array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model,'title'); ?>
                <?php  echo $form->error($model,'title',array('class'=>'label label-important')); ?>
            </div>
        </div>
        <div class="control-group formSep">
            <?php echo $form->label($model,'url',array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model,'url'); ?>
                <?php  echo $form->error($model,'url',array('class'=>'label label-important')); ?>
            </div>
        </div>

        <div class="control-group formSep">
            <?php echo $form->label($model,'start_at',array('class'=>'control-label')); ?>
            <div class="controls">
                <div class="input-append datetimepicker">
                    <?php echo $form->textField($model,'start_at', array('data-format' => 'yyyy-MM-dd', 'placeholder' => 'ГГГГ-ММ-ДД')); ?>
                    <span class="add-on">
                        <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                    </span>
                </div>
                <?php echo $form->error($model,'start_at',array('class'=>'label label-important')); ?>
            </div>
        </div>
    <div class="control-group formSep">
        <?php echo $form->label($model,'end_at',array('class'=>'control-label')); ?>
        <div class="controls">
            <div class="input-append datetimepicker">
                <?php echo $form->textField($model,'end_at', array('data-format' => 'yyyy-MM-dd', 'placeholder' => 'ГГГГ-ММ-ДД')); ?>
                <span class="add-on">
                            <i data-time-icon="icon-time" data-date-icon="icon-calendar"></i>
                        </span>
            </div>
            <?php echo $form->error($model,'end_at',array('class'=>'label label-important')); ?>
        </div>
    </div>
    <div class="control-group formSep">
        <?php echo $form->label($model,'position_id',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model,'position_id', $position, array('empty' => ' -- Выберите позицию')) ?>
            <?php  echo $form->error($model,'position_id',array('class'=>'label label-important')); ?>
        </div>
        <div id="sizes">
            <?php foreach ($bannerPositions as $position) :?>
                <input type="hidden" data-id="<?php echo $position->id; ?>" data-width="<?php echo $position->width; ?>" data-height="<?php echo $position->height; ?>" />
            <?php endforeach; ?>
        </div>
    </div>
    <div class="control-group formSep">
        <?php echo $form->label($model,'image_id',array('class'=>'control-label')); ?>
        <?php echo $form->hiddenField($model,'filename', array('class' => 'images')); ?>
        <?php echo $form->hiddenField($model,'filename_real', array('class' => 'images')); ?>
        <div class="controls well">
            <?php  echo $form->error($model,'filename',array('class'=>'label label-important', 'style' => 'margin: 10px 0px;')); ?>
            <?php
            $id = ($model->id) ? $model->id : '';
            $this->widget('ext.xupload.XUpload', array(
                    'url' => Yii::app()->createUrl( "/admin/banner/banner/uploadBannerFiles/item/" . $id),
                    'model' => $photos,
                    'htmlOptions' => array('id'=>'bannerForm'),
                    'attribute' => 'file',
                    'multiple' => false,
                    'showForm' => false,
                    'formView' => 'ext.xupload.views.form',

                    'uploadView' => 'application.modules.admin.actions.banners.views.upload',

                    'downloadView' => 'application.modules.admin.actions.banners.views.download',
                    'options' => array(
                        'submit' => "js:function (e, data) {
                                return true;
                        }",
                        'success'=>"js:function(result, textStatus, jqXHR) {
                            $('#Banner_filename').attr('value', result[0].filename);
                            $('#Banner_filename_real').attr('value', result[0].filename_real);

                        }",
                        'added' => "js:function() {
                            $('#bannerForm .fileinput-button').addClass('disabled');
                            $('#XUploadForm_file').prop('disabled', true);

                        }",
                        'destroyed' => "js: function() {
                            $('#Banner_filename').attr('value','');
                            $('#Banner_filename_real').attr('value','');
                        }",
                        'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif|swf)$/i",
                        'completed' => "js:function() {
                           $('#Banner_position_id').trigger('change');
                        }",

                    ),
                )
            );
            ?>
        </div>
    </div>

    <div class="control-group formSep">
        <?php echo $form->label($model, "status", array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->checkBox($model, "status"); ?>
            <?php  echo $form->error($model, "status", array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <button id="save_btn" class="btn btn-info" name="save_and_close">Сохранить</button>
            <a href="<?php echo $listUri; ?>" class="btn">Закрыть</a>
        </div>
    </div>

    <?php $this->endWidget(); ?>

<?php
$cs = Yii::app()->clientScript;
$cs->registerScriptFile(
    $this->assetUrl . '/lib/datetimepicker/js/bootstrap-datetimepicker.min.js',
    CClientScript::POS_END
);
$cs->registerCssFile(
    $this->assetUrl . '/lib/datetimepicker/css/bootstrap-datetimepicker.min.css'
);
$cs->registerScriptFile('/js/admin/uploader.js', CClientScript::POS_END);
$cs->registerScriptFile('/js/admin/banner.js', CClientScript::POS_END);
?>
