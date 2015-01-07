<?php
	Yii::import('ext.chosen.Chosen');
?>
<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить упражнение: <?php echo $model->title; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новое упражнение: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>

<ul class="nav nav-tabs" id="exerciseTabs">
    <li><a href="#exercise" class="active" data-toggle="tab">Упражнение</a></li>
    <?php if ($model->isNewRecord):?>
        <li><a style="color:#aaa;" class="elementDownload" data-toggle="tooltip" title="Для добавления изображений создайте Упражнение">Загрузка изображений</a></li>
        <li><a style="color:#aaa;" class="elementDownload" data-toggle="tooltip" title="Для добавления видео создайте Упражнение">Загрузка видео</a></li>
        <script>
            $('.elementDownload').tooltip();
        </script>
    <?php else:?>
        <li><a href="#downloadImages" data-toggle="tab">Загрузка изображений</a></li>
        <li><a href="#downloadVideos" data-toggle="tab">Загрузка видео</a></li>
    <?php endif;?>
</ul>

<div class="tab-content">
    <div class="tab-pane" id="exercise">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id' => 'exerciseForm',
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
            <?php echo $form->label($model,'short_description',array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textArea($model,'short_description'); ?>
                <?php  echo $form->error($model,'short_description',array('class'=>'label label-important')); ?>
            </div>
        </div>

        <div class="control-group formSep">
            <?php echo $form->label($model,'type',array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->radioButtonList($model,'type',$dataType,array('separator'=>'<br>','labelOptions' => array('class' => 'label'), 'style' => 'margin-bottom:3px')); ?>
                <?php  echo $form->error($model,'type',array('class'=>'label label-important')); ?>
            </div>
        </div>

	    <div class="control-group formSep">
		    <?php echo $form->label($model,'muscle_id',array('class'=>'control-label')); ?>
		    <div class="controls">
			    <?php echo $form->dropDownList($model, "muscle_id",CHtml::listData($dataMuscle, 'id', 'muscle'),array('empty'=>' -- Выберите группу')); ?>
			    <?php  echo $form->error($model,'muscle_id',array('class'=>'label label-important')); ?>
		    </div>
	    </div>

	    <div class="control-group formSep">
		    <?php echo $form->label($model,'muscles',array('class'=>'control-label')); ?>
		    <div class="controls">
			    <?php echo Chosen::activeMultiSelect($model,'muscles',CHtml::listData($dataMuscle,'id', 'muscle'), array('class' => '')); ?>
			    <?php echo $form->error($model,'muscles',array('class'=>'label label-important')); ?>
		    </div>
	    </div>

        <div class="control-group formSep">
            <?php Yii::import('ext.imperavi.ImperaviRedactorWidget');

            $this->widget('ImperaviRedactorWidget', array(
                'model' => $model,
                'attribute' => 'description',
                'options' => array(
                    'lang' => 'ru',
                    "imageUpload" => "/admin/exercise/exercise/upload",
                    "minHeight" => 300
                ),
            ));

            ?>
        </div>

        <div class="control-group formSep">
            <?php echo $form->label($model,'video_link',array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model,'video_link',array('style' => 'width: 300px')); ?>
                <?php  echo $form->error($model,'video_link',array('class'=>'label label-important')); ?>
            </div>
        </div>

        <div class="control-group formSep" id="instructionField">
            <?php echo $form->label($model,'instruction',array('class'=>'control-label')); ?>
            <?php foreach($model->instructionArray as $key => $elem): ?>
                <div class="controls row" style="margin-top:4px;margin-bottom:4px;">
                    <?php echo $form->textArea($elem,'['.$key.']title',array('style' => 'width: 300px')); ?>
                    <a class="btn delete_instruction">Удалить</a>
                    <?php echo $form->error($elem,'title',array('class'=>'label label-important')); ?>
                </div>
            <?php endforeach; ?>
            <div class="controls" style="margin-top:4px;margin-bottom:4px;">
                <a id="add_instruction" class="btn">Добавить</a>
            </div>
        </div>

        <div class="control-group formSep">
            <?php echo $form->label($model,'rating',array('class'=>'control-label', 'for' => 'exercise')); ?>
            <div class="controls">
                <div class="jRatingExercise" data-average="<?php echo ($model->rating) ? $model->rating : 0 ;?>"></div>
                <?php echo $form->hiddenField($model, 'rating', array('class' => 'jRatingExerciseHidden')); ?>
                <?php  echo $form->error($model,'rating',array('class'=>'label label-important')); ?>
            </div>
        </div>

	    <div class="control-group formSep" style="display:none">
		    <?php echo $form->label($model,'image_id',array('class'=>'control-label', 'for' => 'exercise')); ?>
		    <?php echo $form->hiddenField($model,'image_id', array('class' => 'images')) ?>
		    <div class="controls">
			    <?php
			    $id = ($model->id) ? $model->id : '';
			    $this->widget('ext.xupload.XUpload', array(
					    'url' => Yii::app( )->createUrl( "/admin/exercise/exercise/uploadMuscleImages/item/" . $id),
					    'model' => $photos,
					    'htmlOptions' => array('id'=>'exerciseForm'),
					    'attribute' => 'file',
					    'multiple' => false,
					    'showForm' => false,
					    'formView' => 'ext.xupload.views.form',
					    'options' => array(
						    'submit' => "js:function (e, data) {
                                    //$('#Exercise_image_id').attr('value','');
                                    //data.formData = {itemId: " . $id . "};
                                    return true;
                                 }",
						    'success'=>"js:function(result, textStatus, jqXHR) {
                                    $('#Exercise_image_id').attr('value', result[0].image_id);
                                }",
						    'added' => "js:function() {
                                    $('#exerciseForm .fileinput-button').addClass('disabled');
                                    $('#XUploadForm_file').prop('disabled', true);
                                }",
						    'destroyed' => "js: function() {
                                    $('#Exercise_image_id').attr('value','');
                                }",
						    'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
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
    </div>


    <div class="tab-pane" id="downloadImages">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id' => 'exerciseImageForm',
            'htmlOptions'=>array('class'=>'form-horizontal ','enctype'=>'multipart/form-data', 'data-id' => ($model->id) ? $model->id : ''),
            'enableClientValidation' => false,
            'clientOptions'=>array(
                'errorCssClass' => 'f_error',
                'validateOnSubmit'=>true,
                'validateOnChange'=>true,
                'validateOnType'=>true
            )
        )); ?>

        <?php
        $id = ($model->id) ? $model->id : '';
        $this->widget('ext.xupload.XUpload', array(
                'url' => Yii::app( )->createUrl( "/admin/exercise/exercise/uploadExerciseImages/item/" . $id),
                'model' => $photos,
                'htmlOptions' => array('id'=>'exerciseImageForm'),
                'attribute' => 'file',
                'multiple' => true,
                'showForm' => false,
                'formView' => 'ext.xupload.views.form',
                'options' => array(

                    'maxNumberOfFiles' => 2,
                    'submit' => "js:function (e, data) {
                                    return true;
                        }",
                    'success'=>"js:function(result, textStatus, jqXHR) {
                                    return true;
                        }",
                    'added' => "js:function() {
                        return true;
                        }",
                    'destroyed' => "js: function() {
                                    return true;
                        }",
                    'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                    'getFilesFromResponse' => "js: function (data) {

                    console.log(11111)
                    return data.result.files;}"
                ),
            )
        );
        ?>
        <?php $this->endWidget(); ?>
    </div>

    <div class="tab-pane" id="downloadVideos">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id' => 'exerciseVideoForm',
            'htmlOptions'=>array('class'=>'form-horizontal ','enctype'=>'multipart/form-data', 'data-id' => ($model->id) ? $model->id : ''),
            'enableClientValidation' => false,
            'clientOptions'=>array(
                'errorCssClass' => 'f_error',
                'validateOnSubmit'=>true,
                'validateOnChange'=>true,
                'validateOnType'=>true
            )
        )); ?>

        <?php
        $id = ($model->id) ? $model->id : '';
        $this->widget('ext.xupload.XUpload', array(
                'url' => Yii::app( )->createUrl( "/admin/exercise/exercise/uploadExerciseVideos/item/" . $id),
                'model' => $photos,
                'htmlOptions' => array('id'=>'exerciseVideoForm'),
                'attribute' => 'file',
                'multiple' => true,
                'showForm' => false,
                'formView' => 'ext.xupload.views.form',
                'options' => array(
                    'submit' => "js:function (e, data) {
                                    return true;
                        }",
                    'success'=>"js:function(result, textStatus, jqXHR) {
                                    return true;
                        }",
                    'added' => "js:function() {
                        return true;
                        }",
                    'destroyed' => "js: function() {
                                    return true;
                        }",
                    //'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                ),
            )
        );
        ?>
        <?php $this->endWidget(); ?>
    </div>

</div>


<?php
Yii::app()->clientScript->registerCssFile('/css/admin/chosen.css');
Yii::app()->clientScript->registerScriptFile(
    $this->assetUrl . '/lib/jRating/jRating.jquery.min.js',
    CClientScript::POS_HEAD
);
Yii::app()->clientScript->registerCssFile(
    $this->assetUrl . '/lib/jRating/jRating.jquery.css'
);
Yii::app()->clientScript->registerScriptFile(
    '/js/admin/uploader.js',
    CClientScript::POS_END
);
Yii::app()->clientScript->registerScriptFile(
    '/js/admin/exercise.js',
    CClientScript::POS_END
);
?>
