<aside class="fl_r">
    <?php if ($this->beginCache(__FILE__.__LINE__, array('dependency'=>array(
        'class'=>'system.caching.dependencies.CDbCacheDependency',
        'sql'=>'SELECT MAX(updated_at) FROM banner LIMIT 1')))): ?>
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
    <div class="clubs_inner">
        <div class="breadcrumbs">
            <a href="<?php echo Yii::app()->getBaseUrl(true);  ?>"> Главная </a>—
            <?php echo CHtml::link(" Фитнес клубы ",'/club/list.html'); ?>
        </div>
        <div class="rating_status">
            <div class="rating_sm">
                <input type="hidden" name="val" value="<?php echo $club->rating;?>"/>
                <input type="hidden" name="votes" value="<?php echo $club->count_reviews;?>"/>
                <input type="hidden" name="vote-id" value="voteID"/>
            </div>
            <?php echo CHtml::link($club->count_reviews,'/club/'.$club->slug.'/reviews.html', array('class' => 'comments_i')); ?>
        </div>
        <h1><?php echo FunctionHelper::upperFirst($club->club); ?></h1>
        <div class="clubs_inner_slider fl_l">
            <div id="clubs_inner_sync1" class="owl-carousel">
                <?php foreach($club->images as $elem): ?>
                    <div class="item"><?php echo CHtml::link('<img src="/pub/club/photo/500x340/'.$elem->image_filename.'" alt="'.$elem->alt.'"/>','javascript::void(0);', array('class' => 'img_cont')); ?></div>
                <?php endforeach; ?>
            </div>
            <div id="clubs_inner_sync2" class="owl-carousel">
                <?php foreach($club->images as $elem): ?>
                    <div class="item"><img src="/pub/club/photo/83x60/<?php echo $elem->image_filename; ?>" alt="<?php echo $elem->alt; ?>"/></div>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="clubs_inner_info">
            <div class="address">г.<?php echo FunctionHelper::upperFirst($address->city->city); ?>,<?php echo $address->address; ?> <div class="map_link"><a href="#show_map">на карте</a></div></div>
            <div class="work_time">
                <dl>
                    <?php foreach($address->worktimes as $key => $elem): ?>
                        <dt><?php echo $days[$elem->from_day].($elem->from_day == $elem->to_day ? '' : ' - '.$days[$elem->to_day].': '); ?></dt>
                        <dd class="clearfix">c <?php echo substr($elem->from_time, 0, 5); ?> до <?php echo substr($elem->to_time, 0, 5); ?><?php echo (isset($address->worktimes[$key+1]) ? ',' : '');?></dd>
                    <?php endforeach; ?>
                </dl>
            </div>
            <div class="phone">
                <?php foreach($address->phones as $elem): ?>
                    <span><?php echo $elem->phone; ?><?php if($elem->description != '') echo ' <span class="phone_comment">('.$elem->description.')</span>'; ?></span>
                <?php endforeach; ?>
            </div>
            <div class="site"><a href="http://<?php echo $club->site; ?>"><?php echo $club->site; ?></a></div>
        </div>
    </div>
    <div class="clubs_inner_nav">
        <?php $this->widget('application.widgets.ClubTabsWidget',array(
            'slug'  => $club->slug,
            'active'=> 'about',
            'club_id' => $club->id,
        )); ?>
    </div>
    <div class="tab active">
        <h2>Описание клуба</h2>
        <table class="club_description">
            <?php foreach($club->propertiesRel as $elem): ?>
            <tr>
                <td><?php echo Yii::app()->image->getImageTag($elem->property->image_id,23,25); ?><?php echo FunctionHelper::upperFirst($elem->property->property); ?></td>
                <td><?php echo FunctionHelper::upperFirst($elem->description); ?></td>
            </tr>
            <?php endforeach; ?>
        </table>
        <?php echo $club->description; ?>
        <h2 id="show_map">Клуб на карте</h2>
        <div class="map" id="map">
            <input type="hidden" id="lat" value="<?php echo $address->lat; ?>"/>
            <input type="hidden" id="lon" value="<?php echo $address->lon; ?>"/>
            <img src="http://static-maps.yandex.ru/1.x/?ll=<?php echo $address->lon; ?>,<?php echo $address->lat; ?>&size=650,350&z=16&l=map&pt=<?php echo $address->lon; ?>,<?php echo $address->lat; ?>,pm2blm" alt=""/>
        </div>
        <div class="social_btns">
            <div class="social_btns_inner">
                <ul>
                    <li><a href=""><img src="/images/fb_bg.png" alt=""></a></li>
                    <li><a href=""><img src="/images/vk_bg.png" alt=""></a></li>
                    <li><a href=""><img src="/images/twitter_bg.png" alt=""></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>
