<?php
/* @var $this LessonController */
/* @var $model Lesson */

$this->breadcrumbs=array(
	'Pelajaran'=>array('index'),
	'Tambah',
);

$this->menu=array(
	array('label'=>'List Lesson', 'url'=>array('index')),
	array('label'=>'Manage Lesson', 'url'=>array('admin')),
);
?>

<h1>Create Lesson</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>