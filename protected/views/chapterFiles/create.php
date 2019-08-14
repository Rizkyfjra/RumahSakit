<?php
/* @var $this ChapterFilesController */
/* @var $model ChapterFiles */

$this->breadcrumbs=array(
	'Chapter Files'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List ChapterFiles', 'url'=>array('index')),
	array('label'=>'Manage ChapterFiles', 'url'=>array('admin')),
);
?>

<h1>Create ChapterFiles</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>