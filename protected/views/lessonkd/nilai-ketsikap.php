<div class="container">
	<h1><?php echo $model->name; ?> Kelas <?php echo $model->class->name; ?> </h1>

	<?php  
	$semester = Option::model()->find(array('condition'=>'key_config LIKE "%semester%"'));
	$tahun_ajaran = Option::model()->find(array('condition'=>'key_config LIKE "%tahun_ajaran%"'));
	$kurikulum = Option::model()->find(array('condition'=>'key_config LIKE "%kurikulum%"'));

	if(!empty($semester) and !empty($tahun_ajaran) and !empty($kurikulum) and $kurikulum->value == '2013'){

		echo "<p>Semester : $semester->value , Tahun Ajaran : $tahun_ajaran->value</p";


	?>

	<div class="col-md-6">
		<?php $url_sikap = Yii::app()->createUrl('/lesson/addMarkKetSik/'.$model->id);?>
		<form method="post" action="<?php echo $url_sikap;?>">
			<p class="text-right"><input type="submit" value="Simpan" class="btn btn-success"></p>
			<input type="hidden" name="lesson_id" value="<?php echo $model->id;?>">
			<input type="hidden" name="semester" value="<?php echo $semester->value;?>">
			<input type="hidden" name="tahun_ajaran" value="<?php echo $tahun_ajaran->value;?>">
			<table class="table table-bordered table-hovered well table-responsive">
				<tbody>
					<tr>
						<th>No</th>
						<th>Nama Siswa</th>
						<th>KD 1</th>
						<th>KD 2</th>
						<th>KD 3</th>
						<th>KD 4</th>
						<th>KD 5</th>
						<th>KD 6</th>
						<th>KD 7</th>
						<th>Nilai Pen</th>
						<th>Nilai Ket</th>
						<th>Nilai Sik</th>
						<th>Input Nilai KD 1</th>
						<th>Input Nilai KD 2</th>
						<th>Input Nilai KD 3</th>
						<th>Input Nilai KD 4</th>
						<th>Input Nilai KD 5</th>
						<th>Input Nilai KD 6</th>
						<th>Input Nilai KD 7</th>
						<th>Input Nilai Pen</th>
						<th>Input Nilai Ket</th>
						<th>Input Nilai Sik</th>
					</tr>
					<?php $no = 1;?>
					<?php foreach ($siswa as $sw) { ?>
						<tr>
							<td><?php echo $no;?></td>
							<td><input type="hidden" name="student_id[]" value="<?php echo $sw->id;?>"><b><?php echo CHtml::encode($sw->display_name);?></b></td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd1'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd2'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd3'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd4'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd5'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd6'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd7'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td class="danger">
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td class="info">
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'keterampilan'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td class="success">
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'sikap'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<input type="number" name="score_kd1[]" class="form-control">
							</td>
							<td>
								<input type="number" name="score_kd2[]" class="form-control">
							</td>
							<td>
								<input type="number" name="score_kd3[]" class="form-control">
							</td>
							<td>
								<input type="number" name="score_kd4[]" class="form-control">
							</td>
							<td>
								<input type="number" name="score_kd5[]" class="form-control">
							</td>
							<td>
								<input type="number" name="score_kd6[]" class="form-control">
							</td>
							<td>
								<input type="number" name="score_kd7[]" class="form-control">
							</td>
							<td class="danger">
								<input type="number" name="score_pen[]" class="form-control">
							</td>
							<td class="info">
								<input type="number" name="score_ket[]" class="form-control">
							</td>
							<td class="success">
								
								<input type="number" name="score_sik[]" class="form-control">
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