<?php
/* @var $this AssignmentController */
/* @var $model Assignment */

$this->breadcrumbs=array(
	'Tugas'=>array('index'),
	'Tambah',
);

/*$this->menu=array(
	array('label'=>'List Assignment', 'url'=>array('index')),
	array('label'=>'Manage Assignment', 'url'=>array('admin')),
);*/
?>

<h1>Buat Tugas</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'lessons'=>$lessons)); ?>