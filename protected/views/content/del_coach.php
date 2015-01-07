<aside class="fl_r">
    <div class="aside_item banner_main">
        <div class="banner_main_info">
            <h5>Мой дневник</h5>
						<span>
							Онлайн органайзер для спортсменов с полезными функциями и удобным дизайном
						</span>
        </div>
        <a href="" class="color_btn_large">Бесплатная регистрация</a>
    </div>

    <div class="aside_item">
        <h4>Новое место</h4>
        <div class="aside_slider">
            <div class="aside_slider_item">
                <a class="img_cont" href="">
                    <img src="images/img_cont/banner2.jpg" alt=""/>
                </a>
                <a class="place_name" href=""><q>Твои секреты</q></a>
                <p class="address">г.Минск, ул. Притыцкого, 83</p>
                <p>
                    Фитнес-клуб премиум класса для тех, кто не привык ограничивать себя в заботе о собственном здоровье.
                </p>
            </div>

            <div class="aside_slider_item">
                <a class="img_cont" href="">
                    <img src="images/img_cont/banner2.jpg" alt=""/>
                </a>
                <a class="place_name" href=""><q>Твои секреты</q></a>
                <p class="address">г.Минск, ул. Притыцкого, 83</p>
                <p>
                    Фитнес-клуб премиум класса для тех, кто не привык ограничивать себя в заботе о собственном здоровье.
                </p>
            </div>
        </div>
    </div>

    <div class="aside_item banner">
        <a href=""><img src="images/img_cont/banner3.jpg" alt=""/></a>
    </div>
</aside>

<div class="main clearfix inner_page_single">
    <div class="trainer_inner">

        <?php
            $this->widget('application.widgets.Breadcrumbs', array(
                'links' => array(
                    'Персональные тренеры' => Yii::app()->createUrl('coaches/index'),
                    (($coach->categoryMain) ? $coach->categoryMain->category->title . ' тренеры' : '') => ($coach->categoryMain) ? Yii::app()->createUrl('coaches', array('category' => $coach->categoryMain->category->slug)) : ''
                )
            ));
        ?>

        <h1><?php echo $coach->name; ?></h1>
        <div class="trainer_inner_slider fl_l">
            <div class="owl-carousel">
                <div class="item"><a class="img_cont" href=""><img src="/images/img_cont/trainer_inner1.jpg" alt=""/></a></div>
                <div class="item"><a class="img_cont" href=""><img src="/images/img_cont/trainer_inner1.jpg" alt=""/></a></div>
                <div class="item"><a class="img_cont" href=""><img src="/images/img_cont/trainer_inner1.jpg" alt=""/></a></div>
                <div class="item"><a class="img_cont" href=""><img src="/images/img_cont/trainer_inner1.jpg" alt=""/></a></div>
                <div class="item"><a class="img_cont" href=""><img src="/images/img_cont/trainer_inner1.jpg" alt=""/></a></div>
            </div>
        </div>
        <div class="trainer_inner_info">
            <div class="address"><a href="">«Национальная школа красоты»</a></div>
            <div class="address"><a href="">«Адреналин Клуб»</a></div>
            <?php if (count($coach->phones)): ?>
                <div class="phone">
                    <?php foreach ($coach->phones as $phone): ?>
                        <span><?php echo $phone->phone; ?> </span>
                    <?php endforeach;?>
                </div>
            <?php endif; ?>
            <?php if ($coach->website): ?>
                <div class="site">
                    <a href="<?php echo $coach->website; ?>" target="_blank"><?php echo $coach->website; ?></a>
                </div>
            <?php endif; ?>
            <?php if ($coach->email): ?>
                <div class="email">
                    <a href="mailto:<?php echo $coach->email; ?>" target="_blank"><?php echo $coach->email; ?></a>
                </div>
            <?php endif; ?>
            <?php if ($coach->skype): ?>
                <div class="skype">
                    <a href="<?php echo $coach->skype; ?>" target="_blank"><?php echo $coach->skype; ?></a>
                </div>
            <?php endif; ?>
            <?php if ($coach->cost): ?>
                <div class="price_list">
                    <p>Стоимость:</p>
                    <?php echo $coach->cost; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <div class="trainer_inner_nav">
        <ul>
            <li class="active"><a href="" class="va_middle_out"><span class="va_middle_in">О тренере</span></a></li>
            <li class=""><a href="" class="va_middle_out"><span class="va_middle_in">Видео тренера</span></a></li>
            <li class=""><a href="" class="va_middle_out"><span class="va_middle_in">Новости</span></a></li>
            <li class=""><a href="" class="va_middle_out"><span class="va_middle_in">Отзывы (<span>12</span>) </span></a></li>
        </ul>
    </div>
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
</div>