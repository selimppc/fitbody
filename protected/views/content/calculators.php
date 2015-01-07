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

    <h1>Калькуляторы</h1>
    <?php if ($calculators): ?>
        <ul class="main_content_list calc">
            <?php foreach($calculators as $calculator): ?>
                <li>
                    <a class="img_cont fl_l" href="<?php echo $calculator->getCalculatorUrl(); ?>">
                        <?php if ($calculator->image): ?>
                            <?php echo CHtml::image('/pub/calculator/main/photo/265x180/' . $calculator->image->image_filename, $calculator->title); ?>
                        <?php endif; ?>
                    </a>
                    <div>
                        <h6><a href="<?php echo Yii::app()->createUrl('calculator/' . $calculator->slug); ?>"><?php echo CHtml::encode($calculator->title); ?></a></h6>
                        <p class="details"><?php echo CHtml::encode($calculator->short_description); ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>