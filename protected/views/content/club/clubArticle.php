<div class="tab active one_news">
    <div class="breadcrumbs">
        <?php echo CHtml::link('Все новости клуба','/club/'.$this->slug.'/news.html'); ?>
    </div>
    <h2><?php echo $article->title; ?></h2>
    <?php echo $article->news; ?>
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