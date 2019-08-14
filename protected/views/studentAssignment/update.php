<?php
/* @var $this StudentAssignmentController */
/* @var $model StudentAssignment */

$this->breadcrumbs=array(
	'Tugas Siswa'=>array('index'),
	$model->student->display_name=>array('view','id'=>$model->id),
	'Update',
);

/*$this->menu=array(
	array('label'=>'List StudentAssignment', 'url'=>array('index')),
	array('label'=>'Create StudentAssignment', 'url'=>array('create')),
	array('label'=>'View StudentAssignment', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage StudentAssignment', 'url'=>array('admin')),
);*/
?>

<h2>Tugas <?php echo $model->teacher_assign->title; ?></h2>
<h3><STRONG>Nama Siswa : <?php echo $model->student->display_name; ?></STRONG></h3>
<?php $this->renderPartial('_form', array('model'=>$model,'type'=>$type)); ?>