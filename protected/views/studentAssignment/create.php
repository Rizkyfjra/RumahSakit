<?php
/* @var $this StudentAssignmentController */
/* @var $model StudentAssignment */

$this->breadcrumbs=array(
	'Tugas Siswa'=>array('index'),
	'Tambah',
);

$this->menu=array(
	array('label'=>'List StudentAssignment', 'url'=>array('index')),
	//array('label'=>'Manage StudentAssignment', 'url'=>array('admin')),
);
?>

<h1>Form Upload Tugas</h1>

<?php $this->renderPartial('_form', array('model'=>$model,'tugas_id'=>$tugas_id)); ?>