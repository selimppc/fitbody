/**
 * Created by shumer on 7/18/14.
 */
var Program = {
    editData : {
        object : null,
        meal : '',
        pharmacology : '',
        note : ''
    },
    changeProgram : function(formatDate){
        $('table.program_table').hide(1,function(){
            Program.getProgram(formatDate);
        });
    },
    getProgram : function(date){
        $.ajax({
            "type"      : "post",
            "url"       : '/ajax-request/profile/program/change-date',
            "data"      : {date : date, profile_id : Variables.profile_id},
            "dataType"  : "json",
            success     : Program.parseProgram
        });
    },
    parseProgram : function(response){
        Variables.program_id = response.program_id;
        var link = $('a.edit');
        link.attr('href', Program.changeEditLink(link.attr('href')));
        $('table.program_table').html(response.html).show(1);

        addNoteHandlers();
    },
    changeEditLink : function(href){
        if(href){
            var arr = href.split('/');
            if(Variables.program_id){
                arr[arr.length-1] = Variables.program_id + '.html';
            } else {
                arr.splice(arr.length-1, 1);
                arr[arr.length-1] = 'add' + '.html';
            }

            href = arr.join('/');
        }
        return href;
    },
    editNote : function(meal, pharmacology, note, date){
        Program.editData.meal = meal;
        Program.editData.pharmacology = pharmacology;
        Program.editData.note = note;

        $.ajax({
            "type"      : "post",
            "url"       : '/ajax-request/profile/program/edit-note',
            "data"      : {date : date, program_id : Variables.program_id, meal : meal, pharmacology : pharmacology, note : note},
            "dataType"  : "json",
            success     : Program.parseNote
        });
    },
    parseNote : function(response){
        if(response.success){
            var html = '<div class="notice"><dl>';

            html += ( Program.editData.meal != '' ? '<dt>Питание:</dt><dd class="data-meal">'+  Program.editData.meal +'</dd>' : '');
            html += ( Program.editData.pharmacology != '' ? '<dt>Фармакология:</dt><dd class="data-pharmacology">'+  Program.editData.pharmacology +'</dd>' : '');
            html += ( Program.editData.note != '' ? '<dt>Общие заметки:</dt><dd class="data-note">'+  Program.editData.note +'</dd>' : '');
            html += '</dl></div><div class="table_edit"><a class="edit_note" href="">Редактировать</a></div>';

            Program.editData.object.find('td.day_note').html(html);
            openEdit(Program.editData.object);
            Program.editData.object = null;
            Program.editData.meal = '';
            Program.editData.pharmacology = '';
            Program.editData.note = '';
        }
    }
};
var onSaveButt  = function(_this){
    _this.find('a.color_btn.blue').on('click', function(){
        var form = _this.find('div.notice');
        var meal = form.find('textarea[name=meal]');
        var pharmacology = form.find('textarea[name=pharmacology]');
        var note = form.find('textarea[name=note]');
        var date = _this.attr('data-date');

        if(meal.val() == '' && pharmacology.val() == '' && note.val() == ''){
            meal.addClass('error');
            pharmacology.addClass('error');
            note.addClass('error');
        } else {
            meal.removeClass('error');
            pharmacology.removeClass('error');
            note.removeClass('error');
            Program.editData.object = _this;
            Program.editNote(meal.val(), pharmacology.val(), note.val(), date);
        }
        return false;
    });
};
var openEdit = function(_this){
    _this.find('a.edit_note').on('click', function(){
        var meal = (_this.find('dd.data-meal').length ? _this.find('dd.data-meal').html() : ''),
            pharmacology = (_this.find('dd.data-pharmacology').length ? _this.find('dd.data-pharmacology').html() : ''),
            note = (_this.find('dd.data-note').length ? _this.find('dd.data-note').html() : '');


        var html = '<div class="notice"><dl><dt>Питание:</dt><dd><label><textarea name="meal">'+ meal+'</textarea></label></dd><dt>Фармакология:</dt><dd><label><textarea name="pharmacology">'+ pharmacology+'</textarea></label></dd><dt>Общие заметки:</dt><dd><label><textarea name="note">'+ note+'</textarea></label></dd></dl><div class="save_changes"><a href="" class="color_btn blue">Сохранить</a><a class="cancel_link" href="">Отменить</a></div></div>';

        _this.find('td.day_note').html(html);
        closeEdit(_this, meal, pharmacology, note);
        onSaveButt(_this);
        return false;
    });
};
var closeEdit = function(_this, meal, pharmacology, note){

    _this.find('a.cancel_link').on('click', function(){
        var html = '<div class="notice"><dl>';

        html += (meal != '' ? '<dt>Питание:</dt><dd class="data-meal">'+ meal +'</dd>' : '');
        html += (pharmacology != '' ? '<dt>Фармакология:</dt><dd class="data-pharmacology">'+ pharmacology +'</dd>' : '');
        html += (note != '' ? '<dt>Общие заметки:</dt><dd class="data-note">'+ note +'</dd>' : '');
        html += '</dl></div><div class="table_edit"><a class="edit_note" href="">Редактировать</a></div>';

        _this.find('td.day_note').html(html);
        openEdit(_this);
        return false;
    });

};
var closeAdd = function(_this){
    _this.find('a.cancel_link').on('click', function(){
        $(this).parents('.notice').parent().append('<a href="" class="add_notice">+ Добавить заметки</a>');
        $(this).parents('.notice').remove();
        openAdd(_this);
        return false;
    });
};

var openAdd = function(_this){
    _this.find('a.add_notice').on('click', function(){
        $(this).parent().append(
            '<div class="notice"><dl><dt>Питание:</dt><dd><label><textarea name="meal"></textarea></label></dd><dt>Фармакология:</dt><dd><label><textarea name="pharmacology"></textarea></label></dd><dt>Общие заметки:</dt><dd><label><textarea name="note"></textarea></label></dd></dl><div class="save_changes"><a href="" class="color_btn blue">Сохранить</a><a class="cancel_link" href="">Отменить</a></div></div>'
        );
        $(this).remove();

        closeAdd(_this);
        onSaveButt(_this);
        return false;
    });
};

var addNoteHandlers = function(){
    $('.data-row').each(function(){
        var _this = $(this);

        openAdd(_this);
        openEdit(_this);
    });
};
$(function(){
    addNoteHandlers();

    $(".datepicker_period").datepicker({
        dateFormat: "dd.mm",
        onSelect: function( selectedDate ) {
            var date = $('.datepicker_period').datepicker("getDate");
            var day = (date.getDay() ? date.getDay() : 7);
            var dateStart = new Date();
            dateStart.setDate(date.getDate() - (day - 1));
            var dateEnd = new Date();
            dateEnd.setDate(date.getDate() + (7 - day));
            $(this).val($.datepicker.formatDate('dd.mm', new Date(dateStart)) + "-" + $.datepicker.formatDate('dd.mm', new Date(dateEnd)));

            var formatDate = date.getFullYear() + '-' + ('0' + (date.getMonth()+1)).slice(-2) + '-' + ('0' + date.getDate()).slice(-2);
            Program.changeProgram(formatDate)

        }
    });
});
