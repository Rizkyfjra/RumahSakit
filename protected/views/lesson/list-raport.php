<?php
/* @var $this LessonController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pelajaran',
	'Raport'
);

/*$this->menu=array(
	array('label'=>'Create Lesson', 'url'=>array('create')),
	array('label'=>'Manage Lesson', 'url'=>array('admin')),
);*/
?>

<h1>Mata Pelajaran</h1>
<div class="col-md-12">
<div class="row">
<?php if(!empty($dataProvider->getData())){ ?>
	<?php $lesson = $dataProvider->getData();?>
	<?php foreach ($lesson as $value) { ?>
		<div class="col-md-4">
			<div class="panel panel-info">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo CHtml::link(CHtml::encode($value->name), array('rekapNilai', 'id'=>$value->id)); ?></h3>
				</div>
				<div class="panel-body">
					<table class="table table-user-information">
						<tbody>
							<tr>
								<th>Pelajaran</th>
								<td><?php echo $value->name; ?></td>
							</tr>
							<tr>
								<th>Kelas</th>
								<td><?php echo $value->class->name; ?></td>
							</tr>
							<?php
								$cek=User::model()->findAll(array('condition'=>'class_id = '.$value->class->id));
								$total=count($cek);
							?>
							<?php if(!Yii::app()->user->YiiStudent){ ?>
							<tr>
								<th>Jumlah Siswa</th>
								<td>
									<?php echo CHtml::link($total, array('/clases/view','id'=>$value->class->id));?>
								</td>
							</tr>
							<?php }else{ ?>
								<?php
									$cekTugas=Assignment::model()->findAll(array('condition'=>'lesson_id = '.$value->id.' and assignment_type is null and recipient is null or recipient = '.Yii::app()->user->id));
									$bn=0;
									foreach ($cekTugas as $tgs) {
										$cekTS = StudentAssignment::model()->findByAttributes(array('student_id'=>Yii::app()->user->id, 'assignment_id'=>$tgs->id));
										if(empty($cekTS)){
											$bn++;
										}
									}

									$bk=0;
									$cekKuis=Quiz::model()->findAll(array('condition'=>'lesson_id = '.$value->id.' and status is not null'));
									foreach ($cekKuis as $ks) {
										$cekKS = StudentQuiz::model()->findByAttributes(array('student_id'=>Yii::app()->user->id, 'quiz_id'=>$ks->id));
										if(empty($cekKS)){
											$bk++;
										}
									}
								?>
								<tr>
									<th>Keterangan</th>
									<td>
										<p><?php echo CHtml::link('Tugas Belum Dikerjakan <span class="badge">'.$bn.'</span>', array('/assignment/index','type'=>1,'l_id'=>$value->id));?></p>
										<p><?php echo CHtml::link('Ulangan Belum Dikerjakan <span class="badge">'.$bk.'</span>', array('/quiz/index','type'=>1));?></p>
									</td>
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
