/**
 * Created by shumer on 7/23/14.
 */
var comments = {
    options: {
        login: false
    },
    init: function(options) {
        if (options) $.extend(this.options, options);
        this.initEvents();
    },
    initEvents: function() {
        if (this.options.login) {
            $(document).on('click', '.comments_box .answer-link', $.proxy(this.toggleForm, this));
        }
    },
    toggleForm: function(e) {
        var $target = $(e.currentTarget);
        if ($target.attr('data-reply') === 'open') {
            $('li.comment_edit').remove();
            $('a.answer-link[data-reply="close"]').attr('data-reply', 'open');
            var commentLi = $target.closest('li.comment');
            var parentId = commentLi.attr('data-comment-id');
            var tpl = $('#comment-form-tpl').html();
            var commentForm = $(tpl).attr('action', '/ajax-request/profile/photo/change-comments?image=' + Photo).wrap('<li></li>').parent().addClass('comment_edit').find('input.comment_parent_id').first().attr('id', 'Comment' + parentId).val(parentId).closest('li.comment_edit');
            $(commentForm).insertAfter($target.closest('li.comment'));
            $target.attr('data-reply', 'close');
        } else {
            var nextEl = $target.closest('li.comment').next('.comment_edit');
            $target.attr('data-reply', 'open');
            nextEl.remove();
        }

        e.preventDefault();
    }
};

$(function() {
    var options = {
        login: app.isLoginUser()
    };
    comments.init(options);
});