/**
 * Created by shumer on 7/26/14.
 */
var Map = function(id, func){
    var _this = this;

    _this.map     = null;
    _this.location= null;
    _this.currentLocation = null;
    _this.address = $("#address_field");
    _this.range   = $("#address_range");
    _this.buttom  = $("#address_show");
    _this.marks = [];

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

    _this.findCoords = function(success, fail){
        var myGeocoder = ymaps.geocode(_this.address.val());
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
            preset: 'islands#redDotIcon'
        });
        _this.map.geoObjects.add(_this.location);
        _this.centerMap(coords);
    };

    _this.changeMarks = function(marks){
        if(_this.marks.length){
            _this.marks.forEach(function(elem){
                _this.map.geoObjects.remove(elem);
            });
            _this.marks = [];
        }

        marks.forEach(function(elem){
            _this.marks.push(new ymaps.Placemark([elem['lat'],elem['lon']],{
                iconContent : '<a href="">'+elem['name']+'</a>'
            },{
                preset: 'islands#blueStretchyIcon'
            }));
        });

        _this.marks.forEach(function(elem){
            _this.map.geoObjects.add(elem);
        });
    };

    _this.init = function(position){

        _this.map = new ymaps.Map(id, {
            center: position,
            zoom: 13
        });

        _this.changeLocation(position);
        func(position);

        _this.buttom.off().on('click',function(e){
            e.preventDefault();

            if(_this.address.hasClass("error")){
                _this.address.removeClass("error");
            }
            if (_this.address.val() != ''){
                _this.findCoords(function(res){
                    var coordinates = res.geoObjects.get(0).geometry.getCoordinates();
                    _this.changeLocation(coordinates);
                    func(coordinates);
                },function(err){
                    console.log('Ошибка:');
                    console.log(err);
                });
            } else {
                _this.address.addClass("error");
            }
        });
    };

    _this.loadYMaps();
};

$(function(){
    $("#address_show").on('click',function(){
        e.preventDefault();
    });

    var category_id = window.category_id;
    var onButton = function(coords){
        $.ajax({
            "type"      : "post",
            "url"       : '/ajax-request/shop/get-shops',
            "data"      : {data : {coords : coords, range : map.range.val(), category_id : category_id}},
            "dataType"  : "json",
            success     : function(response){
                if(response){
                    map.changeMarks(response.marks);
                    $("#list").html(response.html);
                }
            }
        });
    };
    var map = new Map("map", onButton);
});