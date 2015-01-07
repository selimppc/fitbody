<div class="tab active">
    <!--TODO:: video -->
    <h2 class="brd_btm p_b_20">Видео тренера</h2>
    <?php if ($video->data): ?>
        <?php foreach ($video->data as $item): ?>
            <div class="video_box m_t_30">
                <iframe width="100%" height="515" src="//www.youtube.com/embed/<?php echo $item->code; ?>" frameborder="0" allowfullscreen></iframe>
                <p class="video_title"><?php echo $item->title; ?></p>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    <div class="pagination_box va_middle_out">
        <?php $this->widget('application.widgets.PagerLink', array(
            'pages' => $video->getPagination(),
        )) ?>
    </div>
</div>