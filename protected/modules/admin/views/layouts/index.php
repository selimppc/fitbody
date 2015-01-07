<!DOCTYPE html>
<html lang="en" class="login_page">
	<head>
	    <meta charset="utf-8">
		<script src="/js/ajax-connector.js"></script>
		<script src="/js/observer.js"></script>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
	    <title><?php echo CHtml::encode($this->pageTitle); ?></title>
	    <!--[if lte IE 8]>
		<link rel="stylesheet" href="<?php echo $this->assetUrl;?>/css/ie.css" />
	    <![endif]-->
	    <!--[if lt IE 9]>
			<script src="<?php echo $this->assetUrl;?>/js/ie/html5.js"></script>
			<script src="<?php echo $this->assetUrl;?>/js/ie/respond.min.js"></script>
			<script src="<?php echo $this->assetUrl;?>/lib/flot/excanvas.min.js"></script>
	    <![endif]-->
	    <script type="text/javascript">
	        document.documentElement.className += 'js';
	    </script>
	</head>
	<body>
		<a href="javascript:void(0)" class="sidebar_switch ttip_r on_switch">Sidebar switch</a>
		<div id="maincontainer" class="clearfix">
			<header>
				<div class="navbar navbar-fixed-top">
				    <div class="navbar-inner">
				        <div class="container-fluid">
				            <a class="brand" href="http://<?php echo Yii::app()->request->getServerName(); ?>"><i class="icon-home icon-white"></i> <?php echo Yii::app()->name;?></a>
				            <ul class="nav user_menu pull-right">
					            <?php $this->widget($this->module->navRightMenuClass, $this->module->navRightMenuParams); ?>
				            </ul>
					        <!-- Nav Menu Widget -->
				            <div class="nav-collapse"><nav><?php $this->widget($this->module->navMenuClass, $this->module->navMenuParams);?></nav></div>
				        </div>
				    </div>
				</div>
			</header>
		    <div id="contentwrapper">
		        <div class="main_content">
			        <?php
			            foreach ($this->module->contentTopWidgets as $widget) {
				            $this->widget($widget['name'], array_key_exists('params', $widget) ? $widget['params'] : array());
			            }
			        ?>
			        <?php echo $content; ?>
			    </div>
		    </div>
		    <div class="sidebar">
		        <div class="antiScroll">
		            <div class="antiscroll-inner">
		                <div class="antiscroll-content">
		                    <div class="sidebar_inner">
			                    <?php
			                        foreach ($this->module->sideBarWidgets as $widget)
				                        $this->widget($widget['name'], array_key_exists('params', $widget) ? $widget['params'] : array());
			                    ?>
		                    </div>
		                </div>
					</div>
				</div>
			</div>
		</div>
	</body>
</html>
