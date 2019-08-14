<?php
/* @var $this ClasesController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Kelas',
);

$this->menu=array(
	array('label'=>'Create Clases', 'url'=>array('create')),
	array('label'=>'Manage Clases', 'url'=>array('admin')),
);
?>
<h1>Daftar Kelas</h1>
<?php if(Yii::app()->user->YiiAdmin){ ?>
<p class="text-right"><?php echo CHtml::link('Tambah Kelas', array('addExcel'),array('class'=>'btn btn-success'));?></p>
<p class="text-right"><?php echo CHtml::link('Import Nilai UAS', array('lesson/importnilaiuas'),array('class'=>'btn btn-danger'));?></p>
<?php } ?>
<table class='table table-bordered table-hover table-responsive table-responsive well'>
	<!-- <th>ID</th> -->
	<th>Nama Kelas</th>
	<th>Penanggung Jawab</th>
	<th>ID Kelas</th>
	<th></th>
<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
</table>
