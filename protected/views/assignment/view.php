<?php
/* @var $this AssignmentController */
/* @var $model Assignment */

if($mapel->moving_class == 1){
										$kelasnya = $mapel->name;
										$idkelasnya = $mapel->id;
										$path_nya = 'lesson/'.$idkelasnya;
									}else{
										$kelasnya = $mapel->name;
										$idkelasnya = $mapel->id;
										$path_nya = 'lesson/'.$idkelasnya;
									}

$this->breadcrumbs=array(
	$kelasnya=>array($path_nya."?type=tugas"),
	$model->title,
);
$cekTugas = StudentAssignment::model()->findByAttributes(array('assignment_id'=>$model->id,'student_id'=>Yii::app()->user->id));

// echo"<pre>";
// 	print_r($mapel);
// echo"</pre>";
/*$this->menu=array(
	array('label'=>'List Assignment', 'url'=>array('index')),
	array('label'=>'Create Assignment', 'url'=>array('create')),
	array('label'=>'Update Assignment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Assignment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Assignment', 'url'=>array('admin')),
);*/
$video=array('mp4', 'MP4', 'avi', 'flv', 'mkv');
//print_r($video);
?>
<div class="container">

<h1><?php echo $model->title; ?> </h1>
<?php if(!Yii::app()->user->YiiStudent){ ?>
	<p>
		<?php if($model->assignment_type == NULL){ ?>
			<?php echo CHtml::link('Edit <i class="fa fa-pencil-square-o"></i>', array('update','id'=>$model->id),array('class'=>'btn btn-primary'))?>
			<?php echo CHtml::link('Salin <i class="fa fa-files-o"></i>', array('copy','id'=>$model->id),array('class'=>'btn btn-warning'))?>
			<?php echo CHtml::link('Hapus <i class="fa fa-times"></i>', array('hapusoffline','id'=>$model->id,'type'=>1),array('class'=>'btn btn-danger',"confirm"=>"Anda yakin akan menghapus ini ?"))?>
		<?php }else{ ?>
			<?php echo CHtml::link('Edit <i class="fa fa-pencil-square-o"></i>', array('update','id'=>$model->id,'type'=>1),array('class'=>'btn btn-primary'))?>
			<?php echo CHtml::link('Hapus <i class="fa fa-times"></i>', array('hapusoffline','id'=>$model->id,'type'=>1),array('class'=>'btn btn-danger',"confirm"=>"Anda yakin akan menghapus ini ?"))?>
		<?php } ?>
	</p>
<?php } ?>
	<?php if($type == NULL && empty($model->assignment_type)){ ?>
	<div class="col-md-8">
	<div class="panel panel-info">
			<div class="panel-heading ">
			    <h3 class="panel-title">Batas Pengumpulan : <STRONG><?php echo date('d M Y (H:i)',strtotime($model->due_date));?></STRONG>
			    	<?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?> 
			    	<span class="pull-right"><?php echo CHtml::link("<i class='fa fa-pencil-square-o'></i> Edit", array("/assignment/update","id"=>$model->id));?></span>
			    	<?php } ?>
			    </h3>
			</div>
			<div class="panel-body">
				<p><?php echo $model->content;?></p>
				<?php if(!empty($model->file)){ ?>
					<?php $file_tugas = json_decode($model->file,true);?>
					<?php if(json_last_error() === JSON_ERROR_NONE){ ?>
						<?php foreach ($file_tugas as $nama) { ?>
						<?php $ext = pathinfo($nama, PATHINFO_EXTENSION);?>
						<p class="text-left"><span><?php echo CHtml::link('<i class="fa fa-download"></i> '.$nama, array('assignment/download', 'id'=>$model->id,'nama'=>$nama));?></span></p>
							<?php if($ext == 'jpg' || $ext == 'png' || $ext == 'png'){ ?>
								<img src="<?php echo Yii::app()->baseUrl;?>/images/assignment/<?php echo $model->id;?>/<?php echo $nama;?>" class="thumbnails" height="250px" width="400px">
							<?php }elseif(in_array($ext, $video)){ ?> 
								<div class="img-responsive">
									<center>
										<?php
											$this->widget('ext.jwplayer.Jwplayer',array(
											    'width'=>500,
											    'height'=>360,
											    'file'=>Yii::app()->baseUrl.'/images/assignment/'.$model->id.'/'.$nama, // the file of the player, if null we use demo file of jwplayer
											    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
											    'options'=>array(
											        'controlbar'=>'bottom'
											    )
											));
										?>
									</center>
								</div>
							<?php }elseif($ext == 'pdf'){ ?> 

								<div style="height:600px;">
								<?php

								Yii::app()->clientScript->registerCoreScript('jquery');

								$this->widget('ext.pdfJs.QPdfJs',array(
									'url'=>Yii::app()->baseUrl."/images/assignment/".$model->id."/".$nama,
									))
								?>
								</div>
							<?php } ?>
						<?php } ?>
					<?php }else{ ?>
						<?php $ext = pathinfo($model->file, PATHINFO_EXTENSION);?>
						<p class="text-left"><span><?php echo CHtml::link('<i class="fa fa-download"></i> '.$model->file, array('assignment/download', 'id'=>$model->id));?></span></p>
							<?php if($ext == 'jpg' || $ext == 'png' || $ext == 'png'){ ?>
								<img src="<?php echo Yii::app()->baseUrl;?>/images/assignment/<?php echo $model->id;?>/<?php echo $model->file;?>" class="thumbnails" height="250px" width="400px">
							<?php }elseif(in_array($ext, $video)){ ?> 
								<div class="img-responsive">
									<center>
										<?php
											$this->widget('ext.jwplayer.Jwplayer',array(
											    'width'=>500,
											    'height'=>360,
											    'file'=>Yii::app()->baseUrl.'/images/assignment/'.$model->id.'/'.$model->file, // the file of the player, if null we use demo file of jwplayer
											    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
											    'options'=>array(
											        'controlbar'=>'bottom'
											    )
											));
										?>
									</center>
								</div>
							<?php }elseif($ext == 'pdf'){ ?> 

								<div style="height:600px;">
								<?php

								Yii::app()->clientScript->registerCoreScript('jquery');

								$this->widget('ext.pdfJs.QPdfJs',array(
									'url'=>Yii::app()->baseUrl."/images/assignment/".$model->id."/".$model->file,
									))
								?>
								</div>
							<?php } ?>
						<?php } ?>
				<?php } ?>
			</div>
			<div class="panel-footer">
				<p class="text-right">Published by <STRONG><?php echo ucfirst($model->teacher->display_name);?></STRONG></p>
			</div>    
		</div>
	</div>
	<?php } ?>
	<?php if(empty($model->assignment_type)){ ?>
		<?php if(Yii::app()->user->YiiStudent){ ?>
			<?php if(empty($cekTugas)){ ?>
			<div class="col-md-8">
				<div class="panel panel-primary">
					<div class="panel-heading ">
					    <h3 class="panel-title">Upload Tugas</h3>
					</div>
					<div class="panel-body">
						<?php $this->renderPartial('/studentAssignment/_form', array('model'=>$studentAssignment,'tugas_id'=>$model->id,'type'=>$type)); ?>
					</div>   
				</div>
			</div>
			<?php } elseif(!empty($cekTugas)){ ?>
				<?php if($cekTugas->status == NULL){ ?>
					<div class="col-md-8">
						<div class="panel panel-primary">
							<div class="panel-heading ">
							    <h3 class="panel-title">Upload Tugas</h3>
							</div>
							<div class="panel-body">
								<p>Tugas Anda Sudah Tersimpan. Upload Lagi Jika Ingin Memperbaiki</p>
								<?php echo CHtml::link('Update Tugas', array('/studentAssignment/update','id'=>$cekTugas->id))?>
								<?php //$this->renderPartial('/studentAssignment/_form', array('model'=>$studentAssignment,'tugas_id'=>$model->id,'id_sa'=>$id_sa)); ?>
							</div>   
						</div>
					</div>
				<?php }elseif($cekTugas->score == NULL){?>
					<div class="col-md-8">
						<div class="panel panel-primary">
							<div class="panel-heading ">
							    <h3 class="panel-title">Upload Tugas</h3>
							</div>
							<div class="panel-body">
								<p>Anda Sudah Mengumpulkan Tugas Ini. Upload Lagi Jika Ingin Memperbaiki Sebelum Diberi Nilai</p>
								<?php echo CHtml::link('Update Tugas', array('/studentAssignment/update','id'=>$cekTugas->id))?>
								<?php //$this->renderPartial('/studentAssignment/_form', array('model'=>$studentAssignment,'tugas_id'=>$model->id,'id_sa'=>$id_sa)); ?>
							</div>   
						</div>
					</div>
				<?php } else { ?>
					<div class="col-md-8">
						<div class="panel panel-primary">
							<div class="panel-heading ">
							    <h3 class="panel-title">Upload Tugas</h3>
							</div>
							<div class="panel-body">
								<p>Anda Sudah Mengumpulkan Tugas Ini Dan Sudah Diberi Nilai</p>
							</div>   
						</div>
					</div> 
				<?php } ?>
				<script type="text/javascript">
					localStorage.clear();
					console.log(localStorage);
				</script>
			<?php } else { ?>
				
			<?php } ?>
		<?php } else {?>
			<div class="col-md-12">
				<hr>
				<h2>Tugas Siswa</h2>
				<div id="s-form" class="search-form col-md-3">
					<p><b>Cari berdasarkan</b></p>
					<?php $this->renderPartial('_search-tugas',array('id'=>$model->id
						)); 
					?>
				</div>
				<p></p>
				<?php 
					$tsiswa = $studentTasks->getData();
					//$tsiswa = $
				?>
				<table class="table table-bordered table-condensed table-striped table-responsive">
					<tr class="danger">
						<th class="text-center">No</th>
						<th class="text-center">Nama Siswa</th>
						<th class="text-center">Nama Pelajaran</th>
						<th class="text-center">Kelas</th>
						<th class="text-center">Batas Pengumpulan</th>
						<th class="text-center">Dikumpulkan Tanggal</th>
						<th class="text-center">Status</th>
						<th class="text-center">Nilai</th>
						<th class="text-center">Tepat Waktu</th>
						<th class="text-center">Aksi</th>
					</tr>
				<?php if(!empty($tsiswa)){ ?>
					<?php $no=1;?>
					<?php foreach ($tsiswa as $tugas) { ?>
						
						<tr class="active">
							<td class="text-center"><?php echo $no;?></td>
							<td><?php echo $tugas->student->display_name;?></td>
							<td><?php echo $tugas->teacher_assign->lesson->name;?></td>
							<td><?php echo $tugas->teacher_assign->lesson->class->name;?></td>
							<td><?php echo date('d M Y H:i:s',strtotime($model->due_date));?></td>
							<td><?php echo date('d M Y H:i:s',strtotime($tugas->created_at));?></td>
							<td>
								<?php
									
									if(!empty($tugas->score)){
										echo "Sudah Mengumpulkan dan Diberi Nilai";	
									} elseif(empty($tugas->score) && $tugas->status == NULL) {
										echo "Belum Mengumpulkan";
									} elseif(empty($tugas->score) && $tugas->status == 1) {
										echo "Sudah Mengumpulkan dan Belum Diberi Nilai";
									}	
								?>
							</td>
							<td><?php echo $tugas->score;?></td>
							<td>
								<?php
						
									if(!empty($model->due_date > $tugas->created_at)){
										echo "Ya";	
									} else {
										echo "Tidak";
									}
								?>
							</td>
							<td class="text-center"><?php echo CHtml::link("Lihat",array('studentAssignment/view','id'=>$tugas->id),array('class'=>'btn btn-success btn-xs','title'=>'Lihat Tugas'));?> 
							<?php echo CHtml::link("Nilai",array('studentAssignment/update','id'=>$tugas->id,'type'=>1),array('class'=>'btn btn-primary btn-xs','title'=>'Beri Nilai'));?></td>
							
						</tr>
						<?php $no++; ?>
		
					<?php } ?>
					<div class="text-center">
						<?php
						  $this->widget('CLinkPager', array(
						                'pages'=>$studentTasks->pagination,
						                ));
						?>
					</div>		
				<?php } ?>
				</table>
			</div>
		<?php } ?>
	<?php } else{ ?>
		<?php if(!Yii::app()->user->YiiStudent){ ?>
			<?php if(!empty($siswa)){ ?>
			<div class="col-md-6">
				<?php $url_tugas = Yii::app()->createUrl('/assignment/addMark/'.$model->id);?>
			<!-- <form method="post" action="<?php //echo Yii::app()->baseUrl;?>/assignment/addMark/<?php echo $model->id;?>"> -->
				<form method="post" action="<?php echo $url_tugas;?>" onsubmit="return confirm('Yakin selesai menambahkan nilai ?');">
				<p class="text-right"><input type="submit" value="Simpan" class="btn btn-success"></p>
				<input type="hidden" name="assignment_id" value="<?php echo $model->id;?>">
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
										$cekNilai = OfflineMark::model()->findByAttributes(array('student_id'=>$sw->id,'assignment_id'=>$model->id));
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
	<?php } ?>	
</div>
