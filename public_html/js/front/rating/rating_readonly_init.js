
$(function(){
    $('.rating_status .rating_sm').rating({
        image: '/js/front/rating/stars_sm.png',
        titles: ['','',''],
        loader: '/js/front/rating/ajax-loader.gif',
        readOnly: true
    });

    $('.rate_box .rating_lg').rating({
        image: '/js/front/rating/stars_lg.png',
        loader: '/js/front/rating/ajax-loader.gif',
        click: function (val) {
            $('#rating-hidden').val(val);
        }
    });
    //$('#rating-hidden').val($('.rating_lg[name="val"]').val());
});