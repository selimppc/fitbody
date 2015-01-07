/**
 * Created by shumer on 7/21/14.
 */
var deleteHandler = function(li, _this){
    var image_id = _this.attr('data-image_id');
    if(image_id){
        $.ajax({
            "url"       :  "/profile/photo/delete/" + image_id,
            "dataType"  : "json",
            success : function(response){
                if(response.success){
                    li.remove();
                }
            }
        });
    }
};
$(function(){
    $('li','div.photo_block').each(function(){
        var li = $(this);
        $(this).find('span.delete_block').on('click', function(){
            deleteHandler(li, $(this));
            return false;
        });
    });

    $('#fileupload').fileupload({
        dataType: 'json',
        success : function(response){
            var resp = response[0];
            var html = '<li><a href="/profile/'+Variables.profile_id+'/photo/gallery.html#'+resp.image_id+'"><img src="' + resp.url + '" alt=""/><span class="comment_block"><span>0</span></span><span class="delete_block" data-image_id="' + resp.image_id + '"></span></a></li>';

            $('li','div.photo_block').last().after(html);

            var li = $('li','div.photo_block').last();
            li.find('span.delete_block').on('click', function(){
                deleteHandler(li, $(this));
                return false;
            });
        }
    });
});