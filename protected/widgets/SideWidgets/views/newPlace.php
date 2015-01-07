<div class="aside_item">
    <h4><?php echo FunctionHelper::upperFirst(Yii::app()->static->get('index/new_place_title')); ?></h4>
    <div class="aside_slider">
        <?php foreach ($newClubs as $place): ?>
            <div class="aside_slider_item">
                <a class="img_cont" href="<?php echo $place->getUrlAbout(); ?>"><?php echo CHtml::image($place->getNewPlaceImagePath(), $place->club); ?></a>
                <a class="place_name" href="<?php echo $place->getUrlAbout(); ?>"><q><?php echo CHtml::encode($place->club); ?></q></a>
                <?php if ($place->addressesRel): ?>
                    <p class="address"><?php echo CHtml::encode($place->addressesRel[0]->address); ?></p>
                <?php endif; ?>
                <p><?php echo $place->short_description; ?></p>
            </div>
        <?php endforeach; ?>
        <?php foreach ($newShops as $place): ?>
            <div class="aside_slider_item">
                <a class="img_cont" href="<?php echo $place->getUrlAbout(); ?>"><?php echo CHtml::image($place->getNewPlaceImagePath(), $place->title); ?></a>
                <a class="place_name" href="<?php echo $place->getUrlAbout(); ?>"><q><?php echo CHtml::encode($place->title); ?></q></a>
                <?php if ($place->addressesRel): ?>
                    <p class="address"><?php echo CHtml::encode($place->addressesRel[0]->address); ?></p>
                <?php endif; ?>
                <p><?php echo $place->short_description; ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</div>