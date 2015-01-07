<div class="tab active">
    <h2>Новости</h2>
    <?php if ($news->data): ?>
        <ul class="main_content_list">
            <?php foreach ($news->data as $item): ?>
                <li>
                    <a class="img_cont fl_l" href="<?php echo $item->getNewsUrl($this->coach->slug); ?>">
                        <?php echo CHtml::image($item->getMainImagePath(), $item->title); ?>
                    </a>
                    <div>
                        <span class="path"><?php echo DateHelper::convertNewsDate($item->created_at); ?></span>
                        <h6><a href="<?php echo $item->getNewsUrl($this->coach->slug); ?>"><?php echo $item->title; ?></a></h6>
                        <p class="details"><?php echo $item->short_description; ?></p>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php endif; ?>
</div>