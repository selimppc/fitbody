

<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить статью: <?php echo $model->title; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новую Статью: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'articleForm',
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
	<?php echo $form->label($model,'article_subcategory_id',array('class'=>'control-label')); ?>
	<div class="controls">
		<?php echo $form->dropDownList(
			$model, "article_subcategory_id",
			CHtml::listData(ArticleSubcategory::model()->with('category')->findAll(array('condition' => 't.status = :status AND category.status = :status', 'params' => array(':status' => 1), 'order' => 't.title')), 'id', 'title', 'category.category'),array('empty'=>' -- Выберите категорию'));
		?>
		<?php  echo $form->error($model,'article_subcategory_id',array('class'=>'label label-important')); ?>
	</div>
</div>

<div class="control-group formSep">
    <?php echo $form->label($model,'introduction',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textArea($model,'introduction', array('class'=>'', 'maxlength' => 255, 'rows' => 6, 'cols' => 50)); ?>
        <?php  echo $form->error($model,'introduction',array('class'=>'label label-important')); ?>
    </div>
</div>
<div class="control-group formSep">
    <?php echo $form->label($model,'end_at',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textField($model,'end_at', array('class'=>'')); ?>
        <?php  echo $form->error($model,'end_at',array('class'=>'label label-important')); ?>
    </div>
</div>


<div class="control-group formSep">
    <?php echo $form->label($model,'image_id',array('class'=>'control-label')); ?>
    <?php echo $form->hiddenField($model,'image_id', array('class' => 'images')); ?>
    <div class="controls well">
        <?php  echo $form->error($model,'image_id',array('class'=>'label label-important', 'style' => 'margin: 10px 0px;')); ?>
        <?php
        $id = ($model->id) ? $model->id : '';
        $this->widget('ext.xupload.XUpload', array(
                'url' => Yii::app( )->createUrl( "/admin/article/article/uploadArticleImage/item/" . $id),
                'model' => $photos,
                'htmlOptions' => array('id'=>'articleForm'),
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
                                    $('#Article_image_id').attr('value', result[0].image_id);
                                }",
                    'added' => "js:function() {
                                    $('#articleForm .fileinput-button').addClass('disabled');
                                    $('#XUploadForm_file').prop('disabled', true);
                                }",
                    'destroyed' => "js: function() {
                                    $('#Article_image_id').attr('value','');
                                }",
                    'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                ),
            )
        );
        ?>
    </div>
</div>


<div class="control-group formSep">
    <?php echo $form->label($model, "article", array('class'=>'control-label')); ?>
    <div class="controls">
        <?php Yii::import('ext.imperavi.ImperaviRedactorWidget');
            $this->widget('ImperaviRedactorWidget', array(
                'model' => $model,
                'attribute' => 'article',
                'options' => array(
                    'lang' => 'ru',
                    "imageUpload" => "/admin/article/article/upload",
                    "minHeight" => 300
                ),
            ));
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

<div class="control-group formSep">
    <?php echo $form->label($model, "pick", array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->checkBox($model, "pick"); ?>
        <?php  echo $form->error($model, "pick", array('class'=>'label label-important')); ?>
    </div>
</div>

<div class="control-group formSep">
    <?php echo $form->label($model, "show", array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->checkBox($model, "show"); ?>
        <?php  echo $form->error($model, "show", array('class'=>'label label-important')); ?>
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
    $cs->registerScriptFile($this->assetUrl.'/lib/datetimepicker/js/bootstrap-datetimepicker.min.js',CClientScript::POS_END);
    $cs->registerCssFile($this->assetUrl.'/lib/datetimepicker/css/bootstrap-datetimepicker.min.css');
    $cs->registerScriptFile('/js/admin/uploader.js', CClientScript::POS_END);
    $cs->registerScriptFile('/js/admin/article.js', CClientScript::POS_END);
?>
