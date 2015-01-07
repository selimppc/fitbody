<div class="tab active">
    <?php if($this->owner || $this->profile->show_progress): ?>
        <h2>
            Прогресс
            <a href="<?php echo Yii::app()->createUrl('profile/'.$user_id.'/progress'); ?>" class="block_link fl_r"><?php echo ($progress_count >= 5 ? 'Смотреть все <span>'.$progress_count.'</span> фотографий' : 'Все фотографии'); ?></a>
        </h2>
        <div class="progress_block ov">
            <figure class="img_before fl_l">
                <?php if($before && $before->before_image): ?>
                    <img src="/pub/profile_progress/photo/369x369/<?php echo $before->before_image->image_filename; ?>" alt=""/>
                    <span class="sm_info">
                        <b>Было</b> <?php echo date('d.m.Y',strtotime($before->before_date)); ?>
                    </span>
                    <figcaption>
                        <p class="title"><?php echo FunctionHelper::upperFirst($before->title); ?></p>
                        <p><?php echo FunctionHelper::upperFirst($before->before_description); ?></p>
                    </figcaption>
                <?php endif; ?>
            </figure>
            <figure class="img_after fl_r">
                <?php if($now && $now->now_image): ?>
                    <img src="/pub/profile_progress/photo/369x369/<?php echo $now->now_image->image_filename; ?>" alt=""/>
                    <span class="sm_info">
                        <b>Стало</b> <?php echo date('d.m.Y',strtotime($now->now_date)); ?>
                    </span>
                    <figcaption>
                        <p class="title"><?php echo FunctionHelper::upperFirst($now->title); ?></p>
                        <p><?php echo FunctionHelper::upperFirst($now->now_description); ?></p>
                    </figcaption>
                <?php endif; ?>
            </figure>
        </div>
    <?php endif; ?>

    <?php if($goals && ($this->owner || $this->profile->show_goals)): ?>
    <h2>
        Цели
        <a href="<?php echo Yii::app()->createUrl('profile/'.$user_id.'/goals'); ?>" class="block_link fl_r">Все цели</a>
    </h2>
    <div class="goal_blocks ov">
        <?php foreach($goals as $elem): ?>
            <?php if($elem->type == 'fat'): ?>
                <div class="goal_block fl_l">
                    <div class="img_cont fl_l"><img src="/images/img_cont/profile/goal_1.jpg" alt=""/></div>
                    <?php if((int)$elem->start_weight && (int)$elem->current_weight && (int)$elem->end_weight): ?>
                        <div class="goal_info">
                            <h6>Изменение веса тела</h6>
                            <ul>
                                <li>
                                    <span class="key">Сейчас:</span>
                                    <span class="value"><?php echo (float)$elem->current_weight; ?> кг</span>
                                </li>
                                <li>
                                    <span class="key">Цель:</span>
                                    <span class="value"><?php echo (float)$elem->end_weight; ?> кг</span>
                                </li>
                                <li>
                                    <span class="key">Достижение:</span>
                                    <span class="value"><?php echo date('d.m.Y',strtotime($elem->start_weight_date)); ?> - <?php echo date('d.m.Y',strtotime($elem->end_weight_date)); ?></span>
                                </li>
                            </ul>
                            <div class="goal_progress_bar">
                                <?php
                                    if($elem->end_weight > $elem->start_weight){
                                        if($elem->current_weight > $elem->end_weight)
                                            $percentage = 100;
                                        elseif($elem->current_weight < $elem->start_weight)
                                            $percentage = 0;
                                        else
                                            $percentage = round(($elem->current_weight - $elem->start_weight)/($elem->end_weight - $elem->start_weight) * 100);
                                    } elseif($elem->end_weight < $elem->start_weight) {
                                        if($elem->current_weight > $elem->end_weight)
                                            $percentage = 100;
                                        elseif($elem->current_weight < $elem->start_weight)
                                            $percentage = 0;
                                        else
                                            $percentage = round(($elem->start_weight - $elem->current_weight)/($elem->start_weight - $elem->end_weight) * 100);
                                    } else
                                        $percentage = 100;
                                ?>
                                <div class="goal_progress_drag" style="width: <?php echo $percentage; ?>%;"></div>
                            </div>
                            <div class="progress_bar_comment">
                                <span class="reached"> Достигнуто <span><?php echo $percentage; ?></span>%</span>
                                |
                                <span class="left"> Осталось: <span>
                                    <?php
                                        $time = (strtotime($elem->end_weight_date) - time());
                                        if($time > 0){
                                            $days = round(($time/60/60/24),0,PHP_ROUND_HALF_UP);
                                            echo FunctionHelper::getTerm($days);
                                        } else
                                            echo '0 дней';
                                    ?>
                                </span></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="goal_info m_t_30">
                            <h6>Изменение веса тела</h6>
                            <p>У пользователя нет цели</p>
                        </div>
                    <?php endif; ?>
                    <?php if((int)$elem->start_fat && (int)$elem->current_fat && (int)$elem->end_fat): ?>
                        <div class="goal_info">
                            <h6>Изменение % жира</h6>
                            <ul>
                                <li>
                                    <span class="key">Сейчас:</span>
                                    <span class="value"><?php echo (float)$elem->current_fat; ?> %</span>
                                </li>
                                <li>
                                    <span class="key">Цель:</span>
                                    <span class="value"><?php echo (float)$elem->end_fat; ?> %</span>
                                </li>
                                <li>
                                    <span class="key">Достижение:</span>
                                    <span class="value"><?php echo date('d.m.Y',strtotime($elem->start_fat_date)); ?> - <?php echo date('d.m.Y',strtotime($elem->end_fat_date)); ?></span>
                                </li>
                            </ul>
                            <div class="goal_progress_bar">
                                <?php
                                    if($elem->end_fat > $elem->start_fat){
                                        if($elem->current_fat > $elem->end_fat)
                                            $percentage = 100;
                                        elseif($elem->current_fat < $elem->start_fat)
                                            $percentage = 0;
                                        else
                                            $percentage = round(($elem->current_fat - $elem->start_fat)/($elem->end_fat - $elem->start_fat) * 100);
                                    } elseif($elem->end_fat < $elem->start_fat) {
                                        if($elem->current_fat > $elem->end_fat)
                                            $percentage = 100;
                                        elseif($elem->current_fat < $elem->start_fat)
                                            $percentage = 0;
                                        else
                                            $percentage = round(($elem->start_fat - $elem->current_fat)/($elem->start_fat - $elem->end_fat) * 100);
                                    } else
                                        $percentage = 100;
                                ?>
                                <div class="goal_progress_drag" style="width: <?php echo $percentage; ?>%;"></div>
                            </div>
                            <div class="progress_bar_comment">
                                <span class="reached"> Достигнуто <span><?php echo $percentage; ?></span>%</span>
                                |
                                <span class="left"> Осталось: <span>
                                    <?php
                                    $time = (strtotime($elem->end_fat_date) - time());
                                    if($time > 0){
                                        $days = round(($time/60/60/24),0,PHP_ROUND_HALF_UP);
                                        echo FunctionHelper::getTerm($days);
                                    } else
                                        echo '0 дней';
                                    ?>
                                </span></span>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="goal_info m_t_30">
                            <h6>Изменение % жира</h6>
                            <p>У пользователя нет цели</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php elseif($elem->type == 'size'): ?>
                <div class="goal_block fl_r no_illustr">
                    <div class="goal_info">
                        <h6>Изменить объем <?php echo mb_strtolower($elem->body_part->genitive, 'utf-8'); ?></h6>
                        <ul>
                            <li>
                                <span class="key">Сейчас</span>
                                <span class="value"><?php echo (float)$elem->current_size; ?> см</span>
                            </li>
                            <li>
                                <span class="key">Цель</span>
                                <span class="value"><?php echo (float)$elem->end_size; ?> см</span>
                            </li>
                            <li>
                                <span class="key">Дата достижение</span>
                                <span class="value"><?php echo date('d.m.Y',strtotime($elem->start_date)); ?> - <?php echo date('d.m.Y',strtotime($elem->end_date)); ?></span>
                            </li>
                        </ul>
                        <div class="goal_progress_bar">
                            <?php
                                if($elem->end_size > $elem->start_size){
                                    if($elem->current_size > $elem->end_size)
                                        $percentage = 100;
                                    elseif($elem->current_size < $elem->start_size)
                                        $percentage = 0;
                                    else
                                        $percentage = round(($elem->current_size - $elem->start_size)/($elem->end_size - $elem->start_size) * 100);
                                } elseif($elem->end_size < $elem->start_size) {
                                    if($elem->current_size > $elem->end_size)
                                        $percentage = 100;
                                    elseif($elem->current_size < $elem->start_size)
                                        $percentage = 0;
                                    else
                                        $percentage = round(($elem->start_size - $elem->current_size)/($elem->start_size - $elem->end_size) * 100);
                                } else
                                    $percentage = 100;
                            ?>
                            <div class="goal_progress_drag" style="width: <?php echo $percentage; ?>%;"></div>
                        </div>
                        <div class="progress_bar_comment">
                            <span class="reached"> Достигнуто <span><?php echo $percentage; ?></span>%</span>
                            |
                            <span class="left"> Осталось: <span>
                                <?php
                                $time = (strtotime($elem->end_date) - time());
                                if($time > 0){
                                    $days = round(($time/60/60/24),0,PHP_ROUND_HALF_UP);
                                    echo FunctionHelper::getTerm($days);
                                } else
                                    echo '0 дней';
                                ?>
                            </span></span>
                        </div>
                    </div>
                </div>
            <?php elseif($elem->type == 'weight'): ?>
                <div class="goal_block fl_r no_illustr">
                    <div class="goal_info">
                        <h6><?php echo FunctionHelper::upperFirst($elem->title); ?></h6>
                        <ul>
                            <li>
                                <span class="key">Сейчас</span>
                                <span class="value"><?php echo (float)$elem->weight_current; ?> кг</span>
                            </li>
                            <li>
                                <span class="key">Дата старта</span>
                                <span class="value"><?php echo date('d.m.Y',strtotime($elem->start_date)); ?></span>
                            </li>
                            <li>
                                <span class="key">Цель</span>
                                <span class="value"><?php echo (float)$elem->weight_end; ?> кг</span>
                            </li>
                            <li>
                                <span class="key">Дата достижения</span>
                                <span class="value"><?php echo date('d.m.Y',strtotime($elem->end_date)); ?></span>
                            </li>
                        </ul>
                        <div class="goal_progress_bar">
                            <?php
                            if($elem->weight_end > $elem->weight_start){
                                if($elem->weight_current > $elem->weight_end)
                                    $percentage = 100;
                                elseif($elem->weight_current < $elem->weight_start)
                                    $percentage = 0;
                                else
                                    $percentage = round(($elem->weight_current - $elem->weight_start)/($elem->weight_end - $elem->weight_start) * 100);
                            } elseif($elem->weight_end < $elem->weight_start) {
                                if($elem->weight_current > $elem->weight_end)
                                    $percentage = 100;
                                elseif($elem->weight_current < $elem->weight_start)
                                    $percentage = 0;
                                else
                                    $percentage = round(($elem->weight_start - $elem->weight_current)/($elem->weight_start - $elem->weight_end) * 100);
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
                            <span class="left"> Осталось: <span>
                                <?php
                                $time = (strtotime($elem->end_date) - time());
                                if($time > 0){
                                    $days = round(($time/60/60/24),0,PHP_ROUND_HALF_UP);
                                    echo FunctionHelper::getTerm($days);
                                } else
                                    echo '0 дней';
                                ?>
                            </span></span>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <?php if($images && ($this->owner || $this->profile->show_photo)): ?>
    <h2>
        Фотографии
        <a href="<?php echo Yii::app()->createUrl('profile/'.$user_id.'/photo'); ?>" class="block_link fl_r">Все фотографии</a>
    </h2>
    <div class="photo_block">
        <ul>
            <?php foreach($images as $elem): ?>
                <li>
                    <a href="/profile/<?php echo $user_id.'/photo/gallery.html#'.$elem->image->id; ?>">
                        <div style="width:164px; height:164px; text-align: center;" ><img src="/pub/profile_photo/164x164/<?php echo $elem->image->image_filename; ?>" alt=""/></div>
                        <span class="comment_block"><span><?php echo ($elem->comment ? $elem->comment[0]->cnt : 0); ?></span></span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php endif; ?>

    <h2>Активность</h2>
    <div class="activity_block">
        <?php foreach ($dataProvider->getData() as $item): ?>
            <div class="activity">
                <span class="date"><?php echo DateHelper::convertDate($item['created_at']); ?></span>
                <?php if ($item['type'] === AddImageActivity::TYPE_OF_ACTIVITY): ?>
                    <?php $idsItemImageArray = explode(',', $item['ids']); ?>
                    <span class="action">добавил <?php echo count($idsItemImageArray); ?> новых фотографий</span>
                    <ul class="activity_list_img">
                        <?php foreach ($idsItemImageArray as $idImage): ?>
                            <?php if (isset($activity_images[(int)$idImage])): ?>
                                <li>
                                    <a href="<?php echo Yii::app()->createUrl('profile/photo/index', array('user_id' => $item['user_id'])); ?>">
                                        <?php echo CHtml::image(AddImageActivity::get50x50Path($activity_images[(int)$idImage]['image_filename']), AddImageActivity::get50x50Path($activity_images[(int)$idImage]['alt'])); ?>
                                    </a>
                                </li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                    <div class="all"><a href="<?php echo Yii::app()->createUrl('profile/photo/index', array('user_id' => $item['user_id'])); ?>">Все фотографии</a></div>
                <?php endif; ?>

                <?php if ($item['type'] === AddGoalActivity::TYPE_OF_ACTIVITY): ?>
                    <span class="action">добавил новую цель</span>
                    <p>
                        <?php if (isset($activity_goals[$item['source_id']])): ?>
                            <a href="<?php echo Yii::app()->createUrl('profile/goals/index', array('user_id' => $item['user_id'])) . '#goals-tab'; ?>">
                                <?php if ($activity_goals[$item['source_id']]['type'] === ProfileGoalLink::TYPE_FAT): ?>
                                    Изменить вес и процент жира
                                <?php endif; ?>
                                <?php if ($activity_goals[$item['source_id']]['type'] === ProfileGoalLink::TYPE_WEIGHT): ?>
                                    Увеличить силу
                                <?php endif; ?>
                                <?php if ($activity_goals[$item['source_id']]['type'] === ProfileGoalLink::TYPE_SIZE): ?>
                                    Изменить объем частей тела
                                <?php endif; ?>
                            </a>
                        <?php endif; ?>
                    </p>
                <?php endif; ?>

                <?php if ($item['type'] === RegisterActivity::TYPE_OF_ACTIVITY): ?>

                    <span class="action">зарегистрировался на сайте</span>
                    <p>
                        <a href="<?php echo Yii::app()->createUrl('profile/profile/index', array('user_id' => $item['user_id'])); ?>">
                            Профиль пользователя
                        </a>
                    </p>
                <?php endif; ?>

                <?php if ($item['type'] === AddProgressActivity::TYPE_OF_ACTIVITY): ?>
                    <span class="action">создал новый прогресс</span>
                    <p>
                        <a href="<?php echo Yii::app()->createUrl('profile/progress/index', array('user_id' => $item['user_id'])); ?>">
                            Прогресс пользователя
                        </a>
                    </p>
                <?php endif; ?>

                <?php if ($item['type'] === UpdateProfileActivity::TYPE_OF_ACTIVITY): ?>
                    <span class="action">обновил свои личные данные</span>
                    <p>
                        <a href="<?php echo Yii::app()->createUrl('profile/profile/index', array('user_id' => $item['user_id'])); ?>">
                            Профиль пользователя
                        </a>
                    </p>
                <?php endif; ?>

                <?php if ($item['type'] === AddProgramActivity::TYPE_OF_ACTIVITY): ?>

                    <span class="action">добавил новую программу тренировок</span>
                    <p>
                        <a href="<?php echo Yii::app()->createUrl('profile/program/index', array('user_id' => $item['user_id'])); ?>#program-tab">
                            Программа тренировок
                        </a>
                    </p>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>
        Стена
        <a href="#comment-form" class="block_link fl_r">Прокомментировать</a>
    </h2>
    <?php $this->widget('application.widgets.CommentsWidget', array('itemId' => $this->profile->id, 'modelName' => 'ProfileComment')); ?>
</div>