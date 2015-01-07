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

<div class="main inner_page_nosplit clearfix">
    <?php
        $this->widget('application.widgets.Breadcrumbs', array(
            'links' => array(
                'База упражнений' => Yii::app()->createUrl('exercises/' . $type)
            )
        ));
    ?>

    <div class="base_sub_block">
        <h1>Упражнения на <?php echo mb_convert_case($muscle->accusative,MB_CASE_LOWER,'UTF-8')?></h1>
        <div class="table">
            <?php echo Chtml::image($muscle->getMainImageCategoryMusclePath(), $muscle->muscle, array("class" => "table_img")); ?>
            <p class="table_block"><?php echo $muscle->description; ?></p>
        </div>
    </div>

    <ul class="main_content_list">
        <?php foreach($exercises as $elem): ?>
            <li>
                <span class="rating_status"><?php echo $elem->rating ? sprintf("%.1f", round($elem->rating, 1)) : 0; ?></span>
                <a class="img_cont fl_l two_img" href="<?php echo Yii::app()->createUrl('exercise/' . $elem->id); ?>">
                    <?php foreach($elem->images as $inner): ?>
                        <?php echo CHtml::image($elem->getImageUrlListExercise($inner->image_filename), $inner->alt, array('width' => '131px', 'height' => '131px')); ?>
                    <?php endforeach; ?>
                </a>
                <div>
                    <h6><?php echo CHtml::link($elem->title, array('exercise/'.$elem->id)); ?></h6>
                        <span class="tag">
                            Группы мышц:
                            <?php echo CHtml::link(FunctionHelper::upperFirst($elem->muscle->muscle), array('exercises/'.$type.'/'.$elem->muscle->slug));?>
                            <?php foreach($elem->muscles as $key => $inner){
                                echo ', '.CHtml::link(FunctionHelper::upperFirst($inner->muscle), array('exercises/'.$type.'/'.$inner->slug));
                            }?>
                        </span>
                    <p class="details"><?php echo $elem->short_description; ?></p>
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
