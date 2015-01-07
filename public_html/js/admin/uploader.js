

function Uploader(options) {

    this.formId = options.formId;
    this.uploadedUrl = options.uploadedUrl;
    this.imagesClass = options.imagesClass || 'images';


    this.getImagesArray = function() {
        var images = $(this.formId + ' .' + this.imagesClass);
        var elements = [];
        $.each(images, function () {
            var val = $(this).val();
            if (val) {
                elements.push(val);
            }
        });
        return elements;
    }

    // get single upload images
    this.constructor.prototype.UploadedSingleImages = function () {

        var _this = this;
        var that = $(_this.formId);
        var elements = _this.getImagesArray();
        if (elements.length > 0) {
            $.ajax({
                "type": "post",
                "url": _this.uploadedUrl,
                "dataType" : "json",
                "data": {
                    "images" : elements
                },
                success: function (result, textStatus) {
                    if (result && result.length) {
                        that.fileupload('option', 'done').call(that, null, {result: result});
                        $(_this.formId + ' .fileinput-button').addClass('disabled');
                        $(_this.formId + ' #XUploadForm_file').prop('disabled', true);
                    }
                },
                beforeSend: function () {
                }
            });
        }
    }
    //TODO::Multiple inputs for $_POST
    // get multiple upload images
    this.constructor.prototype.UploadedMultipleImages = function (id) {
        var _this = this;
        $(this.formId).each(function () {
            if (id) {
                var _that = this;
                $.getJSON(_this.uploadedUrl + id, function (result) {
                    if (result && result.length) {
                        $(_that).fileupload('option', 'done').call(_that, null, {result: result});
                    }
                });
            }
        });
    }

}