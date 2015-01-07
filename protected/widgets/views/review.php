<div class="tab review active">
    <div class="ov">
        <div class="fl_r comment_link"><a href="#review-form-box">Оставить отзыв</a></div>
        <h2 id="coach-reviews">Отзывы о тренере</h2>
    </div>
    <div class="comments_box">
        <ul class="comments">
            <?php foreach ($reviews as $key => $review): ?>
                <li id="review-id-<?php echo $review['id']; ?>" class="comment">
                    <div class="rating_status">
                        <div class="rating_sm">
                            <input type="hidden" name="val" value="<?php echo $review['rating']; ?>"/>
                            <input type="hidden" name="vote-id" value="voteID"/>
                        </div>
                    </div>
                    <a class="img_box fl_l" href="<?php echo $review['user']['urlProfile']; ?>"><?php echo CHtml::image($review['user']['image'], 'image-' . $review['user']['nickname']); ?></a>
                    <span class="comments_info fl_l">
                        <span class="fl_r comment_link"></span>
                        <span class="name"><a href="<?php echo $review['user']['urlProfile']; ?>"><?php echo CHtml::encode($review['user']['nickname'] ? $review['user']['nickname'] : $review['user']['first_name'] . ' ' . $review['user']['last_name']); ?></a></span>
                        <span class="date"><?php echo DateHelper::convertDate($review['created_at']); ?></span>
                        <span class="comment_body">
                            <?php echo CHtml::encode($review['review']); ?>
                        </span>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>

        <div id="review-form-box" class="comment_edit">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'reviewForm',
                'errorMessageCssClass' => 'review_error_cause',
                'htmlOptions'=>array('data-id' => ($model->id) ? $model->id : ''),
                'action' => (Yii::app()->request->getUrl() . '#review-form-box')
            )); ?>
                <?php if (!$this->controller->isGuest): ?>
                    <a class="comment_photo fl_l" href="<?php echo Yii::app()->user->getState('urlProfile'); ?>">
                        <?php echo CHtml::image(Yii::app()->user->getState('image'), Yii::app()->user->getState('nickname')); ?>
                    </a>
                <?php endif; ?>
                <div class="row with_pict">
                    <?php echo $form->textArea($model, 'review', array('cols' => '30', 'rows' => '10', 'placeholder' => 'Ваш отзыв')); ?>
                </div>
            <?php echo $form->hiddenField($model, 'rating', array('id' => 'rating-hidden')); ?>
                <div class="row with_pict ov">
                    <div class="rate_box">
                        <span class="rate_label">Оценка работы:</span>
                        <div class="rating_lg">
                            <input type="hidden" name="val" value="<?php echo ($model->rating) ? $model->rating : '0'; ?>" />
                            <input type="hidden" name="vote-id" value="lg_rating" />
                        </div>
                    </div>
                    <input type="submit" value="Отправить отзыв" id="add-review" class="fl_r h_34 color_btn blue must-login"/>
                </div>
                <?php  echo $form->error($model, "review"); ?>
                <?php  echo $form->error($model, "rating"); ?>
            <?php $this->endWidget(); ?>
        </div>
    </div>
</div>