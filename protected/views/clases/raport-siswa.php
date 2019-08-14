<html>
<head>
<style type="text/css">
	<?php echo $inline_style ?>
</style>
</head>
<body>
<?php
	$nama_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_name%"'));
	$kepala_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%kepsek_id%"'));
	$alamat_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_address%"'));
	
	if(!empty($nama_sekolah)){
		$school_name = strtoupper($nama_sekolah[0]->value);
	}else{
		$school_name = "PINISI SCHOOL";
	}

	if(!empty($kepala_sekolah)){
		$user_kepsek = User::model()->findByPk($kepala_sekolah[0]->value);
		$kepsek = $user_kepsek->display_name;
		$nik = $user_kepsek->username;
	}else{
		$kepsek = "Medidu";
		$nik = "022";
	}	
?>
<section id="typography">
	<div class="page-header">
	<!-- <h3>
		Laporan Penilaian Tertulis Tengah Semester Genap<br>
		Tahun Pelajaran 2014/2015<br>
		MTs Miftahul Huda</h3>
	<h4>
		Jl. Cikadut No. 252 A.H Nasution Kec Mandalajati Kota Bandung 40194 Telp. (022) 7237649<br>
		www.mimhakita.com email:mtsmimhabandung@gmail.com
	</h4> -->
	<h3 class="text-center">
		Laporan Penilaian Tertulis <br>
		<?php 
			echo $school_name;
		?>
		
	</h3>
	</div>	
	<table>
		<tr>
			<td>Nama Peserta Didik</td>
			<td>:</td>
			<td><?php echo $siswa->display_name;?></td>
		</tr>
		<tr>
			<td>Kelas</td>
			<td>:</td>
			<td><?php echo $siswa->class->name;?></td>
		</tr>
	</table>
	<h5><b>Penilaian Mata Pelajaran</h5>
	<table class="table table-bordered">
		<tr>
		<th><center>No</center></th>
		<th><center>Mata Pelajaran</center></th>
		<th><center>Rata-rata Nilai Harian</center></th>
		<th><center>Nilai Murni UTS</center></th>
		<th><center>Nilai Murni UAS</center></th>
		<th><center>Keterangan</center></th>
		</tr>
		<?php $no = 1;?>
		<?php foreach ($mapel as $key) { ?>
			<?php

				$tugas = Assignment::model()->findAll(array('condition'=>'lesson_id = '.$key->id.' AND add_to_summary is null'));
				$tugasOnline = Assignment::model()->findAll(array('condition'=>'lesson_id = '.$key->id.' and assignment_type is null AND add_to_summary is null'));
				$kuis = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$key->id.' AND add_to_summary is null and quiz_type = 0'));
				$uts=Quiz::model()->findAll(array('condition'=>'lesson_id = '.$key->id.' AND add_to_summary is null and quiz_type = 1'));
				$uas=Quiz::model()->findAll(array('condition'=>'lesson_id = '.$key->id.' AND add_to_summary is null and quiz_type = 2'));
				
				$nuas = NULL;
				$nuts = NULL;
				$nharian = NULL;	
				$div = NULL;
				$tnHarian = NULL;

				foreach ($tugas as $tgs) {
					$ntgs = StudentAssignment::model()->findByAttributes(array('assignment_id'=>$tgs->id,'student_id'=>$siswa->id));
					$om = OfflineMark::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' AND lesson_id = '.$key->id.' AND mark_type = 1 AND assignment_id = '.$tgs->id));
					if($tgs->assignment_type == NULL){	
						if(!empty($ntgs)){
							$nharian=$nharian+$ntgs->score;
						}
					}else{
						if(!empty($om)){
							$nharian=$nharian+$om[0]->score;
						}
					}

					$div++;
				}

				/*foreach ($tugasOnline as $tgsOl) {
					$ntgsOl = StudentAssignment::model()->findByAttributes(array('assignment_id'=>$tgsOl->id,'student_id'=>$siswa->id));
					if(!empty($ntgsOl)){
						$nharian=$nharian+$ntgsOl->score;
					}

					$div++;
				}*/

				foreach ($kuis as $ks) {
					$nKs = StudentQuiz::model()->findByAttributes(array('quiz_id'=>$ks->id,'student_id'=>$siswa->id));
					if(!empty($nKs)){
						$nharian=$nharian+$nKs->score;
					}

					$div++;
				}

				if($nharian !== NULL){
					$tnHarian = round($nharian/$div);
				}
				
				foreach ($uas as $ukk) {
					$sq=StudentQuiz::model()->findByAttributes(array('quiz_id'=>$ukk->id,'student_id'=>$siswa->id));
					if(!empty($sq)){
						$nuas=$sq->score;
					}
				}

				foreach ($uts as $uks) {
					$su=StudentQuiz::model()->findByAttributes(array('quiz_id'=>$uks->id,'student_id'=>$siswa->id));
					if(!empty($su)){
						$nuts=$su->score;
					}
				}
			?>
			<tr>
				<td><center><?php echo $no;?></center></td>
				<td><center><?php echo $key->name;?></center></td>
				<td><center><?php echo $tnHarian;?></center></td>
				<td><center><?php echo $nuts;?></center></td>
				<td><center><?php echo $nuas ;?></center></td>
				<td><center></center></td>
			</tr>
		<?php $no++;?>	
		<?php } ?>

	</table>
	<p class="text-right">........, <?php echo date('d F Y');?></p>
	<br>
	<table class="table">
		<tbody>
			<tr>
				<td>
					<p class="text-center"> Mengetahui, <br>Kepala <?php echo $school_name;?><br><br><br><br><br>
						<strong>(<?php echo $kepsek;?>)<br>NUPTK : <?php echo $nik;?></strong>
					</p>
				</td>
				<!-- <td>
					<p class="text-center"> Mengetahui, <br>Kepala Phinisi School<br><br><br><br><br>
						<strong>(Setiadi, A.Ma)<br>NUPTK : 9549759660200013</strong>
					</p>
				</td> -->
				<td>
					<p class="text-center"> Wali Kelas, <br> <br><br><br><br><br>
						<strong> (.....)<br>NUPTK : </strong>
					</p>
				</td>
			</tr>
		</tbody>
	</table>
</section>	
</body>
</html>
