/**
 * jquery popup plagin
 *
 * @version 0.9.0
 *
 * @example:
 *
 *  $('#my_popup_div').togglePopup();
 *
 *  if need show opaco without animation - set flag  animateOpaco in false
 *  if not need align center popup - set flag  alignCenter in false
 *
 *
 *  $('#my_popup_div').togglePopup({
 *      animateOpaco: false,
 *      alignCenter: false
 *  });
 *
 *  default parameters:
 *  alignCenter => true
 *  animateOpaco => true
 *
 *
 * @param o - settings
 * @returns {*|jQuery|HTMLElement}
 */
jQuery.browser = {};
(function () {
	jQuery.browser.msie = false;
	jQuery.browser.version = 0;
	if (navigator.userAgent.match(/MSIE ([0-9]+)\./)) {
		jQuery.browser.msie = true;
		jQuery.browser.version = RegExp.$1;
	}
})();
$.fn.togglePopup = function(o) {
	var params = {
		'alignCenter': true,
		'animateOpaco': true
	};
	for(var i in o)
		params[i] = o[i];

	var self = $(this);
	// if popup not initialized - init popup
	if(!$(self).data('jpopup'))
		__initPopup(self);

	// set opaco element
	var $opaco = $('#opaco');
	// if opaco not exists - add opaco html
	if(!$opaco.length)
		$opaco = __initOpaco();

	if(self.hasClass('none')) {
		if($.browser.msie) {
			var documentHeight = $(document).height();
			$opaco.height(documentHeight);
			$opaco.toggleClass('none');
			$opaco.css('opacity', 0.4);
			$opaco.on('click',function(){
				$(self).togglePopup();
			});
		} else {
			$opaco.height($(document).height()).toggleClass('none');
			if(params.animateOpaco)
				$opaco.fadeTo('slow', 0.4);
			else
				$opaco.css('opacity', 0.4);
			$opaco.click(function(){
				$(self).togglePopup();
			});
		}
		self.toggleClass('none');
		if(params.alignCenter)
			self.alignCenterPopup();
	} else {
		//visible - then hide
		$opaco.toggleClass('none').setDefaultCssOpaco().unbind('click');
		self.toggleClass('none');
	}

	return self;
};

$.fn.alignCenterPopup = function () {
	var marginLeft  = -$(this).width()/2 + 'px';
	var marginTop   = -$(this).height()/2 + 'px';
	var top         = '50%';
	var left        = '50%';
	var position    = 'fixed';
	if($(window).height() < $(this).height()) {
		position    = 'absolute';
		top         = 0;
		marginTop   = $(document).scrollTop();
	}
	this.css({
		'top'           : top,
		'left'          : left,
		'margin-left'   : marginLeft,
		'margin-top'    : marginTop,
		'position'      : position
	});
	return this;
};

$(function () {
	$(document).on('keyup', function(e) {
		if(e.keyCode == 27) {
			$('*[popup=true]').each(function () {
				if(!$(this).hasClass('none'))
					$(this).togglePopup();
			});
		}
	});
});

function __initPopup(e) {
	// add css on element
	$(e).css({
		position: 'absolute',
		'z-index': 9999
	});
	// set in data parameter, responsible for the fact that popap initted
	$(e).data('jpopup',true);
	$(e).attr('popup','true');
	// return element
	return e;
}

function __initOpaco() {
	$('body').prepend('<div id="opaco" class="none"></div>');
	var opaco = $('#opaco');
	opaco.setDefaultCssOpaco();
	return opaco;
}

$.fn.setDefaultCssOpaco = function () {
	this.removeAttr('style');
	this.css({
		'background-color'  : '#000000',
		'left'              : 0,
		'moz-opacity'       : 0,
		'-khtml-opacity'    : 0,
		'opacity'           : 0,
		'position'          : 'absolute',
		'top'               : 0,
		'width'             : '100%',
		'z-index'           : 9999
	});
	return this;
};