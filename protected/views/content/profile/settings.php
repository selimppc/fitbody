<div class="tab active">
<h2>Настройки профиля</h2>
<div class="settings_block">
<div class="settings_nav">
    <div class="delete_profile fl_r"><a href="#sett_delete" id="delete_tab">Удалить профайл</a></div>
    <ul>
        <li class="active"><a href="#sett_main">Основные данные</a></li>
        <li><a href="#sett_muscle_volume">Объемы мышц</a></li>
        <li><a href="#sett_privacy">Приватность</a></li>
        <li><a href="#sett_rss">Подписка</a></li>
        <li><a href="#sett_pass">Пароль</a></li>
    </ul>
</div>

<div class="settings_tab active" id="sett_main">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'main_info',
        'htmlOptions'=>array()
    )); ?>
        <div class="sett_block">
            <div class="img_edit fl_r">
                <?php if($this->profile->user->image_id): ?>
                    <img src="/pub/user_photo/146x146/<?php echo $this->profile->user->image->image_filename; ?>" alt="" id="user_photo"/>
                <?php endif; ?>
                <input id="image_value" type="hidden" value="<?php echo $this->profile->user->image_id; ?>"/>
                <div class="edit_functions">
                    <a href="" class="delete_link fl_r" id="delete_image"><img src="/images/delete_i.png" alt=""/></a>
                    <div class="upload_link">
                        <span>Загрузить</span>
                        <?php echo CHtml::activeFileField($uploadModel, 'file', array('id' => 'fileupload','data-url' => "/profile/settings/upload")); ?>
                    </div>
                </div>
            </div>
            <div class="row_inline" id="first_name">
                <label>
                    Имя
                    <input type="text" placeholder="Введите имя" value="<?php echo $this->profile->user->first_name; ?>"/>
                </label>
            </div>
            <div class="row_inline" id="last_name">
                <label>
                    Фамилия
                    <input type="text" placeholder="Введите фамилию" value="<?php echo $this->profile->user->last_name; ?>"/>
                </label>
            </div>
            <div class="row_inline" id="email">
                <label>
                    Электронная почта
                    <input type="text" placeholder="Введите электронную почту" value="<?php echo $this->profile->user->email; ?>"/>
                </label>
            </div>
            <div class="row_inline" id="nickname">
                <label>
                    Никнейм
                    <input type="text" placeholder="Введите никнейм" value="<?php echo $this->profile->user->nickname; ?>"/>
                </label>
            </div>
        </div>

        <div class="sett_block input_sm">
            <div class="row_inline gender ov" id="gender">
                <label>
                    <span class="gender_top">Пол</span>
                    <a href="" class="gender_btn<?php echo ($this->profile->user->gender == 'male') ? ' active' : ''; ?>" gender="male">Муж</a>
                    <a href="" class="gender_btn<?php echo ($this->profile->user->gender == 'female') ? ' active' : ''; ?>" gender="female">Жен</a>
                </label>
            </div>
            <div class="row_inline"  id="birthday">
                <label>
                    Дата рождения
                    <input type="text" class="datepicker" value="<?php if($this->profile->user->birthday != '0000-00-00') echo date('d.m.Y',strtotime($this->profile->user->birthday)); ?>"/>
                </label>
            </div>
            <div class="row_inline"  id="height">
                <label>
                    Рост <span class="unit">(см)</span>
                    <input type="text" value="<?php if($this->profile->height) echo $this->profile->height; ?>"/>
                </label>
            </div>
            <div class="row_inline" id="weight">
                <label>
                    Вес <span class="unit">(кг)</span>
                    <input type="text" value="<?php if($this->profile->weight) echo $this->profile->weight; ?>"/>
                </label>
            </div>
            <div class="row_inline" id="fat">
                <label>
                    %&nbsp;Жира
                    <input type="text" value="<?php if($this->profile->fat) echo $this->profile->fat; ?>"/>
                </label>
            </div>
        </div>

        <div class="sett_block">
            <div class="row_inline" id="country_id">
                <label>
                    Страна
                    <?php echo $form->dropDownList($this->profile->user,'country_id', CHtml::listData($countries, 'id', 'title'), array('prompt'=>' - Страна - ')); ?>
                </label>
            </div>
            <div class="row_inline" id="city">
                <label>
                    Город
                    <input type="text" placeholder="Введите название города" value="<?php if($this->profile->user->city) echo $this->profile->user->city; ?>"/>
                </label>
            </div>
        </div>

        <div class="row" id="main_errors" style="display: none">
            <ul class="error_cause">
            </ul>
        </div>

        <div class="row" id="main_success" style="display: none">
            <div class="success">— Данные успешно сохранены</div>
        </div>

        <div class="bordered m_0">
            <div class="row_inline"><input type="submit" value="Сохранить изменения" class="h_34" id="submit_main_info"/></div>
            <div class="row_inline"><a href="" class="cancel_link" id="revert_main_info">Отменить</a></div>
        </div>
    <?php $this->endWidget(); ?>
</div>
<div class="settings_tab" id="sett_muscle_volume">
    <form action="#">
        <div class="sett_block input_sm input_inline justified justified_3">
            <div class="row_inline" id="biceps">
                <label>
                    <span class="label">Бицепс</span>
                    <input type="text" value="<?php if($this->profile->biceps) echo $this->profile->biceps; ?>"/> <span class="unit">см</span>
                </label>
            </div>
            <div class="row_inline" id="neck">
                <label>
                    <span class="label">Шея</span>
                    <input type="text" value="<?php if($this->profile->neck) echo $this->profile->neck; ?>"/> <span class="unit">см</span>
                </label>
            </div>
            <div class="row_inline" id="thigh">
                <label>
                    <span class="label">Бедро</span>
                    <input type="text" value="<?php if($this->profile->thigh) echo $this->profile->thigh; ?>"/> <span class="unit">см</span>
                </label>
            </div>
            <div class="row_inline" id="forearm">
                <label>
                    <span class="label">Предплечье</span>
                    <input type="text" value="<?php if($this->profile->forearm) echo $this->profile->forearm; ?>"/> <span class="unit">см</span>
                </label>
            </div>
            <div class="row_inline" id="chest">
                <label>
                    <span class="label">Грудь</span>
                    <input type="text" value="<?php if($this->profile->chest) echo $this->profile->chest; ?>"/> <span class="unit">см</span>
                </label>
            </div>
            <div class="row_inline" id="buttocks">
                <label>
                    <span class="label">Ягодицы</span>
                    <input type="text" value="<?php if($this->profile->buttocks) echo $this->profile->buttocks; ?>"/> <span class="unit">см</span>
                </label>
            </div>
            <div class="row_inline" id="wrist">
                <label>
                    <span class="label">Запястье</span>
                    <input type="text" value="<?php if($this->profile->wrist) echo $this->profile->wrist; ?>"/> <span class="unit">см</span>
                </label>
            </div>
            <div class="row_inline" id="waist">
                <label>
                    <span class="label">Талия</span>
                    <input type="text" value="<?php if($this->profile->waist) echo $this->profile->waist; ?>"/> <span class="unit">см</span>
                </label>
            </div>
            <div class="row_inline"  id="shin">
                <label>
                    <span class="label">Голень</span>
                    <input type="text" value="<?php if($this->profile->shin) echo $this->profile->shin; ?>"/> <span class="unit">см</span>
                </label>
            </div>
        </div>

        <div class="row" id="muscles_errors" style="display: none">
            <ul class="error_cause">
            </ul>
        </div>

        <div class="row" id="muscles_success" style="display: none">
            <div class="success">— Данные успешно сохранены</div>
        </div>

        <div class="bordered m_0">
            <div class="row_inline"><input type="submit" value="Сохранить изменения" class="h_34" id="submit_muscles_info"/></div>
            <div class="row_inline"><a href="" class="cancel_link" id="revert_muscles_info">Отменить</a></div>
        </div>
    </form>
</div>
<div class="settings_tab" id="sett_privacy">
    <form action="#">
        <div class="row">
            <label>
                <input type="radio" name="radio1" show-profile="false" <?php if(!$this->profile->show_profile) echo 'checked="checked"'; ?>/>
                закрыть профиль полностью
            </label>
        </div>
        <div class="row">
            <label>
                <input type="radio"  name="radio1" show-profile="true" class="choose_privacy" <?php if($this->profile->show_profile) echo 'checked="checked"'; ?>/>
                настроить приватность профиля
            </label>
            <div class="sub_checkbox <?php if($this->profile->show_profile) echo 'active'; ?>">
                <div class="row" id="show_photo">
                    <label>
                        <input type="checkbox" name="" <?php if(!$this->profile->show_photo) echo 'checked'; ?>/>
                        спрятать фотографии
                    </label>
                </div>
                <div class="row" id="show_progress">
                    <label>
                        <input type="checkbox" name="" <?php if(!$this->profile->show_progress) echo 'checked'; ?>/>
                        спрятать прогресс
                    </label>
                </div>
                <div class="row" id="show_program">
                    <label>
                        <input type="checkbox" name="" <?php if(!$this->profile->show_program) echo 'checked'; ?>/>
                        спрятать программу тренировок
                    </label>
                </div>
                <div class="row" id="show_goals">
                    <label>
                        <input type="checkbox" name="" <?php if(!$this->profile->show_goals) echo 'checked'; ?>/>
                        спрятать поставленные цели
                    </label>
                </div>
            </div>
        </div>

        <div class="row" id="privacy_errors" style="display: none">
            <ul class="error_cause">
            </ul>
        </div>

        <div class="row" id="privacy_success" style="display: none">
            <div class="success">— Данные успешно сохранены</div>
        </div>

        <div class="bordered m_0">
            <div class="row_inline"><input type="submit" value="Сохранить изменения" class="h_34" id="submit_privacy_info"/></div>
            <div class="row_inline"><a href="" class="cancel_link" id="revert_privacy_info">Отменить</a></div>
        </div>
    </form>
</div>
<div class="settings_tab" id="sett_rss">
    <form action="#">
        <div class="row">
            <div class="row" id="rss_article">
                <label>
                    <input type="checkbox" name="" <?php if($this->profile->rss_article) echo 'checked'; ?>/>
                    получать новые статьи
                </label>
            </div>
            <div class="row" id="rss_exercise">
                <label>
                    <input type="checkbox" name="" <?php if($this->profile->rss_exercise) echo 'checked'; ?>/>
                    получать новые упражнения
                </label>
            </div>
            <div class="row" id="rss_company_news">
                <label>
                    <input type="checkbox" name="" <?php if($this->profile->rss_company_news) echo 'checked'; ?>/>
                    получать новости компаний
                </label>
            </div>
        </div>

        <div class="row" id="rss_errors" style="display: none">
            <ul class="error_cause">
            </ul>
        </div>

        <div class="row" id="rss_success" style="display: none">
            <div class="success">— Данные успешно сохранены</div>
        </div>

        <div class="bordered m_0">
            <div class="row_inline"><input type="submit" value="Сохранить изменения" class="h_34" id="submit_rss_info"/></div>
            <div class="row_inline"><a href="" class="cancel_link" id="revert_rss_info">Отменить</a></div>
        </div>
    </form>
</div>
<div class="settings_tab" id="sett_pass">
    <form action="#">
        <div class="sett_block">
            <div class="row_inline" id="pass_main">
                <label>
                    Новый пароль
                    <input type="password" placeholder="Введите пароль"/>
                </label>
            </div>
            <div class="row_inline" id="pass_retype">
                <label>
                    Повторите пароль
                    <input type="password" placeholder="Введите пароль повторно"/>
                </label>
            </div>
        </div>

        <div class="row" id="pass_errors" style="display: none">
            <ul class="error_cause">
            </ul>
        </div>

        <div class="row" id="pass_success" style="display: none">
            <div class="success">— Данные успешно сохранены</div>
        </div>

        <div class="bordered m_0">
            <div class="row_inline"><input type="submit" value="Сохранить изменения" class="h_34" id="submit_pass_info"/></div>
            <div class="row_inline"><a href="" class="cancel_link" id="revert_pass_info">Отменить</a></div>
        </div>
    </form>
</div>
<div class="settings_tab" id="sett_delete">
    <?php $form = $this->beginWidget('CActiveForm', array(
        'id' => 'deleteProfileForm',
        'htmlOptions'=>array('enctype'=>'multipart/form-data', 'data-id' => ''),
    )); ?>
    <div class="sett_block">
        <div class="row_inline" id="pass_main">
            <label>
                Вы уверены, что хотите удалить профиль?
                <input type="hidden" name="DeleteProfile[user_id]" value="<?php echo $this->profile->user->id; ?>"/>
            </label>
        </div>
    </div>
    <div class="row_inline"><input type="submit" value="Удалить" class="h_34" id="delete_profile"/></div>
    <?php $this->endWidget(); ?>
</div>
</div>
</div>