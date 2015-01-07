<?php if(count($companyNews)): ?>
    <h5>Новости компаний</h5>
    <ul class="company_news">
        <?php foreach($companyNews as $elem) : ?>
            <?php if(isset($elem->club)): ?>
                <li>
                    <span class="company_name"><?php echo CHtml::link(FunctionHelper::upperFirst($elem->club->club), '/club/'.$elem->club->slug.'/about.html'); ?></span>
                    <?php echo CHtml::link(FunctionHelper::upperFirst($elem->title), '/club/'.$elem->club->slug.'/article/'.$elem->slug.'.html', array('class' => 'description')); ?>
                </li>
            <?php else: ?>
                <li>
                    <span class="company_name"><?php echo CHtml::link(FunctionHelper::upperFirst($elem->shop->title), '/shop/'.$elem->shop->slug.'/about.html'); ?></span>
                    <?php echo CHtml::link(FunctionHelper::upperFirst($elem->title), '/shop/'.$elem->shop->slug.'/article/'.$elem->slug.'.html', array('class' => 'description')); ?>
                </li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
<?php endif; ?>