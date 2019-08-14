<?php
/* @var $this ChapterFilesController */
/* @var $model ChapterFiles */

$this->breadcrumbs=array(
	'Chapter Files'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List ChapterFiles', 'url'=>array('index')),
	array('label'=>'Create ChapterFiles', 'url'=>array('create')),
	array('label'=>'View ChapterFiles', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage ChapterFiles', 'url'=>array('admin')),
);
?>

<h1>Update ChapterFiles <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>