/**
 * Created by shumer on 7/14/14.
 */
var profileModule = function(id){
    var _this = this;

    _this.input = $('#ProfileProgress_' + id + '_image_id');
    _this.id = id;
    _this.image_id = '';
    _this.delete_url = null;
    _this.init =  function () {
        _this.initUploader();
    };
    _this.initUploader = function () {
        var _this = this;
        $('#fileupload_' + _this.id).fileupload({
            dataType: 'json',
            //formData: data,
            submit: function () {
                console.log("Asd");

            },
            success : function(response){
                var resp = response[0];
                var block = $('#image_' + _this.id);

                $('#label_' + _this.id).css('display','block');
                if(block.find('img').length) {
                    $.ajax({
                        "url"       :  '/profile/progress/upload/method/delete/image/' + _this.image_id,
                        "dataType"  : "json"
                    });
                    block.find('img').remove();
                }
                block.prepend($('<img>').attr('src',resp.url));

                _this.image_id = resp.image_id;
                _this.delete_url = resp.delete_url;
                _this.input.val(resp.image_id);
                console.log(response[0]);
            }
        });
    };
    _this.init();
};

$(function(){
    var before = new profileModule('before');
    var now = new profileModule('now');

    var before_image = $('img', 'div.img_before div.img_cont');
    var after_image = $('img', 'div.img_after div.img_cont');

    if(after_image.length){
        now.image_id = after_image.attr('data-image');
    }
    if(before_image.length){
        before.image_id = before_image.attr('data-image');
    }

    document.getElementById("progressForm_save").addEventListener("click", function () {
        document.getElementById("progressForm").submit();
    });
});