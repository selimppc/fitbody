<?php if ($banner): ?>
    <div class="banner">
        <?php if ($banner->getBannerExtension() === 'swf'): ?>
            <embed wmode="opaque" width="<?php echo $banner->positionRel->width; ?>" height="<?php echo $banner->positionRel->height; ?>" src="<?php echo $banner->getBannerSourcePath(); ?>?clickTAG=<?php echo $banner->getBannerUrl(); ?>" type="application/x-shockwave-flash"></embed>
        <?php else: ?>
            <a target="_blank" data-url="<?php echo $banner->getBannerUrl(); ?>" title="<?php echo $banner->title; ?>" href="<?php echo $banner->getBannerUrl(); ?>"><?php echo CHtml::image($banner->getBannerSourcePath(), $banner->title, array('width' => $banner->positionRel->width, 'height' => $banner->positionRel->height)); ?></a>
        <?php endif; ?>
    </div>
<?php endif; ?>