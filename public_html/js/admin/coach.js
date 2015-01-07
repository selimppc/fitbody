/**
 * Created by shumer on 6/9/14.
 */
$(function(){
    var uploader = new Uploader({
        formId: '#coachForm',
        uploadedUrl: '/admin/coach/coach/UploadedSingleImages/'
    });

    uploader.UploadedSingleImages();

    // Multiple Upload
    var uploaderImages = new Uploader({
        formId: '#coachImageForm',
        uploadedUrl: '/admin/coach/coach/UploadedMultipleImages/'
    });
    var clubId = $('#coachForm').attr('data-id');
    if (clubId) {
        uploaderImages.UploadedMultipleImages(clubId);
    }
    // End Multiple Upload
});
// Add simpe club
$(document).on('click', '.add-club', function(e) {
    e.preventDefault();
    var siblings = $(this).siblings('.countClubs').first();
    var count = siblings.val();
    var countProperty = $(this).closest('.propertyRow').attr('data-count-row');
    var html = $('#clubTemplate').html().replace(/__count__/ig, count).replace(/__countProperty__/ig, countProperty);
    var newCount = ++count;
    siblings.val(newCount);
    $(html).hide().insertBefore( $(this).closest('.controls')).slideDown('300');

});

$(document).on('click', '.delete-club', function(e) {
    $(this).closest('.controls').slideUp('300', function () {
        $(this).remove();
    });
    e.preventDefault();
});
// Add phone
$(document).on('click', '.add-phone', function(e) {
    e.preventDefault();
    var siblings = $(this).siblings('.countPhones').first();
    var count = siblings.val();
    var countProperty = $(this).closest('.propertyRow').attr('data-count-row');
    var html = $('#phoneTemplate').html().replace(/__count__/ig, count).replace(/__countProperty__/ig, countProperty);
    var newCount = ++count;
    siblings.val(newCount);
    $(html).hide().insertBefore( $(this).closest('.control-group')).slideDown('300');

});

$(document).on('click', '.delete-phone', function(e) {
    $(this).closest('.control-group').slideUp('300', function () {
        $(this).remove();
    });
    e.preventDefault();
});
// Add property
$(document).on('click', '.add-param', function(e) {
    e.preventDefault();
    var siblings = $(this).siblings('.countParam').first();
    var count = siblings.val();
    var html = $('#paramTemplate').html().replace(/__count__/ig, count);
    var newCount = ++count;
    siblings.val(newCount);
    $(html).hide().insertBefore( $(this).closest('.control-group')).slideDown('300');
    $id = $(html).find('select').attr('id');
    $('#' + $id).chosen();
});
// End Add

// Delete
$(document).on('click', '.delete-param', function(e) {
    $(this).closest('.control-group').slideUp('300', function () {
        $(this).remove();
    });
    e.preventDefault();
});


// history tab
$('#coachTabs a:first').tab('show');
$('a[data-toggle="tab"]').on('shown', function (e) {
    localStorage.setItem('lastTab', $(e.target).attr('href'));
    $('.chosen-container').css('width', '470px'); // fix width for chosen in tabs
});
var lastTab = localStorage.getItem('lastTab');
if (lastTab) {
    $('#coachTabs a[href="' + lastTab + '"]' ).tab('show');
}
// end history tab