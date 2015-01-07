/**
 * Created by shumer on 7/25/14.
 */
$(function() {
    var uploader = new Uploader({
        formId: '#newsForm',
        uploadedUrl: '/admin/shop/news/UploadedSingleImages/'
    });

    uploader.UploadedSingleImages();


});