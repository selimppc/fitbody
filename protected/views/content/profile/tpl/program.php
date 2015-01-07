<tr>
    <th>День недели/дата</th>
    <th>Упражнение</th>
</tr>

<?php if($program): ?>
<?php foreach($days as $val): ?>
    <?php $elem = $program->$val['en']; ?>
    <?php if($elem['show']): ?>
        <tr class="data-row" data-date="<?php echo $elem['date']; ?>">
            <td>
                <span class="day"><?php echo FunctionHelper::upperFirst($val['ru']); ?></span>
                <span class="date"><?php echo date('d.m.Y',strtotime($elem['date'])); ?></span>
            </td>
            <td>
                <table>
                    <?php foreach($elem['exercises'] as $k => $exercise): ?>
                        <tr>
                            <td><?php echo ($k+1).'. '; ?><?php echo CHtml::link(FunctionHelper::upperFirst($exercise->exercise->title),'/exercise/'.$exercise->exercise->id.'.html'); ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if($elem['notes']): ?>
                        <tr>
                            <td class="day_note">
                                <div class="notice">
                                    <dl>
                                        <?php if($elem['notes']->meal): ?>
                                            <dt>Питание:</dt>
                                            <dd class="data-meal"><?php echo $elem['notes']->meal; ?></dd>
                                        <?php endif; ?>
                                        <?php if($elem['notes']->pharmacology): ?>
                                            <dt>Фармакология:</dt>
                                            <dd class="data-pharmacology"><?php echo $elem['notes']->pharmacology; ?></dd>
                                        <?php endif; ?>
                                        <?php if($elem['notes']->note): ?>
                                            <dt>Общие заметки:</dt>
                                                <dd class="data-note"><?php echo $elem['notes']->note; ?></dd>
                                        <?php endif; ?>
                                    </dl>
                                </div>
                                <div class="table_edit">
                                    <?php if($this->owner): ?>
                                        <a class="edit_note" href="">Редактировать</a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php if($this->owner): ?>
                            <tr>
                                <td class="day_note"><a href="" class="add_notice">+ Добавить заметки</a></td>
                            </tr>
                        <?php endif; ?>
                    <?php endif; ?>
                </table>
            </td>
        </tr>
    <?php endif; ?>
<?php endforeach; ?>
<?php endif; ?>