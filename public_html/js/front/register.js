console.log('register');
var register = {
    init: function () {
        this.initEvents();
    },
    initEvents: function () {
        $(document).on('click', '.gender_btn', $.proxy(this.setGender, this))
    },
    setGender: function (e) {
        var val = $(e.currentTarget).attr('data-value');
        $('#User_gender').val(val);
    }

}

$(function () {
    register.init();
});