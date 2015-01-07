<div class="tab active">
    <h2>О себе</h2>
    <?php if (count($coach->propertiesRel)): ?>
        <table class="trainer_description">
            <?php foreach ($coach->propertiesRel as $item): ?>
                <tr>
                    <td><?php echo $item->property->property; ?></td>
                    <td><?php echo $item->description; ?></td>
                </tr>
            <?php endforeach;?>
        </table>
    <?php endif; ?>
    <?php echo $coach->about; ?>
    <div class="social_btns">
        <div class="social_btns_inner">
            <ul>
                <li><a href=""><img src="images/fb_bg.png" alt=""></a></li>
                <li><a href=""><img src="images/vk_bg.png" alt=""></a></li>
                <li><a href=""><img src="images/twitter_bg.png" alt=""></a></li>
            </ul>
        </div>
    </div>
</div>