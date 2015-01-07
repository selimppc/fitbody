/**
 * Created by shumer on 6/12/14.
 */
$(function(){
    var uploader = new Uploader({
        formId: '#muscleForm',
        uploadedUrl: '/admin/exercise/muscle/UploadedSingleImages/'
    });

    uploader.UploadedSingleImages();
});