/**
 * Created by User on 09.06.2014.
 */
$(function () {
	//Custom select and inputs
	if(!$(this).parents('.popup_inner').hasClass('popup_inner')){
		$('select').styler({
			selectSearch: false,
			onSelectOpened: function() {
				$(".jq-selectbox__dropdown ul", $(this)).each(function () {
					$(this).jScrollPane({
						mouseWheelSpeed				: 8,
						verticalGutter				: 0,
						horizontalGutter			: 0,
						trackClickSpeed				: 30
					});
				});
			}
		});
	}


});