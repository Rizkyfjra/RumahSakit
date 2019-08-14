<?php
/* @var $this ChaptersController */
/* @var $model Chapters */

$this->breadcrumbs=array(
	'Materi'=>array('index'),
	'Tambah',
);

/*$this->menu=array(
	array('label'=>'List Chapters', 'url'=>array('index')),
	array('label'=>'Manage Chapters', 'url'=>array('admin')),
);*/
?>

<h1>Tambah Materi</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'model2'=>$model2,'lessons'=>$lessons)); ?>