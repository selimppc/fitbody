<aside class="fl_r">
    <?php
    $dependency = new CChainedCacheDependency(array(
        new CTagCacheDependency(Coach::COACH_CACHE_TAG), // coaches
        new CTagCacheDependency(Club::CLUB_CACHE_TAG), // newPlace
        new CDbCacheDependency('SELECT MAX(updated_at) FROM banner LIMIT 1'), // banners
    ));
    if ($this->beginCache(__FILE__.__LINE__, array('duration' => 3600, 'dependency'=> $dependency))): ?>
        <?php $this->widget('application.widgets.SidebarWidget',array(
            'organizer' => true,
            'newPlace' => true,
            'upperRightBanner' => true,
            'bottomRightBanner' => true,
            'coaches'  => true,
        )); ?>
    <?php $this->endCache(); endif; ?>
</aside>

<div class="main clearfix inner_page">
    <div class="main_nav fl_l">
        <h5>Статьи</h5>

        <?php $this->widget('application.widgets.SideMenuWidget',array(
            'category' => (isset($widgetCategory) ? $widgetCategory : false),
            'subcategory' => (isset($widgetSubcategory) ? $widgetSubcategory : false)
        )); ?>

        <?php $this->widget('application.widgets.SideWidgets.BannerWidget', array(
            'positionId' => BannerPosition::UPPER_LEFT
        )); ?>

        <div class="week_best">
            <?php $this->widget('application.widgets.MostViewedArticlesWidget'); ?>
        </div>
    </div>
    <div class="main_content">
        <h1><?php echo $title; ?></h1>
        <ul class="main_content_list">
            <?php foreach($articles as $elem): ?>
                <li <?php echo ($elem->pick ? 'class="vip"' : ''); ?>>
                    <a class="img_cont fl_l" href="<?php echo $elem->getUrlArticle(); ?>">
                        <?php echo CHtml::image($elem->getMainImageListUrlArticle(), $elem->title); ?>
                    </a>
                    <div>
                        <span class="path"><?php echo date('j',strtotime($elem->updated_at)); ?> <?php echo $this->months[intval(date('n',strtotime($elem->updated_at)))][1]; ?></span>
                        <h6><a href="<?php echo $elem->getUrlArticle(); ?>"><?php echo CHtml::encode($elem->title); ?></a></h6>
                        <p class="details"><?php echo CHtml::encode($elem->introduction); ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="pagination_box va_middle_out">
            <?php $this->widget('application.widgets.PagerLink', array(
                'pages' => $pages,
            )) ?>
        </div>
    </div>
</div>