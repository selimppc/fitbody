$(window).resize(function() {
//	content height
//	var windowHeight = $(window).height();
//	$('.main').css({minHeight: windowHeight - footerHeight - 215 + 'px'})
});
$(function() {
	$(window).resize();

	//footer height
	var footerHeight = $('footer').height();
	$('footer').css({height: footerHeight + 'px',marginTop: -footerHeight + 'px'});
	$('#content').css({paddingBottom: footerHeight + 'px'});

	//content height
	var windowHeight = $(window).height();
	$('.main').css({minHeight: windowHeight - footerHeight - 215 + 'px'});

	//Init aside slider
	$(".aside_slider").owlCarousel({
		navigation: true,
		slideSpeed: 300,
		paginationSpeed: 400,
		singleItem: true,
		pagination: false,
		theme: 'aside_owl',
		rewindNav: false
	});

	//Init index main_slider
	var sync1 = $("#sync1");
	var sync2 = $("#sync2");

	sync1.owlCarousel({
		singleItem: true,
		slideSpeed: 1000,
		pagination: false,
		afterAction: syncPosition,
		responsiveRefreshRate: 200,
		theme: 'index_main_owl'
	});

	sync2.owlCarousel({
		items: 4,
		itemsDesktop: [1199, 4],
		itemsDesktopSmall: [979, 4],
		itemsTablet: [768, 4],
		itemsMobile: [479, 4],
		pagination: false,
		responsiveRefreshRate: 100,
		navigation: true,
		theme: 'index_main_owl',
		afterInit: function(el) {
			el.find(".owl-item").eq(0).addClass("synced");
		}
	});

	function syncPosition(el) {
		var current = this.currentItem;
		$("#sync2")
			.find(".owl-item")
			.removeClass("synced")
			.eq(current)
			.addClass("synced")
		if ($("#sync2").data("owlCarousel") !== undefined) {
			center(current)
		}
	}

	$("#sync2").on("click", ".owl-item", function(e) {
		e.preventDefault();
		var number = $(this).data("owlItem");
		sync1.trigger("owl.goTo", number);
	});

	function center(number) {
		var sync2visible = sync2.data("owlCarousel").owl.visibleItems;
		var num = number;
		var found = false;
		for (var i in sync2visible) {
			if (num === sync2visible[i]) {
				var found = true;
			}
		}

		if (found === false) {
			if (num > sync2visible[sync2visible.length - 1]) {
				sync2.trigger("owl.goTo", num - sync2visible.length + 2)
			} else {
				if (num - 1 === -1) {
					num = 0;
				}
				sync2.trigger("owl.goTo", num);
			}
		} else if (num === sync2visible[sync2visible.length - 1]) {
			sync2.trigger("owl.goTo", sync2visible[1])
		} else if (num === sync2visible[0]) {
			sync2.trigger("owl.goTo", num - 1)
		}

	}
	//Content_nav animation
	//	$(document).on('click','.main_nav_list > li > a',function (e) {
	//		e.preventDefault();
	//		var needOpen = !$(this).parent('.main_nav_list > li').hasClass('active');
	//		$('.main_nav_list > li > a').parent('.main_nav_list > li')
	//			.removeClass('active')
	//			.end()
	//			.next('.sub_main_nav_list')
	//			.slideUp();
	//		if(needOpen) {
	//			$('.sub_main_nav_list li').removeClass('active');
	//			$(this).parent('.main_nav_list > li')
	//				.addClass('active')
	//				.end()
	//				.next('.sub_main_nav_list')
	//				.slideDown();
	//		}
	//	});
	//
	//	$(document).on('click','.sub_main_nav_list li a',function (e) {
	//		e.stopPropagation();
	//		e.preventDefault();
	//		$('.sub_main_nav_list li').removeClass('active');
	//		$(this).parent('.sub_main_nav_list li').addClass("active");
	//	});

	//	Clubs page
	//show img on hover
	$('.img_preview_box img').hover(function() {
			$('img.' + $(this).data('img-class'), $(this).parents('.with_preview')).css('opacity', '1', 'zIndex', '1');

			$('img', $(this).parents('.img_preview_box')).css('opacity', '.5');
			$(this).css('opacity', '1');
		},
		function() {
			$('img.' + $(this).data('img-class'), $(this).parents('.with_preview')).css('opacity', '0', 'zIndex', '0');

			$(this).css('opacity', '.5');
		});
	$('.img_preview_box').mouseleave(function() {
		$('li:first-child img', $(this)).css('opacity', '1');
	});

	//Tabs
	$(".tab_nav li").click(function() {
		$('.tab_nav li').removeClass('active');
		$(this).addClass("active");

		var hr = $("a", $(this)).attr("href");

		$('.tab').removeClass("active");
		$(hr).addClass("active");

		$('select').trigger('refresh');
		return false;
	});
	//Goal_tabs
	$(".goal_nav li").click(function() {
		$('.goal_nav li').removeClass('active');
		$(this).addClass("active");

		var hr = $("a", $(this)).attr("href");

		$('.goal_tab').removeClass("active");
		$(hr).addClass("active");

		$('select').trigger('refresh');
		return false;
	});
	//Settings_tabs
	$(".settings_nav li").click(function() {
		$('.settings_nav li').removeClass('active');
		$(this).addClass("active");

		var hr = $("a", $(this)).attr("href");

		$('.settings_tab').removeClass("active");
		$(hr).addClass("active");

		$('select').trigger('refresh');
		return false;
	});
	//gender
	$('.gender_btn').click(function(e) {
		$('.gender_btn').removeClass('active');
		$(this).addClass('active');
		e.preventDefault();
	});
	//login_btn
	$(document).on('click', '.login', function(e) {
		e.preventDefault();
		e.stopPropagation();
		$(this).addClass('active');
		$(this).next('.login_box').addClass('active');
	});
	$(document).on('click', '.login_block', function(e) {
		e.stopPropagation();
	});
	$(document).click(function() {
		$('.login').removeClass('active');
		$('.login').next('.login_box').removeClass('active');
	});
	//logout
	$(document).on('click', '.personal_link', function(e) {
		e.preventDefault();
		e.stopPropagation();
		$(this).next('.personal_block').addClass('active');
	});
	$(document).on('click', '.personal_block', function(e) {
		e.stopPropagation();
	});
	$(document).click(function() {
		$('.personal_block').removeClass('active');
	});
	//on Profile settings page show/hide profile privacy settings
	$('#sett_privacy input[type="radio"]').change(function() {
		if (this.checked && $(this).hasClass('choose_privacy')) {
			$('.sub_checkbox').addClass('active');
		} else {
			$('.sub_checkbox').removeClass('active');
		}
	});
	//Program_page
		//Table tr numeration
		if (!$('table').hasClass('program_table_edit')) {
			$($('td table'), $(this)).each(function(i) {
				$(this).find('td').each(function(i) {
					if ($(this).children().hasClass('add_notice') || $(this).children().hasClass('notice')) {
						$(this).html($(this).html());
					} else {
						$(this).html(i + 1 + ".&nbsp;" + $(this).html());
					}
				});
			});
		}
		//Show/hide form
		$(document).on('click', '.add_notice', function(e) {
			$(this).parent().append(
				'<div class="notice"><dl><dt>Питание:</dt><dd><label><textarea></textarea></label></dd><dt>Фармакология:</dt><dd><label><textarea></textarea></label></dd><dt>Общие заметки:</dt><dd><label><textarea></textarea></label></dd></dl><div class="save_changes"><a href="" class="color_btn blue">Сохранить</a><a class="cancel_link" href="">Отменить</a></div></div>'
			);
			$(this).remove();
			return false;
		});
		$(document).on('click', '.cancel_link', function(e) {
			$(this).parents('.notice').parent().append('<a href="" class="add_notice">+ Добавить заметки</a>');
			$(this).parents('.notice').remove();
			return false;
		});

		//on Profile program_edit page color change on checked
		$('.exercise_box input[type="checkbox"]').change(function() {
			if (this.checked) {
				$(this).parents('label').addClass('checked');
			} else {
				$(this).parents('label').removeClass('checked');
			}
		});
});
