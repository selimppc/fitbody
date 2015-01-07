<div class="goal_links edit_link">
    <a class="goal_delete_link delete_button" data-id="<?php echo (float)$goal->id; ?>" href="javascript:;">Удалить</a>
    <a class="edit_button" href="javascript:;">Редактировать</a>
</div>
<div class="goal_links progress_link">
    <a class="progress_button" href="javascript:;">Прогресс</a>
</div>
<div class="goal_info">
    <input type="hidden" class="goal_id" value="<?php echo (float)$goal->id; ?>"/>
    <h6>Изменить объем <?php echo mb_strtolower($goal->body_part->genitive, 'utf-8'); ?></h6>
    <input type="hidden" class="goal_title" value="<?php echo $goal->body_part->id; ?>"/>
    <ul>
        <li>
            <span class="key">Сейчас</span>
            <span class="value"><?php echo (float)$goal->current_size; ?> см</span>
        </li>
        <li>
            <span class="key">Цель</span>
            <input type="hidden" class="goal_value" value="<?php echo floatval((float)$goal->end_size); ?>"/>
            <span class="value"><?php echo (float)$goal->end_size; ?> см</span>
        </li>
        <li>
            <span class="key">Дата достижения</span>
            <input type="hidden" class="goal_date" value="<?php echo date('d.m.Y',strtotime($goal->end_date)); ?>"/>
            <span class="value"><?php echo date('d.m.Y',strtotime($goal->start_date)); ?> - <?php echo date('d.m.Y',strtotime($goal->end_date)); ?></span>
        </li>
    </ul>
    <div class="goal_progress_bar">
        <?php
            if($goal->end_size > $goal->start_size){
                if($goal->current_size > $goal->end_size)
                    $percentage = 100;
                elseif($goal->current_size < $goal->start_size)
                    $percentage = 0;
                else
                    $percentage = round(($goal->current_size - $goal->start_size)/($goal->end_size - $goal->start_size) * 100);
            } elseif($goal->end_size < $goal->start_size) {
                if($goal->current_size < $goal->end_size)
                    $percentage = 100;
                elseif($goal->current_size > $goal->start_size)
                    $percentage = 0;
                else
                    $percentage = round(($goal->start_size - $goal->current_size)/($goal->start_size - $goal->end_size) * 100);
            } else {
                $percentage = 100;
            }
        ?>
        <div class="goal_progress_drag" style="width: <?php echo $percentage; ?>%;"></div>
    </div>
    <div class="progress_bar_comment">
        <span class="reached"> Достигнуто <span><?php echo $percentage; ?></span>%</span>
        |
        <span class="left"> Осталось <?php
            $time = (strtotime($goal->end_date) - time());
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