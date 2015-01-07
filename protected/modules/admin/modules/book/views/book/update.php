<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить книгу: <?php echo CHtml::encode($model->title); ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новую книгу: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>
<h3 class="heading">Описание</h3>
<?php $form = $this->beginWidget('CActiveForm', array(
    'id' => 'bookForm',
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
        <?php echo $form->textField($model,'title', array('class' => 'span6')); ?>
        <?php  echo $form->error($model,'title',array('class'=>'label label-important')); ?>
    </div>
</div>
<div class="control-group formSep">
    <?php echo $form->label($model,'description',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->textArea($model,'description', array('class' => 'span6', 'cols' => 15, 'rows' => 12)); ?>
        <?php  echo $form->error($model,'description',array('class'=>'label label-important')); ?>
    </div>
</div>
<div class="control-group formSep">
    <?php echo $form->label($model,'category_id',array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->dropDownList($model,'category_id', $categories, array('empty' => '-- Выберите категорию --')); ?>
        <?php  echo $form->error($model,'category_id',array('class'=>'label label-important')); ?>
    </div>
</div>
<div class="control-group">
    <?php echo $form->label($model, "status", array('class'=>'control-label')); ?>
    <div class="controls">
        <?php echo $form->checkBox($model, "status"); ?>
        <?php  echo $form->error($model, "status", array('class'=>'label label-important')); ?>
    </div>
</div>
<h3 class="heading">Главное изображение</h3>
<div class="control-group formSep">
    <?php echo $form->label($model,'image_id',array('class'=>'control-label')); ?>
    <?php echo $form->hiddenField($model,'image_id', array('class' => 'images')) ?>
    <div class="controls well">
        <?php  echo $form->error($model,'image_id',array('class'=>'label label-important', 'style' => 'margin: 10px 0px;')); ?>
        <?php
            $id = ($model->id) ? $model->id : '';
            $this->widget('ext.xupload.XUpload', array(
                    'id' => 'a1',
                    'url' => Yii::app( )->createUrl( "/admin/book/book/uploadBookImage/item/" . $id),
                    'model' => new XUploadForm,
                    'htmlOptions' => array('id'=>'bookForm'),
                    'attribute' => 'file',
                    'multiple' => false,
                    'formView' => 'ext.xupload.views.form',
                    'showForm' => false,
                    'options' => array(
                        'submit' => "js:function (e, data) {
                                        //$('#Book_image_id').attr('value','');
                                        //data.formData = {itemId: " . $id . "};
                                        return true;
                                     }",
                        'success'=>"js:function(result, textStatus, jqXHR) {
                                        $('#Book_image_id').attr('value', result[0].image_id);
                                    }",
                        'added' => "js:function() {
                                        $('#bookForm .fileinput-button').addClass('disabled');
                                        $('#XUploadForm_file').prop('disabled', true);
                                    }",
                        'destroyed' => "js: function() {
                                        $('#Book_image_id').attr('value','');
                                    }",
                        'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                    ),
                )
            );
        ?>
    </div>
</div>

<?php echo $form->hiddenField($model, "file"); ?>

<?php $this->endWidget(); ?>

<h3 class="heading">Файл</h3>
<div class="row">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'bookFileForm',
        'htmlOptions'=>array('class'=>'form-horizontal ','enctype'=>'multipart/form-data', 'data-id' => ($model->id) ? $model->id : ''),
        'enableClientValidation' => false,
        'clientOptions'=>array(
            'errorCssClass' => 'f_error',
            'validateOnSubmit'=>true,
            'validateOnChange'=>true,
            'validateOnType'=>true
        )
    )); ?>

    <div class="control-group" style="margin-left: 30px;">
        <?php echo $form->label($model,'file',array('class'=>'control-label')); ?>
        <div class="controls well">
            <?php
                $id = ($model->id) ? $model->id : '';
                $this->widget('ext.xupload.XUpload', array(
                        'url' => Yii::app( )->createUrl( "/admin/book/book/uploadBookFile/item/" . $id),
                        'model' => new XUploadFormFile,
                        'htmlOptions' => array('id' => 'bookFileForm'),
                        'attribute' => 'file',
                        'multiple' => false,
                        'formView' => 'ext.xupload.views.form',
                        'showForm' => false,
                        'options' => array(
                            'submit' => "js:function (e, data) {
                                                    //$('#Book_image_id').attr('value','');
                                                    //data.formData = {itemId: " . $id . "};
                                                    return true;
                                                 }",
                            'success'=>"js:function(result, textStatus, jqXHR) {
                                                    $('#Book_file').attr('value', result[0].file);
                                                }",
                            'added' => "js:function() {
                                                    $('#bookFormFile .fileinput-button').addClass('disabled');
                                                    $('#XUploadFormFile_file').prop('disabled', true);
                                                }",
                            'destroyed' => "js: function() {
                                                    $('#Book_file').attr('value','');
                                                }",
                            //'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                        ),
                    )
                );
            ?>
            <?php  echo $form->error($model,'file',array('class'=>'label label-important')); ?>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>
<div class="row">
    <div class="offset2 control-group">
        <div class="controls">
            <button id="save_btn" class="btn btn-info" name="save_and_close">Сохранить</button>
            <a href="<?php echo $listUri; ?>" class="btn">Закрыть</a>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).on('click', '#save_btn', function () {
        $('#bookForm').submit();
    });

</script>
<?php
    Yii::app()->clientScript
        ->registerScriptFile('/js/admin/uploader.js', CClientScript::POS_END)
        ->registerScriptFile('/js/admin/book.js', CClientScript::POS_END);

    if ($model->file) {
        Yii::app()->clientScript
            ->registerScript('flp', '
                $(function() {
                    var file = {
                        delete_type: "POST",
                        delete_url: "/admin/book/book/uploadBookFile/method/delete/filename/' . $model->file . '",
                        name: "' . $model->file .'",
                        url: "/books/download/' . $model->file_hash .'"
                    };
                    $("#bookFileForm").fileupload("option", "done").call($("#bookFileForm"), null,{result: [file]});
                    $("#bookFileForm .fileinput-button").addClass("disabled");
                    $("#bookFileForm #XUploadForm_file").prop("disabled", true);
                });
            ');
    }

?>