<div class="tab active club_news">
    <h2>Новости магазина</h2>
    <ul class="main_content_list">
        <?php foreach($news as $elem): ?>
            <li>
                <?php echo CHtml::link('<img src="/pub/article/photo/200x150/'.$elem->image->image_filename.'" alt="">','/shop/'.$this->shop->slug.'/article/'.$elem->slug.'.html',array('class' => 'img_cont fl_l')); ?>
                <div>
                    <span class="path"><?php echo date('j',strtotime($elem->updated_at)); ?> <?php echo $this->months[intval(date('n',strtotime($elem->updated_at)))][1]; ?></span>
                    <h6><?php echo CHtml::link(FunctionHelper::upperFirst($elem->title),'/shop/'.$this->shop->slug.'/article/'.$elem->slug.'.html'); ?></h6>
                    <p class="details"><?php echo $elem->short_description; ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>