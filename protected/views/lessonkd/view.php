<?php
/* @var $this LessonKdController */
/* @var $model LessonKd */

$this->breadcrumbs=array(
	'Lesson Kds'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List LessonKd', 'url'=>array('index')),
	array('label'=>'Create LessonKd', 'url'=>array('create')),
	array('label'=>'Update LessonKd', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete LessonKd', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage LessonKd', 'url'=>array('admin')),
);
?>

<h1>View LessonKd #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'title',
		'lesson_id',
		'description',
		'created_at',
		'updated_at',
		'created_by',
		'updated_by',
		'trash',
		'semester',
		'tahun_ajaran',
	),
)); ?>
