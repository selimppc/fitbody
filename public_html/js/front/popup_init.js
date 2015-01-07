/**
 * Created by Lagunaby1 on 18.06.2014.
 */
function openLogin() {
$('#popup_login').togglePopup();
	$('input, select').styler();
}
function closeLogin() {
	$('#popup_login').togglePopup();
}
function openExercise() {
	$('#popup_add_exercise').togglePopup();
	setTimeout(function() {
		$('input, select').trigger('refresh');
	}, 1);
}
function closeExercise() {
	$('#popup_add_exercise').togglePopup();
}


function openProgress() {
	$('#popup_progress').togglePopup();
	setTimeout(function() {
		$('input, select').trigger('refresh');
	}, 1);
}
function closeProgress() {
	$('#popup_progress').togglePopup();
}

function openEditGoal() {
	$('#popup_edit_goal').togglePopup();
	setTimeout(function() {
		$('input, select').trigger('refresh');
	}, 1);
}
function closeEditGoal() {
	$('#popup_edit_goal').togglePopup();
}
