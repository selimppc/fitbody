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
<div class="main">
    <div class="index_main_slider">
        <div id="sync1" class="owl-carousel">
            <?php foreach($indexArticles as $elem) : ?>
                <div class="item ov">
                    <a class="img_cont fl_l" href="<?php echo $elem->getUrlArticle(); ?>"><?php echo CHtml::image($elem->getMainImageUrlArticle(), $elem->title); ?></a>
                    <h3><a href="<?php echo $elem->getUrlArticle(); ?>"><?php echo CHtml::encode($elem->title); ?></a></h3>
                    <p><?php echo CHtml::encode($elem->introduction); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
        <div id="sync2" class="owl-carousel">
            <?php foreach($indexArticles as $elem) : ?>
                <div class="item">
                    <?php echo CHtml::image($elem->getMainImageThumbnailUrlArticle(), $elem->title); ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php $this->widget('application.widgets.ExercisesWidget'); ?>
    <div class="updates">
        <h2>Лента обновлений</h2>
        <div class="updates_right fl_r">
            <?php $this->widget('application.widgets.CompanyNewsWidget'); ?>
            <?php $this->widget('application.widgets.SideWidgets.BannerWidget', array('positionId' => BannerPosition::INDEX_CENTER)); ?>
            <div class="week_best">
                <?php $this->widget('application.widgets.MostViewedArticlesWidget'); ?>
            </div>
        </div>
        <div class="updates_left">
            <ul class="updates_list">
                <?php foreach($articles as $elem) : ?>
                    <li>
                        <a class="img_cont fl_l" href="<?php echo $elem->getUrlArticle(); ?>">
                            <?php echo CHtml::image($elem->getMainImageListUrlArticle(), $elem->title); ?>
                        </a>
                        <div>
                            <span class="path">
                                <?php echo CHtml::link($elem->subcategory->title, '/news/subcategory/'.$elem->subcategory->slug.'.html', array('class' => 'category_name')); ?>
                                <?php echo date('j',strtotime($elem->created_at)); ?>
                                <?php echo $this->month[intval(date('n',strtotime($elem->created_at)))][1]; ?>
                            </span>
                            <h6><a href="<?php echo $elem->getUrlArticle(); ?>"><?php echo CHtml::encode($elem->title); ?></a></h6>
                            <p class="details"><?php echo CHtml::encode($elem->introduction); ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="clear">&nbsp;</div>
    </div>
</div>