<?php if($articles): ?>
    <h5>Лучшее за неделю</h5>
        <ul class="week_best_list">
            <?php foreach($articles as $elem): ?>
                <li>
                    <span class="category_name">
                        <?php echo CHtml::link(FunctionHelper::upperFirst($elem->subcategory->title), '/news/subcategory/'.$elem->subcategory->slug.'.html'); ?>
                    </span>
                    <?php echo CHtml::link(FunctionHelper::upperFirst($elem->title), $elem->getUrlArticle(), array('class' => 'description')); ?>
                </li>
            <?php endforeach; ?>
        </ul>
<?php endif;  ?>