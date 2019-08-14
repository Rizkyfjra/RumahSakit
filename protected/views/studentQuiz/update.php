<?php
/* @var $this StudentQuizController */
/* @var $model StudentQuiz */

$this->breadcrumbs=array(
	'Ulangan Siswa'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List StudentQuiz', 'url'=>array('index')),
	array('label'=>'Create StudentQuiz', 'url'=>array('create')),
	array('label'=>'View StudentQuiz', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage StudentQuiz', 'url'=>array('admin')),
);
?>

<h1>Update StudentQuiz <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>