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

<div class="main clearfix inner_page_single">
    <h1><?php echo FunctionHelper::upperFirst($title); ?></h1>
    <?php echo $content; ?>
</div>