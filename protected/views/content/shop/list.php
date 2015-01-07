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
        <h5>Категории</h5>
        <?php $this->widget('application.widgets.ShopMenuWidget',array(
            'active' => $category,
        )); ?>

        <?php $this->widget('application.widgets.SideWidgets.BannerWidget', array('positionId' => BannerPosition::UPPER_LEFT)); ?>

        <?php if (count($lastNews)): ?>
            <div class="week_best">
                <h5>Последние новости</h5>
                <ul class="week_best_list">
                    <?php foreach ($lastNews as $item): ?>
                        <li>
                            <span class="category_name"><a href="<?php echo $item->shop->getUrlAbout(); ?>"><?php echo FunctionHelper::upperFirst($item->shop->title); ?></a></span>
                            <a href="<?php echo $item->getUrl($item->shop->slug); ?>" class="description"><?php echo $item->title; ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

    </div>
    <div class="main_content">
        <h1>Магазины</h1>
        <?php if($pickedShops): ?>
            <div class="recommend">
                Рекомендуем
            </div>
        <?php endif; ?>
        <ul class="main_content_list clubs">
            <?php foreach($pickedShops as $elem): ?>
                <li class="vip">
                    <div class="img_cont with_preview fl_l">
                        <a class="img_lg_box" href="<?php echo Yii::app()->createUrl('shop/'.$elem->slug.'/about'); ?>">
                            <?php foreach($elem->images as $innerKey => $innerElem){
                                if($innerKey == 5) break;
                                echo '<img class="img_lg img_lg'.$innerKey.'" width="200" height="149" src="/pub/shop/images/200x149/'.$innerElem['image_filename'].'" alt="1164389797_1384907646918995.jpg">';
                            } ?>
                        </a>
                        <ul class="img_preview_box">
                            <?php foreach($elem->images as $innerKey => $innerElem){
                                if($innerKey == 5) break;
                                echo '<li>'.CHtml::link('<img data-img-class="img_lg'.$innerKey.'" width="38" height="39" src="/pub/shop/images/38x39/'.$innerElem['image_filename'].'" alt="1164389797_1384907646918995.jpg">','/shop/'.$elem->slug.'/about.html').'</li>';
                            } ?>
                        </ul>
                    </div>

                    <div class="item_info">
                        <h6><?php echo CHtml::link(FunctionHelper::upperFirst($elem->title),'/shop/'.$elem->slug.'/about.html'); ?></h6>
                        <div class="address"><?php echo 'г.'.FunctionHelper::upperFirst($elem->addressesRel[0]->city->city).', '.$elem->addressesRel[0]->address; ?></div>
                        <div class="work_time">
                            <dl>
                                <?php foreach($elem->addressesRel[0]->worktimes as $innerKey => $innerElem): ?>
                                    <dt><?php echo $days[$innerElem->from_day].($innerElem->from_day != $innerElem->to_day ? ' - '.$days[$innerElem->to_day] : '').': '; ?></dt>
                                    <dd><?php echo mb_substr($innerElem->from_time,0,5).' - '.mb_substr($innerElem->to_time,0,5); ?><?php echo (isset($elem->addressesRel[0]->worktimes[$innerKey+1]) ? "," : "");?></dd>
                                    <br>
                                <?php endforeach; ?>
                            </dl>
                        </div>
                        <div class="phone">
                            <?php foreach($elem->addressesRel[0]->phones as $innerKey => $innerElem): ?>
                                <span><?php echo $innerElem->phone; ?></span>
                                <?php echo $innerKey%2 ? "<br>" : "";?>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>

        <ul class="tab_nav">
            <li class="active"><a href="#clubs_list" class="va_middle_out"><span class="va_middle_in">Списком</span></a></li>
            <li class=""><a href="#clubs_on_map" class="va_middle_out"><span class="va_middle_in">На карте</span></a></li>
        </ul>

        <div class="tab active" id="clubs_list">
            <ul class="main_content_list clubs">
                <?php foreach($shops as $elem): ?>
                    <li class="">
                        <div class="img_cont with_preview fl_l">
                            <a class="img_lg_box" href="<?php echo Yii::app()->createUrl('shop/'.$elem->slug.'/about'); ?>">
                                <?php foreach($elem->images as $innerKey => $innerElem){
                                    if($innerKey == 5) break;
                                    echo '<img class="img_lg img_lg'.$innerKey.'" width="200" height="149" src="/pub/shop/images/200x149/'.$innerElem['image_filename'].'" alt="1164389797_1384907646918995.jpg">';
                                } ?>
                            </a>
                            <ul class="img_preview_box">
                                <?php foreach($elem->images as $innerKey => $innerElem){
                                    if($innerKey == 5) break;
                                    echo '<li>'.CHtml::link('<img data-img-class="img_lg'.$innerKey.'" width="38" height="39" src="/pub/shop/images/38x39/'.$innerElem['image_filename'].'" alt="1164389797_1384907646918995.jpg">','/shop/'.$elem->slug.'/about.html').'</li>';
                                } ?>
                            </ul>
                        </div>

                        <div class="item_info">
                            <h6><?php echo CHtml::link(FunctionHelper::upperFirst($elem->title),'/shop/'.$elem->slug.'/about.html'); ?></h6>
                            <div class="address"><?php echo 'г.'.FunctionHelper::upperFirst($elem->addressesRel[0]->city->city).', '.$elem->addressesRel[0]->address; ?></div>
                            <div class="work_time">
                                <dl>
                                    <?php foreach($elem->addressesRel[0]->worktimes as $innerKey => $innerElem): ?>
                                        <dt><?php echo $days[$innerElem->from_day].($innerElem->from_day != $innerElem->to_day ? ' - '.$days[$innerElem->to_day] : '').': '; ?></dt>
                                        <dd><?php echo mb_substr($innerElem->from_time,0,5).' - '.mb_substr($innerElem->to_time,0,5); ?><?php echo (isset($elem->addressesRel[0]->worktimes[$innerKey+1]) ? "," : "");?></dd>
                                        <br>
                                    <?php endforeach; ?>
                                </dl>
                            </div>
                            <div class="phone">
                                <?php foreach($elem->addressesRel[0]->phones as $innerKey => $innerElem): ?>
                                    <span><?php echo $innerElem->phone; ?></span>
                                    <?php echo $innerKey%2 ? "<br>" : "";?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
        <div class="tab ov" id="clubs_on_map">
            <div class="place_filter">
                <p>Показать тренажерные залы рядом с вами</p>
                <form action="#">
                    <div class="row_inline">
                        <input type="text" id="address_field" placeholder="Введите город и адрес"/>
                    </div>
                    <div class="row_inline h_34">
                        <label>
                            <style>
                                .jq-selectbox{
                                    min-width: 110px;
                                }
                            </style>
                            <select name="" id="address_range">
                                <option value="3000000">Все</option>
                                <option value="2500">до 2500 м.</option>
                                <option value="2000">до 2000 м.</option>
                                <option value="1500">до 1500 м.</option>
                                <option value="1000">до 1000 м.</option>
                                <option value="500">до 500 м.</option>
                        </label>
                    </div>
                    <div class="row_inline">
                        <input type="submit" id="address_show" value="Показать" class="h_34"/>
                    </div>
                    <div class="clear">&nbsp;</div>
                </form>
            </div>
            <div class="map" id="map" style="width: 659px; height: 455px"></div>
            <ul class="main_content_list clubs no_illustr" id="list">

            </ul>
        </div>

        <div class="pagination_box va_middle_out">
            <?php $this->widget('application.widgets.PagerLink', array(
                'pages' => $pages,
            )) ?>
        </div>
    </div>
</div>