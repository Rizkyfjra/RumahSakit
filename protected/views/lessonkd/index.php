<?php
/* @var $this LessonKdController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Lesson Kds',
);

$this->menu=array(
	array('label'=>'Create LessonKd', 'url'=>array('create')),
	array('label'=>'Manage LessonKd', 'url'=>array('admin')),
);
?>

<h1>Lesson Kds</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
