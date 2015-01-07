// Add
$(document).on('click', '#add-property', function(e) {
    e.preventDefault();
    var count = $('#countProperties').val();
    var html = $('#propertyTemplate').html().replace(/__count__/ig, count);
    var newCount = ++count;
    $('#countProperties').val(newCount);
    $(html).hide().insertBefore( $('#add-property').closest('.control-group')).slideDown('300');
    $id = $(html).find('select').attr('id');
    $('#' + $id).chosen();
});


$(document).on('click', '.add-worktime', function(e) {
    e.preventDefault();
    var firstSibling = $(this).siblings('.countWorktime').first();
    var count = firstSibling.val();
    var countProperty = $(this).closest('.propertyRow').attr('data-count-row');
    var html = $('#worktimeTemplate').html().replace(/__count__/ig, count).replace(/__countProperty__/ig, countProperty);
    var newCount = ++count;
    firstSibling.val(newCount);
    $(html).hide().insertBefore( $(this).closest('.control-group')).slideDown('300').find('.range-slider').noUiSlider({
        start: [0, 6],
        step: 1,
        range: {
            'min': [0],
            'max': [6]
        },
        connect: true,
        serialization: {
            format: {
                decimals: 0
            }
        }
    }).trigger('slide');
    initDateTimepicker();
});

$(document).on('click', '.add-phone', function(e) {
    e.preventDefault();
    var siblings = $(this).siblings('.countPhones').first();
    var count = siblings.val();
    var countProperty = $(this).closest('.propertyRow').attr('data-count-row');
    var html = $('#phoneTemplate').html().replace(/__count__/ig, count).replace(/__countProperty__/ig, countProperty);
    var newCount = ++count;
    siblings.val(newCount);
    $(html).hide().insertBefore( $(this).closest('.control-group')).slideDown('300');

});

$(document).on('click', '.add-param', function(e) {
    e.preventDefault();
    var siblings = $(this).siblings('.countParam').first();
    var count = siblings.val();
    var html = $('#paramTemplate').html().replace(/__count__/ig, count);
    var newCount = ++count;
    siblings.val(newCount);
    $(html).hide().insertBefore( $(this).closest('.control-group')).slideDown('300');
    $id = $(html).find('select').attr('id');
    $('#' + $id).chosen();
});
// End Add

// Delete
$(document).on('click', '.delete-param', function(e) {
    $(this).closest('.control-group').slideUp('300', function () {
        $(this).remove();
    });
    e.preventDefault();
});


$(document).on('click', '.delete-worktime', function(e) {
    $(this).closest('.worktime-container').slideUp('300', function () {
        $(this).remove();
    });
    e.preventDefault();
});

$(document).on('click', '.delete-phone', function(e) {
    $(this).closest('.control-group').slideUp('300', function () {
        $(this).remove();
    });
    e.preventDefault();
});

$(document).on('click', '.delete-property', function(e) {
    $(this).closest('.well').slideUp('300', function () {
       $(this).remove();
    });
    e.preventDefault();
});

// End Delete

// slider event
$(document).on('slide', '.range-slider', function () {
    var _this = $(this);
    var arr = _this.val();
    var daysOfWeek = ['Пн', 'Вт', 'Ср', 'Чт', 'Пт','Сб','Вс'];
    var siblings = _this.siblings('div.days');
    if (arr[0] === arr[1]) {
        siblings.html(daysOfWeek[arr[0]]);
    } else {
        siblings.html(daysOfWeek[arr[0]] + " - " + daysOfWeek[arr[1]]);
    }
    _this.siblings('.slider-from_day').first().val(arr[0]);
    _this.siblings('.slider-to_day').first().val(arr[1]);
});
// end slider event

$(document).ready(function() {
    // Upload
    var uploaderImages = new Uploader({
        formId: '#clubImageForm',
        uploadedUrl: '/admin/club/club/UploadedMultipleImages/'
    });
    var clubId = $('#clubForm').attr('data-id');
    if (clubId) {
        uploaderImages.UploadedMultipleImages(clubId);
    }

    var uploaderPrice = new Uploader({
        formId: '#clubForm',
        uploadedUrl: '/admin/club/club/UploadedSingleImages/'
    });

    var uploaderMainImage = new Uploader({
        formId: '#clubFormMainImage',
        uploadedUrl: '/admin/club/club/UploadedSingleMainImage/'
    });
    uploaderPrice.UploadedSingleImages();
    uploaderMainImage.UploadedSingleImages();
    // End Upload

    // init slider
    var sliders = $(".range-slider");
    if (sliders.length > 0) {
        sliders.noUiSlider({
            start: [0, 6],
            step: 1,
            range: {
                'min': [0],
                'max': [6]
            },
            connect: true,
            serialization: {
                format: {
                    decimals: 0
                }
            }
        });
        sliders.each(function($index, $val) {
            var _this = $(this);
            var valFrom = _this.siblings('.slider-from_day').first().val();
            var valTo = _this.siblings('.slider-to_day').first().val();
            _this.val([valFrom, valTo]);
        });

        sliders.trigger('slide');
    }
    //end init slider

    //init datetimepicker
    initDateTimepicker();
    // end init datetimepicker

});

// history tab
$('#clubTabs a:first').tab('show');
$('a[data-toggle="tab"]').on('shown', function (e) {
    localStorage.setItem('lastTab', $(e.target).attr('href'));
    $('.chosen-container').css('width', '220px'); // fix width for chosen in tabs
});
var lastTab = localStorage.getItem('lastTab');
if (lastTab) {
    $('#clubTabs a[href="' + lastTab + '"]' ).tab('show');
}
// end history tab

//datetimepicker
function initDateTimepicker() {
    $('.datetimepicker').datetimepicker({
        pickDate: false,
        pickSeconds: false
    });
}
$(document).on('click', '.onDateTimePicker', function () {
    $(this).closest('div.datetimepicker').datetimepicker('show');
});
//end datetimepicker

$(document).on('click', '#Club_radio>input', function(e) {
    var _this = $(this);
    var radio_block = $("#chain_radio");
    var chain_name_block = $("#chainName_field");
    var chain_select_block = $("#chainSelect_field");

    if(_this.val() == 0){
        radio_block.hasClass("formSep") ? '' : radio_block.addClass("formSep");
        chain_name_block.css('display','none');
        chain_select_block.css('display','none');
    } else if(_this.val() == 1) {
        radio_block.hasClass("formSep") ? radio_block.removeClass("formSep") : '' ;
        chain_name_block.css('display','none');
        chain_select_block.css('display','block');
    } else if(_this.val() == 2) {
        radio_block.hasClass("formSep") ? radio_block.removeClass("formSep") : '' ;
        chain_name_block.css('display','block');
        chain_select_block.css('display','none');
    }
});

var Map = function(id){
    var _this = this;

    _this.map     = null;
    _this.location= null;
    _this.currentLocation = null;
    _this.address = $("#address_field").find('input');
    _this.city    = $("#city_field").find('select');
    _this.buttom  = $("#address_show");
    _this.coordField = $("#coords").find('input[type=hidden]');

    _this.loadYMaps = function(){
        ymaps.ready(_this.loadPosition);
    };

    _this.loadPosition = function(){
        ymaps.geolocation.get({
            provider: 'yandex',
            mapStateAutoApply: true
        }).then(function (result) {
            _this.currentLocation = result.geoObjects.position;
            _this.init(_this.currentLocation);
        });
    };

    _this.findCoords = function(address, success, fail){
        var myGeocoder = ymaps.geocode(address);
        myGeocoder.then(success,fail);
    };

    _this.centerMap = function(position){
        _this.map.setCenter(position, 13);
    };

    _this.changeLocation = function(coords){
        if(_this.location){
            _this.map.geoObjects.remove(_this.location);
            _this.location = null;
        }

        _this.location = new ymaps.Placemark(coords,{},{
            preset: 'islands#redIcon',
            draggable : true
        });
        _this.onLocationDrag(_this.location);
        _this.map.geoObjects.add(_this.location);
        _this.centerMap(coords);
    };

    _this.onLocationDrag = function(obj){
        var foo = function(){
            _this.findCoords(obj.geometry.getCoordinates(),function(res){
                var arr = res.geoObjects.get(0).properties.get('text').split(',',5);
                var city = arr[1].replace(/\s+/g, '');
                var address = arr.slice(2,arr.length).join();

                _this.changeFields(city, address);
                _this.changeHiddenFields(obj.geometry.getCoordinates());
            }, function(err){
                console.log('Ошибка:');
                console.log(err);
            });
        };

        foo();
        obj.events.add('dragend',foo);
    };

    _this.changeFields = function(city, address){
        _this.address.val(address);
        _this.city.val(_this.city.find('option:contains("' + city + '")').attr("value"));
        _this.city.trigger("chosen:updated");
    };

    _this.changeHiddenFields = function(coords){
        _this.coordField.filter('.lat').val(coords[0]);
        _this.coordField.filter('.lon').val(coords[1]);
    };

    _this.initStartLocation = function(){
        var lat = _this.coordField.filter('.lat').val();
        var lon = _this.coordField.filter('.lon').val();

        if(lat != '' && lon != ''){
            _this.changeLocation([parseFloat(lat),parseFloat(lon)]);
        }
    };

    _this.init = function(position){

        _this.map = new ymaps.Map(id, {
            center: position,
            zoom: 13
        });

        _this.initStartLocation();

        _this.buttom.on('click',function(e){
            e.preventDefault();

            var city_id = _this.city.chosen().val();
            var city    = city_id ? _this.city.find('option[value='+city_id+']').html() : null;
            var address = _this.address.val();

            if(city != '' && address != ''){
                _this.findCoords(city + " " + address,function(res){
                    _this.changeLocation(res.geoObjects.get(0).geometry.getCoordinates());
                },function(err){
                    console.log('Ошибка:');
                    console.log(err);
                });
            } else {
                _this.changeLocation(_this.currentLocation);
            }
        });
    };

    _this.loadYMaps();
};

$(function(){
    var map = new Map("map");
});