<?php
/* @var $this LessonKdController */
/* @var $model LessonKd */

$this->breadcrumbs=array(
	'Lesson Kds'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List LessonKd', 'url'=>array('index')),
	array('label'=>'Create LessonKd', 'url'=>array('create')),
	array('label'=>'View LessonKd', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage LessonKd', 'url'=>array('admin')),
);
?>

<h1>Update LessonKd <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>