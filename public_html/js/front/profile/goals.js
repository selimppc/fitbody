/**
 * Created by shumer on 7/26/14.
 */
var Goal = function(){
    this.template = undefined;
    this.fields = {};
    this.success = true;
    this.block = null;

    this.clearFields = function(){
        var _this = this;
        for(var index in _this.fields){
            if(_this.fields.hasOwnProperty(index)){
                _this.fields[index].val();
            }
        }
    };
    this.setFields = function(object){
        var _this = this;
        for(var index in _this.fields){
            if(_this.fields.hasOwnProperty(index) && object.hasOwnProperty(index)){
                _this.fields[index].val(object[index]);
            }
        }
        $('input, select', _this.template).trigger('refresh');
    };
    this.open = function(){
        this.template.togglePopup();
    };
    this.removeErrors = function(){
        var _this = this;
        for(var index in _this.fields){
            if(_this.fields.hasOwnProperty(index) && _this.fields[index].hasClass('error')){
                _this.fields[index].removeClass('error');
            }
        }
        $('div.jq-selectbox__select', _this.template).removeClass('error');
    };
    this.defaultValidator = function(){
        var _this   = this;

        _this.success = true;
        _this.removeErrors();
        if(!parseInt(_this.fields.id.val())){
            for(var index in _this.fields){
                if(_this.fields.hasOwnProperty(index)){
                    _this.addError(_this.fields[index]);
                }
            }
        }
    };
    this.validate = function(){};
    this.addError = function(obj){
        this.success = false;
        if(!obj.hasClass('error')){
            obj.addClass('error');
        }
    };
    this.clearErrors = function(){
        $('.error_cause', this.template).html('');
    };
    this.showErrors = function(arr){
        var html = '';

        this.clearErrors();
        for(var index in arr){
            if(arr.hasOwnProperty(index)){
                if(arr[index] instanceof Array){
                    html += '<li>- '+arr[index][0]+'</li>';
                } else {
                    html += '<li>- '+arr[index]+'</li>'
                }
            }
        }
        $('.error_cause', this.template).html(html);
    };
    this.save = function(block){
        var _this = this;

        _this.clearErrors();
        $.ajax({
            "type"      : "post",
            "url"       : '/ajax-request/profile/goals/edit',
            "data"      : _this.form.serialize(),
            "dataType"  : "json"
        }).done(function(response){
            if(response.success){
                block.html(response.html);
                _this.template.togglePopup();
            } else {
                _this.showErrors(response.errors);
            }
        });
    };
    this.init = function(input_object, block, foo){
        var _this = this;

        _this.clearFields();
        _this.setFields(input_object);
        if(foo) foo(_this);
        $('a.save_butt', _this.template).off().on('click',function(){
            if(_this.validate()){
                _this.save(block);
            }
            return false;
        });
    };
};


var GoalFat = function(input_object, block){
    this.template = $('#goal_fat_edit');
    this.form = $('#goalFatForm');
    this.fields = {
        id           : $('#GoalFat_id'),
        fat_start    : $('#GoalFat_start_fat'),
        fat_value    : $('#GoalFat_end_fat'),
        fat_date     : $('#GoalFat_end_fat_date'),
        weight_start : $('#GoalFat_start_weight'),
        weight_value : $('#GoalFat_end_weight'),
        weight_date  : $('#GoalFat_end_weight_date')
    };
    this.validate = function(){
        var _this   = this,
            fields = _this.fields;

        _this.defaultValidator();
        if((fields.fat_value.val() || fields.fat_start.val()) && fields.fat_date.val().length != 10){
            _this.addError(fields.fat_date);
        }
        if((fields.fat_value.val() || fields.fat_start.val() || fields.fat_date.val().length == 10) && !parseInt(fields.fat_value.val())){

            _this.addError(fields.fat_value);
        }
        if((fields.fat_value.val() || fields.fat_start.val() || fields.fat_date.val().length == 10) && !parseInt(fields.fat_start.val())){
            _this.addError(fields.fat_start);
        }

        if((fields.weight_value.val() || fields.weight_start.val()) && fields.weight_date.val().length != 10){
            _this.addError(fields.weight_date);
        }
        if((fields.weight_value.val() || fields.weight_start.val() || fields.fat_date.val().length == 10) && !parseInt(fields.weight_value.val())){
            _this.addError(fields.weight_value);
        }
        if((fields.weight_value.val() || fields.weight_start.val() || fields.fat_date.val().length == 10) && !parseInt(fields.weight_start.val())){
            _this.addError(fields.weight_start);
        }

        if(fields.weight_date.val().length != 10 && fields.fat_date.val().length != 10 && !parseInt(fields.weight_value.val()) && !parseInt(fields.fat_value.val())){
            _this.addError(fields.weight_date);
            _this.addError(fields.weight_value);
            _this.addError(fields.fat_date);
            _this.addError(fields.fat_value);
        }

        return _this.success;
    };
    this.hideUseless = function(_this){
        var fat_part = $('div.fat_part',  _this.template),
            weight_part = $('div.weight_part', _this.template);
        if(_this.fields.fat_start.val() && _this.fields.fat_value.val()){
            $('.empty', fat_part).css('display', 'none');
            $('span.not_empty', fat_part).css('display', 'block');
            $('label.not_empty', fat_part).css('margin-left', '0px');
        } else {
            $('label.empty', fat_part).css('display', 'inline-block');
            $('span.empty', fat_part).css('display', 'block');
            $('span.not_empty', fat_part).css('display', 'none');
            $('label.not_empty', fat_part).css('margin-left', '-20px');
        }
        if(_this.fields.weight_start.val() && _this.fields.weight_value.val()){
            $('.empty', weight_part).css('display', 'none');
            $('span.not_empty', weight_part).css('display', 'block');
            $('label.not_empty', weight_part).css('margin-left', '0px');
        } else {
            $('label.empty', weight_part).css('display', 'inline-block');
            $('span.empty', weight_part).css('display', 'block');
            $('span.not_empty', weight_part).css('display', 'none');
            $('label.not_empty', weight_part).css('margin-left', '-20px');
        }
    };
    this.init(input_object, block, this.hideUseless);
};
GoalFat.prototype = new Goal();


var GoalSize = function(input_object, block){
    this.template = $('#goal_size_edit');
    this.form = $('#goalSizeForm');
    this.fields = {
        id    : $('#GoalSize_id'),
        title : $('#GoalSize_body_part_id'),
        value : $('#GoalSize_end_size'),
        date  : $('#GoalSize_end_date')
    };
    this.validate = function(){
        var _this   = this,
            fields = _this.fields;

        _this.defaultValidator();
        if(!parseInt(fields.title.val())){
            _this.addError($('div.jq-selectbox__select',_this.template));
        }
        if(!fields.value.val() || !parseInt(fields.value.val())){
            _this.addError(fields.value);
        }
        if(fields.date.val().length != 10){
            _this.addError(fields.date);
        }
        return _this.success;
    };
    this.init(input_object, block);
};
GoalSize.prototype = new Goal();


var GoalWeight = function(input_object, block){
    this.template = $('#goal_weight_edit');
    this.form = $('#goalWeightForm');
    this.fields = {
        id    : $('#GoalWeight_id'),
        title : $('#GoalWeight_title'),
        value : $('#GoalWeight_weight_end'),
        date  : $('#GoalWeight_end_date')
    };
    this.validate = function(){
        var _this   = this,
            fields = _this.fields;

        _this.defaultValidator();
        if(!fields.title.val().length){
            _this.addError(fields.title);
        }
        if(!fields.value.val() || !parseInt(fields.value.val())){
            _this.addError(fields.value);
        }
        if(fields.date.val().length != 10){
            _this.addError(fields.date);
        }
        return _this.success;
    };

    this.init(input_object, block);

};
GoalWeight.prototype = new Goal();

var Progress = function(type, goal_id, block){
    this.type = '';
    this.form = null;
    this.template = undefined;
    this.fields = {};
    this.success = true;
    this.block = undefined;

    this.clear = function(){
        this.fields.goal_id.val('');
        this.fields.value.val('');
        this.fields.date.val('');
        this.clearError();
    };
    this.open = function(){
        this.template.togglePopup();
    };
    this.makeList = function(ul, data, s){
        var html = '';
        data.forEach(function(elem){
            html += '<li>'+
                '<span class="date">' + elem.date + '</span>'+
                '<span class="weight">' + parseFloat(elem.value).toString() + ' ' + s + '</span>'+
                '<input type="hidden" class="link_id" value="' + elem.id + '" />'+
                '<span class="progress">Достигнуто: ' + elem.percentage + '%</span>'+
                '<a class="li_close">&nbsp;</a>'+
            '</li>';
        });
        ul.html(html);
        this.addDelAction(ul);
    };
    this.addDelAction = function(ul){
        var _this = this;
        $('li', ul).each(function(){
            var elem = $(this);
            elem.off().on('click','a.li_close',function(){
                $.ajax({
                    "type"      : "post",
                    "url"       : '/ajax-request/profile/goals/remove_progress',
                    "data"      : {id : $('input.link_id', elem).val()},
                    "dataType"  : "json"
                }).done(function(response){
                    elem.remove();
                    _this.block.html(response.block);
                });
                return false;
            });
        });
    };
    this.parseList = function(open){
        var _this = this;

        $.ajax({
            "type"      : "post",
            "url"       : '/ajax-request/profile/goals/get-list',
            "data"      : {type : _this.type, goal_id : _this.fields.goal_id.val()},
            "dataType"  : "json"
        }).done(function(response){
            if(response.success){
                if(_this.type === 'fat'){
                    _this.makeList($('ul.fat_list', _this.template), response.data.fat, '%');
                    _this.makeList($('ul.weight_list', _this.template), response.data.weight, 'кг');
                } else {
                    _this.makeList($('ul.popup_progress_list', _this.template), response.data, (_this.type == 'size' ? 'см' : 'кг'));
                }
            }
            if(open){
                _this.open()
            }
        });
    };
    this.validate = function(){
        var _this = this;
        _this.success = true;
        _this.clearError();
        if(!parseInt(_this.fields.value.val())){
            _this.addError(_this.fields.value);
        }
        if(_this.fields.date.val().length != 10){
            _this.addError(_this.fields.date);
        }
        return _this.success;
    };
    this.addError = function(field){
        if(!field.hasClass('error')){
            field.addClass('error');
        }
        this.success = false;
    };
    this.clearError = function(){
        var _this = this;
        for(var index in _this.fields){
            if(_this.fields.hasOwnProperty(index)){
                if(_this.fields[index].hasClass('error')){
                    _this.fields[index].removeClass('error');
                }
            }
        }
        $('.error_cause', _this.template).html('');
    };
    this.showErrors = function(arr){
        var html = '';

        for(var index in arr){
            if(arr.hasOwnProperty(index)){
                if(arr[index] instanceof Array){
                    html += '<li>- '+arr[index][0]+'</li>';
                } else {
                    html += '<li>- '+arr[index]+'</li>'
                }
            }
        }
        $('.error_cause', this.template).html(html);
    };
    this.save = function(){
        var _this = this;
        $.ajax({
            "type"      : "post",
            "url"       : '/ajax-request/profile/goals/progress-add',
            "data"      : _this.form.serialize(),
            "dataType"  : "json"
        }).done(function(response){
            if(response.success){
                _this.clearError();
                _this.parseList(false);
                _this.block.html(response.block);
            } else {
                _this.showErrors(response.errors);
            }
        });
    };
    this.init = function(type, goal_id, block){
        var _this = this;
        this.type = type;
        this.block = block;
        this.template = $('#goal_' + type.toLowerCase() + '_progress');
        this.form = $('#goalProgressForm', this.template);
        this.fields = {
            type    : $('#GoalProgress_type', _this.template),
            goal_id : $('#GoalProgress_goal_id', _this.template),
            value   : $('#GoalProgress_value', _this.template),
            date    : $('.datepicker', _this.template)
        };
        this.clear();
        this.fields.goal_id.val(goal_id);
        this.parseList(true);
        $(this.template).off().on('click','a.save', function(){
            if(_this.validate()){
                _this.save();
            }
            return false;
        });
    };
    this.init(type, goal_id, block);
};

$(function(){
    //weight
    $('.goal_block.weight_block', document).each(function(){
        var _this = $(this);
        var block = $('.goal_info',_this);

        _this.on('click','a.progress_button', function(){
            var progress = new Progress('weight', $('.goal_id',block).val(), _this);
            return false;
        });
        _this.on('click','a.edit_button',function(){
            var fields = {
                id    : $('.goal_id',block).val(),
                title : $('.goal_title',block).val(),
                value : parseFloat($('.goal_value',block).val()),
                date  : $('.goal_date',block).val()
            };
            var goal = new GoalWeight(fields, _this);
            goal.open();
            return false;
        });
        _this.on('click','a.delete_button',function(){
            deleteGoal('weight',$(this).attr('data-id'),_this);
        });
    });
    $(document).on('click','#goal_weight_edit div.close',function(){
        $('#goal_weight_edit').togglePopup();
    });
    $(document).on('click','#goal_weight_progress div.close',function(){
        $('#goal_weight_progress').togglePopup();
    });


    //fat
    $('.goal_block.fat_block', document).each(function(){
        var _this = $(this);

        _this.on('click','a.progress_button', function(){
            var progress = new Progress('fat', $('.goal_id',_this).val(), _this);
            return false;
        });
        _this.on('click','a.edit_button',function(){
            var fields = {
                id           : $('.goal_id',_this).val(),
                fat_value    : $('.goal_fat',_this).val(),
                fat_start    : $('.goal_fat_start',_this).val(),
                fat_date     : $('.goal_fat_date',_this).val(),
                weight_value : $('.goal_weight',_this).val(),
                weight_start : $('.goal_weight_start',_this).val(),
                weight_date  : $('.goal_weight_date',_this).val()
            };
            var goal = new GoalFat(fields, _this);
            goal.open();
            return false;
        });
        _this.on('click','a.delete_button',function(){
            deleteGoal('fat',$(this).attr('data-id'),_this);
        });
    });
    $(document).on('click','#goal_fat_edit div.close',function(){
        $('#goal_fat_edit').togglePopup();
    });
    $(document).on('click','#goal_fat_progress div.close',function(){
        $('#goal_fat_progress').togglePopup();
    });


    //size
    $('.goal_block.size_block', document).each(function(){
        var _this = $(this);
        var block = $('.goal_info',_this);

        _this.on('click','a.progress_button', function(){
            var progress = new Progress('size', $('.goal_id',block).val(), _this);
            return false;
        });
        _this.on('click','a.edit_button',function(){
            var fields = {
                id    : $('.goal_id',block).val(),
                title : parseInt($('.goal_title',block).val()),
                value : parseFloat($('.goal_value',block).val()),
                date  : $('.goal_date',block).val()
            };
            var goal = new GoalSize(fields, _this);
            goal.open();
            return false;
        });
        _this.on('click','a.delete_button',function(){
            deleteGoal('size',$(this).attr('data-id'),_this);
        });
    });
    $(document).on('click','#goal_size_edit div.close',function(){
        $('#goal_size_edit').togglePopup();
    });
    $(document).on('click','#goal_size_progress div.close',function(){
        $('#goal_size_progress').togglePopup();
    });

    var deleteGoal = function(type, id, object){
        console.log(type, id);
        $.ajax({
            "type"      : "post",
            "url"       : '/ajax-request/profile/goals/remove',
            "data"      : {type : type, goal_id : id},
            "dataType"  : "json"
        }).done(function(response){
            if(response.success){
                object.remove();
            }
        });
    };

    var popup = $('#goal_fat_progress');
    var changeList = function(select){
        if(parseInt(select.val()) == 1){
            $('ul.popup_progress_list.weight_list', popup).css('display','block');
            $('ul.popup_progress_list.fat_list', popup).css('display','none');
        } else {
            $('ul.popup_progress_list.weight_list', popup).css('display','none');
            $('ul.popup_progress_list.fat_list', popup).css('display','block');
        }
    };
    var select = $('form select',popup);
    changeList(select);
    select.on('change', function(){
        changeList($(this));
    });
});