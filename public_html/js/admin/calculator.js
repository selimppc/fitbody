$(function() {
    var uploader = new Uploader({
        formId: '#calculatorForm',
        uploadedUrl: '/admin/calculators/calculators/UploadedSingleImages/'
    });

    uploader.UploadedSingleImages();
})