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

<div class="main clearfix inner_page_single">
    <?php
        $this->widget('application.widgets.Breadcrumbs', array(
            'links' => array(
                'База упражнений' => Yii::app()->createUrl('exercises/' . $type),
                $exercise->muscle->muscle => Yii::app()->createUrl('exercises/' . $type . '/' . $exercise->muscle->slug)
            )
        ));
    ?>
    <h1>
        <?php echo $exercise->title; ?>
        <span class="rating_status"><?php echo sprintf("%.1f", round($exercise->rating, 1)); ?></span>
    </h1>
    <div class="img_cont base_inner">
        <div class="img_cont_left">
            <?php if(count($exercise->images) >= 1): ?>
                <?php echo CHtml::image($exercise->getImagesExercise($exercise->images[0]->image_filename), $exercise->images[0]->alt); ?>
            <?php endif; ?>
        </div>
        <div class="img_cont_right">
            <?php if(count($exercise->images) >= 2): ?>
                <?php echo CHtml::image($exercise->getImagesExercise($exercise->images[1]->image_filename), $exercise->images[1]->alt); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="aside_right fl_r">
        <?php echo CHtml::image($exercise->muscle->getMainImageMusclePath(), $exercise->muscle->muscle); ?>
    </div>
    <div class="main_content right_margin">
        <h2>Описание</h2>
        <div>
            <dl class="ov">
                <dt>Основная группа мышц:</dt>
                <dd class="clearfix"><?php echo CHtml::link(' '.$exercise->muscle->muscle.' ', array('exercises/'.$type.'/'.$exercise->muscle->slug)); ?></dd>

                <?php if($exercise->muscles): ?>
                    <dt>Дополнительные мышцы:</dt>
                    <dd class="clearfix">
                        <?php foreach($exercise->muscles as $key => $inner){
                            echo CHtml::link($inner->muscle, array('exercises/'.$type.'/'.$inner->slug)).(isset($exercise->muscles[$key+1]) ? ', ' : '');
                        }?>
                    </dd>
                <?php endif; ?>
            </dl>
        </div>

        <?php echo $exercise->description; ?>
        <?php if($exercise->video_link): ?>
            <div class="video_box">
                <?php
                    $arr = explode('/', $exercise->video_link);
                    $arr2 = explode('=',$arr[sizeof($arr) - 1]);
                    $video = $arr2[sizeof($arr2) - 1];
                ?>
                <iframe width="565" height="360" src="//www.youtube.com/embed/<?php echo $video; ?>" frameborder="0" allowfullscreen></iframe>
            </div>
        <?php endif; ?>


        <?php if($exercise->instructions): ?>
            <h2>Инструкция по выполнению</h2>
            <ol class="instruction">
                <?php foreach($exercise->instructions as $elem): ?>
                    <li><?php echo $elem->title; ?></li>
                <?php endforeach; ?>
            </ol>
        <?php endif; ?>
    </div>


    <div class="rating">
        <?php $this->widget('application.widgets.RatingWidget', array('itemId' => $exercise->id, 'modelName' => 'ExerciseRatingUser', 'field' => 'exercise_id')); ?>
        <div class="disscus va_middle_out">
            <a href="/forum.html" class="va_middle_in">Обсудить на форуме</a>
        </div>
        <?php $this->widget('application.widgets.SocialButtons'); ?>
    </div>

    <h4>Другие упражнения на <?php echo $exercise->muscle->accusative; ?></h4>

    <ul class="main_content_list horizontal">
        <?php foreach($other as $elem): ?>
        <li>
            <?php echo CHtml::link(($elem->images ? CHtml::image($elem->getImageOtherExercise($elem->images[0]->image_filename), $elem->images[0]->alt) : ''), array('exercise/' . $elem->id),array('class' => 'img_cont')); ?>
            <div>
                <h6><?php echo CHtml::link($elem->title, array('exercise/' . $elem->id)); ?></a></h6>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
</div>
