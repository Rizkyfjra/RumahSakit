<?php
/* @var $this ChapterFilesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Chapter Files',
);

$this->menu=array(
	array('label'=>'Create ChapterFiles', 'url'=>array('create')),
	array('label'=>'Manage ChapterFiles', 'url'=>array('admin')),
);
?>

<h1>Chapter Files</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
