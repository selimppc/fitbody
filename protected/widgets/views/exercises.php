<div class="base_exercises">
    <?php echo ($active ? '<h1>' : '<h1>');?>
        База упражнений
        <a <?php if($active === 'with-weights') echo 'class="active"'; ?>href="/exercises/with-weights.html">с отягощениями</a>
        или
        <a <?php if($active === 'trx') echo 'class="active"'; ?>href="/exercises/trx.html">TRX</a>
    <?php echo ($active ? '</h2>' : '</h2>');?>
    <div class="base_exercises_box">
        <img src="/images/img_cont/base_exercises_img.jpg" alt=""/>
        <ul class="base_exercises_list left">
            <?php foreach($left as $key => $elem): ?>
                <li><a href="/exercises/<?php echo ($active === 'trx' ? 'trx' : 'with-weights');?>/<?php echo $elem->slug; ?>.html"><?php echo FunctionHelper::upperFirst($elem->muscle); ?></a></li>
            <?php endforeach; ?>
        </ul>
        <ul class="base_exercises_list right">
            <?php foreach($right as $key => $elem): ?>
                <li><a href="/exercises/<?php echo ($active === 'trx' ? 'trx' : 'with-weights');?>/<?php echo $elem->slug; ?>.html"><?php echo FunctionHelper::upperFirst($elem->muscle); ?></a></li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>
