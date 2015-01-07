<div class="tab active">
    <h2>
        <?php if($edit): ?>
            Редактирование программы
            <a href="" class="add_btn color_btn blue fl_r" id="save_program">+ Сохранить</a>
        <?php else: ?>
            Новая программа
            <a href="" class="add_btn color_btn blue fl_r" id="save_program">+ Создать</a>
        <?php endif; ?>
    </h2>
    <div class="program_block ov">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id' => 'profileProgramForm',
            'htmlOptions'=>array('enctype'=>'multipart/form-data', 'data-id' => $model->id),
            'enableClientValidation' => false,
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
                'validateOnChange'=>true,
                'validateOnType'=>true
            )
        )); ?>
        <h3><span>Основные данные</span></h3>
        <div class="program_edit_settings clearfix">
            <label>
                <span class="label">Начало</span>
                <?php echo $form->textField($model,'start_date', array('class' => 'datepicker')); ?>
            </label>
            <label>
                <span class="label">Конец</span>
                <?php echo $form->textField($model,'end_date', array('class' => 'datepicker')); ?>
            </label>
        </div>

        <h3><span>Упражнения на каждый день</span></h3>

        <table class="program_table program_table_edit">
            <tr>
                <th>День недели</th>
                <th>Упражнение</th>
            </tr>
            <tr class="weekday">
                <td>Понедельник</td>
                <td>
                    <?php echo $form->hiddenField($model,'monday',array('class' => 'json')); ?>
                    <table>
                        <?php $monday = json_decode($model->monday); ?>
                        <?php foreach($monday as $key => $elem): ?>
                            <tr class="tr_row" data-id="<?php echo (int)$key; ?>">
                                <td>
                                    <a class="fl_r">
                                        <img src="/images/delete_table_arrow.png">
                                    </a>
                                    <?php echo FunctionHelper::upperFirst($elem->title); ?>
                                    <span> (<?php echo FunctionHelper::upperFirst($elem->muscle); ?>)</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td><a class="add_exercise" href="">+ <?php echo ($model->monday== '{}') ? 'добавить упражнение' : 'еще одно'; ?></a></td>
                        </tr>
                    </table>

                </td>
            </tr>

            <tr class="weekday">
                <td>Вторник</td>
                <td>
                    <?php echo $form->hiddenField($model,'tuesday',array('class' => 'json')); ?>
                    <table>
                        <?php $tuesday = json_decode($model->tuesday); ?>
                        <?php foreach($tuesday as $key => $elem): ?>
                            <tr class="tr_row" data-id="<?php echo (int)$key; ?>">
                                <td>
                                    <a class="fl_r">
                                        <img src="/images/delete_table_arrow.png">
                                    </a>
                                    <?php echo FunctionHelper::upperFirst($elem->title); ?>
                                    <span> (<?php echo FunctionHelper::upperFirst($elem->muscle); ?>)</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td><a class="add_exercise" href="">+ добавить упражнение</a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="weekday">
                <td>Среда</td>
                <td>
                    <?php echo $form->hiddenField($model,'wednesday',array('class' => 'json')); ?>
                    <table>
                        <?php $wednesday = json_decode($model->wednesday); ?>
                        <?php foreach($wednesday as $key => $elem): ?>
                            <tr class="tr_row" data-id="<?php echo (int)$key; ?>">
                                <td>
                                    <a class="fl_r">
                                        <img src="/images/delete_table_arrow.png">
                                    </a>
                                    <?php echo FunctionHelper::upperFirst($elem->title); ?>
                                    <span> (<?php echo FunctionHelper::upperFirst($elem->muscle); ?>)</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td><a href="" class="add_exercise">+ добавить упражнение</a></td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="weekday">
                <td>Четверг</td>
                <td>
                    <?php echo $form->hiddenField($model,'thursday',array('class' => 'json')); ?>
                    <table>
                        <?php $thursday = json_decode($model->thursday); ?>
                        <?php foreach($thursday as $key => $elem): ?>
                            <tr class="tr_row" data-id="<?php echo (int)$key; ?>">
                                <td>
                                    <a class="fl_r">
                                        <img src="/images/delete_table_arrow.png">
                                    </a>
                                    <?php echo FunctionHelper::upperFirst($elem->title); ?>
                                    <span> (<?php echo FunctionHelper::upperFirst($elem->muscle); ?>)</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td><a class="add_exercise" href="">+ добавить упражнение</a></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="weekday">
                <td>Пятница</td>
                <td>
                    <?php echo $form->hiddenField($model,'friday',array('class' => 'json')); ?>
                    <table>
                        <?php $friday = json_decode($model->friday); ?>
                        <?php foreach($friday as $key => $elem): ?>
                            <tr class="tr_row" data-id="<?php echo (int)$key; ?>">
                                <td>
                                    <a class="fl_r">
                                        <img src="/images/delete_table_arrow.png">
                                    </a>
                                    <?php echo FunctionHelper::upperFirst($elem->title); ?>
                                    <span> (<?php echo FunctionHelper::upperFirst($elem->muscle); ?>)</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td><a href="" class="add_exercise">+ добавить упражнение</a></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="weekday">
                <td>Суббота</td>
                <td>
                    <?php echo $form->hiddenField($model,'saturday',array('class' => 'json')); ?>
                    <table>
                        <?php $saturday = json_decode($model->saturday); ?>
                        <?php foreach($saturday as $key => $elem): ?>
                            <tr class="tr_row" data-id="<?php echo (int)$key; ?>">
                                <td>
                                    <a class="fl_r">
                                        <img src="/images/delete_table_arrow.png">
                                    </a>
                                    <?php echo FunctionHelper::upperFirst($elem->title); ?>
                                    <span> (<?php echo FunctionHelper::upperFirst($elem->muscle); ?>)</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td><a class="add_exercise" href="">+ добавить упражнение</a></td>
                        </tr>
                    </table>
                </td>
            </tr>

            <tr class="weekday">
                <td>Воскресенье</td>
                <td>
                    <?php echo $form->hiddenField($model,'sunday',array('class' => 'json')); ?>
                    <table>
                        <?php $sunday = json_decode($model->sunday); ?>
                        <?php foreach($sunday as $key => $elem): ?>
                            <tr class="tr_row" data-id="<?php echo (int)$key; ?>">
                                <td>
                                    <a class="fl_r">
                                        <img src="/images/delete_table_arrow.png">
                                    </a>
                                    <?php echo FunctionHelper::upperFirst($elem->title); ?>
                                    <span> (<?php echo FunctionHelper::upperFirst($elem->muscle); ?>)</span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <tr>
                            <td><a class="add_exercise" href="">+ добавить упражнение</a></td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
        <?php $this->endWidget(); ?>
    </div>
</div>

    <div id="popup_add_exercise" class="none"><!--open popup-->
    <div class="popup_inner">
        <div class="close" onCLick="closeExercise();">Закрыть</div>
        <div class="add_exercise_box">
            <form action="#">
                <h2>Упражнения на понедельник</h2>
                <div class="row_inline">
                    <label class="h_34">
                        <?php echo CHtml::dropDownList('type', 'id', $typeSelect, array('data-select' => 'type')); ?>
                    </label>
                </div>
                <div class="row_inline m_l_10">
                    <label class="h_34">
                        <?php echo CHtml::dropDownList('muscle', 'id', $muscleSelect, array('data-select' => 'muscle')); ?>
                    </label>
                </div>
                <div class="exercise_box">
                    <div class="base_list">
                        <ul>
                            <?php foreach($exercise as $elem): ?>
                                <li data-id="<?php echo $elem->id; ?>" data-muscle_type="<?php echo $elem->type; ?>" data-muscle_title="<?php echo FunctionHelper::upperFirst($elem->muscle->accusative); ?>" data-muscle_id="<?php echo $elem->muscle_id; ?>">
                                    <a class="img_cont fl_l" href="<?php echo Yii::app()->createUrl('exercise/' . $elem->id);?>"
                                        <?php if ($elem->images): ?>
                                            <?php if (isset($elem->images[0])): ?>
                                                data-src1="/pub/exercise/photo/65x61/<?php echo $elem->images[0]->image_filename; ?>"
                                            <?php endif; ?>
                                            <?php if (isset($elem->images[1])): ?>
                                                data-src2="/pub/exercise/photo/65x61/<?php echo $elem->images[1]->image_filename; ?>"
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        data-rendered="false"
                                    ></a>
                                    <div data-title><?php echo CHtml::link(FunctionHelper::upperFirst($elem->title),'/exercise/'.$elem->id.'.html',array('class' => 'exercise_name')); ?></div>
                                    <label class="value checked">
                                        <input type="checkbox" name="" checked/>
                                        Добавить упражнение
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
                <div class="">
                    <a href="" class="color_btn blue save_butt">Готово</a>
                    <a class="cancel_link cancel_butt" href="">Отменить</a>
                </div>
            </form>

        </div>
    </div>
</div><!--close popup-->