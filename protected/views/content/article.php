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

<div class="main clearfix inner_page_single">
    <?php $this->widget('application.widgets.Breadcrumbs', array(
            'links' => array(
                'Статьи' => Yii::app()->createUrl('news/index'),
                FunctionHelper::upperFirst($article->subcategory->category->category) => Yii::app()->createUrl('news/category', array('slug' => $article->subcategory->category->slug)),
                FunctionHelper::upperFirst($article->subcategory->title) => Yii::app()->createUrl('news/subcategory', array('slug' => $article->subcategory->slug))
            )
        ));
    ?>

    <h1><?php echo FunctionHelper::upperFirst($article->title); ?></h1>
    <?php echo $article->article; ?>
    <?php $this->widget('application.widgets.SocialButtons'); ?>
    <?php if($other): ?>
        <h4>Читайте также</h4>
        <ul class="main_content_list horizontal">
            <?php foreach($other as $elem): ?>
                <li>
                    <a class="img_cont" href="<?php echo $elem->getUrlArticle(); ?>">
                        <?php echo CHtml::image($elem->getMainImageThumbnailUrlArticle(), $elem->title); ?>
                    </a>
                    <div>
                        <?php echo CHtml::link(FunctionHelper::upperFirst($elem->subcategory->title),'/news/subcategory/'.$elem->subcategory->slug.'.html ', array('class' => 'category_name')); ?>
                        <h6><a href="<?php echo $elem->getUrlArticle(); ?>"><?php echo FunctionHelper::upperFirst($elem->title); ?></a></h6>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>

    <h4 class="m_b_5">Комментарии к статье</h4>
    <div class="ov">
        <div class="comments_post_name fl_l">«<?php echo FunctionHelper::upperFirst($article->title); ?>»</div>
        <div class="fl_r comment_link"><a href="#comment-form">Прокомментировать</a></div>
    </div>

    <?php $this->widget('application.widgets.CommentsWidget', array('itemId' => $article->id, 'modelName' => 'ArticleComment')); ?>
</div>