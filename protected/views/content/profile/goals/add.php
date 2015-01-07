<div class="tab active">
    <h2>
        Добавление новой цели
    </h2>
    <div class="add_goal_block">
        <div class="goal_nav">
            <ul>
                <li class="text_c va_middle_out <?php if($activeTab == 'fat') echo 'active'; ?>">
                    <a href="#fat_percent" class="va_middle_in">
                        <img src="/images/goal_fat_percent_i.png" alt=""/>
                        <p><span class="title">Вес и процент жира</span></p>
                        <p>
                            Фитнес-клуб премиум класса для тех, кто не привык ограничивать себя в заботе о собственном здоровье.
                        </p>
                    </a>
                </li>
                <li class="text_c va_middle_out <?php if($activeTab == 'size') echo 'active'; ?>">
                    <a href="#body_volume" class="va_middle_in">
                        <img src="/images/body_volume.png" alt=""/>
                        <p><span class="title">объем частей тела</span></p>
                        <p>
                            Фитнес-клуб премиум класса для тех, кто не привык ограничивать себя в заботе о собственном здоровье.
                        </p>
                    </a>
                </li>
                <li class="text_c va_middle_out <?php if($activeTab == 'weight') echo 'active'; ?>">
                    <a href="#power_increase" class="va_middle_in">
                        <img src="/images/goal_power_increase.png" alt=""/>
                        <p><span class="title">Увеличить силу</span></p>
                        <p>
                            Фитнес-клуб премиум класса для тех, кто не привык ограничивать себя в заботе о собственном здоровье.
                        </p>
                    </a>
                </li>
            </ul>
        </div>

        <div class="goal_tab ov <?php if($activeTab == 'fat') echo 'active'; ?>" id="fat_percent">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'goalFatForm',
                'htmlOptions'=>array('enctype'=>'multipart/form-data', 'data-id' => ''),
                'enableClientValidation' => false,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                    'validateOnChange'=>true,
                    'validateOnType'=>true
                )
            )); ?>
                <fieldset class="fl_l w_46">
                    <legend><span>Изменение веса</span></legend>
                    <div class="row_inline">
                        <label>
                            <span class="label">Вес <span>(сейчас/желаемый)</span></span>
                            <?php echo $form->textField($modelGoalFat,'start_weight'); ?>
                            <span class="unit">кг</span>
                        </label>
                        <label>
                            <span class="label">&nbsp;</span>
                            <?php echo $form->textField($modelGoalFat,'end_weight'); ?>
                            <span class="unit">кг</span>
                        </label>
                    </div>
                    <div class="row_inline fl_r">
                        <label>
                            <span class="label">Дата достижения</span>
                            <?php echo $form->textField($modelGoalFat,'end_weight_date', array('class'=>'datepicker')); ?>
                        </label>
                    </div>
                </fieldset>
                <fieldset class="fl_r w_46">
                    <legend><span>Изменение Процента жира</span></legend>
                    <div class="row_inline">
                        <label>
                            <span class="label">Жир <span>(сейчас/желаемый)</span></span>
                            <?php echo $form->textField($modelGoalFat,'start_fat'); ?>
                            <span class="unit">%</span>
                        </label>
                        <label>
                            <span class="label">&nbsp;</span>
                            <?php echo $form->textField($modelGoalFat,'end_fat'); ?>
                            <span class="unit">%</span>
                        </label>
                    </div>
                    <div class="row_inline fl_r">
                        <label>
                            <span class="label">Дата достижения</span>
                            <?php echo $form->textField($modelGoalFat,'end_fat_date', array('class'=>'datepicker')); ?>
                        </label>
                    </div>
                </fieldset>
                <div class="clear">&nbsp;</div>

                <div class="row bordered">
                    <label>
                        <a href="javascript:void(0);" class="color_btn blue" id="goalFatForm_save">Сохранить</a>
                    </label>
                </div>
            <?php $this->endWidget(); ?>
        </div>

        <div class="goal_tab <?php if($activeTab == 'size') echo 'active'; ?>" id="body_volume">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'goalSizeForm',
                'htmlOptions'=>array('enctype'=>'multipart/form-data', 'data-id' => ''),
                'enableClientValidation' => false,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                    'validateOnChange'=>true,
                    'validateOnType'=>true
                )
            )); ?>
                <fieldset>
                    <legend><span>Объем частей тела</span></legend>
                    <div class="row_inline h_34">
                        <label>
                            <span class="label">Часть тела</span>
                            <?php echo $form->dropDownList($modelGoalSize, 'body_part_id', $bodyPartsList, array('empty'=>'Выбрать часть тела')); ?>

                        </label>
                    </div>
                    <div class="row_inline">
                        <label>
                            <span class="label">Объем <span>(сейчас/желаемый)</span></span>
                            <?php echo $form->textField($modelGoalSize,'start_size'); ?>
                            <span class="unit">см</span>
                        </label>
                        <label>
                            <span class="label">&nbsp;</span>
                            <?php echo $form->textField($modelGoalSize,'end_size'); ?>
                            <span class="unit">см</span>
                        </label>
                    </div>
                    <div class="row_inline">
                        <label>
                            <span class="label">Дата достижения</span>
                            <?php echo $form->textField($modelGoalSize,'end_date',array('class' => 'datepicker')); ?>
                        </label>
                    </div>
                    <div class="row_inline">
                        <label>
                            <span class="label">&nbsp;</span>
                            <a href="javascript:void(0);" class="color_btn blue" id="goalSizeForm_save">Сохранить</a>
                        </label>
                    </div>
                </fieldset>
            <?php $this->endWidget(); ?>
        </div>

        <div class="goal_tab <?php if($activeTab == 'weight') echo 'active'; ?>" id="power_increase">
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'goalWeightForm',
                'htmlOptions'=>array('enctype'=>'multipart/form-data', 'data-id' => ''),
                'enableClientValidation' => false,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                    'validateOnChange'=>true,
                    'validateOnType'=>true
                )
            )); ?>
                <fieldset>
                    <legend><span>Увеличить силу</span></legend>
                    <div class="row_inline">
                        <label>
                            <span class="label">Часть тела</span>
                            <?php echo $form->textField($modelGoalWeight,'title', array('placeholder' => 'Хочу больше жать на грудь', 'class' => 'w_270')); ?>
                        </label>
                    </div>
                    <div class="row_inline">
                        <label>
                            <span class="label">Вес <span>(сейчас/желаемый)</span></span>
                            <?php echo $form->textField($modelGoalWeight,'weight_start'); ?>
                            <span class="unit">кг</span>
                        </label>
                        <label>
                            <span class="label">&nbsp;</span>
                            <?php echo $form->textField($modelGoalWeight,'weight_end'); ?>
                            <span class="unit">кг</span>
                        </label>
                    </div>
                    <div class="row_inline">
                        <label>
                            <span class="label">Дата достижения</span>
                            <?php echo $form->textField($modelGoalWeight,'end_date',array('class' => 'datepicker')); ?>
                        </label>
                    </div>
                    <div class="row_inline">
                        <label>
                            <span class="label">&nbsp;</span>
                            <a href="javascript:void(0);" class="color_btn blue" id="goalWeightForm_save">Сохранить</a>
                        </label>
                    </div>
                </fieldset>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>