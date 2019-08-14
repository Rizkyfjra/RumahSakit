<?php
/* @var $this AnnouncementsController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pengumuman',
);

/*$this->menu=array(
	array('label'=>'Create Announcements', 'url'=>array('create')),
	array('label'=>'Manage Announcements', 'url'=>array('admin')),
);*/
?>
<H1>Pengumuman</H1>
<?php if(Yii::app()->user->YiiKepsek || Yii::app()->user->YiiAdmin){ ?>
	<p class="text-right"><?php echo CHtml::link('Tambah Pengumuman', array('create'),array('class'=>'btn btn-primary'));?></p>
<?php } ?>
<table class="table table-hover table-responsive">
<th>ID</th>
<th>Nama Pengumuman</th>
<th>Content</th>
<th>Dibuat Tanggal</th>
<?php if(Yii::app()->user->YiiAdmin){ ?>
<th></th>
<?php } ?>
<?php if(!empty($dataProvider->getData())){?>
	<?php $anc = $dataProvider->getData();?>
	<?php foreach ($anc as $value) { ?>
		<tr>
			<td><?php echo CHtml::link($value->id, array('view', 'id'=>$value->id));?></td>
			<td><?php echo CHtml::link($value->title, array('view', 'id'=>$value->id));?></td>
			<td><?php echo $value->content;?></td>
			<td><?php echo $value->created_at;?></td>
		</tr>
	<?php } ?>
<?php } ?>
<?php /*$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
));*/ ?>
</table>