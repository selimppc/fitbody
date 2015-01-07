$(function() {
    var uploader = new Uploader({
        formId: '#newsForm',
        uploadedUrl: '/admin/coach/news/UploadedSingleImages/'
    });

    uploader.UploadedSingleImages();


});