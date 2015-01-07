$(function() {
    $.datepicker.regional['ru'] = {
        closeText: 'Закрыть',
        prevText: '&#x3c;Пред',
        nextText: 'След&#x3e;',
        currentText: 'Сегодня',
        monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь',
            'Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
        monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн',
            'Июл','Авг','Сен','Окт','Ноя','Дек'],
        dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
        dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
        dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
        weekHeader: 'Не',
        firstDay: 1,
        isRTL: false,
        showMonthAfterYear: false,
        yearSuffix: ''};

    $.datepicker.setDefaults($.datepicker.regional['ru']);
    $('#Article_end_at').datepicker({
        changeMonth: true,
        changeYear: true,
        yearRange: "c:c+100",
        dateFormat: "yy-mm-dd",
        altFormat: "yy-mm-dd",
        showMonthAfterYear: true,
        beforeShow:function(input) {
            $(input).css({
                "position": "relative",
                "z-index": 100
            });
        }
    });


    var uploader = new Uploader({
        formId: '#articleForm',
        uploadedUrl: '/admin/article/article/UploadedSingleImages/'
    });

    uploader.UploadedSingleImages();


});