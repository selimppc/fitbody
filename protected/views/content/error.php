<?php
/* @var $this SiteController */
/* @var $error array */

$this->pageTitle=Yii::app()->name . ' - Error';
$this->breadcrumbs=array(
	'Error',
);
?>

<div class="main inner_page_nosplit not_found">
    <h1><?php echo $code; ?>. Страница не найдена</h1>
    <p><?php echo YII_DEBUG ? CHtml::encode($message) : ''; ?></p>
    <p>Страница, которую вы искали, возможно была удалена или перенесена в другой раздел сайта!</p>
    <p>Перейти на <a href="/">главную страницу</a> сайта или посмотрите другие разделы сайта:</p>
</div>