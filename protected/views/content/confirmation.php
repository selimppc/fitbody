<aside class="fl_r">
    <?php
    $dependency = new CChainedCacheDependency(array(
        //new CTagCacheDependency(Coach::COACH_CACHE_TAG), // coaches
        new CTagCacheDependency(Club::CLUB_CACHE_TAG), // newPlace
        new CDbCacheDependency('SELECT MAX(updated_at) FROM banner LIMIT 1'), // banners
    ));
    if ($this->beginCache(__FILE__.__LINE__, array('duration' => 3600, 'dependency'=> $dependency))): ?>
        <?php $this->widget('application.widgets.SidebarWidget',array(
            'organizer' => true,
            'newPlace' => true,
            'upperRightBanner' => true,
            'bottomRightBanner' => true,
            'coaches'  => false,
        )); ?>
    <?php $this->endCache(); endif; ?>
</aside>

<div class="main inner_page_nosplit clearfix">
    <div class="top_with_bg">
        <h1>Ваш аккаунт успешно активирован</h1>
        <p>Перейти на <a href="/">главную страницу</a> сайта</p>
    </div>

    <h2>Преимущества регистрации</h2>
    <ul class="main_content_list no_link">
        <li>
					    <span class="img_cont fl_l">
						    <img src="/images/img_cont/register1.jpg" alt=""/>
					    </span>
            <div>
                <h6>Дневник личных достижений</h6>
                <p class="details">
                    Вы всегда сможете отследить свой прогресс! Насколько сильнее вы стали, начав тренировки? Насколько сантиметров уменьшились в объемах? Вы прогрессируете или стоите на месте? Все это будет отражено в дневнике ваших личных достижений!
                </p>
            </div>
        </li>
        <li>
					    <span class="img_cont fl_l">
						    <img src="/images/img_cont/register2.jpg" alt=""/>
					    </span>
            <div>
                <h6>Ваш персональный календарь тренировок</h6>
                <p class="details">
                    Теперь вы ничего не забудете и сможете выполнять рекомендованную профессионалами программу тренировок как следует. Весь график в удобном формате – стоит только открыть свой персональный календарь тренировок!
                </p>
            </div>
        </li>
        <li>
					    <span class="img_cont fl_l">
						    <img src="/images/img_cont/register3.jpg" alt=""/>
					    </span>
            <div>
                <h6>Дневник личных достижений</h6>
                <p class="details">
                    Мотивация к прогрессу – важная часть тренировочного процесса. Вы должны визуализировать тело, которое мечтаете выковать из себя, это поддержит вас в трудные минуты и не даст свернуть с пути. Заглядывайте в этот раздел, как только почувствовали в себе желание увильнуть от тренировки!
                </p>
            </div>
        </li>
        <li>
					    <span class="img_cont fl_l">
						    <img src="/images/img_cont/register4.jpg" alt=""/>
					    </span>
            <div>
                <h6>Рекомендуемое количество углеводов</h6>
                <p class="details">
                    Углево́ды (сахара́, сахариды) — органические вещества, содержащие карбонильную группу и несколько гидроксильных групп. Название класса соединений происходит от слов «гидраты углерода», оно было впервые предложено К. Шмидтом в 1844 году. Появление такого названия связано с тем, что первые из известных науке углеводов описывались брутто-формулой Cx(H2O)y, формально являясь соединениями углерода и воды.
                </p>
            </div>
        </li>
    </ul>
</div>