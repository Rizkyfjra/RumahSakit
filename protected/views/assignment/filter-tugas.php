<?php
/* @var $this AssignmentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tugas',
);

$this->menu=array(
	array('label'=>'Create Assignment', 'url'=>array('create')),
	array('label'=>'Manage Assignment', 'url'=>array('admin')),
);
?>

<h1>Tugas</h1>
<?php if(!Yii::app()->user->YiiStudent){ ?>
<p class="text-right"><?php echo CHtml::link('Tambah Tugas', array('create'), array('class'=>'btn btn-danger'))?></p>
<?php } ?>
<div id="st-form" class="search-form col-md-3">
	<p><b>Cari berdasarkan</b></p>
	<?php $this->renderPartial('_search-teacher'); 
	?>
</div>
<div class="col-md-12">
<div class="row">
<?php if(!empty($dataProvider->getData())){ ?>
	<?php $assign = $dataProvider->getData();?>
	<?php foreach ($assign as $v) { ?>
		<div class="col-md-4">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo CHtml::link(CHtml::encode($v->title), array('assignment/view', 'id'=>$v->id)); ?></h3>
				</div>
				<div class="panel-body">
					<table class="table table-user-information">
						<tbody>
							<tr>
								<th>Pelajaran</th>
								<td><?php echo $v->lesson->name; ?></td>
							</tr>
							<tr>
								<th>Kelas</th>
								<td><?php echo $v->lesson->class->name; ?></td>
							</tr>
							<tr>
								<th>Batas Pengumpulan</th>
								<td><?php echo CHtml::encode($v->due_date);?></td>
							</tr>
							<?php
								$status = null;
								if (Yii::app()->user->YiiStudent) {
									$current_user = Yii::app()->user->id;
									$assignment_id = $key->id;
									$modelCek=StudentAssignment::model()->findAll(array("condition"=>"assignment_id = $assignment_id and student_id = $current_user"));
									if (!empty($modelCek)){
										$status = "<span class='glyphicon glyphicon-ok'></span>";
									} else {
										$status = "<span class='glyphicon glyphicon-remove'></span>";
									}
								
								$lesson = $key->lesson;
								$class = $lesson->class;
								}
							?>
							<?php if (Yii::app()->user->YiiStudent) { ?>
							<tr>
								<th>Status</th>
								<td><?php echo $status;?></td>
							</tr>
							<?php }else{ ?>
								<?php
									$cek=StudentAssignment::model()->findAll(array('condition'=>'assignment_id = '.$v->id.' and status = 1 and score is null'));
									$total=count($cek);
								?>
								<tr>
									<th>Keterangan</th>
									<td><?php echo CHtml::link('Belum Dinilai <span class="badge">'.$total.'</span>', array('view','id'=>$v->id, 'type'=>1),array('class'=>'btn btn-warning'));?></td>
								</tr>
							<?php } ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php } ?>
<?php } ?>
</div>

<div class="text-center">
	<?php
	  $this->widget('CLinkPager', array(
	                'pages'=>$dataProvider->pagination,
	                ));
	?>
</div>	
</div>