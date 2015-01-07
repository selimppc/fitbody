

<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить тренера: <?php echo $model->name; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить нового тренера: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>


<ul class="nav nav-tabs" id="coachTabs">
    <li><a href="#club" class="active" data-toggle="tab">Тренер</a></li>
    <?php if ($model->isNewRecord):?>
        <li><a style="color:#aaa;" class="elementDownload" data-toggle="tooltip" title="Для добавления изображений создайте Тренера">Загрузка изображений</a></li>
        <script>
            $('.elementDownload').tooltip();
        </script>
    <?php else:?>
        <li><a href="#downloadImages" data-toggle="tab">Загрузка изображений</a></li>
    <?php endif;?>
</ul>

<div class="tab-content">
    <div class="tab-pane" id="club">
    <?php $form=$this->beginWidget('CActiveForm', array(
        'id' => 'coachForm',
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
        <?php echo $form->label($model,'name',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model, 'name', array('class' => 'span5')); ?>
            <?php  echo $form->error($model,'name',array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group formSep">
        <?php echo $form->label($model,'email',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model,'email', array('class' => 'span5')); ?>
            <?php  echo $form->error($model,'email',array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group formSep">
        <?php echo $form->label($model,'skype',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model,'skype', array('class' => 'span5')); ?>
            <?php  echo $form->error($model,'skype',array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group formSep">
        <?php echo $form->label($model,'website',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textField($model,'website', array('class' => 'span5', 'placeholder' => 'Например, http://fitbody.by')); ?>
            <?php  echo $form->error($model,'website',array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group formSep">
        <?php echo $form->label($model,'short_description',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->textArea($model,'short_description', array('class' => 'span5')); ?>
            <?php  echo $form->error($model,'short_description',array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group formSep">
        <?php echo $form->label($model->categoryMainVar,'category_id',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->dropDownList($model->categoryMainVar, "category_id", $categories, array('empty' => '- Выберите основную категорию -', 'class' => 'span5')); ?>
            <?php  echo $form->error($model->categoryMainVar,'category_id',array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->label($model,'categories',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo Chosen::activeMultiSelect($model, 'categories', $categories , array('class' => 'span5')); ?>
            <?php echo $form->error($model,'categories',array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->label($model,'clubs',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo Chosen::activeMultiSelect($model, 'clubs', $clubs , array('class' => 'span5')); ?>
            <?php echo $form->error($model,'clubs',array('class'=>'label label-important')); ?>
        </div>

        <script type="phone/template" id="clubTemplate">
            <?php $obj = new CoachSimpleClub(); ?>
            <div class="controls">
                <br/>
                <?php echo $form->textField($obj, "[__count__]title") ?>
                <button class="btn btn-warning btn-small delete-club">Удалить клуб</button>
                <?php  echo $form->error($obj, "[__count__]title",array('class'=>'label label-important')); ?>
            </div>
        </script>

        <?php foreach ($model->simpleClubsArr as $k => $simpleClub): ?>
            <div class="controls">
                <br/>
                <?php echo $form->textField($simpleClub, "[$k]title"); ?>
                <button class="btn btn-warning btn-small delete-club">Удалить клуб</button>
                <?php echo $form->error($simpleClub, "[$k]title", array('class'=>'label label-important')); ?>
            </div>
        <?php endforeach; ?>

        <div class="controls">
            <br/>
            <input type="hidden" class="countClubs" value="<?php echo count($model->simpleClubsArr); ?>">
            <button class="btn btn-info btn-small add-club">Добавить клуб</button>
        </div>
    </div>

    <div class="control-group">
        <?php echo $form->label($model,'cost',array('class'=>'control-label')); ?>
        <div class="controls">
            <?php Yii::import('ext.imperavi.ImperaviRedactorWidget');
            $this->widget('ImperaviRedactorWidget', array(
                'model' => $model,
                'attribute' => 'cost',
                'options' => array(
                    'buttons' => array('html', 'bold', 'italic', 'underline'),
                    'lang' => 'ru',
                    "imageUpload" => "/admin/coach/coach/upload",
                    "minHeight" => 150
                ),
            ));
            ?>
            <?php echo $form->error($model,'cost',array('class'=>'label label-important')); ?>
        </div>
    </div>


    <script type="phone/template" id="phoneTemplate">
        <?php $obj = new CoachPhone(); ?>
        <div class="control-group">
            <?php echo $form->label($obj, "[__count__]phone",array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($obj, "[__count__]phone") ?>
                <?php echo $form->textField($obj, "[__count__]description", array('placeholder' => 'Описание')) ?>
                <button class="btn btn-warning btn-small delete-phone">Удалить телефон</button>
                <?php  echo $form->error($obj, "[__count__]phone",array('class'=>'label label-important')); ?>
            </div>
        </div>
    </script>


    <h3 class="heading">Контакты</h3>
    <?php foreach ($model->phonesArr as $k => $phone): ?>
        <div class="control-group">
            <?php echo $form->label($phone, "[$k]phone", array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($phone, "[$k]phone"); ?>
                <?php echo $form->textField($phone, "[$k]description", array('placeholder' => 'Описание')); ?>
                <button class="btn btn-warning btn-small delete-phone">Удалить телефон</button>
                <?php echo $form->error($phone, "[$k]phone", array('class'=>'label label-important')); ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="control-group">
        <div class="controls">
            <input type="hidden" class="countPhones" value="<?php echo count($model->phonesArr); ?>">
            <button class="btn btn-info btn-small add-phone">Добавить телефон</button>
        </div>
    </div>





    <h3 class="heading">О себе</h3>
    <div class="control-group formSep">
        <?php echo $form->label($model,'image_id',array('class'=>'control-label')); ?>
        <?php echo $form->hiddenField($model,'image_id', array('class' => 'images')) ?>
        <div class="controls well">
            <?php  echo $form->error($model,'image_id',array('class'=>'label label-important', 'style' => 'margin: 10px 0px;')); ?>
            <?php
            $id = ($model->id) ? $model->id : '';
            $this->widget('ext.xupload.XUpload', array(
                    'url' => Yii::app( )->createUrl( "/admin/coach/coach/uploadCoachImage/item/" . $id),
                    'model' => new XUploadForm,
                    'htmlOptions' => array('id'=>'coachForm'),
                    'attribute' => 'file',
                    'multiple' => false,
                    'formView' => 'ext.xupload.views.form',
                    'showForm' => false,
                    'options' => array(
                        'submit' => "js:function (e, data) {
                                    //$('#Coach_image_id').attr('value','');
                                    //data.formData = {itemId: " . $id . "};
                                    return true;
                                 }",
                        'success'=>"js:function(result, textStatus, jqXHR) {
                                    $('#Coach_image_id').attr('value', result[0].image_id);
                                }",
                        'added' => "js:function() {
                                    $('#coachForm .fileinput-button').addClass('disabled');
                                    $('#XUploadForm_file').prop('disabled', true);
                                }",
                        'destroyed' => "js: function() {
                                    $('#Coach_image_id').attr('value','');
                                }",
                        'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                    ),
                )
            );
            ?>
        </div>
    </div>

    <h3 class="heading">Свойства</h3>

    <script type="param/template" id="paramTemplate">
        <?php $obj = new CoachPropertyLink(); ?>
        <div class="control-group">
            <?php echo $form->label($obj, "[__count__]property_id",array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo Chosen::activeDropDownList($obj, "[__count__]property_id", $properties , array('class' => 'span5', 'empty' => '', 'options' => array( 'allowSingleDeselect' => true))); ?><br /><br />
                <?php echo $form->textArea($obj, "[__count__]description", array('class' => 'span5')) ?>
                <button class="btn btn-warning btn-small delete-param">Удалить свойство</button>
            </div>
            <div class="controls">
                <?php  echo $form->error($obj, "[__count__]property_id",array('class'=>'label label-important')); ?>
                <?php  echo $form->error($obj, "[__count__]description",array('class'=>'label label-important')); ?>
            </div>
        </div>
    </script>
    <!--End templates-->

    <?php foreach ($model->properties as $i => $property): ?>
        <div class="control-group">
            <?php echo $form->label($property, "[$i]property_id",array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo Chosen::activeDropDownList($property, "[$i]property_id", $properties , array('class' => 'span5', 'empty' => '', 'options' => array( 'allowSingleDeselect' => true))); ?><br /><br />
                <?php echo $form->textArea($property, "[$i]description", array('class' => 'span5')) ?>
                <button class="btn btn-warning btn-small delete-param">Удалить свойство</button>
            </div>
            <div class="controls">
                <?php  echo $form->error($property, "[$i]property_id",array('class'=>'label label-important')); ?>
                <?php  echo $form->error($property, "[$i]description",array('class'=>'label label-important')); ?>
            </div>
        </div>

    <?php endforeach; ?>
    <div class="control-group">
        <div class="controls">
            <input type="hidden" class="countParam" value="<?php echo count($model->properties); ?>">
            <button class="btn btn-info btn-small add-param">Добавить свойство</button>
        </div>
    </div>

    <div class="control-group formSep">
        <?php echo $form->label($model, 'about', array('class'=>'control-label')); ?>
        <div class="controls">
            <?php
            $this->widget('ImperaviRedactorWidget', array(
                'model' => $model,
                'attribute' => 'about',
                'options' => array(
                    'lang' => 'ru',
                    "imageUpload" => "/admin/coach/coach/upload",
                    "minHeight" => 300
                ),
            ));
            ?>
            <?php  echo $form->error($model->categoryMainVar, 'about', array('class'=>'label label-important')); ?>
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
        <?php echo $form->label($model, "is_recommended", array('class'=>'control-label')); ?>
        <div class="controls">
            <?php echo $form->checkBox($model, "is_recommended"); ?>
            <?php  echo $form->error($model, "is_recommended", array('class'=>'label label-important')); ?>
        </div>
    </div>

    <div class="control-group">
        <div class="controls">
            <button id="save_btn" class="btn btn-info" name="save_and_close">Сохранить</button>
            <a href="<?php echo $uri; ?>" class="btn">Закрыть</a>
        </div>
    </div>

    <?php $this->endWidget(); ?>
    </div>
    <div class="tab-pane" id="downloadImages">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id' => 'coachImageForm',
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
                'url' => Yii::app( )->createUrl( "/admin/coach/coach/uploadCoachImages/item/" . $id),
                'model' => $photos,
                'htmlOptions' => array('id'=>'coachImageForm'),
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
                    'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                    'getFilesFromResponse' => "js: function (data) {

                    return data.result.files;}"
                ),
            )
        );
        ?>
        <?php $this->endWidget(); ?>
    </div>
</div>



<?php
    $cs = Yii::app()->clientScript
        ->registerScriptFile('/js/admin/coach.js', CClientScript::POS_END)
        ->registerScriptFile('/js/admin/uploader.js', CClientScript::POS_END)
        ->registerCssFile('/css/admin/chosen.css');
?>
