/**
 * Created by shumer on 7/7/14.
 */
$(function(){
    var uploader = new Uploader({
        formId: '#countryForm',
        uploadedUrl: '/admin/place/country/UploadedSingleImages/'
    });

    uploader.UploadedSingleImages();
});