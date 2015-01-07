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
        <h5>Книги</h5>
        <?php $this->widget('application.widgets.SideCategoryMenuWidget', array('slug' => $slug, 'categoryModel' => 'BookCategory', 'titleField' => 'title', 'url' => 'books/category')); ?>
        <?php $this->widget('application.widgets.SideWidgets.BannerWidget', array('positionId' => BannerPosition::UPPER_LEFT)); ?>
    </div>
    <div class="main_content">
        <h1><?php echo ($categoryTitle) ? $categoryTitle : 'Вся литература'; ?></h1>
        <?php if ($books->data): ?>
            <ul class="main_content_list books">
                <?php foreach ($books->data as $book): ?>
                    <li class="bordered_light">
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

        <div class="pagination_box va_middle_out">
            <?php $this->widget('application.widgets.PagerLink', array(
                'pages' => $books->getPagination(),
            )) ?>
        </div>
    </div>
</div>