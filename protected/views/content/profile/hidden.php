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
    <div class="top_with_bg">
        <h1>Профиль скрыт</h1>
        <p class="recover_info">
            Данный пользователь ограничил доступ на свою страницу.
        </p>
        <a href="/" class="link">Перейти на главную</a>
    </div>
</div>
<div class="clear">&nbsp;</div>
