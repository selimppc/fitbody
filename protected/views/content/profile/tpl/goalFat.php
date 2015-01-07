<input type="hidden" class="goal_id" value="<?php echo (float)$goal->id; ?>"/>
<div class="goal_links edit_link">
    <a class="goal_delete_link delete_button" data-id="<?php echo (float)$goal->id; ?>" href="javascript:;">Удалить</a>
    <a class="edit_button" href="javascript:;">Редактировать</a>
</div>
<div class="goal_links progress_link">
    <a class="progress_button" href="javascript:;">Прогресс</a>
</div>
<div class="img_cont fl_l"><img src="/images/img_cont/profile/goal_1.jpg" alt=""/></div>
<?php if((int)$goal->start_weight && (int)$goal->current_weight && (int)$goal->end_weight): ?>
    <div class="goal_info">
        <h6>Изменение веса тела</h6>
        <ul>
            <li>
                <span class="key">Сейчас:</span>
                <input type="hidden" class="goal_weight_start" value="<?php echo floatval((float)$goal->start_weight); ?>"/>
                <span class="value"><?php echo (float)$goal->current_weight; ?> кг</span>
            </li>
            <li>
                <span class="key">Цель:</span>
                <input type="hidden" class="goal_weight" value="<?php echo floatval((float)$goal->end_weight); ?>"/>
                <span class="value"><?php echo (float)$goal->end_weight; ?> кг</span>
            </li>
            <li>
                <span class="key">Достижение:</span>
                <input type="hidden" class="goal_weight_date" value="<?php echo date('d.m.Y',strtotime($goal->end_weight_date)); ?>"/>
                <span class="value"><?php echo date('d.m.Y',strtotime($goal->start_weight_date)); ?> - <?php echo date('d.m.Y',strtotime($goal->end_weight_date)); ?></span>
            </li>
        </ul>
        <div class="goal_progress_bar">
            <?php
            if($goal->end_weight > $goal->start_weight){
                $percentage = round(($goal->current_weight - $goal->start_weight)/($goal->end_weight - $goal->start_weight) * 100);
            } elseif($goal->end_weight < $goal->start_weight) {
                $percentage = round(($goal->start_weight - $goal->current_weight)/($goal->start_weight - $goal->end_weight) * 100);
            } else
                $percentage = 100;
            ?>
            <div class="goal_progress_drag" style="width: <?php echo $percentage; ?>%;"></div>
        </div>
        <div class="progress_bar_comment">
            <span class="reached"> Достигнуто <span><?php echo $percentage; ?></span>%</span>
            |
            <span class="left"> Осталось <?php
                $time = (strtotime($goal->end_weight_date) - time());
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
<?php if((int)$goal->start_fat && (int)$goal->current_fat && (int)$goal->end_fat): ?>
    <div class="goal_info">
        <input type="hidden" class="goal_id" value="<?php echo (float)$goal->id; ?>"/>
        <h6>Изменение % жира</h6>
        <ul>
            <li>
                <span class="key">Сейчас:</span>
                <input type="hidden" class="goal_fat_start" value="<?php echo floatval((float)$goal->start_fat); ?>"/>
                <span class="value"><?php echo (float)$goal->current_fat; ?> %</span>
            </li>
            <li>
                <span class="key">Цель:</span>
                <input type="hidden" class="goal_fat" value="<?php echo floatval((float)$goal->end_fat); ?>"/>
                <span class="value"><?php echo (float)$goal->end_fat; ?> %</span>
            </li>
            <li>
                <span class="key">Достижение:</span>
                <input type="hidden" class="goal_fat_date" value="<?php echo date('d.m.Y',strtotime($goal->end_fat_date)); ?>"/>
                <span class="value"><?php echo date('d.m.Y',strtotime($goal->start_fat_date)); ?> - <?php echo date('d.m.Y',strtotime($goal->end_fat_date)); ?></span>
            </li>
        </ul>
        <div class="goal_progress_bar">
            <?php
            if($goal->end_fat > $goal->start_fat){
                if($goal->current_fat > $goal->end_fat)
                    $percentage = 100;
                elseif($goal->current_fat < $goal->start_fat)
                    $percentage = 0;
                else
                    $percentage = round(($goal->current_fat - $goal->start_fat)/($goal->end_fat - $goal->start_fat) * 100);
            } elseif($goal->end_fat < $goal->start_fat) {
                if($goal->current_fat > $goal->end_fat)
                    $percentage = 100;
                elseif($goal->current_fat < $goal->start_fat)
                    $percentage = 0;
                else
                    $percentage = round(($goal->start_fat - $goal->current_fat)/($goal->start_fat - $goal->end_fat) * 100);
            } else
                $percentage = 100;
            ?>
            <div class="goal_progress_drag" style="width: <?php echo $percentage; ?>%;"></div>
        </div>
        <div class="progress_bar_comment">
            <span class="reached"> Достигнуто <span><?php echo $percentage; ?></span>%</span>
            |
                                <span class="left"> Осталось <?php
                                    $time = (strtotime($goal->end_fat_date) - time());
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