<?php if ($coaches): ?>
    <div class="aside_vip">
        <h5>Рекомендуем</h5>
        <ul>
            <?php foreach ($coaches as $coach): ?>
                <li>

                    <a href="<?php echo $coach->getUrlCoachAllAbout(); ?>" class="img_cont" style="text-align: center;">
                        <?php echo CHtml::image($coach->getImageCoachesPath(), $coach->name); ?>
                    </a>

                    <?php echo CHtml::link($coach->name, $coach->getUrlCoachAllAbout(), array('class' => 'name'))?>
                    <p><?php echo $coach->short_description; ?></p>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>