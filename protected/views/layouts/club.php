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
    <div class="clubs_inner compact">
        <div class="breadcrumbs">
            <a href="<?php echo Yii::app()->getBaseUrl(true);  ?>"> Главная </a>—
            <?php echo CHtml::link(" Фитнес клубы ",'/club/list.html'); ?>
        </div>
        <h1><?php echo FunctionHelper::upperFirst($this->club->club); ?></h1>
        <div class="img_cont fl_l">
            <?php if(isset($this->club->images[0])) echo Yii::app()->image->getImageTag($this->club->images[0]->id,130,90); ?>
        </div>

        <div class="clubs_inner_info">
            <div class="address">г.<?php echo FunctionHelper::upperFirst($this->club->addressesRel[0]->city->city); ?>,<?php echo $this->club->addressesRel[0]->address; ?> <div class="map_link"><?php echo CHtml::link("на карте",'/club/'.$this->club->slug.'/about.html#show_map'); ?></div></div>
            <div class="work_time">
                <dl>
                    <?php foreach($this->club->addressesRel[0]->worktimes as $key => $elem): ?>
                        <dt><?php echo $this->days[$elem->from_day].($elem->from_day == $elem->to_day ? '' : ' - '.$this->days[$elem->to_day].': '); ?></dt>
                        <dd class="clearfix">c <?php echo substr($elem->from_time, 0, 5); ?> до <?php echo substr($elem->to_time, 0, 5); ?><?php echo (isset($this->club->addressesRel[0]->worktimes[$key+1]) ? ',' : '');?></dd>
                    <?php endforeach; ?>
                </dl>
            </div>
            <div class="phone">
                <?php foreach($this->club->addressesRel[0]->phones as $elem): ?>
                    <span><?php echo $elem->phone; ?><?php if($elem->description != '') echo ' <span class="phone_comment">('.$elem->description.')</span>'; ?></span>
                <?php endforeach; ?>
            </div>
            <div class="site"><a href="http://<?php echo $this->club->site; ?>"><?php echo $this->club->site; ?></a></div>
        </div>
    </div>
    <div class="clubs_inner_nav">
        <?php $this->widget('application.widgets.ClubTabsWidget',array(
            'slug'  => $this->slug,
            'active'=> $this->active,
            'club_id'=> $this->club->id,
        )); ?>
    </div>

    <?php echo $content; ?>

</div>
<?php $this->endContent(); ?>