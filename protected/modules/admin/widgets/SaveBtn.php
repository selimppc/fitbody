<?php
/**
 * Created by JetBrains PhpStorm.
 * User: foo
 * Date: 3/13/14
 * Time: 8:28 AM
 * To change this template use File | Settings | File Templates.
 */
class SaveBtn extends CWidget {

	public function init()
	{
		Yii::app()->clientScript->registerScript('primary_edit_btn_options',"
			$('#primary_edit_btn_options li').click(function (e){
				e.preventDefault();
				var key = $(this).data('key');
				$.cookie('primary_edit_btn',key,{experience: 30});
				switch(key) {
					case 's':
						$('#save_btn').text('Save').attr('name','save');
						break;
					case 'sc':
						$('#save_btn').text('Save & Close').attr('name','save_and_close');
						break;
					case 'san':
						$('#save_btn').text('Save & Add new').attr('name','save_and_add');
						break;
				}
				$('#save_btn').trigger('click');
			});
		");
	}

	public function run()
	{
		if(isset($_COOKIE['primary_edit_btn'])) {
			switch($_COOKIE['primary_edit_btn']) {
				case 's':
					$btn = '<button id="save_btn" class="btn btn-info" name="save">Сохранить</button>';
					break;
				case 'sc':
					$btn = '<button id="save_btn" class="btn btn-info" name="save_and_close">Сохранить и закрыть</button>';
					break;
				case 'san':
					$btn = '<button id="save_btn" class="btn btn-info" name="save_and_add">Сохранить и добавить</button>';
					break;
			}
		} else {
			$btn = '<button id="save_btn" class="btn btn-info" name="save_and_close">Сохранить и закрыть</button>';
		}
		echo '
                    <div class="btn-group">
						'.$btn.'
						<a data-toggle="dropdown" class="btn btn-info dropdown-toggle"><span class="caret"></span></a>
						<ul id="primary_edit_btn_options" class="dropdown-menu">
							<li data-key="s"><a href="#">Save</a></li>
							<li data-key="sc"><a href="#">Save & Close</a></li>
							<li data-key="san"><a href="#">Save & Add new</a></li>
						</ul>
					</div>';
	}
}