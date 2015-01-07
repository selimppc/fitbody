<aside class="fl_r">
    <?php
    $dependency = new CChainedCacheDependency(array(
        //new CTagCacheDependency(Coach::COACH_CACHE_TAG), // coaches
        new CTagCacheDependency(Club::CLUB_CACHE_TAG), // newPlace
        new CDbCacheDependency('SELECT MAX(updated_at) FROM banner LIMIT 1'), // banners
    ));
    if ($this->beginCache(__FILE__.__LINE__, array('duration' => 3600, 'dependency'=> $dependency))): ?>
        <?php $this->widget('application.widgets.SidebarWidget',array(
            'organizer' => true,
            'newPlace' => true,
            'upperRightBanner' => true,
            'bottomRightBanner' => true,
            'coaches'  => false,
        )); ?>
        <?php $this->endCache(); endif; ?>
</aside>
<div class="main inner_page_nosplit clearfix">
    <h1 class="m_t_10">Активность пользователей</h1>
    <ul class="activity_list">
        <?php foreach ($dataProvider->getData() as $item): ?>
            <li>
                <a class="user_photo fl_l" href="<?php echo User::getUrlProfileById($item['user_id']); ?>">
                    <?php echo CHtml::image(User::getPathMainImageByFilename($item['image_filename'], $item['alt'])); ?>
                </a>
                <div class="info">
                    <a class="name" href="<?php echo User::getUrlProfileById($item['user_id']); ?>"><?php echo $item['nickname']; ?></a>
                    <span class="date"><?php echo DateHelper::convertDate($item['created_at']); ?></span>


                    <?php if ($item['type'] === AddImageActivity::TYPE_OF_ACTIVITY): ?>
                        <?php $idsItemImageArray = explode(',', $item['ids']); ?>
                        <span class="action">добавил <?php echo count($idsItemImageArray); ?> новых фотографий</span>
                        <ul class="activity_list_img">
                            <?php foreach ($idsItemImageArray as $idImage): ?>
                                <?php if (isset($images[(int)$idImage])): ?>
                                    <li>
                                        <a href="<?php echo Yii::app()->createUrl('profile/photo/index', array('user_id' => $item['user_id'])); ?>">
                                            <?php echo CHtml::image(AddImageActivity::getPathImageByFilename($images[(int)$idImage]['image_filename']), AddImageActivity::getPathImageByFilename($images[(int)$idImage]['alt'])); ?>
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
                            <?php if (isset($goals[$item['source_id']])): ?>
                                <a href="<?php echo Yii::app()->createUrl('profile/goals/index', array('user_id' => $item['user_id'])) . '#goals-tab'; ?>">
                                    <?php if ($goals[$item['source_id']]['type'] === ProfileGoalLink::TYPE_FAT): ?>
                                        Изменить вес и процент жира
                                    <?php endif; ?>
                                    <?php if ($goals[$item['source_id']]['type'] === ProfileGoalLink::TYPE_WEIGHT): ?>
                                        Увеличить силу
                                    <?php endif; ?>
                                    <?php if ($goals[$item['source_id']]['type'] === ProfileGoalLink::TYPE_SIZE): ?>
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
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="pagination_box va_middle_out">
        <?php $this->widget('application.widgets.PagerLink', array(
            'pages' => $dataProvider->getPagination(),
        )) ?>
    </div>
</div>