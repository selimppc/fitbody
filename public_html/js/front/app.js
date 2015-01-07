var app = {
    init: function () {
        this.initEvents();
        this.initAjaxPrefilter();
        // remove hash after facebook redirect
        if (window.location.hash && window.location.hash == "#_=_")
            window.location.hash = "";
    },
    initEvents: function () {
        if (!this.isLoginUser()) {

            $(document).on('show.loginPopup', $.proxy(this.showLoginPopup, this));
            $(document).on('hide.loginPopup', $.proxy(this.hideLoginPopup, this));

            $('#popup_login .close').on('click', function() {
                $(document).trigger('hide.loginPopup');
            });
            // must-login - class for login popup if not register
            $(document).on('click', '.must-login', function (e) {
                $(document).trigger('show.loginPopup');
                e.preventDefault();
            });

        }
    },
    isLoginUser: function () {
        var login = $(document.body).attr('data-login');
        return (login === 'login');
    },
    getCsrfToken: function () {
        return $(document.body).attr('data-csrf');
    },
    getCsrfTokenName: function () {
        return $(document.body).attr('data-csrf-name');
    },
    showLoginPopup: function() {
        $('#popup_login').togglePopup();
        $('input, select').styler();
    },
    hideLoginPopup: function() {
        $('#popup_login').togglePopup();
    },
    initAjaxPrefilter: function () {
        var _this = this;
        $.ajaxPrefilter(function( options, originalOptions, jqXHR ) {
            var data = {};
            var csrf = _this.getCsrfToken();
            var csrfName = _this.getCsrfTokenName();
            data[csrfName] = csrf;
            if (!originalOptions.formData && typeof originalOptions.data === 'object') {
                options.data = $.param($.extend(originalOptions.data, data));
            }

        });
    }
}

$(function () {
   app.init();
});