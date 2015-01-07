<ul class="main_nav_list">
    <?php foreach($category as $elem): ?>
    <li <?php echo ($elem->slug == $active ? 'class="active"' : ""); ?>>
        <?php echo CHtml::link($elem->destination, array('club/list/'.$elem->slug));?>
    </li>
    <?php endforeach; ?>
</ul>