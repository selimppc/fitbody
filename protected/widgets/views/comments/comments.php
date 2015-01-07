<script type="tpl/comment-form" id="comment-form-tpl">
    <?php echo $clearCommentForm; ?>
</script>
<div class="comments_box">
    <ul id="comments-box" class="comments">
        <?php echo FunctionHelper::getBuildComments($comments, $commentForm, ($model->hasErrors()) ? $model->parent_id: ''); ?>
    </ul>
    <div id="comment-form" class="comment_edit">
        <?php echo ($model->hasErrors() && !$model->parent_id) ? $commentForm : $clearCommentForm; ?>
    </div>
</div>