<?php if (count($activities)):?>
    <div class="aside_item activity_feed">
        <h4>Активность</h4>
        <?php foreach ($activities as $item): ?>
            <div class="activity_item">
                <a class="img_cont fl_l" href="<?php echo User::getUrlProfileById($item['user_id']); ?>"><?php echo CHtml::image(User::getPathMainImageByFilename($item['image_filename'], $item['alt'])); ?></a>
                <a class="name" href="<?php echo User::getUrlProfileById($item['user_id']); ?>"><?php echo $item['nickname']; ?></a>
                <p class="date"><?php echo DateHelper::convertDate($item['created_at']); ?></p>
                <p class="action">
                    <?php if ($item['type'] === AddImageActivity::TYPE_OF_ACTIVITY): ?>
                        <a href="<?php echo Yii::app()->createUrl('profile/photo/index', array('user_id' => $item['user_id'])); ?>">добавил <?php echo $item['count']; ?> новых фотографий</a>
                    <?php endif; ?>

                    <?php if ($item['type'] === AddGoalActivity::TYPE_OF_ACTIVITY): ?>
                        <a href="<?php echo Yii::app()->createUrl('profile/goals/index', array('user_id' => $item['user_id'])) . '#goals-tab'; ?>">добавил новую цель</a>
                    <?php endif; ?>

                    <?php if ($item['type'] === RegisterActivity::TYPE_OF_ACTIVITY): ?>
                        <a href="<?php echo Yii::app()->createUrl('profile/profile/index', array('user_id' => $item['user_id'])); ?>">зарегистрировался на сайте</a>
                    <?php endif; ?>

                    <?php if ($item['type'] === AddProgressActivity::TYPE_OF_ACTIVITY): ?>
                        <a href="<?php echo Yii::app()->createUrl('profile/progress/index', array('user_id' => $item['user_id'])); ?>">создал новый прогресс</a>
                    <?php endif; ?>

                    <?php if ($item['type'] === UpdateProfileActivity::TYPE_OF_ACTIVITY): ?>
                        <a href="<?php echo Yii::app()->createUrl('profile/profile/index', array('user_id' => $item['user_id'])); ?>">обновил свои личные данные</a>
                    <?php endif; ?>

                    <?php if ($item['type'] === AddProgramActivity::TYPE_OF_ACTIVITY): ?>
                        <a href="<?php echo Yii::app()->createUrl('profile/program/index', array('user_id' => $item['user_id'])); ?>#program-tab">добавил новую программу тренировок</a>
                    <?php endif; ?>
                </p>
            </div>
        <?php endforeach; ?>
        <div class="activity_item all_activity">
            <a href="<?php echo Yii::app()->createUrl('activity/index'); ?>">Вся активность</a>
        </div>
    </div>
<?php endif; ?>