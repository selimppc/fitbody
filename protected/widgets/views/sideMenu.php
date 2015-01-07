<ul class="main_nav_list">
    <?php foreach($cat as $elem): ?>
        <?php if($elem->id == $category): ?>
            <li class="active">
                <a href="/news/category/<?php echo $elem->slug; ?>.html"><span><?php echo mb_convert_case($elem->category, MB_CASE_TITLE, 'UTF-8'); ?></span></a>
                <ul class="sub_main_nav_list" style="display: block">
                    <?php foreach($elem->subcategories as $inner): ?>
                        <li <?php echo (($inner->id == $subcategory) ? 'class="active"' : ''); ?>>
                            <a href="/news/subcategory/<?php echo $inner->slug; ?>.html"><span><?php echo mb_convert_case($inner->title, MB_CASE_TITLE, 'UTF-8'); ?></span></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php else: ?>
            <li>
                <a href="/news/category/<?php echo $elem->slug; ?>.html"><span><?php echo mb_convert_case($elem->category, MB_CASE_TITLE, 'UTF-8'); ?></span></a>
            </li>
        <?php endif; ?>
    <?php endforeach; ?>
</ul>