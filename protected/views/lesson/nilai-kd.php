<?php
if($model->moving_class == 1){
										$kelasnya = $model->name;
										$idkelasnya = $model->id;
										$path_nya = 'lesson/'.$idkelasnya;
									}else{
										$kelasnya = $model->name;
										$idkelasnya = $model->id;
										$path_nya = 'lesson/'.$idkelasnya;
									}


$this->breadcrumbs=array(
	$kelasnya=>array($path_nya)
);?>
<?php

		$kelasnya = $model->name;
		$idkelasnya = $model->id;
		$path_nya = 'lesson/'.$idkelasnya;
?>
<div class="container">
<div class="col-md-12">
  <div id="bc1" class="btn-group btn-breadcrumb">
	<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>Ujian</div>',array('/quiz/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>List</div>',array('/quiz/list'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>'.CHtml::encode($kelasnya).'</div>',array($path_nya,'type'=>'nilai'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>Nilai Keterampilan</div>',array('#'), array('class'=>'btn btn-success')); ?>
  </div>
</div>
	<h1>Deskripsi KD PENGETAHUAN <?php echo $model->name; ?></h1>
	<h1><?php echo $model->name; ?> Kelas <?php echo $model->class->name; ?> </h1>

	<?php  
	$semester = Option::model()->find(array('condition'=>'key_config LIKE "%semester%"'));
	$tahun_ajaran = Option::model()->find(array('condition'=>'key_config LIKE "%tahun_ajaran%"'));
	$kurikulum = Option::model()->find(array('condition'=>'key_config LIKE "%kurikulum%"'));
	$tahun_ajaran_minusone = $tahun_ajaran->value - 1;
	if(!empty($semester) and !empty($tahun_ajaran) and !empty($kurikulum) and $kurikulum->value == '2013'){

		$optSemester = "";
	$optTahunAjaran = "";
	if(Yii::app()->session['semester']){
					$optSemester = Yii::app()->session['semester'];
				} else {
					$optSemester=Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
				}
					if(Yii::app()->session['tahun_ajaran']){
					$optTahunAjaran = Yii::app()->session['tahun_ajaran'];
				} else {
					$optTahunAjaran=Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
				}

    echo "<p>Semester : $optSemester , Tahun Ajaran : $optTahunAjaran</p";


	?>

	<div class="col-md-6">
		<?php $url_sikap = Yii::app()->createUrl('/lesson/addMarkKd/'.$model->id);?>
		<form method="post" action="<?php echo $url_sikap;?>">
			<p class="text-right"><input type="submit" value="Simpan" class="btn btn-success"></p>
			<input type="hidden" name="lesson_id" value="<?php echo $model->id;?>">
			
			<input type="hidden" name="semester" value="<?php echo $semester->value;?>">
			<input type="hidden" name="tahun_ajaran" value="<?php echo $tahun_ajaran->value;?>">
			<table class="table table-bordered table-hovered well table-responsive">
				<tbody>
					<tr>
						<th>No</th>
						<th>KD</th>
						<th>Deskripsi</th>
						<th>Input Deskripsi</th>
					</tr>
					<?php $no = 1;?>
					<?php foreach ($siswa as $sw) { ?>
						<tr>
							<td><?php echo $no;?></td>
							<td><input type="hidden" name="title[]" value="<?php echo $sw;?>"><b><?php echo CHtml::encode($sw);?></b></td>
							<td>
								<?php 
									$cekDesc = LessonKd::model()->findByAttributes(array('title'=>$sw,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
									if(!empty($cekDesc)){
										echo $cekDesc->description;
									}
									
								?>
							</td>
							<td>
								<input type="text" name="description[]" class="form-control">
							</td>
						</tr>
						<?php $no++; ?>
					<?php } ?>
				</tbody>
			</table>
		</form>
	</div>

	<?php } else { 

		echo "<p>Harap Masukan Config Semester dan Tahun Ajaran, Serta Kurikulum Harus 2013</p>";

	}

	?>
</div>
<script>
  if ($(window).width() > 960) {
    $("#wrapper").toggleClass("toggled");
  }
</script>