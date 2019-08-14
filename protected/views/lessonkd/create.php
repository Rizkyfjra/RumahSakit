<?php
/* @var $this LessonKdController */
/* @var $model LessonKd */

$this->breadcrumbs=array(
	'Lesson Kds'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List LessonKd', 'url'=>array('index')),
	array('label'=>'Manage LessonKd', 'url'=>array('admin')),
);
?>

<h1>Create LessonKd</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>