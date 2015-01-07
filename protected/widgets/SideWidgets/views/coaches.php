<?php if($coaches): ?>
    <div class="aside_item">
        <h4>Тренеры</h4>
        <ul class="coach_list">
            <?php foreach($coaches as $coach): ?>
                <li>
                    <a class="img_cont fl_l" href="<?php echo $coach->getUrlCoachAllAbout(); ?>">
                        <div style="width:80px;height:80px;text-align: center;"><?php echo CHtml::image($coach->getImageThumbnailCoachesPath()); ?></div>
                    </a>
                    <a href="<?php echo $coach->getUrlCoachAllAbout(); ?>" class="coach_name"><?php echo CHtml::encode($coach->name); ?></a>
                    <?php if ($coach->categoriesMM): ?>
                        <p class="coach_post">Персональный тренер по
                            <?php foreach($coach->categoriesMM as $key => $category):
                                 echo (($key > 0) ? (isset($coach->categoriesMM[$key + 1]) ? ', ' : ' и ' ) : '') . mb_convert_case($category->dative, MB_CASE_LOWER, "UTF-8");
                            endforeach; ?>
                        </p>
                    <?php endif; ?>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>