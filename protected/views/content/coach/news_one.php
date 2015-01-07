<div class="tab active one_news">
    <div class="breadcrumbs">
        <a href="<?php echo $this->coach->getUrlCoachAllNews(); ?>">Все новости тренера</a>
    </div>
    <h2><?php echo $newsOne->title; ?></h2>
    <?php echo $newsOne->news; ?>
    <?php $this->widget('application.widgets.SocialButtons'); ?>
</div>