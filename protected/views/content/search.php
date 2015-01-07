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

<div class="main inner_page_nosplit clearfix search_page">
    <div class="top_with_bg">
        <h1>Результаты поиска</h1>
        <?php $lastNumber = (int) substr($dataProvider->totalItemCount, -1); ?>
        <?php if ((int) $lastNumber == 1): ?>
            <p>Найден <?php echo $dataProvider->totalItemCount; ?> результат по запросу<?php echo ($query) ? (' <b>“' . CHtml::encode($query) . '”</b>:') : '.'; ?></p>
        <?php endif; ?>
        <?php if ($lastNumber > 1 && $lastNumber < 5): ?>
            <p>Найдено <?php echo $dataProvider->totalItemCount; ?> результата по запросу<?php echo ($query) ? (' <b>“' . CHtml::encode($query) . '”</b>:') : '.'; ?></p>
        <?php endif; ?>
        <?php if (!(int) $lastNumber || $lastNumber > 4): ?>
            <p>Найдено <?php echo $dataProvider->totalItemCount; ?> результатов по запросу<?php echo ($query) ? (' <b>“' . CHtml::encode($query) . '”</b>:') : '.'; ?></p>
        <?php endif; ?>
    </div>
    <div class="searched_nav fl_l">
        <ul>
            <li <?php echo (!$class) ? 'class="active"' : '' ; ?>><a href="<?php echo Yii::app()->createUrl('search/index'); ?>?q=<?php echo urlencode($query); ?>">Все результаты</a></li>
            <?php foreach ($list as $alias => $item): ?>
                <li <?php echo ($class === $alias) ? 'class="active"' : '' ; ?>><a href="<?php echo Yii::app()->createUrl('search/' . $alias); ?>?q=<?php echo urlencode($query); ?>"><?php echo $item; ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="main_content m_l_240">
        <?php $clubsIds = array(); ?>
        <?php $shopsIds = array(); ?>
        <?php $bookIds = array(); ?>
        <?php $other = array(); ?>

        <?php foreach ($dataProvider->data as $item): ?>
            <?php if($item->material_class === Search::CLASS_CLUB): ?>
                <?php array_push($clubsIds, $item->material_id); ?>
            <?php elseif($item->material_class === Search::CLASS_SHOP): ?>
                <?php array_push($shopsIds, $item->material_id); ?>
            <?php elseif($item->material_class === Search::CLASS_BOOK): ?>
                <?php array_push($bookIds, $item->material_id); ?>
            <?php else: ?>
                <?php array_push($other, $item); ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($clubsIds): ?>
            <?php $clubs = Club::model()->fetchClubsByIds($clubsIds); ?>
            <ul class="main_content_list clubs">
                <?php foreach ($clubs as $club): ?>
                    <li>
                        <a href="<?php echo $club->getUrlAbout(); ?>" class="img_cont fl_l">
                            <?php echo CHtml::image($club->getMainImageUrlSearch(), $club->club); ?>
                        </a>
                        <div class="item_info">
                            <h6><a href="<?php echo $club->getUrlAbout(); ?>"><?php echo $club->club; ?></a></h6>
                            <?php if ($club->addressesRel): ?>
                                <?php foreach ($club->addressesRel as $address): ?>
                                    <div class="address"><?php echo $address->address?></div>
                                    <?php if ($address->phones): ?>
                                        <div class="phone">
                                            <?php foreach ($address->phones as $phone): ?>
                                                <span><?php echo $phone->phone; ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if($shopsIds): ?>
            <?php $shops = Shop::model()->fetchShopsByIds($shopsIds); ?>
            <ul class="main_content_list clubs">
                <?php foreach ($shops as $shop): ?>
                    <li>
                        <a href="<?php echo $shop->getUrlAbout(); ?>" class="img_cont fl_l">
                            <?php echo CHtml::image($shop->getMainImageUrlSearch(), $shop->title); ?>
                        </a>
                        <div class="item_info">
                            <h6><a href="<?php echo $shop->getUrlAbout(); ?>"><?php echo $shop->title; ?></a></h6>
                            <?php if ($shop->addressesRel): ?>
                                <?php foreach ($shop->addressesRel as $address): ?>
                                    <div class="address"><?php echo $address->address?></div>
                                    <?php if ($address->phones): ?>
                                        <div class="phone">
                                            <?php foreach ($address->phones as $phone): ?>
                                                <span><?php echo $phone->phone; ?></span>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($bookIds): ?>
            <ul class="main_content_list books">
                <?php $books = Book::model()->fetchBooksByIds($bookIds); ?>
                <?php foreach ($books as $book): ?>
                    <li>
                        <div class="img_cont fl_l">
                            <div style="width:140px; text-align:center;"><?php echo CHtml::image($book->getImageBookPath()); ?></div>
                            <?php if ($book->file_hash && !$this->isGuest): ?>
                                <div class="load_link" style="width:140px; text-align:center;"><a href="<?php echo $book->getDownloadUrl(); ?>">Скачать книгу</a></div>
                            <?php endif; ?>
                            <?php if ($this->isGuest && $book->file_hash): ?>
                                <div class="load_link must-login" style="width:140px; text-align:center;"><a href="#<?php echo $book->slug; ?>">Скачать книгу</a></div>
                            <?php endif; ?>
                        </div>
                        <div>
                            <h6><?php echo $book->title; ?></h6>
                            <p class="details"><?php echo $book->description; ?></p>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if ($other): ?>
            <ul>
                <?php foreach ($other as $otherItem): ?>
                    <li>
                        <h6><a href="<?php echo $otherItem->path; ?>"><?php echo $otherItem->title; ?></a></h6>
                        <p><?php echo $otherItem->short_description; ?></p>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <div class="pagination_box va_middle_out">
            <?php $this->widget('application.widgets.PagerLink', array(
                'pages' => $dataProvider->getPagination(),
            )) ?>
        </div>
    </div>
</div>