<?php if($organizer): ?>
    <?php $this->widget('application.widgets.SideWidgets.OrganizerWidget'); ?>
<?php endif; ?>

<?php if($newPlace): ?>
    <?php $this->widget('application.widgets.SideWidgets.NewPlaceWidget'); ?>
<?php endif; ?>

<?php if($coaches): ?>
    <?php $this->widget('application.widgets.SideWidgets.CoachesWidget'); ?>
<?php endif; ?>

<?php if($upperRightBanner): ?>
    <?php $this->widget('application.widgets.SideWidgets.AsideBannerWidget', array('positionId' => BannerPosition::UPPER_RIGHT)); ?>
<?php endif; ?>

<?php if($bottomRightBanner): ?>
    <?php $this->widget('application.widgets.SideWidgets.AsideBannerWidget', array('positionId' => BannerPosition::BOTTOM_RIGHT)); ?>
<?php endif; ?>