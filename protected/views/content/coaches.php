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

<div class="main clearfix inner_page">
<div class="main_nav fl_l">
    <h5>Тренеры</h5>
    <?php $this->widget('application.widgets.SideCategoryMenuWidget', array('slug' => $categorySlug, 'categoryModel' => 'CoachCategory', 'titleField' => 'title', 'url' => 'coaches/category'))?>
    <?php // $this->widget('application.widgets.CoachRecommendedSideWidget'); ?>
    <?php $this->widget('application.widgets.SideWidgets.BannerWidget', array('positionId' => BannerPosition::UPPER_LEFT)); ?>
    <?php if (count($lastNews)): ?>
        <div class="week_best">
            <h5>Последние новости</h5>
            <ul class="week_best_list">
                <?php foreach ($lastNews as $item): ?>
                    <li>
                        <span class="category_name"><a href="<?php echo $item->coach->getUrlCoachAllAbout(); ?>"><?php echo $item->coach->name; ?></a></span>
                        <a href="<?php echo $item->getNewsUrl($item->coach->slug); ?>" class="description"><?php echo $item->title; ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>
<div class="main_content">
    <h1><?php echo ($categoryTitle) ? $categoryTitle . ' тренеры' : 'Тренеры' ; ?> </h1>
    <ul class="main_content_list trainers">
        <?php foreach ($coaches->data as $coach): ?>
            <li>
                <div class="rating_status">
                    <div class="rating_sm">
                        <input type="hidden" name="val" value="<?php echo $coach->rating; ?>"/>
                        <input type="hidden" name="votes" value="<?php echo $coach->count_reviews; ?>"/>
                        <input type="hidden" name="vote-id" value="voteID"/>
                    </div>
                    <a href="<?php echo Yii::app()->createUrl('coach/' . $coach->slug . '/reviews') . '#coach-reviews'; ?>" class="comments_i"><?php echo $coach->count_reviews; ?></a>
                </div>

                    <?php echo CHtml::link('<div style="width:200px; text-align: center;">'.CHtml::image($coach->getImageCoachesPath(), $coach->name).'</div>', array('coach/' . $coach->id), array('class' => 'img_cont fl_l')); ?>

                <div>
                    <h6><?php echo CHtml::link(CHtml::encode($coach->name), array('coach/' . $coach->slug)); ?></h6>
                    <p class="details"><?php echo CHtml::encode($coach->short_description); ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <div class="pagination_box va_middle_out">
        <?php $this->widget('application.widgets.PagerLink', array(
            'pages' => $coaches->getPagination(),
        )) ?>
    </div>
</div>
</div>