/**
 * Created by shumer on 6/19/14.
 */
$(function() {

    var uploader = new Uploader({
        formId: '#clubForm',
        uploadedUrl: '/admin/club/property/UploadedSingleImages/'
    });

    uploader.UploadedSingleImages();


});