<?php
/* @var $this SkillController */
/* @var $model Skill */

$this->breadcrumbs=array(
	'Keterampilan'=>array('index'),
	$model->name,
);

/*$this->menu=array(
	array('label'=>'List Skill', 'url'=>array('index')),
	array('label'=>'Create Skill', 'url'=>array('create')),
	array('label'=>'Update Skill', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Skill', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Skill', 'url'=>array('admin')),
);*/
?>

<h1><?php echo $model->name; ?></h1>
<?php if(!Yii::app()->user->YiiStudent){ ?>
	<?php if(!empty($siswa)){ ?>
	<div class="col-md-6">
		<p><?php echo CHtml::link('Edit <i class="fa fa-edit"></i>', array('update','id'=>$model->id),array('class'=>'btn btn-primary','title'=>'Edit'));?><?php echo CHtml::link('Hapus <i class="fa fa-times"></i>', array('hapus','id'=>$model->id),array('class'=>'btn btn-danger','title'=>'Hapus','confirm'=>'Yakin Menghapus Ini ?'));?></p>
		<?php $url_skill = Yii::app()->createUrl('/skill/addMark/'.$model->id);?>
	<!-- <form method="post" action="<?php //echo Yii::app()->baseUrl;?>/assignment/addMark/<?php //echo $model->id;?>"> -->
		<form method="post" action="<?php echo $url_skill;?>" onsubmit="return confirm('Yakin selesai menambahkan nilai ?');">
		<p class="text-right"><input type="submit" value="Simpan" class="btn btn-success"></p>
		<input type="hidden" name="skill_id" value="<?php echo $model->id;?>">
		<input type="hidden" name="lesson_id" value="<?php echo $model->lesson_id;?>">
		<table class="table table-bordered table-hovered well table-responsive">
			<tbody>
				<tr>
					<th>No</th>
					<th>Nama Siswa</th>
					<th>Nilai</th>
					<th></th>
				</tr>
				<?php $no = 1;?>
				<?php foreach ($siswa as $sw) { ?>
					<tr>
						<td><?php echo $no;?></td>
						<td><input type="hidden" name="student_id[]" value="<?php echo $sw->id;?>"><b><?php echo CHtml::encode($sw->display_name);?></b></td>
						<td class="info">
							<?php 
								$cekNilai = StudentSkill::model()->findByAttributes(array('student_id'=>$sw->id,'skill_id'=>$model->id));
								if(!empty($cekNilai)){
									echo $cekNilai->score;
								}
							?>
						</td>
						<td><input type="number" name="score[]" class="form-control"></td>
					</tr>
					<?php $no++; ?>
				<?php } ?>
			</tbody>
		</table>
	</form>
	</div>
	<?php } ?>
<?php } ?>	
<?php /*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'lesson_id',
		'trash',
	),
));*/ ?>
