<html>
<head>
<style type="text/css">
	<?php echo $inline_style ?>
</style>
</head>
<body>
<?php
	$jml = 0;
	if(!empty($tugas)){
		foreach ($tugas as $count) {
			$jml++;
		}
	}

	if(!empty($kuis)){
		foreach ($kuis as $ks) {
			$jml++;
		}
	}

	//$total = $jml+$result;
	$total = $jml;
	$nama_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_name%"'));
	$kepala_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%kepsek_id%"'));
	$alamat_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_address%"'));
	$nilai_harian = Option::model()->findAll(array('condition'=>'key_config LIKE "%nilai_harian%"'));
	$nilai_uts = Option::model()->findAll(array('condition'=>'key_config LIKE "%nilai_uts%"'));
	$nilai_uas = Option::model()->findAll(array('condition'=>'key_config LIKE "%nilai_uas%"'));
	$kurikulum_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%kurikulum%"'));

	if(!empty($nama_sekolah)){
		$school_name = strtoupper($nama_sekolah[0]->value);
	}else{
		$school_name = "";
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
	<!-- <h3><CENTER>
		DAFTAR NILAI MADRASAH TSANAWIYAH (MTs) MIFTAHUL HUDA<br>
		TAHUN AJARAN 2015/2016</CENTER></h3> -->
	<h3><CENTER>
		DAFTAR NILAI <?php echo $school_name;?><br>
		</CENTER></h3>
	<h4>
		<table>
			<tr>
				<td>Mata Pelajaran</td>
				<td>:</td>
				<td><?php echo ucwords($model->name);?></td>
			</tr>
			<tr>
				<td>Kelas</td>
				<td>:</td>
				<td><b><?php echo ucwords($kelas->name);?></b></td>
			</tr>
			<!-- <tr>
				<td>Semester</td>
				<td>:</td>
				<td><b>Genap</b></td>
			</tr> -->
		</table>
	</h4>
	</div>

	<table class="table table-bordered" border="1">
		<tr>
			<th rowspan="2"><center>No</center></th>
			<th rowspan="2"><center>NIS</center></th>
			<th rowspan="2"><center>Nama Siswa</center></th>
			<th colspan="<?php echo $total; ?>"><center>Nilai Harian</center></th>
			<th rowspan="2" class="text-center">RNH</th>
			<th rowspan="2" class="text-center">UTS</th>
			<th rowspan="2" class="text-center">UAS</th>
			<th rowspan="2" class="text-center">Raport<br>RNH+UTS+UAS</th>
		</tr>
		<tr>
			<?php
				for ($i=1; $i <= $total; $i++) {

					//if($tugas[$i-1]->assignment_type == NULL){
					//	echo "<th><center></center></th>";
					//}else{
						echo "<th><center></center></th>";
					//}
				}
			?>
		</tr>
		<?php $no=1;?>
		<?php foreach ($siswa as $key) { ?>
			<tr>
				<td><center><?php echo $no;?></center></td>
				<td><center><?php echo $key->student->username;?></center></td>
				<td><?php echo $key->student->display_name;?></td>
				<?php if(!empty($tugas) || !empty($kuis)){ ?>
			<?php
				$counter=0;
				$cnt=0;
				$rnh=0;
				$tnh=0;
				$pnh=0;
				$div=0;
				$div2=0;
				$nuts=0;
				$puts=0;
				$tuas=0;
				$ruas=0;
				$puas=0;
				$puts=0;
				$ruts=0;
				$nrpt=0;
				foreach ($tugas as $tgs) {
					//$ts = StudentAssignment::model()->with('teacher_assign')->together()->findAll(array('condition'=>'t.student_id = '.$key->id.' AND teacher_assign.lesson_id = '.$model->id.' AND status = 1'));
					$ts = StudentAssignment::model()->findByAttributes(array('student_id'=>$key->student->id,'assignment_id'=>$tgs->id,'status'=>1));
					$om = OfflineMark::model()->findAll(array('condition'=>'student_id = '.$key->student->id.' AND lesson_id = '.$model->id.' AND mark_type = 1 AND assignment_id = '.$tgs->id));
					/*echo "<pre>";
					if(!empty($ts->score)){
						echo $ts->score;
					}						
					echo "</pre>";*/
					if($tgs->assignment_type == NULL){	
						if(!empty($ts->score)){
							//echo "<td>".$ts[$cnt]->score."</td>";
							echo "<td>".$ts->score."</td>";
							$cnt++;
							$tnh=$tnh+$ts->score;
						}else{
							echo "<td></td>";
						}
					}else{
						if(!empty($om)){
							//echo "<td>".$om[$counter]->score."</td>";
							echo "<td>".$om[0]->score."</td>";
							$counter++;
							$tnh=$tnh+$om[0]->score;
						}else{
							echo "<td></td>";
						}	
					}
					$div++;
					
				}
				foreach ($kuis as $q) {
					$sq=StudentQuiz::model()->findByAttributes(array('quiz_id'=>$q->id,'student_id'=>$key->student->id));
					if(!empty($sq)){
						echo "<td>".$sq->score."</td>";
						$tnh=$tnh+$sq->score;
					}else{
						echo "<td></td>";
					}
					$div++;
				}
				$rnh=$tnh/$div;
				//$pnh=round($rnh*$nilai_harian[0]->value/100);
				
				echo "<td><strong>".number_format($rnh,'0',',','.')."</strong></td>";
				if(!empty($uts)){
					foreach ($uts as $uks) {
						$suts=StudentQuiz::model()->findByAttributes(array('quiz_id'=>$uks->id,'student_id'=>$key->student->id));
						if(!empty($suts)){
							echo "<td><strong>".$suts->score."</strong></td>";
							$puts=$suts->score;
						}else{
							echo "<td></td>";
						}
						$div2++;
					}
				}else{
					echo "<td></td>";	
				}
				//$ruts = round($puts*$nilai_uts[0]->value/100);
				//echo "<td></td>";
				
				if(!empty($uas)){
					foreach ($uas as $ukk) {
						$suas=StudentQuiz::model()->findByAttributes(array('quiz_id'=>$ukk->id,'student_id'=>$key->student->id));
						if(!empty($suas)){
							echo "<td><strong>".$suas->score."</strong></td>";
							$puas=$suas->score;
						}else{
							echo "<td></td>";
						}
						$div2++;
					}
				}else{
					echo "<td></td>";	
				}
				
				if($div > 0 || $div2 > 0){
					
					if($kurikulum_sekolah[0]->value == 2013){
						$pnh=($tnh+$puts+$puas)/($div+$div2);
					}else{
						$pnh=$rnh*$nilai_harian[0]->value/100;
					}
				}
					
				if($kurikulum_sekolah[0]->value == 2013){
					$nrpt=round($pnh);
				}else{
					$nuts=$puts*$nilai_uts[0]->value/100;
					$ruas=$puas*$nilai_uas[0]->value/100;
					$nrpt=round($pnh+$nuts+$ruas);
				}
				
				echo "<td><strong>".number_format($nrpt,'0',',','.')."</strong></td>";

			?>
			<?php }else{ ?>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<!-- <td></td> -->
			<?php } ?>
			</tr>
		<?php $no++;?>	
		<?php } ?>
	</table>	
</section>	
</body>
</html>
