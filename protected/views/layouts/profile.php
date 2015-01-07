<?php $this->beginContent('//layouts/main'); ?>
    <aside class="fl_r">
        <?php if ($this->beginCache(__FILE__.__LINE__, array('duration' => 3600, 'dependency'=> new CDbCacheDependency('SELECT MAX(created_at) FROM user_activity LIMIT 1')))): ?>
            <?php $this->widget('application.widgets.SideWidgets.ActivitiesWidget'); ?>
        <?php $this->endCache(); endif; ?>
        <?php
            $dependency = new CChainedCacheDependency(array(
                //new CTagCacheDependency(Coach::COACH_CACHE_TAG), // coaches
                new CTagCacheDependency(Club::CLUB_CACHE_TAG), // newPlace
                new CDbCacheDependency('SELECT MAX(updated_at) FROM banner LIMIT 1'), // banners
        ));
        if ($this->beginCache(__FILE__.__LINE__, array('duration' => 3600, 'dependency'=> $dependency))): ?>
            <?php $this->widget('application.widgets.SidebarWidget', array(
                'organizer' => true,
                'newPlace' => true,
                'upperRightBanner' => true,
                'bottomRightBanner' => true,
                'coaches'  => false
            )); ?>
        <?php $this->endCache(); endif; ?>
    </aside>
    <div class="main profile clearfix">
        <div class="profile_inner">
            <div class="img_cont fl_l">
                <?php if($this->profile->user->image): ?>
                    <img width="150" height="150" id="main_user_photo" src="/pub/user_photo/150x150/<?php echo $this->profile->user->image->image_filename; ?>" alt=""/>
                <?php else: ?>
                    <img width="150" height="150" id="main_user_photo" src="/images/progress_template.png" alt=""/>
                <?php endif; ?>
            </div>
            <div class="profile_inner_info">
                <?php if($this->profile->user->first_name && $this->profile->user->last_name){ ?>
                    <h1><?php echo FunctionHelper::upperFirst($this->profile->user->first_name).' ('.$this->profile->user->nickname.') '.FunctionHelper::upperFirst($this->profile->user->last_name); ?></h1>
                <?php }else{ ?>
                    <h1><?php echo $this->profile->user->nickname; ?></h1>
                <?php } ?>

                <span class="person_basic">
                    <?php if($this->profile->user->country) echo Yii::app()->image->getImageTag($this->profile->user->country->image_id,16,11,array())?>
                    <?php if($this->gender && $this->age){
                        echo $this->gender.', '.$this->age;
                    } elseif($this->gender) {
                        echo $this->gender;
                    } elseif($this->age) {
                        echo $this->age;
                    }?>
                </span>

                <dl class="person_description">
                    <dt class="layout_height" <?php if(!$this->profile->height) echo 'style="display:none"'; ?>>Рост:</dt>
                    <dd class="layout_height" <?php if(!$this->profile->height) echo 'style="display:none"'; ?>><?php echo $this->profile->height; ?> см</dd>

                    <dt class="layout_weight"  <?php if(!$this->profile->weight) echo 'style="display:none"'; ?>>Вес:</dt>
                    <dd class="layout_weight"  <?php if(!$this->profile->weight) echo 'style="display:none"'; ?>><?php echo $this->profile->weight; ?> кг</dd>

                    <dt class="layout_fat" <?php if(!$this->profile->fat) echo 'style="display:none"'; ?>>Жир:</dt>
                    <dd class="layout_fat" <?php if(!$this->profile->fat) echo 'style="display:none"'; ?>><?php echo $this->profile->fat; ?>%</dd>
                </dl>
                <hr/>
                <dl class="profile_main_info">
                    <dt class="layout_biceps" <?php if(!$this->profile->biceps) echo 'style="display:none"'; ?>><span>Бицeпс</span></dt>
                    <dd class="clearfix layout_biceps" <?php if(!$this->profile->biceps) echo 'style="display:none"'; ?>><span class="size"><?php echo $this->profile->biceps; ?> см</span></dd>

                    <dt class="layout_forearm" <?php if(!$this->profile->forearm) echo 'style="display:none"'; ?>><span>Прeдплeчьe</span></dt>
                    <dd class="clearfix layout_forearm" <?php if(!$this->profile->forearm) echo 'style="display:none"'; ?>><span class="size"><?php echo $this->profile->forearm; ?> см</span></dd>

                    <dt class="layout_wrist" <?php if(!$this->profile->wrist) echo 'style="display:none"'; ?>><span>Запястьe</span></dt>
                    <dd class="clearfix layout_wrist" <?php if(!$this->profile->wrist) echo 'style="display:none"'; ?>><span class="size"><?php echo $this->profile->wrist; ?> см</span></dd>
                </dl>

                <dl class="profile_main_info">
                    <dt class="layout_neck" <?php if(!$this->profile->neck) echo 'style="display:none"'; ?>><span>Шeя</span></dt>
                    <dd class="clearfix layout_neck" <?php if(!$this->profile->neck) echo 'style="display:none"'; ?>><span class="size"><?php echo $this->profile->neck; ?> см</span></dd>

                    <dt class="layout_chest" <?php if(!$this->profile->chest) echo 'style="display:none"'; ?>><span>Грудь</span></dt>
                    <dd class="clearfix layout_chest" <?php if(!$this->profile->chest) echo 'style="display:none"'; ?>><span class="size"><?php echo $this->profile->chest; ?> см</span></dd>

                    <dt class="layout_waist" <?php if(!$this->profile->waist) echo 'style="display:none"'; ?>><span>Талия</span></dt>
                    <dd class="clearfix layout_waist" <?php if(!$this->profile->waist) echo 'style="display:none"'; ?>><span class="size"><?php echo $this->profile->waist; ?> см</span></dd>
                </dl>

                <dl class="profile_main_info">
                    <dt class="layout_thigh" <?php if(!$this->profile->thigh) echo 'style="display:none"'; ?>><span>Бeдрo</span></dt>
                    <dd class="clearfix layout_thigh" <?php if(!$this->profile->thigh) echo 'style="display:none"'; ?>><span class="size"><?php echo $this->profile->thigh; ?> см</span></dd>

                    <dt class="layout_buttocks" <?php if(!$this->profile->buttocks) echo 'style="display:none"'; ?>><span>Ягoдицы</span></dt>
                    <dd class="clearfix layout_buttocks" <?php if(!$this->profile->buttocks) echo 'style="display:none"'; ?>><span class="size"><?php echo $this->profile->buttocks; ?> см</span></dd>

                    <dt class="layout_shin" <?php if(!$this->profile->shin) echo 'style="display:none"'; ?>><span>Гoлeнь</span></dt>
                    <dd class="clearfix layout_shin" <?php if(!$this->profile->shin) echo 'style="display:none"'; ?>><span class="size"><?php echo $this->profile->shin; ?> см</span></dd>
                </dl>


            </div>
        </div>
        <div class="profile_inner_nav">
            <?php $this->widget('application.widgets.ProfileTabsWidget',array(
                'active'        => $this->active,
                'profile_id'    => $this->profile->user->id,
                'show_profile'  => $this->owner || $this->profile->show_profile,
                'show_photo'    => $this->owner || $this->profile->show_photo,
                'show_progress' => $this->owner || $this->profile->show_progress,
                'show_program'  => $this->owner || $this->profile->show_program,
                'show_goals'    => $this->owner || $this->profile->show_goals,
            )); ?>
        </div>

        <?php echo $content; ?>
    </div>
<?php $this->endContent(); ?>