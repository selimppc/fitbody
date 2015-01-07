<div class="tab active">
    <?php if($this->owner): ?>
        <h2>
            Мои цели
            <a href="<?php echo Yii::app()->createUrl('profile/goals/add'); ?>" class="add_btn color_btn blue fl_r">+ Добавить цель</a>
        </h2>
    <?php else: ?>
        <h2>
            Цели пользователя
        </h2>
    <?php endif; ?>
    <div class="goal_blocks ov">
        <?php foreach($goals as $elem): ?>
            <?php if($elem->type == 'fat'): ?>
                <div class="goal_block fl_l fat_block">
                    <input type="hidden" class="goal_id" value="<?php echo (float)$elem->goal->id; ?>"/>
                    <?php if($this->owner): ?>
                        <div class="goal_links edit_link">
                            <a class="goal_delete_link delete_button" data-id="<?php echo (float)$elem->goal->id; ?>" href="javascript:;">Удалить</a>
                            <a class="edit_button" href="javascript:;">Редактировать</a>
                        </div>
                        <div class="goal_links progress_link">
                            <a class="progress_button" href="javascript:;">Прогресс</a>
                        </div>
                    <?php endif; ?>
                    <div class="img_cont fl_l"><img src="/images/img_cont/profile/goal_1.jpg" alt=""/></div>
                    <?php if((int)$elem->goal->start_weight && (int)$elem->goal->current_weight && (int)$elem->goal->end_weight): ?>
                        <div class="goal_info">
                            <h6>Изменение веса тела</h6>
                            <ul>
                                <li>
                                    <span class="key">Сейчас:</span>
                                    <input type="hidden" class="goal_weight_start" value="<?php echo floatval((float)$elem->goal->start_weight); ?>"/>
                                    <span class="value"><?php echo (float)$elem->goal->current_weight; ?> кг</span>
                                </li>
                                <li>
                                    <span class="key">Цель:</span>
                                    <input type="hidden" class="goal_weight" value="<?php echo floatval((float)$elem->goal->end_weight); ?>"/>
                                    <span class="value"><?php echo (float)$elem->goal->end_weight; ?> кг</span>
                                </li>
                                <li>
                                    <span class="key">Достижение:</span>
                                    <input type="hidden" class="goal_weight_date" value="<?php echo date('d.m.Y',strtotime($elem->goal->end_weight_date)); ?>"/>
                                    <span class="value"><?php echo date('d.m.Y',strtotime($elem->goal->start_weight_date)); ?> - <?php echo date('d.m.Y',strtotime($elem->goal->end_weight_date)); ?></span>
                                </li>
                            </ul>
                            <div class="goal_progress_bar">
                                <?php
                                    if($elem->goal->end_weight > $elem->goal->start_weight){
                                        if($elem->goal->current_weight > $elem->goal->end_weight)
                                            $percentage = 100;
                                        elseif($elem->goal->current_weight < $elem->goal->start_weight)
                                            $percentage = 0;
                                        else
                                            $percentage = round(($elem->goal->current_weight - $elem->goal->start_weight)/($elem->goal->end_weight - $elem->goal->start_weight) * 100);
                                    } elseif($elem->goal->end_weight < $elem->goal->start_weight) {
                                        if($elem->goal->current_weight > $elem->goal->end_weight)
                                            $percentage = 100;
                                        elseif($elem->goal->current_weight < $elem->goal->start_weight)
                                            $percentage = 0;
                                        else
                                            $percentage = round(($elem->goal->start_weight - $elem->goal->current_weight)/($elem->goal->start_weight - $elem->goal->end_weight) * 100);
                                    } else
                                        $percentage = 100;
                                ?>
                                <div class="goal_progress_drag" style="width: <?php echo $percentage; ?>%;"></div>
                            </div>
                            <div class="progress_bar_comment">
                                <span class="reached"> Достигнуто <span><?php echo $percentage; ?></span>%</span>
                                |
                                <span class="left"> Осталось <?php
                                        $time = (strtotime($elem->goal->end_weight_date) - time());
                                        if($time > 0){
                                            $days = round(($time/60/60/24),0,PHP_ROUND_HALF_UP);
                                            echo '<span>'.$days.'</span> ';
                                            switch ($days%10){
                                                case 1:
                                                    echo 'день';
                                                    break;
                                                case 2:
                                                case 3:
                                                case 4:
                                                    echo 'дня';
                                                    break;
                                                default:
                                                    echo 'дней';
                                            }
                                        } else
                                            echo '<span>0</span> дней';
                                ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="goal_info m_t_30">
                            <h6>Изменение веса тела</h6>
                            <input type="hidden" class="goal_weight" value=""/>
                            <input type="hidden" class="goal_weight_date" value=""/>
                            <p>У пользователя нет цели</p>
                        </div>
                    <?php endif; ?>
                    <?php if((int)$elem->goal->start_fat && (int)$elem->goal->current_fat && (int)$elem->goal->end_fat): ?>
                        <div class="goal_info">
                            <input type="hidden" class="goal_id" value="<?php echo (float)$elem->goal->id; ?>"/>
                            <h6>Изменение % жира</h6>
                            <ul>
                                <li>
                                    <span class="key">Сейчас:</span>
                                    <input type="hidden" class="goal_fat_start" value="<?php echo floatval((float)$elem->goal->start_fat); ?>"/>
                                    <span class="value"><?php echo (float)$elem->goal->current_fat; ?> %</span>
                                </li>
                                <li>
                                    <span class="key">Цель:</span>
                                    <input type="hidden" class="goal_fat" value="<?php echo floatval((float)$elem->goal->end_fat); ?>"/>
                                    <span class="value"><?php echo (float)$elem->goal->end_fat; ?> %</span>
                                </li>
                                <li>
                                    <span class="key">Достижение:</span>
                                    <input type="hidden" class="goal_fat_date" value="<?php echo date('d.m.Y',strtotime($elem->goal->end_fat_date)); ?>"/>
                                    <span class="value"><?php echo date('d.m.Y',strtotime($elem->goal->start_fat_date)); ?> - <?php echo date('d.m.Y',strtotime($elem->goal->end_fat_date)); ?></span>
                                </li>
                            </ul>
                            <div class="goal_progress_bar">
                                <?php
                                    if($elem->goal->end_fat > $elem->goal->start_fat){
                                        if($elem->goal->current_fat > $elem->goal->end_fat)
                                            $percentage = 100;
                                        elseif($elem->goal->current_fat < $elem->goal->start_fat)
                                            $percentage = 0;
                                        else
                                            $percentage = round(($elem->goal->current_fat - $elem->goal->start_fat)/($elem->goal->end_fat - $elem->goal->start_fat) * 100);
                                    } elseif($elem->goal->end_fat < $elem->goal->start_fat) {
                                        if($elem->goal->current_fat > $elem->goal->end_fat)
                                            $percentage = 100;
                                        elseif($elem->goal->current_fat < $elem->goal->start_fat)
                                            $percentage = 0;
                                        else
                                            $percentage = round(($elem->goal->start_fat - $elem->goal->current_fat)/($elem->goal->start_fat - $elem->goal->end_fat) * 100);
                                    } else
                                        $percentage = 100;
                                ?>
                                <div class="goal_progress_drag" style="width: <?php echo $percentage; ?>%;"></div>
                            </div>
                            <div class="progress_bar_comment">
                                <span class="reached"> Достигнуто <span><?php echo $percentage; ?></span>%</span>
                                |
                                <span class="left"> Осталось <?php
                                    $time = (strtotime($elem->goal->end_fat_date) - time());
                                    if($time > 0){
                                        $days = round(($time/60/60/24),0,PHP_ROUND_HALF_UP);
                                        echo '<span>'.$days.'</span> ';
                                        switch ($days%10){
                                            case 1:
                                                echo 'день';
                                                break;
                                            case 2:
                                            case 3:
                                            case 4:
                                                echo 'дня';
                                                break;
                                            default:
                                                echo 'дней';
                                        }
                                    } else
                                        echo '<span>0</span> дней';
                                    ?></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="goal_info m_t_30">
                            <h6>Изменение % жира</h6>
                            <input type="hidden" class="goal_fat" value=""/>
                            <input type="hidden" class="goal_fat_date" value=""/>
                            <p>У пользователя нет цели</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php elseif($elem->type == 'size'): ?>
                <div class="goal_block fl_r no_illustr size_block">
                    <?php if($this->owner): ?>
                        <div class="goal_links edit_link">
                            <a class="goal_delete_link delete_button" data-id="<?php echo (float)$elem->goal->id; ?>" href="javascript:;">Удалить</a>
                            <a class="edit_button" href="javascript:;">Редактировать</a>
                        </div>
                        <div class="goal_links progress_link">
                            <a class="progress_button" href="javascript:;">Прогресс</a>
                        </div>
                    <?php endif; ?>
                    <div class="goal_info">
                        <input type="hidden" class="goal_id" value="<?php echo (float)$elem->goal->id; ?>"/>
                        <h6>Изменить объем <?php echo mb_strtolower($elem->goal->body_part->genitive, 'utf-8'); ?></h6>
                        <input type="hidden" class="goal_title" value="<?php echo $elem->goal->body_part->id; ?>"/>
                        <ul>
                            <li>
                                <span class="key">Сейчас</span>
                                <span class="value"><?php echo (float)$elem->goal->current_size; ?> см</span>
                            </li>
                            <li>
                                <span class="key">Цель</span>
                                <input type="hidden" class="goal_value" value="<?php echo floatval((float)$elem->goal->end_size); ?>"/>
                                <span class="value"><?php echo (float)$elem->goal->end_size; ?> см</span>
                            </li>
                            <li>
                                <span class="key">Дата достижения</span>
                                <input type="hidden" class="goal_date" value="<?php echo date('d.m.Y',strtotime($elem->goal->end_date)); ?>"/>
                                <span class="value"><?php echo date('d.m.Y',strtotime($elem->goal->start_date)); ?> - <?php echo date('d.m.Y',strtotime($elem->goal->end_date)); ?></span>
                            </li>
                        </ul>
                        <div class="goal_progress_bar">
                            <?php
                                if($elem->goal->end_size > $elem->goal->start_size){
                                    if($elem->goal->current_size > $elem->goal->end_size)
                                        $percentage = 100;
                                    elseif($elem->goal->current_size < $elem->goal->start_size)
                                        $percentage = 0;
                                    else
                                        $percentage = round(($elem->goal->current_size - $elem->goal->start_size)/($elem->goal->end_size - $elem->goal->start_size) * 100);
                                } elseif($elem->goal->end_size < $elem->goal->start_size) {
                                    if($elem->goal->current_size > $elem->goal->end_size)
                                        $percentage = 100;
                                    elseif($elem->goal->current_size < $elem->goal->start_size)
                                        $percentage = 0;
                                    else
                                        $percentage = round(($elem->goal->start_size - $elem->goal->current_size)/($elem->goal->start_size - $elem->goal->end_size) * 100);
                                } else
                                    $percentage = 100;
                            ?>
                            <div class="goal_progress_drag" style="width: <?php echo $percentage; ?>%;"></div>
                        </div>
                        <div class="progress_bar_comment">
                            <span class="reached"> Достигнуто <span><?php echo $percentage; ?></span>%</span>
                            |
                            <span class="left"> Осталось <?php
                                $time = (strtotime($elem->goal->end_date) - time());
                                if($time > 0){
                                    $days = round(($time/60/60/24),0,PHP_ROUND_HALF_UP);
                                    echo '<span>'.$days.'</span> ';
                                    switch ($days%10){
                                        case 1:
                                            echo 'день';
                                            break;
                                        case 2:
                                        case 3:
                                        case 4:
                                            echo 'дня';
                                            break;
                                        default:
                                            echo 'дней';
                                    }
                                } else
                                    echo '<span>0</span> дней';
                                ?></span>
                        </div>
                    </div>
                </div>
            <?php elseif($elem->type == 'weight'): ?>
                <div class="goal_block fl_r no_illustr weight_block">
                    <?php if($this->owner): ?>
                        <div class="goal_links edit_link">
                            <a class="goal_delete_link delete_button" data-id="<?php echo (float)$elem->goal->id; ?>" href="javascript:;">Удалить</a>
                            <a class="edit_button" href="javascript:;">Редактировать</a>
                        </div>
                        <div class="goal_links progress_link">
                            <a class="progress_button" href="javascript:;">Прогресс</a>
                        </div>
                    <?php endif; ?>
                    <div class="goal_info">
                        <input type="hidden" class="goal_id" value="<?php echo (float)$elem->goal->id; ?>"/>
                        <h6><?php echo FunctionHelper::upperFirst($elem->goal->title); ?></h6>
                        <input type="hidden" class="goal_title" value="<?php echo $elem->goal->title; ?>"/>
                        <ul>
                            <li>
                                <span class="key">Сейчас</span>
                                <span class="value"><?php echo (float)$elem->goal->weight_current; ?> кг</span>
                            </li>
                            <li>
                                <span class="key">Дата старта</span>
                                <span class="value"><?php echo date('d.m.Y',strtotime($elem->goal->start_date)); ?></span>
                            </li>
                            <li>
                                <span class="key">Цель</span>
                                <input type="hidden" class="goal_value" value="<?php echo floatval((float)$elem->goal->weight_end); ?>"/>
                                <span class="value"><?php echo (float)$elem->goal->weight_end; ?> кг</span>
                            </li>
                            <li>
                                <span class="key">Дата достижения</span>
                                <input type="hidden" class="goal_date" value="<?php echo date('d.m.Y',strtotime($elem->goal->end_date)); ?>"/>
                                <span class="value"><?php echo date('d.m.Y',strtotime($elem->goal->end_date)); ?></span>
                            </li>
                        </ul>
                        <div class="goal_progress_bar">
                            <?php
                            if($elem->goal->weight_end > $elem->goal->weight_start){
                                if($elem->goal->weight_current > $elem->goal->weight_end)
                                    $percentage = 100;
                                elseif($elem->goal->weight_current < $elem->goal->weight_start)
                                    $percentage = 0;
                                else
                                    $percentage = round(($elem->goal->weight_current - $elem->goal->weight_start)/($elem->goal->weight_end - $elem->goal->weight_start) * 100);
                            } elseif($elem->goal->weight_end < $elem->goal->weight_start) {
                                if($elem->goal->weight_current > $elem->goal->weight_end)
                                    $percentage = 100;
                                elseif($elem->goal->weight_current < $elem->goal->weight_start)
                                    $percentage = 0;
                                else
                                    $percentage = round(($elem->goal->weight_start - $elem->goal->weight_current)/($elem->goal->weight_start - $elem->goal->weight_end) * 100);
                            } else
                                $percentage = 100;
                            ?>
                            <div class="goal_progress_drag" style="width: <?php echo $percentage; ?>%;"></div>
                        </div>
                        <div class="progress_bar_comment">
                            <span class="reached"> Достигнуто <span><?php echo $percentage; ?></span>%</span>
                            |
                            <span class="left"> Осталось <span><?php echo $percentage; ?></span>%</span>
                            |
                            <span class="left"> Осталось <?php
                                $time = (strtotime($elem->goal->end_date) - time());
                                if($time > 0){
                                    $days = round(($time/60/60/24),0,PHP_ROUND_HALF_UP);
                                    echo '<span>'.$days.'</span> ';
                                    switch ($days%10){
                                        case 1:
                                            echo 'день';
                                            break;
                                        case 2:
                                        case 3:
                                        case 4:
                                            echo 'дня';
                                            break;
                                        default:
                                            echo 'дней';
                                    }
                                } else
                                    echo '<span>0</span> дней';
                                ?></span></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<div id="goal_weight_edit" class="none">
    <div class="popup_inner">
        <div class="close">Закрыть</div>
        <div class="edit_goal_box clearfix">
            <h1>Редактирование цели</h1>
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
            <?php echo $form->hiddenField($goal_weight,'id'); ?>
                <fieldset>
                    <legend><span>Увеличить силу</span></legend>
                    <div class="row_inline">
                        <label>
                            <span class="label">Введите вашу цель</span>
                            <?php echo $form->textField($goal_weight,'title', array('placeholder' => 'Хочу больше жать на грудь', 'class' => 'w_270')); ?>
                        </label>
                    </div>
                    <div class="row_inline">
                        <span class="label">Вес <span class="">(желаемый)</span></span>
                        <?php echo $form->textField($goal_weight,'weight_end'); ?>
                        <span class="unit">кг</span>
                    </div>
                    <div class="row_inline">
                        <label>
                            <span class="label">Дата достижения</span>
                            <?php echo $form->textField($goal_weight,'end_date',array('class' => 'datepicker')); ?>
                        </label>
                    </div>
                    <div class="row_inline">
                        <label>
                            <span class="label">&nbsp;</span>
                            <a href="" class="color_btn blue save_butt">Сохранить</a>
                        </label>
                    </div>
                </fieldset>
                <div class="row">
                    <ul class="error_cause">
                    </ul>
                </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>

<div id="goal_size_edit" class="none">
    <div class="popup_inner">
        <div class="close">Закрыть</div>
        <div class="edit_goal_box clearfix">
            <h1>Редактирование цели</h1>
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
            <?php echo $form->hiddenField($goal_size,'id'); ?>
                <fieldset>
                    <legend><span>Объем частей тела</span></legend>
                    <div class="row_inline h_34">
                        <label>
                            <span class="label">Часть тела</span>
                            <?php echo $form->dropDownList($goal_size, 'body_part_id', $bodyPartsList, array('empty'=>'Выбрать часть тела')); ?>
                        </label>
                    </div>
                    <div class="row_inline">
                        <label>
                            <span class="label">Объем <span>(желаемый)</span></span>
                            <?php echo $form->textField($goal_size,'end_size'); ?>
                            <span class="unit">см</span>
                        </label>
                    </div>
                    <div class="row_inline">
                        <label>
                            <span class="label">Дата достижения</span>
                            <?php echo $form->textField($goal_size,'end_date',array('class' => 'datepicker')); ?>
                        </label>
                    </div>
                    <div class="row_inline">
                        <label>
                            <span class="label">&nbsp;</span>
                            <a href="" class="color_btn blue save_butt">Сохранить</a>
                        </label>
                    </div>
                </fieldset>
                <div class="row">
                    <ul class="error_cause">
                    </ul>
                </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>

<div id="goal_fat_edit" class="none">
    <div class="popup_inner">
        <div class="close">Закрыть</div>
        <div class="edit_goal_box clearfix">
            <h1>Редактирование цели</h1>
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
            <?php echo $form->hiddenField($goal_fat,'id'); ?>
                <fieldset class="fl_l w_46">
                    <legend><span>Изменение веса</span></legend>
                    <div class="row_inline weight_part">
                        <label class="empty">
                            <span class="label">Вес <span>(сейчас/желаемый)</span></span>
                            <?php echo $form->textField($goal_fat,'start_weight'); ?>
                            <span class="unit">кг</span>
                        </label>
                        <label class="not_empty">
                            <span class="label not_empty">Вес <span>(желаемый)</span></span>
                            <span class="label empty">&nbsp;</span>
                            <?php echo $form->textField($goal_fat,'end_weight'); ?>
                            <span class="unit">кг</span>
                        </label>
                    </div>
                    <div class="row_inline fl_r">
                        <label>
                            <span class="label">Дата достижения</span>
                            <?php echo $form->textField($goal_fat,'end_weight_date', array('class'=>'datepicker')); ?>
                        </label>
                    </div>
                </fieldset>
                <fieldset class="fl_r w_46">
                    <legend><span>Изменение Процента жира</span></legend>
                    <div class="row_inline fat_part">
                        <label class="empty">
                            <span class="label">Жир <span>(сейчас/желаемый)</span></span>
                            <?php echo $form->textField($goal_fat,'start_fat'); ?>
                            <span class="unit">%</span>
                        </label>
                        <label class="not_empty">
                            <span class="label not_empty">Жир <span>(желаемый)</span></span>
                            <span class="label empty">&nbsp;</span>
                            <?php echo $form->textField($goal_fat,'end_fat'); ?>
                            <span class="unit">%</span>
                        </label>
                    </div>
                    <div class="row_inline fl_r">
                        <label>
                            <span class="label">Дата достижения</span>
                            <?php echo $form->textField($goal_fat,'end_fat_date', array('class'=>'datepicker')); ?>
                        </label>
                    </div>
                </fieldset>
                <div class="clear">&nbsp;</div>
                <div class="row">
                    <ul class="error_cause">
                    </ul>
                </div>
                <div class="row bordered">
                    <label>
                        <a href="" class="color_btn blue save_butt">Сохранить</a>
                    </label>
                </div>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>


<div id="goal_weight_progress" class="none"><!--open popup-->
    <div class="popup_inner">
        <div class="close">Закрыть</div>
        <div class="popup_progress_box">
            <h1 class="brd_btm">Ваш прогресс</h1>
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'goalProgressForm',
                'htmlOptions'=>array('enctype'=>'multipart/form-data', 'data-id' => ''),
                'enableClientValidation' => false,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                    'validateOnChange'=>true,
                    'validateOnType'=>true
                )
            )); ?>
                <div class="row_inline m_r_60">
                    <?php echo $form->hiddenField($goal_progress,'type', array('value'=> $goal_progress::TYPE_HEFT)); ?>
                    <?php echo $form->hiddenField($goal_progress,'goal_id');?>
                    <label>
                        <span class="label">Прогресс</span>
                        <?php echo $form->textField($goal_progress,'value', array('class'=>'w_70')); ?>
                        <span class="unit">кг</span>
                    </label>
                </div>
                <div class="row_inline m_r_60">
                    <label>
                        <span class="label">Дата</span>
                        <?php echo $form->textField($goal_progress,'date', array('class'=>'datepicker w_120', 'id' => '')); ?>
                    </label>
                </div>
                <div class="row_inline fl_r m_t_5">
                    <label>
                        <span class="label">&nbsp;</span>
                        <a href="" class="color_btn blue save">Добавить</a>
                    </label>
                </div>
                <div class="row">
                    <ul class="error_cause">
                    </ul>
                </div>
            <?php $this->endWidget(); ?>
            <ul class="popup_progress_list"></ul>
        </div>
    </div>
</div><!--close popup-->

<div id="goal_size_progress" class="none"><!--open popup-->
    <div class="popup_inner">
        <div class="close">Закрыть</div>
        <div class="popup_progress_box">
            <h1 class="brd_btm">Ваш прогресс</h1>
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'goalProgressForm',
                'htmlOptions'=>array('enctype'=>'multipart/form-data', 'data-id' => ''),
                'enableClientValidation' => false,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                    'validateOnChange'=>true,
                    'validateOnType'=>true
                )
            )); ?>
                <div class="row_inline m_r_60">
                    <?php echo $form->hiddenField($goal_progress,'type', array('value'=> $goal_progress::TYPE_SIZE)); ?>
                    <?php echo $form->hiddenField($goal_progress,'goal_id');?>
                    <label>
                        <span class="label">Прогресс</span>
                        <?php echo $form->textField($goal_progress,'value', array('class'=>'w_70')); ?>
                        <span class="unit">см</span>
                    </label>
                </div>
                <div class="row_inline m_r_60">
                    <label>
                        <span class="label">Дата</span>
                        <?php echo $form->textField($goal_progress,'date', array('class'=>'datepicker w_120', 'id' => '')); ?>
                    </label>
                </div>
                <div class="row_inline fl_r m_t_5">
                    <label>
                        <span class="label">&nbsp;</span>
                        <a href="" class="color_btn blue save">Добавить</a>
                    </label>
                </div>
                <div class="row">
                    <ul class="error_cause">
                    </ul>
                </div>
            <?php $this->endWidget(); ?>
            <ul class="popup_progress_list"></ul>
        </div>
    </div>
</div><!--close popup-->

<div id="goal_fat_progress" class="none"><!--open popup-->
    <div class="popup_inner">
        <div class="close">Закрыть</div>
        <div class="popup_progress_box">
            <h1 class="brd_btm">Ваш прогресс</h1>
            <?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'goalProgressForm',
                'htmlOptions'=>array('enctype'=>'multipart/form-data', 'data-id' => ''),
                'enableClientValidation' => false,
                'clientOptions'=>array(
                    'validateOnSubmit'=>true,
                    'validateOnChange'=>true,
                    'validateOnType'=>true
                )
            )); ?>
                <div class="row_inline h_34">
                    <?php echo $form->hiddenField($goal_progress,'goal_id');?>
                    <label>
                        <span class="label">Тип</span>
                        <?php echo $form->dropDownList($goal_progress, 'type', CHtml::listData(array(array('id' => $goal_progress::TYPE_WEIGHT, 'title' => 'Вес'), array('id' => $goal_progress::TYPE_FAT, 'title' => '% жира')),'id','title')); ?>
                    </label>
                </div>
                <div class="row_inline">
                    <label>
                        <span class="label">Прогресс</span>
                        <?php echo $form->textField($goal_progress,'value', array('class'=>'w_70')); ?>
                        <span class="unit">кг</span>
                    </label>
                </div>
                <div class="row_inline">
                    <label>
                        <span class="label">Дата</span>
                        <?php echo $form->textField($goal_progress,'date', array('class'=>'datepicker w_120', 'id' => '')); ?>
                    </label>
                </div>
                <div class="row_inline fl_r m_t_5">
                    <label>
                        <span class="label">&nbsp;</span>
                        <a href="" class="color_btn blue save">Добавить</a>
                    </label>
                </div>
                <div class="row">
                    <ul class="error_cause">
                    </ul>
                </div>
            <?php $this->endWidget(); ?>
            <ul class="popup_progress_list weight_list"></ul>
            <ul class="popup_progress_list fat_list" style="display: none"></ul>
        </div>
    </div>
</div><!--close popup-->