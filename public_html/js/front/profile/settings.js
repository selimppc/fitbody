/**
 * Created by shumer on 7/7/14.
 */
var Settings = {
    path : '/ajax-request/profile/settings/',
    MainInfo : function( profile_id){
        var _this = this;

        _this.textFilds = [
            'first_name',
            'last_name',
            'email',
            'nickname',
            'birthday',
            'height',
            'weight',
            'fat',
            'city'
        ];

        _this.fields = {};
        _this.errors = [];
        _this.errorPlace  = $('#main_errors').find('ul');

        _this.init = function(){
            $('#fileupload').fileupload({
                dataType: 'json',
                submit: function () {
                    console.log("Asd");

                },
                success : function(response){
                    var resp = response[0];
                    var hidden_image = $('#image_value');
                    var old_image_id = parseInt(hidden_image.val());

                    if(old_image_id) {
                        $.ajax({
                            "url"       :  '/profile/settings/upload/method/delete/image/' + old_image_id,
                            "dataType"  : "json"
                        });
                    }

                    $('#user_photo').remove();
                    hidden_image.before($('<img>').attr('src',resp.url).attr('id', 'user_photo'));
                    $('#main_user_photo').attr('src','/pub/user_photo/150x150' + resp.thumbnail_url);
                    hidden_image.val(resp.image_id);
                }
            });
            $("#delete_image").on('click', function(){
                var hidden_image = $('#image_value');
                var old_image_id = parseInt(hidden_image.val());

                if(old_image_id) {
                    $.ajax({
                        "url"       :  '/profile/settings/upload/method/delete/image/' + old_image_id,
                        "dataType"  : "json"
                    });
                }
                $('#user_photo').remove();
                $('#main_user_photo').attr('src','/images/progress_template.png');
                hidden_image.val(undefined);
                return false;
            });

            //init text fields
            _this.textFilds.forEach(function(elem){
                var object = $('#'+elem+' input');

                _this.fields[elem] = {
                    object  : object,
                    initial : object.val(),
                    value   : object.val(),
                    error   : false
                };
            });
            //init country select
            var countryField = $('#country_id').find('select');
            _this.fields['country_id'] = {
                object  : countryField,
                initial : countryField.val(),
                value   : countryField.val(),
                error   : false
            };
            //init gender buttons
            var gender = $('#gender').find('a');
            _this.fields['gender'] = {
                object  : gender,
                initial : gender.siblings('.active').attr('gender'),
                value   : gender.siblings('.active').attr('gender'),
                error   : false
            };
        };

        _this.changeValues = function(){
            _this.textFilds.forEach(function(id){
                _this.fields[id].value = _this.fields[id].object.val();
            });
            _this.fields['country_id'].value = _this.fields['country_id'].object.val();
            _this.fields['gender'].value = _this.fields['gender'].object.siblings('.active').attr('gender');
        };

        _this.revertValues = function(){
            var obj;

            _this.textFilds.forEach(function(id){
                obj = _this.fields[id];
                obj.object.val(obj.initial);
                obj.value = obj.initial;
            });

            obj = _this.fields['country_id'];
            obj.object.val(obj.initial);
            obj.value = obj.initial;
            obj.object.trigger('refresh');

            obj = _this.fields['gender'];
            obj.value = obj.initial;
            obj.object.removeClass('active').siblings('[gender='+obj.initial+']').addClass('active');

            _this.removeErrors();
            _this.errors = [];
            _this.errorPlace.html('');
        };

        _this.save = function(){
            _this.changeValues();

            var data = {};
            for(var name in _this.fields){
                if(_this.fields.hasOwnProperty(name)){
                    data[name] = _this.fields[name].value;
                }
            }

            $.ajax({
                "type"      : "post",
                "url"       : Settings.path + 'change-main-info',
                "data"      : {data : data, profile_id : profile_id},
                "dataType"  : "json",
                success     : _this.afterSave
            });
        };

        _this.afterSave = function(answer){
            $('#main_errors').css('display','none');
            _this.removeErrors();
            _this.errors = [];
            _this.errorPlace.html('');

            if(answer.status === true){
                var block = $('#main_success');
                block.show(500);
                setTimeout(function(){
                    block.hide(1000);
                },3000);
                for(var name in _this.fields){
                    if(_this.fields.hasOwnProperty(name)){
                        _this.fields[name].initial = _this.fields[name].value;
                    }
                }
                _this.changeLayout();
            } else if(answer.status === false) {
                _this.errors.push('- Такого пользователя не существует');
                _this.showErrorMessages();
                $('#main_errors').css('display','block');
            } else {
                _this.addErrors(answer.errors);
                _this.showErrorMessages();
                $('#main_errors').css('display','block');
            }
        };

        _this.removeErrors = function(){
            var input;
            for(var name in _this.fields){
                if(_this.fields.hasOwnProperty(name)){
                    _this.fields[name].error = false;
                    input = _this.fields[name].object;
                    input.hasClass('error') ? input.removeClass('error') : false;
                }
            }
            input = $('#country_id').find('.jq-selectbox__select');
            input.hasClass('error') ? input.removeClass('error') : false;
        };

        _this.addErrors = function(errors){
            for(var name in errors){
                if(errors.hasOwnProperty(name)){
                    var field = _this.fields[name];
                    if(field){
                        field.error = true;
                        if(name != 'country_id')
                            field.object.addClass('error');
                        else
                            $('#country_id').find('.jq-selectbox__select').addClass('error');
                        _this.errors.push('- ' + errors[name][0]);
                    }
                }
            }
        };

        _this.showErrorMessages = function(){
            var html = '';

            _this.errors.forEach(function(elem){
                html += '<li>'+elem+'</li>';
            });
            _this.errorPlace.html(html);
        };

        _this.changeLayout = function(){
            var id, dt, dd;
            var show = function(obj1, obj2, value){
                obj2.html(value);
                obj1.show(300,function(){
                    obj2.show();
                });
            };

            var hide = function(obj1, obj2){
                obj2.hide(150,function(){
                    obj1.hide(150, function(){
                        obj2.html('');
                    });
                });
            };
            var fields = [
                {id : 'weight', type : 'кг'},
                {id : 'height', type : 'см'},
                {id : 'fat', type : '%'}
            ];

            fields.forEach(function(elem){
                id = elem.id;
                dt = $('dt.layout_' + id);
                dd = $('dd.layout_' + id);

                if(_this.fields[id].value){
                    show(dt, dd, _this.fields[id].value + ' ' + elem.type);
                } else {
                    hide(dt, dd);
                }
            });
        };

        _this.init();
    },

    MusclesInfo : function(profile_id){
        var _this = this;

        _this.textFilds = [
            'biceps',
            'neck',
            'thigh',
            'forearm',
            'chest',
            'buttocks',
            'wrist',
            'waist',
            'shin'
        ];

        _this.fields = {};
        _this.errors = [];
        _this.errorPlace  = $('#muscles_errors').find('ul');

        _this.init = function(){
            _this.textFilds.forEach(function(elem){
                var object = $('#'+elem+' input');

                _this.fields[elem] = {
                    object  : object,
                    initial : object.val(),
                    value   : object.val(),
                    error   : false
                };
            });
        };

        _this.changeValues = function(){
            _this.textFilds.forEach(function(id){
                _this.fields[id].value = _this.fields[id].object.val();
            });
        };

        _this.revertValues = function(){
            var obj;

            _this.textFilds.forEach(function(id){
                obj = _this.fields[id];
                obj.object.val(obj.initial);
                obj.value = obj.initial;
            });

            _this.removeErrors();
            _this.errors = [];
            _this.errorPlace.html('');
        };

        _this.save = function(){
            _this.changeValues();

            var data = {};
            for(var name in _this.fields){
                if(_this.fields.hasOwnProperty(name)){
                    data[name] = _this.fields[name].value;
                }
            }

            $.ajax({
                "type"      : "post",
                "url"       : Settings.path + 'change-profile-info',
                "data"      : {data : data, profile_id : profile_id},
                "dataType"  : "json",
                success     : _this.afterSave
            });
        };

        _this.afterSave = function(answer){
            $('#muscles_errors').css('display','none');
            _this.removeErrors();
            _this.errors = [];
            _this.errorPlace.html('');

            if(answer.status === true){
                var block = $('#muscles_success');
                block.show(500);
                setTimeout(function(){
                    block.hide(1000);
                },3000);
                for(var name in _this.fields){
                    if(_this.fields.hasOwnProperty(name)){
                        _this.fields[name].initial = _this.fields[name].value;
                    }
                }
                _this.changeLayout();
            } else if(answer.status === false) {
                _this.errors.push('- Такого пользователя не существует');
                _this.showErrorMessages();
                $('#muscles_errors').css('display','block');
            } else {
                _this.addErrors(answer.errors);
                _this.showErrorMessages();
                $('#muscles_errors').css('display','block');
            }
        };

        _this.removeErrors = function(){
            var input;
            for(var name in _this.fields){
                if(_this.fields.hasOwnProperty(name)){
                    _this.fields[name].error = false;
                    input = _this.fields[name].object;
                    input.hasClass('error') ? input.removeClass('error') : false;
                }
            }
        };

        _this.addErrors = function(errors){
            for(var name in errors){
                if(errors.hasOwnProperty(name)){
                    var field = _this.fields[name];
                    if(field){
                        field.error = true;
                        _this.errors.push('- ' + errors[name][0]);
                    }
                }
            }
        };

        _this.changeLayout = function(){
            for(var name in _this.fields){
                if(_this.fields.hasOwnProperty(name)){
                    var dt = $('dt.layout_' + name);
                    var dd = $('dd.layout_' + name);
                    var span = $('span.size',dd);

                    var show = function(obj1, obj2){
                        obj2.show(500,function(){
                            obj1.show();
                        });
                    };

                    var hide = function(obj1, obj2, span){
                        obj1.hide(100,function(){
                            obj2.hide(300, function(){
                                span.html('');
                            });
                        });
                    };

                    if(_this.fields[name].value){
                        span.html(_this.fields[name].value + ' см');
                        show(dt, dd);

                    } else {
                        hide(dt, dd, span);
                    }
                }
            }
        };

        _this.showErrorMessages = function(){
            var html = '';

            _this.errors.forEach(function(elem){
                html += '<li>'+elem+'</li>';
            });
            _this.errorPlace.html(html);
        };

        _this.init();
    },

    PrivacyInfo : function(profile_id){
        var _this = this;

        _this.checkboxes = [
            'show_photo',
            'show_program',
            'show_progress',
            'show_goals'
        ];
        _this.fields = {};
        _this.errorPlace = $('#privacy_errors').find('ul');
        _this.errors = [];
        _this.init = function(){
            var object;

            _this.checkboxes.forEach(function(elem){
                object = $('#'+elem+' input');

                _this.fields[elem] = {
                    object  : object,
                    initial : !object.is(':checked'),
                    value   : !object.is(':checked')
                };
            });
            object = $('input[type="radio"][name="radio1"]','#sett_privacy');
            _this.fields['show_profile'] = {
                object  : object,
                initial : object.filter(':checked').attr('show-profile') === 'true',
                value   : object.filter(':checked').attr('show-profile') === 'true'
            };
        };

        _this.changeValues = function(){
            _this.checkboxes.forEach(function(id){
                _this.fields[id].value = !_this.fields[id].object.is(':checked');
            });
            _this.fields['show_profile'].value = _this.fields['show_profile'].object.filter(':checked').attr('show-profile') === 'true';
        };

        _this.revertValues = function(){
            var obj, value;
            _this.checkboxes.forEach(function(id){
                obj = _this.fields[id];
                value = !obj.object.is(':checked');
                if(value != obj.initial) obj.object.trigger('click');
                obj.value = obj.initial;
            });

            obj = _this.fields['show_profile'];
            value = (obj.object.filter(':checked').attr('show-profile') === 'true');
            if(value != obj.initial) obj.object.filter('[show-profile='+obj.initial+']').trigger('click');
            obj.value = obj.initial;
        };

        _this.save = function(){
            _this.changeValues();
            _this.errors = [];

            var data = {};
            for(var name in _this.fields){
                if(_this.fields.hasOwnProperty(name)){
                    data[name] = _this.fields[name].value ? 1 : 0;
                }
            }

            $.ajax({
                "type"      : "post",
                "url"       : Settings.path + 'change-profile-info',
                "data"      : {data : data, profile_id : profile_id},
                "dataType"  : "json",
                success     : _this.afterSave
            });
        };

        _this.afterSave = function(answer){
            $('#privacy_errors').css('display','none');
            _this.errorPlace.html('');

            if(answer.status === true){
                var block = $('#privacy_success');
                block.show(500);
                setTimeout(function(){
                    block.hide(1000);
                },3000);
                for(var name in _this.fields){
                    if(_this.fields.hasOwnProperty(name)){
                        _this.fields[name].initial = _this.fields[name].value;
                    }
                }
            } else if(answer.status === false) {
                _this.errors.push('- Такого пользователя не существует');
                _this.showErrorMessages();
                $('#privacy_errors').css('display','block');
            } else {
                _this.addErrors(answer.errors);
                _this.showErrorMessages();
                $('#privacy_errors').css('display','block');
            }
        };

        _this.addErrors = function(errors){
            for(var name in errors){
                if(errors.hasOwnProperty(name)){
                    _this.errors.push('- ' + errors[name][0]);
                }
            }
        };

        _this.showErrorMessages = function(){
            var html = '';

            _this.errors.forEach(function(elem){
                html += '<li>'+elem+'</li>';
            });
            _this.errorPlace.html(html);
        };

        _this.init();
    },

    RssInfo : function(profile_id){
        var _this = this;

        _this.checkboxes = [
            'rss_article',
            'rss_exercise',
            'rss_company_news'
        ];
        _this.fields = {};
        _this.errorPlace = $('#rss_errors').find('ul');
        _this.errors = [];
        _this.init = function(){
            var object;

            _this.checkboxes.forEach(function(elem){
                object = $('#'+elem+' input');

                _this.fields[elem] = {
                    object  : object,
                    initial : object.is(':checked'),
                    value   : object.is(':checked')
                };
            });
        };

        _this.changeValues = function(){
            _this.checkboxes.forEach(function(id){
                _this.fields[id].value = _this.fields[id].object.is(':checked');
            });
        };

        _this.revertValues = function(){
            var obj, value;
            _this.checkboxes.forEach(function(id){
                obj = _this.fields[id];
                value = obj.object.is(':checked');
                if(value != obj.initial) obj.object.trigger('click');
                obj.value = obj.initial;
            });
        };

        _this.save = function(){
            _this.changeValues();
            _this.errors = [];

            var data = {};
            for(var name in _this.fields){
                if(_this.fields.hasOwnProperty(name)){
                    data[name] = _this.fields[name].value ? 1 : 0;
                }
            }

            $.ajax({
                "type"      : "post",
                "url"       : Settings.path + 'change-profile-info',
                "data"      : {data : data, profile_id : profile_id},
                "dataType"  : "json",
                success     : _this.afterSave
            });
        };

        _this.afterSave = function(answer){
            $('#rss_errors').css('display','none');
            _this.errorPlace.html('');

            if(answer.status === true){
                var block = $('#rss_success');
                block.show(500);
                setTimeout(function(){
                    block.hide(1000);
                },3000);
                for(var name in _this.fields){
                    if(_this.fields.hasOwnProperty(name)){
                        _this.fields[name].initial = _this.fields[name].value;
                    }
                }
            } else if(answer.status === false) {
                _this.errors.push('- Такого пользователя не существует');
                _this.showErrorMessages();
                $('#rss_errors').css('display','block');
            } else {
                _this.addErrors(answer.errors);
                _this.showErrorMessages();
                $('#rss_errors').css('display','block');
            }
        };

        _this.addErrors = function(errors){
            for(var name in errors){
                if(errors.hasOwnProperty(name)){
                    _this.errors.push('- ' + errors[name][0]);
                }
            }
        };

        _this.showErrorMessages = function(){
            var html = '';

            _this.errors.forEach(function(elem){
                html += '<li>'+elem+'</li>';
            });
            _this.errorPlace.html(html);
        };

        _this.init();
    },

    Pass : function(profile_id){
        var _this = this;

        _this.init = function(){
            _this.pass   = $('input','#pass_main');
            _this.retype = $('input','#pass_retype');
            _this.errorPlace = $('#pass_errors');
            _this.errorList = _this.errorPlace.find('ul');
            _this.errors = [];
        };

        _this.revertValues = function(){
            _this.pass.val('');
            _this.retype.val('');
            _this.errorPlace.hide(500,function(){
                _this.errorList.html('');
            });
        };

        _this.save = function(){
            _this.errors = [];
            if(_this.pass.val() != '' && _this.retype.val() != '' && _this.pass.val() === _this.retype.val()){
                _this.errorPlace.hide(500);

                $.ajax({
                    "type"      : "post",
                    "url"       : Settings.path + 'change-password',
                    "data"      : {password : _this.pass.val(), profile_id : profile_id},
                    "dataType"  : "json",
                    success     : _this.afterSave
                });
            } else {
                _this.pass.val('');
                _this.retype.val('');
                _this.errors.push('- Пароль введен неверно');
                _this.showErrorMessages();
                $('#pass_errors').show(500);
            }
        };

        _this.afterSave = function(answer){
            _this.errorPlace.css('display','none');
            _this.errorList.html('');

            if(answer.status === true){
                var block = $('#pass_success');
                block.show(500);
                setTimeout(function(){
                    block.hide(500);
                },3000);
            } else if(answer.status === false) {
                _this.errors.push('- Такого пользователя не существует');
                _this.showErrorMessages();
                _this.errorPlace.show(500);
            } else {
                _this.addErrors(answer.errors);
                _this.showErrorMessages();
                _this.errorPlace.show(500);
            }
        };

        _this.addErrors = function(errors){
            for(var name in errors){
                if(errors.hasOwnProperty(name)){
                    _this.errors.push('- ' + errors[name][0]);
                }
            }
        };

        _this.showErrorMessages = function(){
            var html = '';

            _this.errors.forEach(function(elem){
                html += '<li>'+elem+'</li>';
            });
            _this.errorList.html(html);
        };

        _this.init();
    }
};

$(function(){
    //main info
    var mainInfo = new Settings.MainInfo(Variables.profile_id);
    $('#height input, #weight input, #fat input').on('keydown',function(event){
        var key = String.fromCharCode( event.keyCode );
        var regex = /[0-9]|\./;

        return !(event.keyCode != 8 && event.keyCode != 9 && !regex.test(key));
    });

    $('#submit_main_info').click(function(){
        mainInfo.save();
        return false;
    });
    $('#revert_main_info').click(function(){
        mainInfo.revertValues();
        return false;
    });

    //muscles info
    var musclesInfo = new Settings.MusclesInfo(Variables.profile_id);
    $('#biceps input, #neck input, #thigh input, #forearm input, #chest input, #buttocks input, #wrist input, #waist input, #shin input').on('keydown',function(event){
        var key = String.fromCharCode( event.keyCode );
        var regex = /[0-9]|\./;

        return !(event.keyCode != 8 && event.keyCode != 9 && !regex.test(key));
    });
    $('#submit_muscles_info').click(function(){
        musclesInfo.save();
        return false;
    });
    $('#revert_muscles_info').click(function(){
        musclesInfo.revertValues();
        return false;
    });

    //privacy info
    var privacyInfo = new Settings.PrivacyInfo(Variables.profile_id);
    $('#submit_privacy_info').click(function(){
        privacyInfo.save();
        return false;
    });
    $('#revert_privacy_info').click(function(){
        privacyInfo.revertValues();
        return false;
    });

    //rss info
    var rssInfo = new Settings.RssInfo(Variables.profile_id);
    $('#submit_rss_info').click(function(){
        rssInfo.save();
        return false;
    });
    $('#revert_rss_info').click(function(){
        rssInfo.revertValues();
        return false;
    });

    //pass info
    var pass = new Settings.Pass(Variables.profile_id);
    $('#submit_pass_info').click(function(){
        pass.save();
        return false;
    });
    $('#revert_pass_info').click(function(){
        pass.revertValues();
        return false;
    });

    $('#delete_tab').on('click', function(){
        $('div.settings_nav>ul>li').removeClass('active');
        $('div.settings_tab').removeClass('active');
        $('#sett_delete').addClass('active');
        return false;
    });
});
