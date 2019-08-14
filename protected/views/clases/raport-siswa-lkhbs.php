<html>
<head>
	<style type="text/css">
		<?php echo $inline_style ?>

		* {
			font-size: 12px;
		}

		hr {
			margin-top: 5px;
			margin-bottom: 5px;
			margin-left: 0px;
			margin-right: 0px;
		}

		td {
			padding-top: 2px;
			padding-bottom: 2px;
			padding-left: 3px;
		}

		@page {
			margin-top: 30px;
			margin-right: 100px;
			margin-left: 100px;
			margin-bottom: 5px;
		}

		@media print {
			.break-before {
				display: block;
				page-break-before: always;
			}
			.break-after	{
				display: block;
				page-break-after: always;
			}
		}
	</style>
</head>
	<body>
<?php
	$nama_kelas = strtolower($siswa->class->name);

	// if (strpos(strtolower($nama_kelas),'12 ')!==false || strpos(strtolower($nama_kelas),'xii ')!==false) {
	// 	$kkm = 80;
	// } elseif (strpos(strtolower($nama_kelas),'11 ')!==false || strpos(strtolower($nama_kelas),'xi ')!==false) {
	// 	$kkm = 75;
	// } elseif (strpos(strtolower($nama_kelas),'10 ')!==false || strpos(strtolower($nama_kelas),'x ')!==false) {
		$kkm = 75;
	// } else {
	// 	$kkm = "....";
	// }

	function DateToIndo($date) {
		$BulanIndo = array("Januari", "Februari", "Maret",
						"April", "Mei", "Juni",
						"Juli", "Agustus", "September",
						"Oktober", "November", "Desember");

		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$tgl   = substr($date, 7, 2);

		$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
		return($result);
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

	$smt = 0;
	$penanda = 1;

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
		$kepsek = "Kepala Sekolah";
		$nik = "022";
	}

	if(!empty($alamat_sekolah) and !empty($alamat_sekolah[0]->value)){
		$address = $alamat_sekolah[0]->value;
	}else{
		$address = "Jl. Sidomukti No 29 Bandung";
	}

	if(!empty($semester) and !empty($semester[0]->value)){
		if($semester[0]->value == 1){
			$smt = "1 (Satu)";
			$penanda = 1;
		}else{
			$smt = "2 (Dua)";
			$penanda = 2;
		}
	}

	if(!empty($tahun_ajaran) and !empty($tahun_ajaran[0]->value)){
		// if($penanda == 2){
			$ta = ($tahun_ajaran[0]->value-1)."/".$tahun_ajaran[0]->value;
		// }else{
		// 	$ta = $tahun_ajaran[0]->value."/".($tahun_ajaran[0]->value+1);
		// }
	}else{
		$ta = "";
	}
?>


<!-- Halaman 2 -->
<section id="typography" class="break-after" style="margin-top:10px;">		
	<hr style="width:100%;border: 1px solid black;"/>
	<table border="0" style="width:100%;">
		<tr>
			<td>Nama Sekolah</td>
			<td> : <?php echo $school_name; ?></td>
			<td>Kelas</td>
			<td> : <?php echo $siswa->class->name;?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td> : <?php echo $address; ?></td>
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / Genap</td>
		</tr>
		<tr >
			<td>Nama</td>
			<td> : <?php echo $siswa->display_name;?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr colspan="2">
			<td>NIS/NISN</td>
			<td> : <?php
			if(!empty($siswa->username)){
				echo $siswa->username;
			}else{
				echo "-";
			}
			echo " / ";
			if (!empty($profil)) {
				echo $profil->nisn;
			}else{
				echo "-";
			}
			?></td>
		</tr>
	</table>
	<hr style="width:100%;border: 1px solid black;"/>
	<center><h5>LAPORAN KEMAJUAN HASIL BELAJAR SISWA OKTOBER</h5></center>
	<br/>
<b>Pengetahuan dan Keterampilan</b>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th align="center" width="5%">No.</th>
			<th align="center" width="30%">Mata Pelajaran</th>
			
			<th  align="center">Nilai</th>
			<th  align="center">Predikat</th>
			<th  align="center">Deskripsi</th>
		</tr>
	
	
		<?php
			$no = 1;
			if(!empty($peluas1)){
				foreach ($peluas1 as $key => $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-kd2']) or !empty($rowpeluas1['nilai-keterampilan'])){
						if(!empty($rowpeluas1['nilai-kd2'])){
							if($rowpeluas1['nilai-kd2']<75){
								$rowpeluas1['p-np'] = "D";
								$peluas1[$key]['p-np'] = "D";
							}elseif($rowpeluas1['nilai-kd2']>=75 && $rowpeluas1['nilai-kd2']<84){
								$rowpeluas1['p-np'] = "C";
								$peluas1[$key]['p-np'] = "C";
							}elseif($rowpeluas1['nilai-kd2']>=84 && $rowpeluas1['nilai-kd2']<93){
								$rowpeluas1['p-np'] = "B";
								$peluas1[$key]['p-np'] = "B";
							}elseif($rowpeluas1['nilai-kd2']>=93 && $rowpeluas1['nilai-kd2']<=100){
								$rowpeluas1['p-np'] = "A";
								$peluas1[$key]['p-np'] = "A";
							}else{
								$rowpeluas1['p-np'] = "-";
								$peluas1[$key]['p-np'] = "-";
							}
						}
						if(!empty($rowpeluas1['nilai-keterampilan'])){
							if($rowpeluas1['nilai-keterampilan']<75){
								$rowpeluas1['p-nk'] = "D";
								$peluas1[$key]['p-nk'] = "D";
							}elseif($rowpeluas1['nilai-keterampilan']>=75 && $rowpeluas1['nilai-keterampilan']<84){
								$rowpeluas1['p-nk'] = "C";
								$peluas1[$key]['p-nk'] = "C";
							}elseif($rowpeluas1['nilai-keterampilan']>=84 && $rowpeluas1['nilai-keterampilan']<93){
								$rowpeluas1['p-nk'] = "B";
								$peluas1[$key]['p-nk'] = "B";
							}elseif($rowpeluas1['nilai-keterampilan']>=93 && $rowpeluas1['nilai-keterampilan']<=100){
								$rowpeluas1['p-nk'] = "A";
								$peluas1[$key]['p-nk'] = "A";
							}else{
								$rowpeluas1['p-nk'] = "-";
								$peluas1[$key]['p-nk'] = "-";
							}
						}

						// if($rowpeluas1['kelompok']==1){
		?>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;"><?php echo $no; ?></td>
			<td><?php echo $rowpeluas1['name']; ?></td>
			
			<?php if(!empty($rowpeluas1['nilai-kd2']) && $rowpeluas1['nilai-kd2']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-kd2']; ?></td>
			<?php }else{ ?>
				<?php if(!empty($rowpeluas1['nilai-keterampilan']) && $rowpeluas1['nilai-keterampilan']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-keterampilan']; ?></td>
				<?php }else{ ?>
					<td align="center">-</td>
				<?php } ?>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-np']; ?></td>
			<?php }else{ ?>
				<?php if(!empty($rowpeluas1['nilai-keterampilan']) && $rowpeluas1['nilai-keterampilan']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-nk']; ?></td>
				<?php }else{ ?>
					<td align="center">-</td>
				<?php } ?>
			<?php } ?>
			
			<?php if(!empty($rowpeluas1['desc-desc_pengetahuan']) && $rowpeluas1['desc-desc_pengetahuan']!="-") { ?>
				<td align="left"><?php echo $rowpeluas1['desc-desc_pengetahuan']; ?></td>
			<?php }else{ ?>

				<?php if(!empty($rowpeluas1['nilai-kd2']) && $rowpeluas1['nilai-kd2']!="-") { ?>

						<?php if(!empty($rowpeluas1['nilai-kddescription']) && $rowpeluas1['nilai-kddescription']!="-") { ?>
						<td align="left">
							<?php 
							if (!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="A") {
								echo "Memahami, menerapkan, menganalisis pengetahuan tentang  ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))." dengan Sangat Baik";
							} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="B"){
								echo "Memahami, menerapkan, menganalisis pengetahuan tentang  ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))." dengan Baik";
							} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="C"){
								echo "Memahami, menerapkan, menganalisis pengetahuan tentang  ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))." dengan Cukup Baik";
							} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="D"){
								echo "Memahami, menerapkan, menganalisis pengetahuan tentang  ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))." perlu ditingkatkan";
							}?>
						</td>
						<?php } else {?>
						<td align="center">-</td>
						<?php }?>

				<?php }else{ ?>
						<?php if(!empty($rowpeluas1['nilai-kddescription']) && $rowpeluas1['nilai-kddescription']!="-") { ?>
						<td align="left">
							<?php 
							if (!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="A") {
								echo "Sangat terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
							} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="B"){
								echo "Terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
							} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="C"){
								echo "Cukup terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
							} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="D"){
								echo "Kurang terampil ".strtolower(implode(",", $rowpeluas1['nilai-kddescription']))."";
							}?>
						</td>
						<?php } else {?>
						<td align="center">-</td>
						<?php }?>
				<?php }?>

			<?php } ?>
		</tr>
		<?php
							$no++;
						// }
					}
				}
			}
		?>
		
		
		
	</table>
	<br/>
	<p>
				<b>Tabel Interval Predikat</b>
				<table border="5" style="width:100%;border: 2px solid black;">
					<tr valign="center">
						<th rowspan="2" align="center">KKM</th>
						<th colspan="4" align="center">Predikat</th>
					</tr>
					<tr valign="center">
						<th width="20%" align="center">D = Kurang</th>
						<th width="20%" align="center">C = Cukup</th>
						<th width="20%" align="center">B = Baik</th>
						<th width="20%" align="center">A = Sangat Baik</th>
					</tr>
					<tr>
						<td align="center">75</td>
						<td align="center">&#060; 75</td>
						<td align="center">75 - 83</td>
						<td align="center">84 - 92</td>
						<td align="center">93 - 100</td>
					</tr>
				</table>
			</p>

	<table border="0" style="width:100%;border: 0px solid black;margin-top: 0.5px;margin-bottom: 0.5px;">
		<tr>
			<td width="30%">
				<p class="text-center">
					Mengetahui<br/>
					Orang Tua/Wali
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					<br>
					................................
				</p>
			</td>
			<td width="30%">
				<p class="text-center">
					<br/>
					Kepala Sekolah
					<br>
					<img style="margin-bottom: 5px; margin-top: 9px;" src="<?php echo Yii::app()->getBaseUrl(); ?>/images/ttd_kepsek.jpg" width="175px" height="100px">	
					<br>
					<b><?php echo $kepsek;?></b>
					<br>
					<b>NIP. <?php echo $nip = str_replace('-', ' ', $nik);?></b>
				</p>
			</td>
			<td width="30%">
				<p class="text-center">
					Bandung, <?php echo(DateToIndo(date('2017-2-24')));?><br/>
					Wali Kelas
					<br>
					<img style="margin-bottom: 5px; margin-top: 9px;" src="<?php echo Yii::app()->getBaseUrl(); ?>/images/<?php echo $siswa->class->name;?>.jpg" width="175px" height="100px">	
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
		</tr>
	</table>
</section>

