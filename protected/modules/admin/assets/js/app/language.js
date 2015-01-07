/**
 * Created with JetBrains PhpStorm.
 * User: once
 * Date: 11/12/12
 * Time: 3:59 PM
 * To change this template use File | Settings | File Templates.
 */
function changeLanguage(language) {
	$.get('/admin/default/changeLanguage?lang=' + language, function (response) {
		if (response) {
			document.location.href = document.location.href;
		}
	});
}
