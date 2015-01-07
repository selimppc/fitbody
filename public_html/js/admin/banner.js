$(function() {
    $.fn.datetimepicker.dates['ru'] = {
        days: ["Воскресенье", "Понедельник", "Вторник", "Среда", "Четверг", "Пятница", "Суббота", "Воскресенье"],
        daysShort: ["Вск", "Пнд", "Втр", "Срд", "Чтв", "Птн", "Суб", "Вск"],
        daysMin: ["Вс", "Пн", "Вт", "Ср", "Чт", "Пт", "Сб", "Вс"],
        months: ["Январь", "Февраль", "Март", "Апрель", "Май", "Июнь", "Июль", "Август", "Сентябрь", "Октябрь", "Ноябрь", "Декабрь"],
        monthsShort: ["Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек"],
        today: "Сегодня"
    };

    $('.datetimepicker').datetimepicker({
        language: 'ru',
        pickTime: false
    });

    upl.init();

});

var upl = {
    init: function() {
        var that = $('#bannerForm'),
            id = that.attr('data-id'),
            data = {
                filename: $('#Banner_filename').val(),
                filename_real: $('#Banner_filename_real').val()
            };


        if (!data.filename && !data.filename_real) {
            data = false
        }

        $.ajax({
            "type": "post",
            "url": '/admin/banner/banner/UploadedBanner/' + id,
            "dataType" : "json",
            "data": {
                "data" : data
            },
            success: function (result, textStatus) {
                if (result && result.length) {
                    that.fileupload('option', 'done').call(that, null, {result: result});
                    $('#bannerForm' + ' .fileinput-button').addClass('disabled');
                    $('#bannerForm' + ' #XUploadForm_file').prop('disabled', true);
                }
            },
            beforeSend: function () {
            }
        });

    }

}

$(document).on('change', '#Banner_position_id', function() {
    var bannerContainer = $('.banner-container'),
        position = $(this).val(),
        width = '',
        height = '';

    if (position) {
        width = $('#sizes input[data-id=\"' + position + '\"]').attr('data-width');
        height = $('#sizes input[data-id=\"' + position + '\"]').attr('data-height');
    }
    bannerContainer.css({
        'height': height,
        'width': width
    });
    bannerContainer.attr('width', width);
    bannerContainer.attr('height', height);
});