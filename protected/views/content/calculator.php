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
    <?php
        $this->widget('application.widgets.Breadcrumbs', array(
            'links' => array(
                'Калькульторы' => Yii::app()->createUrl('calculators/index')
            )
        ));
    ?>
    <h1><?php echo CHtml::encode($calculator->title); ?></h1>
    <div class="fl_r calc_block">
        <?php echo $calculator->calculator_code; ?>
    </div>
    <div class="calc_inner">
        <?php echo $calculator->description; ?>
    </div>
</div>