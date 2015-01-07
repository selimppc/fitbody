<div class="tab active">
    <h2>Фотографии</h2>
    <div class="photo_block m_b_45">
        <div class="photo_inner_slider">
            <div id="photo_inner_sync1" class="owl-carousel">
                <?php foreach($images as $elem): ?>
                    <div class="item" data-image="<?php echo $elem->image->id; ?>"><a class="img_cont" href=""><img src="/pub/profile_photo/466x466/<?php echo $elem->image->image_filename; ?>" alt=""/></a></div>
                <?php endforeach; ?>
            </div>
            <div id="photo_inner_sync2" class="owl-carousel">
                <?php foreach($images as $elem): ?>
                    <?php $last_image = $elem->image; ?>
                    <div class="item">
                        <img src="/pub/profile_photo/102x102/<?php echo $elem->image->image_filename; ?>" alt=""/>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <h4 class="m_b_5">
        <span class="fl_r comment_link"><a href="#comment-form">Прокомментировать</a></span>
        Комментарии к фотографии
    </h4>
    <div class="comments_box"></div>
</div>