$(document).on('click', '#days-of-week-table input[type="checkbox"]', function (e, data) {
    var day = $(this).attr('data-day');
    var table = $('.day-container[data-day="' + day + '"]' );
    if (data === 'delete') {
        table.fadeOut(200, function () {
            table.addClass('hidden');
        });
        $(this).prop('checked', false);
        e.preventDefault();
    } else {
        if ($(this).is(':checked')) {
            table.fadeIn(200, function() {
                table.removeClass('hidden');
            });
        } else {
            table.fadeOut(200, function () {
                table.addClass('hidden');
            });
        }
    }
});

$(document).on('click', '.btn-add-row', function(e) {
    var input = $('#general-count');
    var counter = input.attr('value');
    var dayWeek = $(this).closest('.program-of-day-table').attr('data-day');
    input.attr('value', ++counter);
    var html = $('#table-tpl').html();
    html = $(html).find('tbody').html();
    var tr = html.replace(/<%counter%>/g, counter);
    tr = tr.replace(/<%weekDay%>/g, dayWeek);
    $(this).closest('table.program-of-day-table').find('tbody').append(tr);
    e.preventDefault();
});

$(document).on('click', '.btn-remove-row', function(e) {
    var length = $(this).closest('tbody').find('tr').length;
    $(this).closest('tr').remove();
    if (length > 1) {
        $(this).closest('tr').remove();
    } else {
        var day = $(this).closest('.program-of-day-table').attr('data-day');
        $('#days-of-week-table input[type="checkbox"][data-day="' + day + '"]').trigger('click', 'delete');
    }
    e.preventDefault();
});

$(document).on('click', '#save_btn', function(e) {
    $('#programForm').submit();
    e.preventDefault();
});

$(function() {
    var uploader = new Uploader({
        formId: '#programForm',
        uploadedUrl: '/admin/program/program/UploadedSingleImages/'
    });

    uploader.UploadedSingleImages();
});

