

<?php if(isset($model->id)): ?>
    <h3 class="mb_30">Обновить клуб: <?php echo $model->club; ?></h3>
<?php else: ?>
    <h3 class="mb_30">Добавить новый клуб: </h3>
<?php endif; ?>

<hr />

<?php echo CHtml::errorSummary($model, null, null, array('class' => 'alert alert-error mb_30')); ?>


<ul class="nav nav-tabs" id="clubTabs">
        <li><a href="#club" class="active" data-toggle="tab">Клуб</a></li>
    <?php if ($model->isNewRecord):?>
        <li><a style="color:#aaa;" class="elementDownload" data-toggle="tooltip" title="Для добавления изображений создайте Клуб">Загрузка изображений</a></li>
        <script>
            $('.elementDownload').tooltip();
        </script>
    <?php else:?>
        <li><a href="#downloadImages" data-toggle="tab">Загрузка изображений</a></li>
    <?php endif;?>
</ul>


<div class="tab-content">
    <div class="tab-pane" id="club">

        <h3 class="heading">Описание клуба</h3>

        <?php $form=$this->beginWidget('CActiveForm', array(
            'id' => 'clubForm',
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
            <?php echo $form->label($model,'club',array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model,'club'); ?>
                <?php  echo $form->error($model,'club',array('class'=>'label label-important')); ?>
            </div>
        </div>


        <div class="control-group <?php echo ($model->radio == 0 ? 'formSep' : ''); ?>" id="chain_radio">
            <?php echo $form->label($model,'radio',array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->radioButtonList($model,'radio',array('0' => 'Нету', '1' => 'Выбрать из списка', '2' => 'Создать'),array('separator'=>'<br>','labelOptions' => array('class' => 'label'), 'style' => 'margin-bottom:3px')); ?>
                <?php  echo $form->error($model,'radio',array('class'=>'label label-important')); ?>
            </div>
        </div>
        <div class="control-group formSep" id="chainSelect_field" <?php echo (($model->radio == 2 || $model->radio == 0) ? 'style="display:none"' : ''); ?>>
            <?php echo $form->label($model, "chain_id",array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->dropDownList($model, "chain_id",$chains,array('empty' => '- Название сети -')); ?>
                <?php  echo $form->error($model, "chain_id",array('class'=>'label label-important')); ?>
            </div>
        </div>
        <div class="control-group formSep" id="chainName_field" <?php echo (($model->radio == 1 || $model->radio == 0) ? 'style="display:none"' : ''); ?>>
            <?php echo $form->label($chain,'title',array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($chain,'title'); ?>
                <?php  echo $form->error($chain,'title',array('class'=>'label label-important')); ?>
            </div>
        </div>


        <div class="control-group">
            <?php echo $form->label($model,'site',array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textField($model,'site'); ?>
                <?php  echo $form->error($model,'site',array('class'=>'label label-important')); ?>
            </div>
        </div>

        <div class="control-group">
            <?php echo $form->label($model,'destinations',array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo Chosen::activeMultiSelect($model, 'destinations', $destinations , array('class' => '')); ?>
                <?php echo $form->error($model,'destinations',array('class'=>'label label-important')); ?>
            </div>
        </div>
        <div class="control-group">
            <?php echo $form->label($model,'short_description',array('class'=>'control-label')); ?>
            <div class="controls">
                <?php echo $form->textArea($model,'short_description'); ?>
                <?php  echo $form->error($model,'short_description',array('class'=>'label label-important')); ?>
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

        <!--Templates-->
        <script type="address/template" id="propertyTemplate">
            <?php $obj = new ClubAddress(); ?>
            <div class="well">
                <h3 class="heading">Адрес</h3>
                <div class="propertyRow" data-count-row="__count__">
                    <div class="control-group">
                        <?php echo $form->label($obj, "[__count__]city_id",array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo Chosen::activeDropDownList($obj, "[__count__]city_id", $cities , array('empty' => '', 'options' => array( 'allowSingleDeselect' => true))); ?>
                            <?php  echo $form->error($obj, "[__count__]city_id",array('class'=>'label label-important')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <?php echo $form->label($obj, "[__count__]address",array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($obj, "[__count__]address") ?>
                            <?php  echo $form->error($obj, "[__count__]address",array('class'=>'label label-important')); ?>
                        </div>
                    </div>
                    <h3 class="heading">Контакты</h3>
                    <div class="control-group">
                        <div class="controls">
                            <input type="hidden" class="countPhones" value="0">
                            <button class="btn btn-info btn-small add-phone">Добавить телефон</button>
                        </div>
                    </div>
                    <h3 class="heading">Рабочее время</h3>
                    <div class="control-group">
                        <div class="controls">
                            <input type="hidden" class="countWorktime" value="0">
                            <button class="btn btn-info btn-small add-worktime">Добавить время работы</button>
                        </div>
                    </div>
                    <hr />
                    <div class="controls text-right">
                        <div class="controls">
                            <button class="btn btn-danger btn-small delete-property">Удалить адрес</button>
                        </div>
                    </div>
                    <hr />
                </div>
            </div>
        </script>

        <script type="phone/template" id="phoneTemplate">
            <?php $obj = new ClubPhone(); ?>
            <div class="control-group">
                <?php echo $form->label($obj, "[__count__][__countProperty__]phone",array('class'=>'control-label')); ?>
                <div class="controls">
                    <?php echo $form->textField($obj, "[__count__][__countProperty__]phone") ?>
                    <?php echo $form->textField($obj, "[__count__][__countProperty__]description", array('placeholder' => 'Контактное лицо')) ?>
                    <button class="btn btn-warning btn-small delete-phone">Удалить телефон</button>
                    <?php  echo $form->error($obj, "[__count__][__countProperty__]phone",array('class'=>'label label-important')); ?>
                </div>
            </div>
        </script>

        <script type="worktime/template" id="worktimeTemplate">
            <?php $obj = new ClubWorktime(); ?>
            <div class="worktime-container">
                <div class="control-group">
                    <?php echo $form->label($obj, "[__count__][__countProperty__]id",array('class'=>'control-label')); ?>
                    <div class="controls">
                        <div class="range-slider" style="width: 300px; margin-top: 10px;"></div>
                        <div class="days" style="margin-top: 20px; "></div>
                        <?php echo $form->error($obj, "[__count__][__countProperty__]city_id", array('class'=>'label label-important')); ?>
                        <?php echo $form->hiddenField($obj, "[__count__][__countProperty__]from_day", array('class'=>'label label-important slider-from_day')); ?>
                        <?php echo $form->hiddenField($obj, "[__count__][__countProperty__]to_day", array('class'=>'label label-important slider-to_day')); ?>
                    </div>
                </div>
                <div class="control-group">
                    <div class="controls">
                        <div class="datetimepicker input-append date">
                            <?php echo $form->textField($obj, "[__count__][__countProperty__]from_time", array('data-format' => "hh:mm", 'placeholder' => 'ЧЧ:ММ', 'class' => 'onDateTimePicker')); ?>
                            <span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
                        </div>
                        <div class="datetimepicker input-append date">
                            <?php echo $form->textField($obj, "[__count__][__countProperty__]to_time", array('data-format' => "hh:mm", 'placeholder' => 'ЧЧ:ММ', 'class' => 'onDateTimePicker')); ?>
                            <span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
                        </div>
                        <button class="btn btn-warning btn-small delete-worktime">Удалить рабочее время</button>
                        <div>
                            <?php  echo $form->error($obj, "[__count__][__countProperty__]from_time",array('class'=>'label label-important')); ?>
                            <?php  echo $form->error($obj, "[__count__][__countProperty__]city_id",array('class'=>'label label-important')); ?>
                        </div>

                    </div>
                </div>
            </div>

        </script>


        <script type="param/template" id="paramTemplate">
            <?php $obj = new ClubPropertyLink(); ?>
            <div class="control-group">
                <?php echo $form->label($obj, "[__count__]club_property_id",array('class'=>'control-label')); ?>
                <div class="controls">
                    <?php echo Chosen::activeDropDownList($obj, "[__count__]club_property_id", $properties , array('empty' => '', 'options' => array( 'allowSingleDeselect' => true))); ?>
                    <?php echo $form->textField($obj, "[__count__]description") ?>
                    <button class="btn btn-warning btn-small delete-param">Удалить направление</button>
                </div>
                <div class="controls">
                    <?php  echo $form->error($obj, "[__count__]club_property_id",array('class'=>'label label-important')); ?>
                    <?php  echo $form->error($obj, "[__count__]description",array('class'=>'label label-important')); ?>
                </div>
            </div>
        </script>
        <!--End templates-->

        <h3 class="heading">Направления</h3>

        <?php foreach ($model->properties as $i => $property): ?>
            <div class="control-group">
                <?php echo $form->label($property, "[$i]club_property_id",array('class'=>'control-label')); ?>
                <div class="controls">
                    <?php echo Chosen::activeDropDownList($property, "[$i]club_property_id", $properties , array('empty' => '', 'options' => array( 'allowSingleDeselect' => true))); ?>
                    <?php echo $form->textField($property, "[$i]description") ?>
                    <button class="btn btn-warning btn-small delete-param">Удалить направление</button>
                </div>
                <div class="controls">
                    <?php  echo $form->error($property, "[$i]club_property_id",array('class'=>'label label-important')); ?>
                    <?php  echo $form->error($property, "[$i]description",array('class'=>'label label-important')); ?>
                </div>
            </div>

        <?php endforeach; ?>
        <div class="control-group">
            <div class="controls">
                <input type="hidden" class="countParam" value="<?php echo count($model->properties); ?>">
                <button class="btn btn-info btn-small add-param">Добавить направление</button>
            </div>
        </div>
        <div class="control-group formSep">
            <?php Yii::import('ext.imperavi.ImperaviRedactorWidget');

            $this->widget('ImperaviRedactorWidget', array(
                'model' => $model,
                'attribute' => 'description',
                'options' => array(
                    'lang' => 'ru',
                    "imageUpload" => "/admin/club/club/upload",
                    "minHeight" => 300
                ),
            ));

            ?>
        </div>


        <!--Params-->
        <?php if($model->addresses): ?>
        <?php foreach ($model->addresses as $i => $item): ?>
            <div class="well">
                <div class="propertyRow" data-id="<?php echo $item->id; ?>" data-count-row="<?php echo $i; ?>">
                    <h3 class="heading">Адрес</h3>
                    <div class="control-group">
                        <?php echo $form->label($item, "[$i]city_id",array('class'=>'control-label')); ?>
                        <div class="controls" id="city_field">
                            <?php echo Chosen::activeDropDownList($item, "[$i]city_id", $cities , array('empty' => '', 'options' => array( 'allowSingleDeselect' => true))); ?>
                            <?php  echo $form->error($item, "[$i]city_id",array('class'=>'label label-important')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <?php echo $form->label($item, "[$i]address",array('class'=>'control-label')); ?>
                        <div class="controls" id="address_field">
                            <?php echo $form->textField($item, "[$i]address") ?>
                            <?php  echo $form->error($item, "[$i]address",array('class'=>'label label-important')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls" id="coords">
                            <?php echo $form->hiddenField($item, "[$i]lat", array('class' => 'lat')) ?>
                            <?php echo $form->hiddenField($item, "[$i]lon", array('class' => 'lon')) ?>
                            <button class="btn btn-info btn-small" id="address_show">Показать на карте</button>
                            <?php  echo $form->error($item, "[$i]lat",array('class'=>'label label-important')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <div id="map" style="width: 659px; height: 455px"></div>
                        </div>
                    </div>

                    <h3 class="heading">Контакты</h3>
                    <?php foreach ($item->phonesArr as $k => $phone): ?>
                        <div class="control-group">
                            <?php echo $form->label($phone, "[$k][$i]phone",array('class'=>'control-label')); ?>
                            <div class="controls">
                                <?php echo $form->textField($phone, "[$k][$i]phone") ?>
                                <?php echo $form->textField($phone, "[$k][$i]description", array('placeholder' => 'Контактное лицо')); ?>
                                <button class="btn btn-warning btn-small delete-phone">Удалить телефон</button>
                                <?php  echo $form->error($phone, "[$k][$i]phone",array('class'=>'label label-important')); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="control-group">
                        <div class="controls">
                            <input type="hidden" class="countPhones" value="<?php echo count($item->phonesArr); ?>">
                            <button class="btn btn-info btn-small add-phone">Добавить телефон</button>
                        </div>
                    </div>



                    <h3 class="heading">Рабочее время</h3>
                    <?php foreach ($item->worktimesArr as $j => $time): ?>
                        <?php $time->from_time = ($time->from_time) ? date('H:i', strtotime($time->from_time)): $time->from_time; ?>
                        <?php $time->to_time = ($time->to_time) ? date('H:i', strtotime($time->to_time)): $time->to_time; ?>
                        <div class="worktime-container">
                            <div class="control-group">
                                <?php echo $form->label($time, "[$j][$i]id",array('class'=>'control-label')); ?>
                                <div class="controls">
                                    <div class="range-slider" style="width: 300px; margin-top: 10px;"></div>
                                    <div class="days" style="margin-top: 20px; "></div>
                                    <?php echo $form->error($time, "[$j][$i]from_day", array('class'=>'label label-important')); ?>
                                    <?php echo $form->error($time, "[$j][$i]to_day", array('class'=>'label label-important')); ?>
                                    <?php echo $form->hiddenField($time, "[$j][$i]from_day", array('class'=>'label label-important slider-from_day')); ?>
                                    <?php echo $form->hiddenField($time, "[$j][$i]to_day", array('class'=>'label label-important slider-to_day')); ?>
                                </div>
                            </div>
                            <div class="control-group">
                                <div class="controls">
                                    <div class="datetimepicker input-append date">
                                        <?php echo $form->textField($time, "[$j][$i]from_time", array('data-format' => "hh:mm", 'placeholder' => 'ЧЧ:ММ', 'class' => 'onDateTimePicker')); ?>
                                        <span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
                                    </div>
                                    <div class="datetimepicker input-append date">
                                        <?php echo $form->textField($time, "[$j][$i]to_time", array('data-format' => "hh:mm", 'placeholder' => 'ЧЧ:ММ', 'class' => 'onDateTimePicker')); ?>
                                        <span class="add-on"><i data-time-icon="icon-time" data-date-icon="icon-calendar"></i></span>
                                    </div>
                                    <button class="btn btn-warning btn-small delete-worktime">Удалить рабочее время</button>
                                    <div>
                                        <?php  echo $form->error($time, "[$j][$i]from_time",array('class'=>'label label-important')); ?>
                                        <?php  echo $form->error($time, "[$j][$i]to_time",array('class'=>'label label-important')); ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="control-group">
                        <div class="controls">
                            <input type="hidden" class="countWorktime" value="<?php echo count($item->worktimesArr); ?>">
                            <button class="btn btn-info btn-small add-worktime">Добавить время работы</button>
                        </div>
                    </div>

                    <h3 class="heading">Дополнительно</h3>
                    <div class="control-group">
                        <?php echo $form->label($item, "[$i]parking",array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($item, "[$i]parking", array('placeholder' => '')); ?>
                            <?php  echo $form->error($item, "[$i]parking",array('class'=>'label label-important')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <?php echo $form->label($item, "[$i]underground_id",array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->dropDownList(
                                $item, "[$i]underground_id",$underground,array('empty' => '- Станция -'));
                            ?>
                            <?php  echo $form->error($item, "[$i]underground_id",array('class'=>'label label-important')); ?>
                        </div>
                    </div>
                    <div class="controls text-right">
                        <div class="controls">
<!--                            <button class="btn btn-danger btn-small delete-property">Удалить адрес</button>-->
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php else: ?>
            <?php $obj = new ClubAddress(); ?>
            <div class="well">
                <h3 class="heading">Адрес</h3>
                <div class="propertyRow" data-count-row="__count__">
                    <div class="control-group">
                        <?php echo $form->label($obj, "[__count__]city_id",array('class'=>'control-label')); ?>
                        <div class="controls" id="city_field">
                            <?php echo Chosen::activeDropDownList($obj, "[__count__]city_id", $cities , array('empty' => '', 'options' => array( 'allowSingleDeselect' => true))); ?>
                            <?php  echo $form->error($obj, "[__count__]city_id",array('class'=>'label label-important')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <?php echo $form->label($obj, "[__count__]address",array('class'=>'control-label')); ?>
                        <div class="controls" id="address_field">
                            <?php echo $form->textField($obj, "[__count__]address") ?>
                            <?php  echo $form->error($obj, "[__count__]address",array('class'=>'label label-important')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls" id="coords">
                            <?php echo $form->hiddenField($obj, "[__count__]lat", array('class' => 'lat')) ?>
                            <?php echo $form->hiddenField($obj, "[__count__]lon", array('class' => 'lon')) ?>
                            <button class="btn btn-info btn-small" id="address_show">Показать на карте</button>
                            <?php  echo $form->error($obj, "[__count__]lat",array('class'=>'label label-important')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <div class="controls">
                            <div id="map" style="width: 659px; height: 455px"></div>
                        </div>
                    </div>

                    <h3 class="heading">Контакты</h3>
                    <div class="control-group">
                        <div class="controls">
                            <input type="hidden" class="countPhones" value="0">
                            <button class="btn btn-info btn-small add-phone">Добавить телефон</button>
                        </div>
                    </div>
                    <h3 class="heading">Рабочее время</h3>
                    <div class="control-group">
                        <div class="controls">
                            <input type="hidden" class="countWorktime" value="0">
                            <button class="btn btn-info btn-small add-worktime">Добавить время работы</button>
                        </div>
                    </div>

                    <h3 class="heading">Дополнительно</h3>
                    <div class="control-group">
                        <?php echo $form->label($obj, "[__count__]parking",array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->textField($obj, "[__count__]parking", $cities , array('placeholder' => '')); ?>
                            <?php  echo $form->error($obj, "[__count__]parking",array('class'=>'label label-important')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <?php echo $form->label($obj, "[__count__]underground_id",array('class'=>'control-label')); ?>
                        <div class="controls">
                            <?php echo $form->dropDownList($obj, "[__count__]underground_id",$underground,array('empty' => '- Станция -')); ?>
                            <?php  echo $form->error($obj, "[__count__]underground_id",array('class'=>'label label-important')); ?>
                        </div>
                    </div>
                    <div class="controls text-right">
                        <div class="controls">
<!--                            <button class="btn btn-danger btn-small delete-property">Удалить адрес</button>-->
                        </div>
                    </div>
                    <hr />
                </div>
            </div>
        <?php endif; ?>

        <h3 class="heading">Цены</h3>
        <div class="control-group">
            <?php echo $form->label($model, "price_description",array('class'=>'control-label', 'form' => 'clubFrom')); ?>
            <div class="controls">
                <?php   $this->widget('ImperaviRedactorWidget', array(
                    'model' => $model,
                    'attribute' => 'price_description',
                    'options' => array(
                        'lang' => 'ru',
                        "imageUpload" => "/admin/club/club/upload",
                        "minHeight" => 300
                    ),
                ));?>
            </div>
        </div>
        <div class="control-group formSep">
            <?php echo $form->label($model, "price_description",array('class'=>'control-label', 'form' => 'clubFrom')); ?>
            <div class="controls">
                <?php echo $form->hiddenField($model,'price_image_id', array('class' => 'images')) ?>
                <?php
                $id = ($model->id) ? $model->id : '';
                $this->widget('ext.xupload.XUpload', array(
                        'url' => Yii::app( )->createUrl( "/admin/club/club/uploadClubPrice/item/" . $id),
                        'model' => $photos,
                        'htmlOptions' => array('id'=>'clubForm'),
                        'attribute' => 'file',
                        'multiple' => false,
                        'formView' => 'ext.xupload.views.form',
                        'showForm' => false,
                        'options' => array(
                            'submit' => "js:function (e, data) {
                                            //$('#Club_price_image_id').attr('value','');
                                            //data.formData = {itemId: " . $id . "};
                                            return true;
                                         }",
                            'success'=>"js:function(result, textStatus, jqXHR) {
                                            $('#Club_price_image_id').attr('value', result[0].image_id);
                                        }",
                            'added' => "js:function() {
                                            $('#clubPriceForm .fileinput-button').addClass('disabled');
                                            $('#XUploadForm_file').prop('disabled', true);
                                        }",
                            'destroyed' => "js: function() {
                                            $('#Club_price_image_id').attr('value','');
                                        }",
                            'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                        ),
                    )
                );
                ?>
            </div>
        </div>

        <div class="control-group formSep">
            <div class="controls">
<!--                <button class="btn btn-small btn-success" id="add-property"><i class="icon-plus"></i> Добавить адрес</button>-->
                <?php  echo $form->error($model, "addresses",array('class'=>'label label-important')); ?>
                <input type="hidden" id="countProperties" value="<?php echo count($model->addresses); ?>">
            </div>
        </div>
        <!--End Params-->


        <?php echo $form->hiddenField($model,'image_id', array('class' => 'images')); ?>
        <?php $this->endWidget(); ?>

        <h3 class="heading">Главное изображение</h3>
        <div class="row">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'clubFormMainImage',
                'htmlOptions'=>array('class'=>'form-horizontal ','enctype'=>'multipart/form-data', 'data-id' => ($model->id) ? $model->id : ''),
                'enableClientValidation' => false,
                'clientOptions'=>array(
                    'errorCssClass' => 'f_error',
                    'validateOnSubmit'=>true,
                    'validateOnChange'=>true,
                    'validateOnType'=>true
                )
            )); ?>
            <?php echo $form->hiddenField($model,'image_id', array('class' => 'images')); ?>
            <div class="control-group" style="margin-left: 30px;">
                <?php echo $form->label($model,'image_id',array('class'=>'control-label')); ?>
                <div class="controls well">
                    <?php
                    $id = ($model->id) ? $model->id : '';
                    $this->widget('ext.xupload.XUpload', array(
                            'url' => Yii::app( )->createUrl( "/admin/club/club/uploadMainImage/item/" . $id),
                            'model' => new XUploadForm,
                            'htmlOptions' => array('id' => 'clubFormMainImage'),
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
                                                    $('#Club_image_id').attr('value', result[0].image_id);
                                                }",
                                'added' => "js:function() {
                                                    $('#bookFormFile .fileinput-button').addClass('disabled');
                                                    $('#XUploadFormFile_file').prop('disabled', true);
                                                }",
                                'destroyed' => "js: function() {
                                                    $('#Club_image_id').attr('value','');
                                                }",
                                //'acceptFileTypes' => "js:/(\.|\/)(jpe?g|png|gif)$/i",
                            ),
                        )
                    );
                    ?>
                    <?php  echo $form->error($model,'image_id',array('class'=>'label label-important')); ?>
                </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
        <div class="row">
            <div class="offset2 control-group">
                <div class="controls">
                    <button id="save_btn_club" class="btn btn-info" name="save_and_close">Сохранить</button>
                    <a href="<?php echo $uri; ?>" class="btn">Закрыть</a>
                </div>
            </div>
        </div>
        <script>
            $(document).on('click', '#save_btn_club', function (e) {
                e.preventDefault();
                $('#clubForm').submit();
            });
        </script>







    </div>

    <div class="tab-pane" id="downloadImages">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id' => 'clubImageForm',
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
                'url' => Yii::app( )->createUrl( "/admin/club/club/uploadClubImages/item/" . $id),
                'model' => $photos,
                'htmlOptions' => array('id'=>'clubImageForm'),
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
    $cs = Yii::app()->clientScript;
    $cs->registerScriptFile('/js/admin/uploader.js', CClientScript::POS_END);
    $cs->registerScriptFile('/js/admin/club.js', CClientScript::POS_END);
    $cs->registerCssFile('/css/admin/chosen.css');

    $cs->registerCssFile(
        $this->assetUrl . '/lib/noUISlider/jquery.nouislider.min.css'
    );
    $cs->registerScriptFile(
        $this->assetUrl . '/lib/noUISlider/jquery.nouislider.min.js'
    );

    $cs->registerCssFile(
        $this->assetUrl . '/lib/tarrudadatetimepicker/css/bootstrap-datetimepicker.min.css'
    );
    $cs->registerScriptFile(
        $this->assetUrl . '/lib/tarrudadatetimepicker/js/bootstrap-datetimepicker.min.js'
    );
    $cs->registerScriptFile('http://api-maps.yandex.ru/2.1/?lang=ru_RU', CClientScript::POS_HEAD);
?>
