<?php
/* @var $this SkillController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Keterampilan',
);

/*$this->menu=array(
	array('label'=>'Create Skill', 'url'=>array('create')),
	array('label'=>'Manage Skill', 'url'=>array('admin')),
);*/
?>

<h1>Keterampilan</h1>
<div class="table-responsive">
	<table class="table table-bordered table-hover well">
		<tr>
			<th>NAMA</th>
			<th>PELAJARAN</th>
			<th>KELAS</th>
		</tr>
		<?php $this->widget('zii.widgets.CListView', array(
			'dataProvider'=>$dataProvider,
			'itemView'=>'_view',
		)); ?>
	</table>
</div>
