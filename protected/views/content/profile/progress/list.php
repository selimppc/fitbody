<div class="tab active">
    <?php if($this->owner): ?>
        <h2>
            Мой прогресс
            <a href="<?php echo Yii::app()->createUrl('profile/progress/add'); ?>" class="add_btn color_btn blue fl_r">+ Добавить прогресс</a>
        </h2>
    <?php else: ?>
        <h2>
            Прогресс пользователя
        </h2>
    <?php endif; ?>
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
    <div class="progress_list">
        <ul>
            <?php foreach($progress as $elem): ?>
                <li>
                    <a class="img_before fl_l" href="javascript:void(0)"><?php //TODO::link ?>
                        <?php if($elem->before_image): ?>
                            <img src="/pub/profile_progress/photo/250x250/<?php echo $elem->before_image->image_filename; ?>" alt=""/>
                            <span class="sm_info"><?php echo date('d.m.Y',strtotime($elem->before_date)); ?></span>
                        <?php endif; ?>
                    </a>
                    <a class="img_after fl_l" href="javascript:void(0)"><?php //TODO::link ?>
                        <?php if($elem->now_image): ?>
                            <img src="/pub/profile_progress/photo/250x250/<?php echo $elem->now_image->image_filename; ?>" alt=""/>
                            <span class="sm_info"><?php echo date('d.m.Y',strtotime($elem->now_date)); ?></span>
                        <?php endif; ?>
                    </a>
                    <p class="title"><?php echo FunctionHelper::upperFirst($elem->title); ?></p>
                    <?php if($elem->now_date != '0000-00-00' && $elem->before_date != '0000-00-00'): ?>
                        <p class="duration">Срок достижения: <?php echo FunctionHelper::getTerm(abs(strtotime($elem->now_date) - strtotime($elem->before_date))/60/60/24); ?></p>
                    <?php endif; ?>
                    <p><?php echo FunctionHelper::upperFirst(($elem->now_description != '') ? $elem->now_description : $elem->before_description); ?></p>
                    <?php if($this->owner): ?>
                        <a class="edit_progress" href="<?php echo Yii::app()->createUrl('profile/progress/edit/'.$elem->id); ?>">Редактировать</a>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>