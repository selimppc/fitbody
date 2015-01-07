<div class="tab active trainer">
    <h2>Тренеры клуба</h2>
    <ul class="main_content_list">
        <?php foreach($coaches as $elem): ?>
            <li>
                <a class="img_cont fl_l" href="/coach/<?php echo $elem->coachRel->slug; ?>.html"><?php echo Yii::app()->image->getImageTag($elem->coachRel->image_id,200,149); ?></a>
                <div>
                    <h6><?php echo CHtml::link($elem->coachRel->name,'/coach/'.$elem->coachRel->slug.'.html'); ?></h6>
                    <p class="details"><?php echo $elem->coachRel->short_description; ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>