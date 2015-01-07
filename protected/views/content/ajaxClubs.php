<?php foreach($clubs as $elem): ?>
    <li class="">
        <div class="item_info">
            <div class="location_i">
                <span>
                    <?php $dist = intval($elem->addressesRel[0]->distance);
                        echo $dist;
                    ?>
                </span>
                <?php
                    switch($dist%10){
                        case 1:
                            echo 'метр';
                            break;
                        case 2:
                        case 3:
                        case 4:
                            echo 'метра';
                            break;
                        default:
                            echo 'метров';
                    }
                ?>
            </div>
            <h6><?php echo CHtml::link($elem->club,'/club/'.$elem->slug.'/about.html'); ?></h6>
            <div class="address"><?php echo 'г.'.FunctionHelper::upperFirst($elem->addressesRel[0]->city->city).', '.$elem->addressesRel[0]->address; ?></div>
            <div class="work_time">
                <dl>
                    <?php foreach($elem->addressesRel[0]->worktimes as $innerKey => $innerElem): ?>
                        <dt><?php echo $days[$innerElem->from_day].($innerElem->from_day != $innerElem->to_day ? ' - '.$days[$innerElem->to_day] : '').': '; ?></dt>
                        <dd><?php echo mb_substr($innerElem->from_time,0,5).' - '.mb_substr($innerElem->to_time,0,5); ?><?php echo (isset($elem->addressesRel[0]->worktimes[$innerKey+1]) ? "," : "");?></dd>
                    <?php endforeach; ?>
                </dl>
            </div>
            <div class="phone">
                <?php foreach($elem->addressesRel[0]->phones as $innerKey => $innerElem): ?>
                    <span><?php echo $innerElem->phone; ?></span>
                <?php endforeach; ?>
            </div>
        </div>
    </li>
<?php endforeach; ?>