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
        <h5>Программы</h5>
        <?php $this->widget('application.widgets.SideCategoryMenuWidget', array('slug' => $slug, 'categoryModel' => 'ProgramCategory', 'url' => 'programs/category')); ?>
        <?php $this->widget('application.widgets.SideWidgets.BannerWidget', array('positionId' => BannerPosition::UPPER_LEFT)); ?>
        <?php if (count($popularPrograms)): ?>
            <div class="week_best">
                <h5>Последние программы</h5>
                <ul class="week_best_list">
                <?php foreach ($popularPrograms as $item): ?>
                    <li>
                        <span class="category_name"><a href="<?php echo $item->category->getUrl(); ?>"><?php echo $item->category->title; ?></a></span>
                        <a href="<?php echo $item->getUrl(); ?>" class="description"><?php echo $item->title; ?></a>
                    </li>
                <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
    <div class="main_content">
        <h1><?php echo $categoryTitle; ?></h1>
        <ul class="main_content_list">
            <?php foreach ($programs->data as $program): ?>
                <li>
                    <a class="img_cont fl_l" href="<?php echo $program->getUrl(); ?>"><?php echo CHtml::image($program->getMainImageUrl(), $program->title); ?></a>
                    <div>
                        <span class="path"><?php echo DateHelper::convertNewsDate($program->created_at); ?></span>
                        <h6><a href="<?php echo $program->getUrl(); ?>"><?php echo $program->title; ?></a></h6>
                        <p class="details">
                            <?php echo $program->short_description; ?>
                        </p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
        <div class="pagination_box va_middle_out">
            <?php $this->widget('application.widgets.PagerLink', array(
                'pages' => $programs->getPagination(),
            )); ?>
        </div>
    </div>
</div>