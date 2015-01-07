/**
 * Created by Lagunaby1 on 16.06.2014.
 */
$(function(){
	//Clubs_inner
	var clubsInnerSync1 = $("#clubs_inner_sync1");
	var clubsInnerSync2 = $("#clubs_inner_sync2");
	clubsInnerSync1.owlCarousel({
		singleItem : true,
		slideSpeed : 1000,
		pagination:false,
		afterAction : syncPosition,
		responsiveRefreshRate : 200,
		theme: 'clubs_inner_owl'
	});

	clubsInnerSync2.owlCarousel({
		items : 5,
		itemsDesktop      : [1199,5],
		itemsDesktopSmall     : [979,5],
		itemsTablet       : [768,5],
		itemsMobile       : [479,5],
		pagination:false,
		responsiveRefreshRate : 100,
		navigation: true,
		theme: 'clubs_inner_owl',
		afterInit : function(el){
			el.find(".owl-item").eq(0).addClass("synced");
		},
		beforeInit : function(el){
			$('#clubs_inner_sync2').css({padding: '2px 38px'});
		}
	});

	function syncPosition(el){
		var current = this.currentItem;
		$("#clubs_inner_sync2")
			.find(".owl-item")
			.removeClass("synced")
			.eq(current)
			.addClass("synced")
		if($("#clubs_inner_sync2").data("owlCarousel") !== undefined){
			center(current)
		}
	}

	$("#clubs_inner_sync2").on("click", ".owl-item", function(e){
		e.preventDefault();
		var number = $(this).data("owlItem");
		clubsInnerSync1.trigger("owl.goTo",number);
	});

	function center(number){
		var sync2visible = clubsInnerSync2.data("owlCarousel").owl.visibleItems;
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
				clubsInnerSync2.trigger("owl.goTo", num);
			}
		} else if(num === sync2visible[sync2visible.length-1]){
			clubsInnerSync2.trigger("owl.goTo", sync2visible[1])
		} else if(num === sync2visible[0]){
			clubsInnerSync2.trigger("owl.goTo", num-1)
		}
	}
});
