<?php
/* @var $this ClasesController */
/* @var $model Clases */

$this->breadcrumbs=array(
	'Kelas'=>array('index'),
	'Tambah',
);

$this->menu=array(
	array('label'=>'List Clases', 'url'=>array('index')),
	array('label'=>'Manage Clases', 'url'=>array('admin')),
);
?>

<h1>Create Clases</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>