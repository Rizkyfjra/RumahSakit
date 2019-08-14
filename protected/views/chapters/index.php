<?php
/* @var $this ChaptersController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Chapters',
);

/*$this->menu=array(
	array('label'=>'Create Chapters', 'url'=>array('create')),
	array('label'=>'Manage Chapters', 'url'=>array('admin')),
);*/
?>

<h1>Materi</h1>
<table class='table table-hover table-responsive'>
	<th>ID</th>
	<th>Mata Pelajaran</th>
	<th>Title</th>
	<th>Dibuat Tanggal</th>
	<th></th>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
</table>
