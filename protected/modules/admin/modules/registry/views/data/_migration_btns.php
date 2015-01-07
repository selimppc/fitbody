<?php if(Yii::app()->static->isExistsNew && Yii::app()->user->role >= Yii::app()->static->accessLevelEdit): ?>
	<div class="btn-group">
		<button onclick="window.location.href = '/admin/registry/migrate.html?r='+window.location.href;" class="btn btn-warning">Apply migrations</button>
		<button onclick="window.location.href = '/admin/registry/migrate/make.html?r='+window.location.href;" class="btn btn-danger">Clear migrations</button>
	</div>
<?php endif; ?>