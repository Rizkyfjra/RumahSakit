<?php
/* @var $this StudentQuizController */
/* @var $model StudentQuiz */

$this->breadcrumbs=array(
	'Ulangan Siswa'=>array('index'),
	'Tambah',
);

$this->menu=array(
	array('label'=>'List StudentQuiz', 'url'=>array('index')),
	array('label'=>'Manage StudentQuiz', 'url'=>array('admin')),
);
?>

<h1>Create StudentQuiz</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>