/**
 * Created with JetBrains PhpStorm.
 * User: viktor
 * Date: 11/27/13
 * Time: 4:53 PM
 * To change this template use File | Settings | File Templates.
 */

var Registry = {

	deleteFolderRequest: new request('admin.modules.registry.controllers.DataController','delete').listen(function (data) {
		window.location.href = data;
	}),

	changeLanguageRequest: new request('admin.modules.registry.controllers.DataController','changeLanguage').listen(function (data) {}),

	_init: function () {
		$(document).on('click','#add_new_element_button',function (e) {
			e.preventDefault();
			if(!$(this).hasClass('active'))
				$('#hide_form_add_new_element').click();
			else {
				var form = $('.add_new_element_form');
				form.show(300);
				form.find('input[type=text]:first').focus();
			}
		});

		$(document).on('click','#hide_form_add_new_element',function (e) {
			e.preventDefault();
			$('.add_new_element_form').hide(300).find('input').each(function () {$(this).val('')});
			$("#add_new_element_button").removeClass('active');
		});

		$(document).on('click','#add_new_folder_button',function (e) {
			e.preventDefault();
			var form = $('.add_new_folder_form');
			if(!$(this).hasClass('active'))
				$('#hide_form_add_new_folder').click();
			else
			{
				if(form.css('display')!='none')
					form.hide(100,function () {
						form.show(300).find('#FolderForm_parent_category_id').val('').end().find('input[type=text]:first').focus();
					});
				else
					form.show(300).find('#FolderForm_parent_category_id').val('');
			}
		});

		$(document).on('click','#hide_form_add_new_folder',function (e) {
			e.preventDefault();
			$('#create_child_folder').removeClass('active');
			$('#add_new_folder_button').removeClass('active');
			$('.add_new_folder_form').hide(300).find('input').each(function () {$(this).val('')});
		});


		$(document).on('click','.add_folder_a',function (e) {
			e.preventDefault();
			$('#create_child_folder').removeClass('active');
			$('#add_new_folder_button').removeClass('active');
			var self = this;
			var form = $('.add_new_folder_form');
			if(form.css('display')!='none')
				form.hide(100,function () {
					form.show(300, function () {
						form.find('input[type=text]:first').focus();
					}).find('#FolderForm_parent_category_id').val($(self).data('parent-id'));

				});
			else
				form.show(300).find('#FolderForm_parent_category_id').val($(self).data('parent-id')).end().find('input[type=text]:first').focus();;
		});

		$(document).on('click','#create_child_folder',function (e) {
			e.preventDefault();
			if(!$(this).hasClass('active'))
				$('#hide_form_add_new_folder').click();
			else
				$('.add_new_folder_form').show(300).find('#FolderForm_parent_category_id').val($(this).data('current')).end().find('input[type=text]:first').focus();
		});

		$(document).on('click','.delete_folder_a',function (e) {
			e.preventDefault();
			var id = $(this).data('parent-id');
			if(confirm('You really want to delete the folder?')) {
				Registry.deleteFolderRequest.data({id:id,url:window.location.href}).send();
			}
		});

		$(document).on('change','#change_language',function (e) {
			Registry.changeLanguage($(this).data('folder-id'),$(this).val());
		});

		setTimeout(function () {
			$('#other_language_label').fadeOut(400,function () {
				$('#other_language_label').hide();
				$('#language_div').removeClass('alert');
			});
		},1500);

		$('[data-redactorin=true]').redactor({
			minHeight: 200,
			buttons: ['html', '|', 'formatting', '|', 'bold', 'italic', 'deleted', '|',
				'unorderedlist', 'orderedlist', 'outdent', 'indent', '|',
				'image', 'table', 'link', '|', 'alignment', '|', 'horizontalrule'],
			imageUpload: '/admin/registry/data/uploader.html?debug=0'
		});

		$(document).on('click','.delete_element',function (e) {
			e.preventDefault();
			window.location.href = '/admin/registry/data/delete.html?id='+$(this).data('id')+'&r='+window.location.href;
		});
	},

	changeLanguage: function (folderId,language) {
		$('[data-language]').hide(0, function () {
			$('div[data-language='+language+']').show(200);
		});
		Registry.changeLanguageRequest.data({id:folderId,lang:language}).send();
	}
};

$(function () {
	Registry._init();
});