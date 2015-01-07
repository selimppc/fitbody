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

<div class="main clearfix inner_page_single program">

    <?php
        $this->widget('application.widgets.Breadcrumbs', array(
            'links' => array_merge(array(
                'Программы тренировок' => Yii::app()->createUrl('programs/index'),
                //$program->category->title => Yii::app()->createUrl('programs', array('category' => $program->category->slug))

            ), $breadcrumbs)
        ));
    ?>

    <h1><?php echo $program->title; ?> <span class="rating_status"><?php echo sprintf("%.1f", round($program->rating, 1)); ?></span></h1>
    <div class="briefly">
        <?php echo $program->short_description; ?>
    </div>

    <?php echo $program->description; ?>

    <?php if ($program->daysRel): ?>
        <h2>Программа тренировок</h2>
        <dl>
            <?php foreach ($program->daysRel as $day): ?>
                <dt><?php echo (isset($daysOfWeek[$day->day_of_week])) ? $daysOfWeek[$day->day_of_week] : ''; ?></dt>
                <?php if ($day->description && !$day->exercisesRel): ?>
                    <dd>
                        <p class="details">
                            <?php echo $day->description; ?>
                        </p>
                    </dd>
                <?php endif; ?>
                <?php if ($day->exercisesRel): ?>
                    <?php foreach($day->exercisesRel as $exercise): ?>
                        <dd>
                            <a class="img_cont fl_l two_img" href="<?php echo Yii::app()->createUrl('exercise/' . $exercise->exercise_id);?>">
                                <?php if ($exercise->exerciseRel->images): ?>
                                    <?php foreach ($exercise->exerciseRel->images as $count => $image): ?>
                                        <?php echo CHtml::image('/pub/exercise/photo/65x65/' . $image->image_filename); ?>
                                        <?php if($count == 2) break; ?>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </a>
                            <a href="<?php echo Yii::app()->createUrl('exercise/' . $exercise->exercise_id); ?>" class="base_name">
                                <?php echo $exercise->exerciseRel->title; ?>
                            </a>
                            <?php if ($exercise->description): ?>
                                <p class="details">
                                    <?php echo $exercise->description; ?>
                                </p>
                            <?php endif; ?>
                        </dd>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </dl>
    <?php endif; ?>
    <?php echo $program->description_after; ?>
    <div class="rating">
        <?php $this->widget('application.widgets.RatingWidget', array('itemId' => $program->id, 'modelName' => 'ProgramRatingUser', 'field' => 'program_id')); ?>
        <div class="disscus va_middle_out">
            <a href="" class="va_middle_in">Обсудить на форуме</a>
        </div>
        <?php $this->widget('application.widgets.SocialButtons'); ?>
    </div>
    <h4 class="m_b_5">Комментарии к программе</h4>
    <div class="ov">
        <div class="comments_post_name fl_l">«<?php echo CHtml::encode($program->title); ?>»</div>
        <div class="fl_r comment_link"><a href="#comment-form">Прокомментировать	</a></div>
    </div>
    <?php $this->widget('application.widgets.CommentsWidget', array('itemId' => $program->id, 'modelName' => 'ProgramComment')); ?>
</div>