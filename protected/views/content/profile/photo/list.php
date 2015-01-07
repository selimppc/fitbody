<div class="tab active">
    <h2>Фотографии</h2>
    <div class="photo_block">
        <ul>
            <?php if($this->owner): ?>
                <?php $form=$this->beginWidget('CActiveForm', array(
                    'id' => 'photoForm',
                    'htmlOptions'=>array('enctype'=>'multipart/form-data', 'data-id' => ''),
                    'enableClientValidation' => false,
                    'clientOptions'=>array(
                        'validateOnSubmit'=>true,
                        'validateOnChange'=>true,
                        'validateOnType'=>true
                    )
                )); ?>
                    <li class="add_photo">
                        <label class="file_label" for="fileupload">
                            <?php echo CHtml::activeFileField($photo, 'file', array('id' => 'fileupload','data-url' => "/profile/photo/upload")); ?>
                            <span class="link">Загрузить фото</span>
                        </label>
                    </li>
                <?php $this->endWidget(); ?>
            <?php endif; ?>
            <?php foreach($images as $elem): ?>
                <li>
                    <a href="/profile/<?php echo $user_id.'/photo/gallery.html#'.$elem->image->id; ?>">
                            <div style="width:164px;height:164px;">
                                <img  style="margin:auto; position:absolute; top:0; left:0; bottom:0; right:0;" src="/pub/profile_photo/164x164/<?php echo $elem->image->image_filename; ?>" alt=""/>
                            </div>
                        <span class="comment_block"><span><?php echo ($elem->comment ? $elem->comment[0]->cnt : 0); ?></span></span>
                        <?php if($this->owner): ?>
                            <span class="delete_block" data-image_id="<?php echo $elem->image->id ;?>"></span>
                        <?php endif; ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</div>