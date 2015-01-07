/**
 * Created by Lagunaby1 on 16.06.2014.
 */
$(function(){
	//Photo_inner
	var photoInnerSync1 = $("#photo_inner_sync1");
	var photoInnerSync2 = $("#photo_inner_sync2");
	photoInnerSync1.owlCarousel({
		singleItem : true,
		slideSpeed : 1000,
		pagination:false,
		navigation: true,
		afterAction : syncPosition,
		responsiveRefreshRate : 200,
		theme: 'photo_inner_owl',
		rewindNav: false
	});

	photoInnerSync2.owlCarousel({
		items : 8,
		itemsDesktop      : [1199,8],
		itemsDesktopSmall     : [979,8],
		itemsTablet       : [768,8],
		itemsMobile       : [479,8],
		pagination:false,
		responsiveRefreshRate : 100,
		navigation: true,
		theme: 'photo_inner_owl',
		rewindNav: false,
		afterInit : function(el){
			el.find(".owl-item").eq(0).addClass("synced");
		}
	});

	function syncPosition(el){
		var current = this.currentItem;
		$("#photo_inner_sync2")
			.find(".owl-item")
			.removeClass("synced")
			.eq(current)
			.addClass("synced")
		if($("#photo_inner_sync2").data("owlCarousel") !== undefined){
			center(current)
		}
	}

	$("#photo_inner_sync2").on("click", ".owl-item", function(e){
		e.preventDefault();
		var number = $(this).data("owlItem");
		photoInnerSync1.trigger("owl.goTo",number);
	});

	function center(number){
		var sync2visible = photoInnerSync2.data("owlCarousel").owl.visibleItems;
		var num = number;
		var found = false;
		for(var i in sync2visible){
			if(num === sync2visible[i]){
				var found = true;
			}
		}
		if(found===false){
			if(num>sync2visible[sync2visible.length-1]){
				sync2.trigger("owl.goTo", num - sync2visible.length+2)
			}else{
				if(num - 1 === -1){
					num = 0;
				}
				photoInnerSync2.trigger("owl.goTo", num);
			}
		} else if(num === sync2visible[sync2visible.length-1]){
			photoInnerSync2.trigger("owl.goTo", sync2visible[1])
		} else if(num === sync2visible[0]){
			photoInnerSync2.trigger("owl.goTo", num-1)
		}
	}
});
