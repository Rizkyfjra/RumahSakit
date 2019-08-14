<?php
/* @var $this LessonController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Pelajaran',
);

/*$this->menu=array(
	array('label'=>'Create Lesson', 'url'=>array('create')),
	array('label'=>'Manage Lesson', 'url'=>array('admin')),
);*/
$lesson = $dataProvider->getData();
?>

<!-- <h1>Mata Pelajaran</h1> -->
<div class="row">
	<div id="ls-form" class="search-form col-md-3">
	<p><b>Cari berdasarkan</b></p>
	<?php $this->renderPartial('_search-new'); 
	?>
</div>

<?php if(Yii::app()->user->YiiAdmin){ ?>
<p class="text-right"><?php echo CHtml::link('Tambah <i class="fa fa-plus"></i>', array('create'),array('class'=>'btn btn-success','title'=>'Tambah Pelajaran'));?></p>
<?php } ?>
<div class="col-md-12">
<?php if(!empty($lesson) && (!Yii::app()->user->YiiAdmin)){ ?>
	<?php foreach ($lesson as $value) { ?>
		<?php
			$list_siswa = LessonMc::model()->findAll(array('condition'=>'lesson_id = '.$value->id));
			$list_big = '';
			$final_list = array();
			if(!empty($list_siswa)){
				foreach ($list_siswa as $ls) {
					array_push($final_list, $ls->user_id);
				}
				$list_big = implode(',', $final_list);
			}
			if(!empty($list_big)){
				$cek=User::model()->findAll(array('condition'=>'class_id IN ('.$list_big.')'));
			}else{
				$cek=NULL;
			}
			
			
			if($value->moving_class == 1){
				$nama_kelas = $value->grade->name;
			}else{
				$nama_kelas = $value->class->name;
			}
			
			$total=count($cek);
		?>
<div class="row panel-medidu" style="padding:10px;text-align:center;">		
		<?php if(stristr(strtolower($value->name), 'matematika')){ ?>
				<div class="con-pelajaran">
					<a href="<?php echo Yii::app()->baseUrl;?>/lesson/<?php echo $value->id?>">
					<div class="panel-pelajaran bg2"></div>
					<div class="panel-nama">
						<?php echo CHtml::link(CHtml::encode($value->name." ".$nama_kelas), array('view', 'id'=>$value->id)); ?>
						<?php if(!Yii::app()->user->YiiStudent){ ?>
						<!-- <span class="badge pull-right">
								<small><?php //echo CHtml::link($total, array('/clases/view','id'=>$value->class->id));?> Siswa</small>			
						</span> -->
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
							<?php echo CHtml::link('<span class="badge pull-right">'.$bn.' Tugas</span>', array('/assignment/index','type'=>1,'l_id'=>$value->id));?>
							<?php echo CHtml::link('<span class="badge pull-right">'.$bk.' Ulangan</span>', array('/quiz/index','type'=>1));?>
						<?php } ?>	
					</div>
					</a>
				</div>
		<?php }elseif (stristr(strtolower($value->name), 'ipa')) { ?>
				<div class="con-pelajaran">
					<a href="<?php echo Yii::app()->baseUrl;?>/lesson/<?php echo $value->id?>">
					<div class="panel-pelajaran bg4"></div>
					<div class="panel-nama">
						<?php echo CHtml::link(CHtml::encode($value->name." ".$nama_kelas), array('view', 'id'=>$value->id)); ?>
						<?php if(!Yii::app()->user->YiiStudent){ ?>
						<!-- <span class="badge pull-right">
								<small><?php //echo CHtml::link($total, array('/clases/view','id'=>$value->class->id));?> Siswa</small>			
						</span> -->
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
							<?php echo CHtml::link('<span class="badge pull-right">'.$bn.' Tugas</span>', array('/assignment/index','type'=>1,'l_id'=>$value->id));?>
							<?php echo CHtml::link('<span class="badge pull-right">'.$bk.' Ulangan</span>', array('/quiz/index','type'=>1));?>
						<?php } ?>	
					</div>
					</a>
				</div>
		<?php }elseif(stristr(strtolower($value->name), 'komputer')) { ?>
				<div class="con-pelajaran">
					<a href="<?php echo Yii::app()->baseUrl;?>/lesson/<?php echo $value->id?>">
					<div class="panel-pelajaran bg3"></div>
					<div class="panel-nama">
						<?php echo CHtml::link(CHtml::encode($value->name." ".$nama_kelas), array('view', 'id'=>$value->id)); ?>
						<?php if(!Yii::app()->user->YiiStudent){ ?>
						<!-- <span class="badge pull-right">
								<small><?php //echo CHtml::link($total, array('/clases/view','id'=>$value->class->id));?> Siswa</small>			
						</span> -->
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
							<?php echo CHtml::link('<span class="badge pull-right">'.$bn.' Tugas</span>', array('/assignment/index','type'=>1,'l_id'=>$value->id));?>
							<?php echo CHtml::link('<span class="badge pull-right">'.$bk.' Ulangan</span>', array('/quiz/index','type'=>1));?>
						<?php } ?>	
					</div>
					</a>
				</div>			
		<?php }else{ ?>
				<div class="con-pelajaran">
					<a href="<?php echo Yii::app()->baseUrl;?>/lesson/<?php echo $value->id?>">
					<div class="panel-pelajaran bg1"></div>
					<div class="panel-nama">
						<?php echo CHtml::link(CHtml::encode($value->name." ".$nama_kelas), array('view', 'id'=>$value->id)); ?>
						<?php if(!Yii::app()->user->YiiStudent){ ?>
						<!-- <span class="badge pull-right">
								<small><?php //echo CHtml::link($total, array('/clases/view','id'=>$nama_kelas));?> Siswa</small>			
						</span> -->
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
							<?php echo CHtml::link('<span class="badge pull-right">'.$bn.' Tugas</span>', array('/assignment/index','type'=>1,'l_id'=>$value->id));?>
							<?php echo CHtml::link('<span class="badge pull-right">'.$bk.' Ulangan</span>', array('/quiz/index','type'=>1));?>
						<?php } ?>	
					</div>
					</a>
				</div>
		<?php } ?>
		
	<?php } ?>
<?php } else if (!empty($lesson) && (Yii::app()->user->YiiAdmin)) {?>
	<table class="table table-bordered table-condensed table-responisve well">
		<tbody>
			<tr>
				<th class="info">No</th>
				<th class="info">ID Pelajaran</th>
				<th class="info">Nama Pelajaran</th>
				<th class="info">Guru</th>
				<th class="info">Kelas</th>
				<th class="info">Total siswa</th>						
			</tr>
	<?php 
	$no = 1;
	foreach ($lesson as $value) { ?>
	<?php
			$list_siswa = LessonMc::model()->findAll(array('condition'=>'lesson_id = '.$value->id));
			$list_big = '';
			$final_list = array();
			if(!empty($list_siswa)){
				foreach ($list_siswa as $ls) {
					array_push($final_list, $ls->user_id);
				}
				$list_big = implode(',', $final_list);
			}
			if(!empty($list_big)){
				$cek=User::model()->findAll(array('condition'=>'class_id IN ('.$list_big.')'));
			}else{
				$cek=NULL;
			}

			if($value->moving_class == 1){
				$nama_kelas = $value->grade->name;
			}else{
				$nama_kelas = $value->class->name;
			}

			if(!empty($value->user_id)){
				$cekGuru=User::model()->findAll(array('condition'=>'id = '.$value->user_id));
			}else{
				$cekGuru=NULL;
			}
			$lsn_url = Yii::app()->createUrl('/lesson/view/'.$value->id);
			$total=count($list_siswa);
	?>
			<tr>
				<td><?php echo $no; ?></td>
				<td><a href="<?php echo $lsn_url;?>"><?php echo $value->id; ?></a></td>
				<td><a href="<?php echo $lsn_url;?>"><?php echo $value->name; ?></a></td>
				<td><?php echo $cekGuru[0]->display_name; ?></td>
				<td><?php echo $nama_kelas; ?></td>
				<td><?php echo $total; ?></td>
			</tr>
	<?php $no++; ?>
	<?php } ?>
		</tbody>
	</table>
<?php }?>
	<div class="text-center">
		<?php
		  $this->widget('CLinkPager', array(
		                'pages'=>$dataProvider->pagination,
		                ));
		?>
	</div>	
</div>	
</div>