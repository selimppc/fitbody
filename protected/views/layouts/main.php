<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html>
	<head>
        <meta charset="utf-8" />
		<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
		<meta name="format-detection" content="telephone=no">
		<title><?php echo CHtml::encode($this->pageTitle); ?></title>

		<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

		<!--[if IE 8]><link media="screen,projection" type="text/css" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie8.css"/><![endif]-->
		<!--[if IE 9]><link media="screen,projection" type="text/css" rel="stylesheet" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie9.css"/><![endif]-->

        <script>
            (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
                (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
                m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
            })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

            ga('create', 'UA-55164157-1', 'auto');
            ga('send', 'pageview');

        </script>
	</head>
	<body data-csrf-name="<?php echo Yii::app()->request->csrfTokenName; ?>" data-csrf="<?php echo Yii::app()->request->csrfToken; ?>" data-login="<?php echo (!$this->isGuest) ? 'login' : 'not-login'; ?>">
		<div id="wrapper"><!--open wrapper-->
            <?php if ($this->isGuest): ?>
                <?php $this->widget('application.widgets.PopupLoginWidget'); ?>
            <?php endif; ?>
			<?php $this->widget('application.widgets.HeaderWidget'); ?>
			<div id="content" class="clearfix"><!--open content-->
				<div class="box">
					<?php $this->widget('application.widgets.MenuWidget'); ?>
					<div class="clear">&nbsp;</div>

					<?php echo $content; ?>

					<div class="clear">&nbsp;</div>
				</div>
			</div><!--close content-->
		</div><!--close wrapper-->
		<?php $this->widget('application.widgets.FooterWidget'); ?>
	</body>
</html>