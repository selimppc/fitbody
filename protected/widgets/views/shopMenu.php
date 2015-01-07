<ul class="main_nav_list">
    <?php foreach($category as $elem): ?>
        <li <?php echo ($elem->slug == $active ? 'class="active"' : ""); ?>>
            <?php echo CHtml::link(FunctionHelper::upperFirst($elem->title), array('shop/list/'.$elem->slug));?>
        </li>
    <?php endforeach; ?>
</ul>