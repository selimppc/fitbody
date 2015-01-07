<?php $coach = $this->coach; ?>
<?php $this->beginContent('//layouts/main'); ?>
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
        <div class="trainer_inner">
            
            <?php
                $mainCategory = null;
                foreach ($coach->categories as $cat) {
                    if ((int) $cat->is_main === CoachCategoryLink::MAIN_CATEGORY) {
                        $mainCategory = $cat;
                        break;
                    }
                    // if not have main category ---> assign random category
                    $mainCategory = $cat;
                }

                $this->widget('application.widgets.Breadcrumbs', array(
                    'links' => array(
                        'Персональные тренеры' => Yii::app()->createUrl('coaches/index'),
                        (($mainCategory) ? $mainCategory->category->title . ' тренеры' : '') => ($mainCategory) ? Yii::app()->createUrl('coaches', array('category' => $mainCategory->category->slug)) : ''
                    )
                ));
            ?>
            <div class="rating_status">
                <div class="rating_sm">
                    <input type="hidden" name="val" value="<?php echo $coach->rating; ?>"/>
                    <input type="hidden" name="votes" value="<?php echo $coach->count_reviews; ?>"/>
                    <input type="hidden" name="vote-id" value="voteID"/>
                </div>
                <a href="<?php echo $coach->getUrlCoachAllReviews() . '#coach-reviews'; ?>" class="comments_i"><?php echo $coach->count_reviews; ?></a>
            </div>
            <h1><?php echo $coach->name; ?></h1>
            <style>
                div.owl-item{
                    text-align: center;
                    display: table;
                }
            </style>
            <?php if (count($coach->images)): ?>
                <div class="trainer_inner_slider fl_l">
                    <div class="owl-carousel">
                        <?php foreach($coach->images as $image): ?>
                            <div class="item">
                                <a class="img_cont" href="">
                                    <?php echo CHtml::image('/pub/coach/photo/500x340/' . $image->image_filename); ?>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            <div class="trainer_inner_info">
                <?php if (count($coach->clubsRel)): ?>
                    <?php foreach ($coach->clubsRel as $club): ?>
                        <div class="address"><a href="<?php echo Yii::app()->createUrl('club/' . $club->slug . '/about'); ?>">«<?php echo $club->club; ?>»</a></div>
                    <?php endforeach; ?>
                    <?php foreach ($coach->simpleClubs as $club): ?>
                        <div class="address">«<?php echo FunctionHelper::upperFirst($club->title); ?>»</div>
                    <?php endforeach; ?>
                <?php endif; ?>
                <?php if (count($coach->phones)): ?>
                    <div class="phone">
                        <?php foreach ($coach->phones as $phone): ?>
                            <span><?php echo $phone->phone; ?> </span>
                        <?php endforeach;?>
                    </div>
                <?php endif; ?>
                <?php if ($coach->website): ?>
                    <div class="site">
                        <a href="<?php echo $coach->website; ?>" target="_blank"><?php echo $coach->website; ?></a>
                    </div>
                <?php endif; ?>
                <?php if ($coach->email): ?>
                    <div class="email">
                        <a href="mailto:<?php echo $coach->email; ?>" target="_blank"><?php echo $coach->email; ?></a>
                    </div>
                <?php endif; ?>
                <?php if ($coach->skype): ?>
                    <div class="skype">
                        <a target="_blank"><?php echo $coach->skype; ?></a>
                    </div>
                <?php endif; ?>
                <?php if ($coach->cost): ?>
                    <div class="price_list">
                        <p>Стоимость:</p>
                        <?php echo $coach->cost; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <div class="trainer_inner_nav">
            <ul>
                <li id="tab-about" class="<?php echo (Yii::app()->controller->action->id === 'index') ? 'active' : ''; ?>"><a href="<?php echo $coach->getUrlCoachAllAbout(); ?>#tab-about" class="va_middle_out"><span class="va_middle_in">О тренере</span></a></li>
                <li id="tab-video" class="<?php echo (Yii::app()->controller->action->id === 'video') ? 'active' : ''; ?>"><a href="<?php echo $coach->getUrlCoachAllVideo(); ?>#tab-video" class="va_middle_out"><span class="va_middle_in">Видео тренера</span></a></li>
                <li id="tab-news" class="<?php echo (Yii::app()->controller->action->id === 'news') ? 'active' : ''; ?>"><a href="<?php echo $coach->getUrlCoachAllNews(); ?>#tab-news" class="va_middle_out"><span class="va_middle_in">Новости</span></a></li>
                <li id="tab-reviews" class="<?php echo (Yii::app()->controller->action->id === 'reviews') ? 'active' : ''; ?>"><a href="<?php echo $coach->getUrlCoachAllReviews(); ?>#tab-reviews" class="va_middle_out"><span class="va_middle_in">Отзывы (<span><?php echo $coach->count_reviews; ?></span>) </span></a></li>
            </ul>
        </div>

        <?php echo $content; ?>

    </div>


<?php $this->endContent(); ?>