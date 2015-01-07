$(function() {
    var uploader = new Uploader({
        formId: '#bookForm',
        uploadedUrl: '/admin/book/book/UploadedSingleImages/'
    });

    uploader.UploadedSingleImages();
})