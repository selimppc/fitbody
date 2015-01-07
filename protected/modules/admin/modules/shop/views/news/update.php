<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить новость: <?php echo $model->title; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новую новость: </h3>
<?php endif; ?>

    <hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'newsForm',
    'htmlOptions'=>array('class'=>'form-horizontal ','enctype'=>'multipart/form-data', 'data-id' => ($model->id) ? $model->id : ''),
    'enableClientValidation' => false,
    'clientOptions'=>array(
        'errorCssClass' => 'f_error',
        'validateOnSubmit'=>true,
        'validateOnChange'=>true,
        'validateOnType'=>true
    )
)); ?>

    <div class="control-group">
        <?php echo $form->label($model,'shop_id',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo Chosen::activeDropDownList($model, "shop_id", $shops , array('empty' => '', 'options' => array( 'allowSingleDeselect' => true))); ?>
            <?php echo $form->error($model,'shop_id',array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group formSep">
        <?php echo $form->label($model,'title',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model,'title'); ?>
            <?php  echo $form->error($model,'title',array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group formSep">
        <?php echo $form->label($model,'short_description',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textArea($model,'short_description'); ?>
            <?php  echo $form->error($model,'short_description',array('class'=>'label label-important')); ?>
        </div>
    </div>
    <div class="control-group formSep">

        <?php echo $form->label($model,'description',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php Yii::import('ext.imperavi.ImperaviRedactorWidget');
            $this->widget('ImperaviRedactorWidget', array(
                'model' => $model,
                'attribute' => 'description',
                'options' => array(
                    'lang' => 'ru',
                    "imageUpload" => "/admin/shop/news/upload",
                    "minHeight" => 300
                ),
            ));?>
        </div>
    </div>

    <div class="control-group formSep">
        <?php echo $form->label($model, "status", array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->checkBox($model, "status"); ?>
            <?php  echo $form->error($model, "status", array('class'=>'label label-important')); ?>
        </div>
    </div>



    <div class="control-group formSep">
        <?php echo $form->label($model,'image_id',array('class'=>'control-label')); ?>
        <?php echo $form->hiddenField($model,'image_id', array('class' => 'images')) ?>
        <div class="controls well">
            <?php  echo $form->error($model,'image_id',array('class'=>'label label-important', 'style' => 'margin: 10px 0px;')); ?>
            <?php
            $id = ($model->id) ? $model->id : '';
            $this->widget('ext.xupload.XUpload', array(
                    'url' => Yii::app( )->createUrl( "/admin/shop/news/uploadNewsImage/item/" . $id),
                    'model' => $photos,
                    'htmlOptions' => array('id'=>'newsForm'),
                    'attribute' => 'file',
                    'multiple' => false,
                    'formView' => 'ext.xupload.views.form',
                    'showForm' => false,
                    'options' => array(
                        'submit' => "js:function (e, data) {
                                    //$('#Material_image_id').attr('value','');
                                    //data.formData = {itemId: " . $id . "};
                                    return true;
                                 }",
                        'success'=>"js:function(result, textStatus, jqXHR) {
                                    $('#ShopNews_image_id').attr('value', result[0].image_id);
                                }",
                        'added' => "js:function() {
                                    $('#newsForm .fileinput-button').addClass('disabled');
                                    $('#XUploadForm_file').prop('disabled', true);
                                }",
                        'destroyed' => "js: function() {
                                    $('#ShopNews_image_id').attr('value','');
                                }",
                        'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                    ),
                )
            );
            ?>
        </div>
    </div>


    <div class="control-group">
        <div class="controls">
            <button id="save_btn" class="btn btn-info" name="save_and_close">Сохранить</button>
            <a href="<?php echo $uri; ?>" class="btn">Закрыть</a>
        </div>
    </div>

<?php $this->endWidget(); ?>

<?php
$cs = Yii::app()->clientScript;
$cs->registerCssFile('/css/admin/chosen.css');
$cs->registerScriptFile('/js/admin/uploader.js', CClientScript::POS_END);
$cs->registerScriptFile('/js/admin/shopNews.js', CClientScript::POS_END);
?>