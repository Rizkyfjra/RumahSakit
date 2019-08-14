<?php
/* @var $this ClasesController */
/* @var $model Clases */

$this->breadcrumbs=array(
	'Kelas'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Clases', 'url'=>array('index')),
	array('label'=>'Create Clases', 'url'=>array('create')),
	array('label'=>'View Clases', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Clases', 'url'=>array('admin')),
);
?>

<h1>Update Clases <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>