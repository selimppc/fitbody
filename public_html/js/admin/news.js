$(function() {
    var uploader = new Uploader({
        formId: '#newsForm',
        uploadedUrl: '/admin/club/news/UploadedSingleImages/'
    });

    uploader.UploadedSingleImages();


});