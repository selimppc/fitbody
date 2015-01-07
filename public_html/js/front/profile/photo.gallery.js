/**
 * Created by shumer on 7/21/14.
 */
var Photo;

$(function(){
    var changeComments = function(image_id){


        $.ajax({
            "type"      : "post",
            "url"       :  "/ajax-request/profile/photo/change-comments",
            "dataType"  : "json",
            "data"      : {image_id : image_id},
            success : function(response){

                if(response){
                    $('#comment-form-tpl').remove();
                    $('div.comments_box').replaceWith(response.html);
                    $('#comment-form form').attr('action','/ajax-request/profile/photo/change-comments?image='+image_id);
                }
            }
        });
    };

    var image = parseInt(window.location.hash.substr(1));
    var photoInnerSync1 = $("#photo_inner_sync1");
    var carousel = photoInnerSync1.data('owlCarousel');

    var count = 0;

    var items = $('div.item', photoInnerSync1);
    carousel.options.afterMove = function(some){
        var div = items.eq(carousel.currentItem);
        var image_id = parseInt(div.attr('data-image'));
        Photo = image_id;

        changeComments(image_id);
        window.location.hash = image_id;
    };

    if(image){
        Photo = image;
        changeComments(image);

        items.each(function(){
            if($(this).attr('data-image') == image){
                carousel.jumpTo(count);
            }
            count++;
        });
    } else {
        carousel.jumpTo(0);
        var div = items.eq(carousel.currentItem);
        var image_id = parseInt(div.attr('data-image'));
        Photo = image_id;

        changeComments(image_id);
        window.location.hash = image_id;
    }

    $(document).on('click', 'form input[type=submit]', function(){
        var form = $(this).parent().parent();
        $.ajax({
            "type"      : "post",
            "url"       :  form.attr('action'),
            "dataType"  : "json",
            "data"      : form.serialize(),
            success : function(response){

                if(response){
                    $('#comment-form-tpl').remove();
                    $('div.comments_box').replaceWith(response.html);
                    $('#comment-form form').attr('action','/ajax-request/profile/photo/change-comments?image='+Photo);
                }
            }
        });
        return false;
    });
});
