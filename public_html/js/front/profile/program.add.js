/**
 * Created by shumer on 7/16/14.
 */
var Popup = function(){
    var _this       = this;

    _this.init = function(){
        _this.input = null;
        _this.popup = $('#popup_add_exercise');
        _this.closeButt = $('div.close',_this.popup);
        _this.saveButt = $('a.save_butt',_this.popup);
        _this.cancelButt = $('a.cancel_butt',_this.popup);
        _this.type = {
            obj     : $('select[data-select=type]',_this.popup),
            value   : null
        };
        _this.muscle = {
            obj     : $('select[data-select=muscle]',_this.popup),
            value   : null
        };
        _this.exercises = {};
        _this.type.obj.find('option').each(function(index, type){
            _this.exercises[type.value] = {};
            _this.muscle.obj.find('option').each(function(index, muscle){
                _this.exercises[type.value][muscle.value] = {};

            });
        });
        $('div.base_list',_this.popup).find('li').each(function(index, elem){
            var li = $(this);
            var label = $('label.value', li);
            _this.exercises[elem.dataset.muscle_type][elem.dataset.muscle_id][elem.dataset.id] = {
                id      : elem.dataset.id,
                obj     : li,
                label   : label,
                checkbox: $('input[type=checkbox]',label),
                muscle  : elem.dataset.muscle_title,
                title   : $('div[data-title]>a.exercise_name',li).html(),
                display : false
            };
        });

        _this.reset();
        _this.bindEvents();
    };

    _this.show = function(type, muscle){
        _this.toEach(function(object, outer, inner, id){
            if(type == outer && muscle == inner){
                var a = $('a.img_cont.fl_l',object.obj);
                if(a.attr('data-rendered') == 'false'){
                    var img2 = $('<img>', { width : 65, height : 61, src : a.attr('data-src2') }).load(function(){
                        a.prepend(img2);
                    });
                    var img1 = $('<img>', { width : 65, height : 61, src : a.attr('data-src1') }).load(function(){
                        a.prepend(img1);
                    });
                    a.attr('data-rendered', 'true')
                }
                object.obj.css('display','block');
            } else {
                object.obj.css('display','none');
            }
        });
    };

    _this.toEach = function(foo){
        for(var outer in _this.exercises){
            if(_this.exercises.hasOwnProperty(outer)){
                for(var inner in _this.exercises[outer]){
                    if(_this.exercises[outer].hasOwnProperty(inner)){
                        for(var id in _this.exercises[outer][inner]){
                            if(_this.exercises[outer][inner].hasOwnProperty(id)){
                                foo(_this.exercises[outer][inner][id], outer, inner, id);

                            }
                        }
                    }
                }
            }
        }
    };

    _this.setArray = function(input){
        var arr = {};
        _this.toEach(function(obj, outer, inner, id){
            if(obj.checkbox.prop('checked')){
                arr[id] = {
                    title   : obj.title,
                    muscle  : obj.muscle
                };
            }
        });

        input.val(JSON.stringify(arr));
    };

    _this.setCheckboxes = function(){
        if(_this.input){
            var arr = JSON.parse(_this.input.val());
            _this.toEach(function(obj, outer, inner, id){
                if(arr[id]){
                    obj.checkbox.prop('checked',true);
                    if(!obj.label.hasClass('checked'))
                        obj.label.addClass('checked');
                } else {
                    obj.checkbox.prop('checked',false);
                    if(obj.label.hasClass('checked'))
                        obj.label.removeClass('checked');
                }
            });
        }
    };

    _this.reset = function(){
        _this.type.value = _this.type.obj[0].options[0].value;
        _this.muscle.value = _this.muscle.obj[0].options[0].value;
        _this.type.obj[0].value = _this.type.value;
        _this.muscle.obj[0].value = _this.muscle.value;
        _this.show(_this.type.obj[0].options[0].value, _this.muscle.obj[0].options[0].value);
    };

    _this.bindEvents = function(){
        _this.type.obj.off().on('change',function(){
            _this.type.value = _this.type.obj.val();
            _this.show(_this.type.value, _this.muscle.value);
        });

        _this.muscle.obj.off().on('change',function(){
            _this.muscle.value = _this.muscle.obj.val();
            _this.show(_this.type.value, _this.muscle.value);
        });

        _this.cancelButt.off().on('click',function(){
            closeExercise();
            return false;
        });

        _this.saveButt.off().on('click', function(){
            _this.setArray(_this.input);
            _this.afterSave();
            closeExercise();
            return false;
        });
    };

    _this.afterSave = function(){};

    _this.init();
};

$(function(){
    var popup = new Popup();

    var createRow = function(table, tr_title, muscle, index){
        var row = table[0].insertRow(0);
        row.className = 'tr_row';
        row.setAttribute('data-id', index);
        var cell = row.insertCell(0);
        var title = document.createTextNode(tr_title);
        var span = document.createElement('span');
        span.innerHTML = ' (' + muscle + ')';
        var a = document.createElement('a');
        a.className = 'fl_r';
        var img = document.createElement("img");
        img.setAttribute('src', '/images/delete_table_arrow.png');
        a.appendChild(img);
        cell.appendChild(a);
        cell.appendChild(title);
        cell.appendChild(span);
    };

    var removeAction = function(table, input, arr, a){
        var count = 0;
        $('tr.tr_row',table).each(function(){
            var elem = $(this),
                id = elem.attr('data-id');

            count++;
            $('a.fl_r',$(this)).on('click',function(){

                delete arr[id];
                input.val(JSON.stringify(arr));
                elem.remove();
                count--;
                if(count == 0){
                    a.html('+ добавить упражнение');
                } else {
                    a.html('+ еще одно');
                }
            });
        });
    };

    $('tr.weekday').each(function(){
        var tr = $(this),
            input = $('input[type=hidden].json',tr),
            table = $('table',tr),
            arr = JSON.parse(input.val());


        $('a.add_exercise', tr).each(function(){
            var _this = $(this);
            removeAction(table, input, arr, _this);

            _this.on('click',function(){

                popup.reset();
                popup.input = input;
                popup.setCheckboxes();

                popup.afterSave = function(){
                    $('tr.tr_row',table).each(function(){
                        $(this).remove();
                    });

                    arr = JSON.parse(input.val());
                    var count = 0;
                    for(var index in arr){
                        if(arr.hasOwnProperty(index)){
                            count++;
                            createRow(table, arr[index].title, arr[index].muscle, index);
                        }
                    }
                    if(count == 0){
                        _this.html('+ добавить упражнение');
                    } else {
                        _this.html('+ еще одно');
                    }

                    removeAction(table, input, arr, _this);
                };
                openExercise();
                return false;
            });
        });
    });

    $('a#save_program').on('click', function(){
        $('#profileProgramForm').submit();
        return false;
    });
});