<html>
<head>
<style type="text/css">
	<?php echo $inline_style ?>
	@page {
            margin-top: 100px;
            margin-right: 100px;
            margin-left: 100px;
            margin-bottom: 5px;
            
        }
</style>
</head>
<body>
<?php  
	$nama_kelas = strtolower($siswa->class->name);
	if (strpos($nama_kelas,'ipa') !== false) {
	    $kode_rapot = "A";
	} elseif (strpos($nama_kelas,'ips') !== false) {
		$kode_rapot = "S";
	} else {
		$kode_rapot = "B";
	}
	$nama_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_name%"'));
	$kepala_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%kepsek_id%"'));
	$alamat_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_address%"'));
	$semester = Option::model()->findAll(array('condition'=>'key_config LIKE "%semester%"'));
	$tahun_ajaran = Option::model()->findAll(array('condition'=>'key_config LIKE "%tahun_ajaran%"'));
	$nilai_harian = Option::model()->findAll(array('condition'=>'key_config LIKE "%nilai_harian%"'));
	$nilai_uts = Option::model()->findAll(array('condition'=>'key_config LIKE "%nilai_uts%"'));
	$nilai_uas = Option::model()->findAll(array('condition'=>'key_config LIKE "%nilai_uas%"'));
	$kurikulum_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%kurikulum%"'));

	if(!empty($nama_sekolah) and !empty($nama_sekolah[0]->value)){
		$school_name = strtoupper($nama_sekolah[0]->value);
	}else{
		$school_name = "PINISI SCHOOL";
	}

	if(!empty($kepala_sekolah) and !empty($kepala_sekolah[0]->value)){
		$user_kepsek = User::model()->findByPk($kepala_sekolah[0]->value);
		$kepsek = $user_kepsek->display_name;
		$nik = $user_kepsek->username;
	}else{
		$kepsek = "Medidu";
		$nik = "022";
	}

	if(!empty($alamat_sekolah) and !empty($alamat_sekolah[0]->value)){
		$address = $alamat_sekolah[0]->value;
	}else{
		$address = "Jl. Sidomukti No 29 Bandung";
	}	
?>
<section id="typography" style="font-size:14px;margin-top:100px;">
	<table style="width:100%;">
		<tr >
			<td>Nama Sekolah</td>
			<td> : <b><?php echo $school_name;?></b></td>
			<td>Nomor Seri</td>
			<td> : <b><?php echo $kode_rapot; ?>.<?php echo substr($siswa->username, 0,2)?>10<?php echo substr($siswa->username, -4)?></b></td>
		</tr>
		<tr >
			<td>Alamat</td>
			<td> : <?php echo $address;?></td>
			<td>Kelas</td>
			<td> : <b><?php echo $siswa->class->name;?></b></td>
		</tr>
		<tr >
			<td>Nama Peserta Didik</td>
			<td> : <?php echo $siswa->display_name;?></td>
			<td>Semester</td>
			<td> : <?php echo $semester[0]->value;?></td>
		</tr>
		<tr >
			<td>Nomor Induk</td>
			<td> : <?php echo $siswa->username;?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $tahun_ajaran[0]->value;?></td>
		</tr>
	</table>
	<p ><b>CAPAIAN KOMPETENSI</b></p>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr >
			<th rowspan="3" colspan="2" style="text-align: center;">Mata Pelajaran</th>
			<th rowspan="2" colspan="2" style="text-align: center;">Pengetahuan</th>
			<th rowspan="2" colspan="2" style="text-align: center;">Keterampilan</th>
			<th colspan="2" style="text-align: center;">Sikap</th>
		</tr>
		<tr>
			<th rowspan="2" style="text-align: center;">Dalam Mapel</th>
			<th rowspan="2" style="text-align: center;">Antar Mapel</th>
		</tr>
		<tr>
			<th><center>Angka</center></th>
			<th><center>Predikat</center></th>
			<th><center>Angka</center></th>
			<th><center>Predikat</center></th>
		</tr>

		<tr>
			<td colspan="2"><strong>Kelompok A (Wajib)</strong></td>
			<td><strong><center>0 - 100</center></strong></td>
			<td></td>
			<td><strong><center>0 - 100</center></strong></td>
			<td></td>
			<td><strong><center>SB/ B/ C/ K</center></strong></td>
			<td rowspan="20"></td>
		</tr>

		<?php 
		//ksort($klmA);
		$no = 1;
		if(!empty($mapel1)){
			foreach ($mapel1 as $row1) { ?>
				<?php
					$cekTugasHarian1 = Assignment::model()->findAll(array('condition'=>'lesson_id = '.$row1->id.' AND add_to_summary is null and assignment_type is null and trash is null and trash is null and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value));
					$cekTugasOfflineHarian1 = Assignment::model()->findAll(array('condition'=>'lesson_id = '.$row1->id.' AND add_to_summary is null and assignment_type = 1 and trash is null and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value));
					$cekUlanganHarian1 = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$row1->id.' AND add_to_summary is null and quiz_type < 1 and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value.' and trash is null'));
					$cekUts1 = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$row1->id.' AND add_to_summary is null and quiz_type = 1 and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value.' and trash is null'));
					$cekUas1 = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$row1->id.' AND add_to_summary is null and quiz_type = 2 and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value.' and trash is null'));
					$cekSkill1 = Skill::model()->findAll(array('condition'=>'lesson_id = '.$row1->id.' and trash is null'));
					
					$nilaiHarian1 = 0;
					$nilaiRaport1 = 0;
					$nilaiUts1 = 0;
					$nilaiUas1 = 0;
					$nilaiKeterampilan1 = 0;
					$nk1 = 0;
					$totalNilaiHarian1 = 0;

					$pembagi1 = 0;
					$pembagiNilaiHarian1 = 0;
					$pembagianUtsUas1 = 0;

					if(!empty($cekTugasHarian1)){
						foreach ($cekTugasHarian1 as $cth1) {
							$nilaiTugasHarian1 = StudentAssignment::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and assignment_id ='.$cth1->id.' and trash is null'));
							if(!empty($nilaiTugasHarian1)){
								$nilaiHarian1 = $nilaiHarian1+$nilaiTugasHarian1[0]->score;
							}
							$pembagiNilaiHarian1++;
						}
					}

					if(!empty($cekTugasOfflineHarian1)){
						foreach ($cekTugasOfflineHarian1 as $ctoh1) {
							$nilaiTugasOfflineHarian1 = OfflineMark::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and assignment_id ='.$ctoh1->id));
							if(!empty($nilaiTugasOfflineHarian1)){
								$nilaiHarian1 = $nilaiHarian1+$nilaiTugasOfflineHarian1[0]->score;
							}
						}
						$pembagiNilaiHarian1++;
					}

					if(!empty($cekUlanganHarian1)){
						foreach ($cekUlanganHarian1 as $cuh1) {
							$nilaiUlanganHarian1 = StudentQuiz::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and quiz_id ='.$cuh1->id.' and trash is null'));
							if(!empty($nilaiUlanganHarian1)){
								$nilaiHarian1 = $nilaiHarian1+$nilaiUlanganHarian1[0]->score;
							}
						}
						$pembagiNilaiHarian1++;
					}

					if(!empty($cekUts1)){
						foreach ($cekUts1 as $cuts1) {
							$nuts1 = StudentQuiz::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and quiz_id ='.$cuts1->id.' and trash is null'));
							if(!empty($nuts1)){
								$nilaiUts1 = $nilaiUts1+$nuts1[0]->score;
							}
							$pembagianUtsUas1++;
						}
					}

					if(!empty($cekUas1)){
						foreach ($cekUas1 as $cuas1) {
							$nuas1 = StudentQuiz::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and quiz_id ='.$cuas1->id.' and trash is null'));
							if(!empty($nuas1)){
								$nilaiUas1 = $nilaiUas1+$nuas1[0]->score;
							}
							$pembagianUtsUas1++;
						}
					}

					if(!empty($cekSkill1)){
						foreach ($cekSkill1 as $cs1) {
							$ns1 = StudentSkill::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and skill_id = '.$cs1->id));
							if(!empty($ns1)){
								$nk1 = $nilaiKeterampilan1+$ns1[0]->score;
							}
							$pembagi1++;
						}
						$nilaiKeterampilan1 = round($nk1/$pembagi1);
					}

					if($pembagiNilaiHarian1 > 0 || $pembagianUtsUas1 > 0){
						if($kurikulum_sekolah[0]->value == 2013){
							$totalNilaiHarian1 = ($nilaiHarian1+$nilaiUts1+$nilaiUas1)/($pembagiNilaiHarian1+$pembagianUtsUas1);
						}else{
							$totalNilaiHarian1 = $nilaiHarian1/$pembagiNilaiHarian1;
						}
					}

					if($kurikulum_sekolah[0]->value == 2013){
						$nilaiRaport1 = round($totalNilaiHarian1);
					}else{
						$nilaiRaport1 = round(($totalNilaiHarian1*$nilai_harian[0]->value/100)+($nilaiUts1*$nilai_uts[0]->value/100)+($nilaiUas1*$nilai_uas[0]->value/100));
					}
					
					
					$predikat1 = Clases::model()->predikat($nilaiRaport1); 	
					$predikatKeterampilan1 = Clases::model()->predikat($nilaiKeterampilan1);
				?>
			<tr>
				<td style="text-align: center;" width="3%"><?php echo $no;?></td>
				<td width="30%">
						<strong><?php echo $row1->name;?><br>
						(<?php echo $row1->users->display_name;?>)
					</strong>
				</td>
				<td style="font-size:20px;"><center><?php echo $nilaiRaport1;?></center></td>
				<td style="font-size:20px;">
					<center>
					<?php echo $predikat1; ?>
					</center>
				</td>
				<td style="font-size:20px;"><center><?php echo $nilaiKeterampilan1;?></center></td>
				<td style="font-size:20px;">
					<center>
						<?php
							echo $predikatKeterampilan1;
						?>
					</center>
				</td>
				<td style="font-size:20px;">
					<center>
						<?php
							//echo $row['nhs'];
						?>
					</center>
				</td>
			</tr>

		<?php 
			$no++;
			} 
		}
		?>

		<tr>
			<td colspan="7"><strong>Kelompok B (Wajib)</strong></td>
		</tr>
		<?php 
		//ksort($klmB);
		$no = 1;
		if(!empty($mapel2)){
			foreach ($mapel2 as $row2) { ?>
				<?php
					$cekTugasHarian2 = Assignment::model()->findAll(array('condition'=>'lesson_id = '.$row2->id.' AND add_to_summary is null and assignment_type is null and trash is null and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value));
					$cekTugasOfflineHarian2 = Assignment::model()->findAll(array('condition'=>'lesson_id = '.$row2->id.' AND add_to_summary is null and assignment_type = 1 and trash is null and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value));
					$cekUlanganHarian2 = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$row2->id.' AND add_to_summary is null and quiz_type < 1 and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value.' and trash is null'));
					$cekUts2 = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$row2->id.' AND add_to_summary is null and quiz_type = 1 and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value.' and trash is null'));
					$cekUas2 = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$row2->id.' AND add_to_summary is null and quiz_type = 2 and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value.' and trash is null'));
					$cekSkill2 = Skill::model()->findAll(array('condition'=>'lesson_id = '.$row2->id.' and trash is null'));

					$nilaiHarian2 = 0;
					$nilaiRaport2 = 0;
					$nilaiUts2 = 0;
					$nilaiUas2 = 0;
					$nilaiKeterampilan2 = 0;
					$nk2 = 0;
					$totalNilaiHarian2 = 0;

					$pembagi2 = 0;
					$pembagiNilaiHarian2 = 0;
					$pembagianUtsUas2 = 0;

					if(!empty($cekTugasHarian2)){
						foreach ($cekTugasHarian2 as $cth2) {
							$nilaiTugasHarian2 = StudentAssignment::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and assignment_id ='.$cth2->id.' and trash is null'));
							if(!empty($nilaiTugasHarian2)){
								$nilaiHarian2 = $nilaiHarian2+$nilaiTugasHarian2[0]->score;
							}
							$pembagiNilaiHarian2++;
						}
					}

					if(!empty($cekTugasOfflineHarian2)){
						foreach ($cekTugasOfflineHarian2 as $ctoh2) {
							$nilaiTugasOfflineHarian2 = OfflineMark::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and assignment_id ='.$ctoh2->id));
							if(!empty($nilaiTugasOfflineHarian2)){
								$nilaiHarian2 = $nilaiHarian2+$nilaiTugasOfflineHarian2[0]->score;
							}
							$pembagiNilaiHarian2++;
						}
					}

					if(!empty($cekUlanganHarian2)){
						foreach ($cekUlanganHarian2 as $cuh2) {
							$nilaiUlanganHarian2 = StudentQuiz::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and quiz_id ='.$cuh2->id.' and trash is null'));
							if(!empty($nilaiUlanganHarian1)){
								$nilaiHarian2 = $nilaiHarian2+$nilaiUlanganHarian2[0]->score;
							}
							$pembagiNilaiHarian2++;
						}
					}

					
					if(!empty($cekUts2)){
						foreach ($cekUts2 as $cuts2) {
							$nuts2 = StudentQuiz::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and quiz_id ='.$cuts2->id.' and trash is null'));
							if(!empty($nuts2)){
								$nilaiUts2 = $nilaiUts2+$nuts2[0]->score;
							}
							$pembagianUtsUas2++;
						}
					}

					if(!empty($cekUas2)){
						foreach ($cekUas2 as $cuas3) {
							$nuas2 = StudentQuiz::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and quiz_id ='.$cuas3->id.' and trash is null'));
							if(!empty($nuas2)){
								$nilaiUas2 = $nilaiUas2+$nuas2[0]->score;
							}
							$pembagianUtsUas2++;
						}
					}

					if($pembagiNilaiHarian2 > 0 || $pembagianUtsUas2 > 0){
						if($kurikulum_sekolah[0]->value == 2013){
							$totalNilaiHarian2 = ($nilaiHarian2+$nilaiUts2+$nilaiUas2)/($pembagiNilaiHarian2+$pembagianUtsUas2);
						}else{
							$totalNilaiHarian2 = round($nilaiHarian2/$pembagiNilaiHarian2);
						}
						
					}

					if(!empty($cekSkill2)){
						foreach ($cekSkill2 as $cs2) {
							$ns2 = StudentSkill::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and skill_id = '.$cs2->id));
							if(!empty($ns2)){
								$nk2 = $nilaiKeterampilan1+$ns2[0]->score;
							}
							$pembagi2++;
						}
						$nilaiKeterampilan2 = round($nk2/$pembagi2);
					}

					if($kurikulum_sekolah[0]->value == 2013){
						$nilaiRaport2 = round($totalNilaiHarian2);
					}else{
						$nilaiRaport2 = round(($totalNilaiHarian2*$nilai_harian[0]->value/100)+($nilaiUts2*$nilai_uts[0]->value/100)+($nilaiUas2*$nilai_uas[0]->value/100));
					}

					$predikat2 = Clases::model()->predikat($nilaiRaport2);
					$predikatKeterampilan2 = Clases::model()->predikat($nilaiKeterampilan2);
				?>
			<tr>
				<td style="text-align: center;" width="3%"><?php echo $no;?></td>
				<td width="30%">
						<strong><?php echo $row2->name;?><br>
						(<?php echo $row2->users->display_name;?>)
					</strong>
				</td>
				<td style="font-size:20px;"><center><?php echo $nilaiRaport2;?></center></td>
				<td style="font-size:20px;">
					<center>
					<?php echo $predikat2; ?>
					</center>
				</td>
				<td style="font-size:20px;"><center><?php echo $nilaiKeterampilan2;?></center></td>
				<td style="font-size:20px;">
					<center>
						<?php
							echo $predikatKeterampilan2;
						?>
					</center>
				</td>
				<td style="font-size:20px;">
					<center>
						<?php
							//echo $row['nhs'];
						?>
					</center>
				</td>
			</tr>

		<?php 
			$no++;
			} 
		}
		?>

		<tr>
			<td colspan="7"><strong>Kelompok C (Peminatan)</strong></td>
		</tr>

		<?php
			
			$no = 1;
			if(!empty($mapel3)){
				foreach ($mapel3 as $row3) { ?>
					<?php
						$cekTugasHarian3 = Assignment::model()->findAll(array('condition'=>'lesson_id = '.$row3->id.' AND add_to_summary is null and assignment_type is null and trash is null and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value));
						$cekTugasOfflineHarian3 = Assignment::model()->findAll(array('condition'=>'lesson_id = '.$row3->id.' AND add_to_summary is null and assignment_type = 1 and trash is null and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value));
						$cekUlanganHarian3 = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$row3->id.' AND add_to_summary is null and quiz_type < 1 and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value.' and trash is null'));
						$cekUts3 = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$row3->id.' AND add_to_summary is null and quiz_type = 1 and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value.' and trash is null'));
						$cekUas3 = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$row3->id.' AND add_to_summary is null and quiz_type = 2 and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value.' and trash is null'));
						$cekSkill3 = Skill::model()->findAll(array('condition'=>'lesson_id = '.$row3->id.' and trash is null'));

						$nilaiHarian3 = 0;
						$nilaiRaport3 = 0;
						$nilaiUts3 = 0;
						$nilaiUas3 = 0;
						$nilaiKeterampilan3 = 0;
						$nk3 = 0;
						$totalNilaiHarian3 = 0;

						$pembagi3 = 0;
						$pembagiNilaiHarian3 = 0;
						$pembagianUtsUas3 = 0;

						if(!empty($cekTugasHarian3)){
							foreach ($cekTugasHarian3 as $cth3) {
								$nilaiTugasHarian3 = StudentAssignment::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and assignment_id ='.$cth3->id.' and trash is null'));
								if(!empty($nilaiTugasHarian3)){
									$nilaiHarian3 = $nilaiHarian3+$nilaiTugasHarian3[0]->score;
								}
								$pembagiNilaiHarian3++;
							}
						}

						if(!empty($cekTugasOfflineHarian3)){
							foreach ($cekTugasOfflineHarian3 as $ctoh3) {
								$nilaiTugasOfflineHarian3 = OfflineMark::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and assignment_id ='.$ctoh3->id));
								if(!empty($nilaiTugasOfflineHarian3)){
									$nilaiHarian3 = $nilaiHarian3+$nilaiTugasOfflineHarian3[0]->score;
								}
								$pembagiNilaiHarian3++;
							}
						}

						if(!empty($cekUlanganHarian3)){
							foreach ($cekUlanganHarian3 as $cuh3) {
								$nilaiUlanganHarian3 = StudentQuiz::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and quiz_id ='.$cuh3->id.' and trash is null'));
								if(!empty($nilaiUlanganHarian3)){
									$nilaiHarian3 = $nilaiHarian3+$nilaiUlanganHarian3[0]->score;
								}
								$pembagiNilaiHarian3++;
							}
						}

						if(!empty($cekUts3)){
							foreach ($cekUts3 as $cuts3) {
								$nuts3 = StudentQuiz::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and quiz_id ='.$cuts3->id.' and trash is null'));
								if(!empty($nuts3)){
									$nilaiUts3 = $nilaiUts3+$nuts3[0]->score;
								}
								$pembagianUtsUas3++;
							}
						}

						if(!empty($cekUas3)){
							foreach ($cekUas3 as $cuas3) {
								$nuas3 = StudentQuiz::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and quiz_id ='.$cuas3->id.' and trash is null'));
								if(!empty($nuas3)){
									$nilaiUas3 = $nilaiUas3+$nuas3[0]->score;
								}
								$pembagianUtsUas3++;
							}
						}

						if($pembagiNilaiHarian3 > 0 || $pembagianUtsUas3 > 0){
							if($kurikulum_sekolah[0]->value == 2013){
								$totalNilaiHarian3 = ($nilaiHarian3+$nilaiUts3+$nilaiUas3)/($pembagiNilaiHarian3+$pembagianUtsUas3);
							}else{
								$totalNilaiHarian3 = round($nilaiHarian3/$pembagiNilaiHarian3);
							}
							
						}

						if(!empty($cekSkill3)){
							foreach ($cekSkill3 as $cs3) {
								$ns3 = StudentSkill::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and skill_id = '.$cs3->id));
								if(!empty($ns3)){
									$nk3 = $nilaiKeterampilan1+$ns3[0]->score;
								}
								$pembagi3++;
							}
							$nilaiKeterampilan3 = round($nk3/$pembagi3);
						}

						if($kurikulum_sekolah[0]->value == 2013){
							$nilaiRaport3 = round($totalNilaiHarian3);
						}else{
							$nilaiRaport3 = round(($totalNilaiHarian3*$nilai_harian[0]->value/100)+($nilaiUts3*$nilai_uts[0]->value/100)+($nilaiUas3*$nilai_uas[0]->value/100));
						}
		
						$predikat3 = Clases::model()->predikat($nilaiRaport3);
						$predikatKeterampilan3 = Clases::model()->predikat($nilaiKeterampilan3);
					?>
				<tr>
					<td style="text-align: center;" width="3%"><?php echo $no;?></td>
					<td width="30%">
							<strong><?php echo $row3->name;?><br>
							(<?php echo $row3->users->display_name;?>)
						</strong>
					</td>
					<td style="font-size:20px;"><center><?php echo $nilaiRaport3;?></center></td>
					<td style="font-size:20px;">
						<center>
						<?php echo $predikat3; ?>
						</center>
					</td>
					<td style="font-size:20px;"><center><?php echo $nilaiKeterampilan3;?></center></td>
					<td style="font-size:20px;">
						<center>
							<?php
								echo $predikatKeterampilan3;
							?>
						</center>
					</td>
					<td style="font-size:20px;">
						<center>
							<?php
								//echo $row['nhs'];
							?>
						</center>
					</td>
				</tr>

			<?php 
				$no++;
				} 
			}
			?>

		<?php
			
			//$no = 1;
			if(!empty($mapel4)){	

				foreach ($mapel4 as $row4) { ?>
					<?php
						$cekTugasHarian4 = Assignment::model()->findAll(array('condition'=>'lesson_id = '.$row4->id.' AND add_to_summary is null and assignment_type is null and trash is null and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value));
						$cekTugasOfflineHarian4 = Assignment::model()->findAll(array('condition'=>'lesson_id = '.$row4->id.' AND add_to_summary is null and assignment_type = 1 and trash is null and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value));
						$cekUlanganHarian4 = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$row4->id.' AND add_to_summary is null and quiz_type < 1 and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value.' and trash is null'));
						$cekUts4 = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$row4->id.' AND add_to_summary is null and quiz_type = 1 and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value.' and trash is null'));
						$cekUas = Quiz::model()->findAll(array('condition'=>'lesson_id = '.$row4->id.' AND add_to_summary is null and quiz_type = 2 and semester = '.$semester[0]->value.' and year = '.$tahun_ajaran[0]->value.' and trash is null'));
						$cekSkill4 = Skill::model()->findAll(array('condition'=>'lesson_id = '.$row4->id.' and trash is null'));

						$nilaiHarian4 = 0;
						$nilaiRaport4 = 0;
						$nilaiUts4 = 0;
						$nilaiUas4 = 0;
						$nilaiKeterampilan4 = 0;
						$nk4 = 0;
						$totalNilaiHarian4 = 0;

						$pembagi4 = 0;
						$pembagiNilaiHarian4 = 0;
						$pembagianUtsUas4 = 0;

						if(!empty($cekTugasHarian4)){
							foreach ($cekTugasHarian4 as $cth4) {
								$nilaiTugasHarian4 = StudentAssignment::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and assignment_id ='.$cth4->id.' and trash is null'));
								if(!empty($nilaiTugasHarian4)){
									$nilaiHarian4 = $nilaiHarian4+$nilaiTugasHarian4[0]->score;
								}
								$pembagiNilaiHarian4++;
							}
						}

						if(!empty($cekTugasOfflineHarian4)){
							foreach ($cekTugasOfflineHarian4 as $ctoh4) {
								$nilaiTugasOfflineHarian4 = OfflineMark::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and assignment_id ='.$ctoh4->id));
								if(!empty($nilaiTugasOfflineHarian4)){
									$nilaiHarian4 = $nilaiHarian4+$nilaiTugasOfflineHarian4[0]->score;
								}
								$pembagiNilaiHarian4++;
							}
						}

						if(!empty($cekUlanganHarian4)){
							foreach ($cekUlanganHarian4 as $cuh4) {
								$nilaiUlanganHarian4 = StudentQuiz::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and quiz_id ='.$cuh4->id.' and trash is null'));
								if(!empty($nilaiUlanganHarian4)){
									$nilaiHarian4 = $nilaiHarian4+$nilaiUlanganHarian4[0]->score;
								}
								$pembagiNilaiHarian4++;
							}
						}

						if($pembagiNilaiHarian4 > 0){
							$totalNilaiHarian4 = round($nilaiHarian4/$pembagiNilaiHarian4);
						}

						if(!empty($cekUts4)){
							foreach ($cekUts4 as $cuts4) {
								$nuts4 = StudentQuiz::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and quiz_id ='.$cuts4->id.' and trash is null'));
								if(!empty($nuts4)){
									$nilaiUts4 = $nilaiUts4+$nuts4[0]->score;
								}
							}
						}

						if(!empty($cekUas)){
							foreach ($cekUas as $cuas4) {
								$nuas4 = StudentQuiz::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and quiz_id ='.$cuas4->id.' and trash is null'));
								if(!empty($nuas4)){
									$nilaiUas4 = $nilaiUas4+$nuas4[0]->score;
								}
							}
						}

						if($pembagiNilaiHarian4 > 0 || $pembagianUtsUas4 > 0){
							if($kurikulum_sekolah[0]->value == 2013){
								$totalNilaiHarian4 = ($nilaiHarian4+$nilaiUts4+$nilaiUas4)/($pembagiNilaiHarian4+$pembagianUtsUas4);
							}else{
								$totalNilaiHarian4 = round($nilaiHarian4/$pembagiNilaiHarian4);
							}
							
						}

						if(!empty($cekSkill4)){
							foreach ($cekSkill4 as $cs4) {
								$ns4 = StudentSkill::model()->findAll(array('condition'=>'student_id = '.$siswa->id.' and skill_id = '.$cs4->id));
								if(!empty($ns4)){
									$nk4 = $nilaiKeterampilan1+$ns4[0]->score;
								}
								$pembagi4++;
							}
							$nilaiKeterampilan4 = round($nk4/$pembagi4);
						}

						if($kurikulum_sekolah[0]->value == 2013){
							$nilaiRaport4 = round($totalNilaiHarian4);
						}else{
							$nilaiRaport4 = round(($totalNilaiHarian4*$nilai_harian[0]->value/100)+($nilaiUts4*$nilai_uts[0]->value/100)+($nilaiUas4*$nilai_uas[0]->value/100));
						}
						
						
						$predikat4 = Clases::model()->predikat($nilaiRaport4);
						$predikatKeterampilan4 = Clases::model()->predikat($nilaiKeterampilan4);
				?>
				<tr>
					<td style="text-align: center;" width="3%"><?php echo $no;?></td>
					<td width="30%">
							<strong><?php echo $row4->name;?><br>
							(<?php echo $row4->users->display_name;?>)
						</strong>
					</td>
					<td style="font-size:20px;"><center><?php echo $nilaiRaport4;?></center></td>
					<td style="font-size:20px;">
						<center>
						<?php echo $predikat4; ?>
						</center>
					</td>
					<td style="font-size:20px;"><center><?php echo $nilaiKeterampilan4;?></center></td>
					<td style="font-size:20px;">
						<center>
							<?php
								echo $predikatKeterampilan4;
							?>
						</center>
					</td>
					<td style="font-size:20px;">
						<center>
							<?php
								//echo $row['nhs'];
							?>
						</center>
					</td>
				</tr>

			<?php 
				$no++;
				} 

			}	
			?>	

	</table>
	

	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th><center><strong>Ekstra Kurikuler</strong></center></th>
			<th><center><strong>Kegiatan yang telah dilakukan</strong></center></th>
		</tr>
		<tr>
			<td>1. </td>
			<td>0 (0)</td>
		</tr>
		<tr>
			<td>2. </td>
			<td>0 (0)</td>
		</tr>
	</table>

	<table border="5" style="width:40%;border: 2px solid black;margin-top: 15px">
		<tr>
			<th colspan="3"><center><strong>Ketidakhadiran</strong></center></th>
		</tr>
		<tr>
			<td width="10%" style="border: 0;">Sakit</td>
			<td width="5%" style="border: 0;">:</td>
			<td width="5%" style="border: 0;">0 Hari</td>
		</tr>
		<tr>
			<td style="border: 0;">Izin</td>
			<td style="border: 0;">:</td>
			<td style="border: 0;">0 Hari</td>
		</tr>
		<tr>
			<td style="border: 0;">Tanpa Keterangan</td>
			<td style="border: 0;">:</td>
			<td style="border: 0;">0 Hari</td>
		</tr>
	</table>

	<table class="table" style="margin-top: 0.5px;margin-bottom: 0.5px;">
		<tr>
			<td>
				<p class="text-center" style="font-size:15px;">
					Mengetahui:
					<br>Orang Tua/Wali
					<br>
					<br>
					<br>
					<br>
					...................
				</p>

			</td>
			<td>
				<br>
				<p class="text-center" style="font-size:15px;">
					Wali Kelas
					<br>
					<br>
					<br>
					<br>
					<b>
						<?php
							if(!empty($model->teacher_id)){
								echo $model->teacher->display_name;	
							} 
							
						?>
					</b>
					<br>	
					<b>NIP. 
						<?php 
							if(!empty($model->teacher_id)){
								echo $nip = str_replace('-', ' ', $model->teacher->username);
							} 
							
						?>
					</b>
				</p>
				
			</td>
			<td>
				<p class="text-center" style="font-size:15px;">
					Bandung, <?php echo date('d F Y');?>
					<br>Kepala Sekolah,
					<br>
					<br>
					<br>
					<br>
					<?php //$kepsek = User::model()->findByPk('2');?>
					<b><?php echo $kepsek;?></b>	
					<br>
					<b>NIP. <?php echo $nip = str_replace('-', ' ', $nik);?></b>
				</p>

			</td>
		</tr>
	</table>
</section>	
</body>
</html>
