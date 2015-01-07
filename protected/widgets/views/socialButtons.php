<!--vk-->
<?php if ($vkAppId): ?>
<script type="text/javascript">
    VK.init({apiId: <?php echo $vkAppId; ?>, onlyWidgets: true});
</script>
<?php endif; ?>
<!--end vk-->


<div class="social_btns">
    <div class="social_btns_inner">
        <ul>
            <?php if ($vkAppId): ?>
                <li class="widget-vk"><div id="vk_like"></div></li>
            <?php endif; ?>
            <?php if ($twitter): ?>
                <li class="widget-tw"><a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo Yii::app()->request->getBaseUrl(true) . Yii::app()->request->getUrl(); ?>" data-via="<?php echo $twitter; ?>" data-lang="en">Tweet</a></li>
            <?php endif; ?>
            <?php if ($facebookAppId): ?>
                <li class="widget-fb"><div class="fb-like" data-width="90" data-href="<?php echo Yii::app()->request->getBaseUrl(true) . Yii::app()->request->getUrl(); ?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="false"></div></li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<!--vk-->
<?php if ($vkAppId): ?>
    <script type="text/javascript">
        VK.Widgets.Like("vk_like", {type: "mini", height: 20});
    </script>
<?php endif; ?>
<!--end vk-->

<!--Facebook-->
<?php if ($facebookAppId): ?>
    <div id="fb-root"></div>
    <script>(function(d, s, id) {
            var js, fjs = d.getElementsByTagName(s)[0];
            if (d.getElementById(id)) return;
            js = d.createElement(s); js.id = id;
            js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=<?php echo $facebookAppId?>&version=v2.0";
            fjs.parentNode.insertBefore(js, fjs);
        }(document, 'script', 'facebook-jssdk'));</script>
<?php endif; ?>
<!--end Facebook-->

<!--twitter -->
<?php if ($twitter): ?>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
<?php endif; ?>
<!--end twitter -->

