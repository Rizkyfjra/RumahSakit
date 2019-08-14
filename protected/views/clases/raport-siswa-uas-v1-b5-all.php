<html>
<head>
	<style type="text/css">
		<?php echo $inline_style ?>

		* {
			font-size: 10px;
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
	function DateToIndo($date) {
		$BulanIndo = array("Januari", "Februari", "Maret",
						"April", "Mei", "Juni",
						"Juli", "Agustus", "September",
						"Oktober", "November", "Desember");

		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$tgl   = substr($date, 8, 2);

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
		if($penanda == 2){
			$ta = ($tahun_ajaran[0]->value-1)."/".$tahun_ajaran[0]->value;
		}else{
			$ta = $tahun_ajaran[0]->value."/".($tahun_ajaran[0]->value+1);
		}
	}else{
		$ta = "";
	}
?>
<?php
	$count_siswa = count($siswa);
	for($z=0; $z < $count_siswa; $z++) {
		$nama_kelas = strtolower($siswa[$z]->class->name);

		if (strpos(strtolower($nama_kelas),'12 ')!==false || strpos(strtolower($nama_kelas),'xii ')!==false) {
			$kkm = 80;
		} elseif (strpos(strtolower($nama_kelas),'11 ')!==false || strpos(strtolower($nama_kelas),'xi ')!==false) {
			$kkm = 75;
		} elseif (strpos(strtolower($nama_kelas),'10 ')!==false || strpos(strtolower($nama_kelas),'x ')!==false) {
			$kkm = 75;
		} else {
			$kkm = "....";
		}
?>

<!-- Halaman 1 -->
<section id="typography" class="break-after" style="margin-top:10px;">
<!-- 	<sub>
		REG.
		<?php
			$x = $z + 1;
			echo $x;
		?>
	</sub> -->
	<hr style="width:100%;border: 1px solid black;"/>
	<table border="0" style="width:100%;">
		<tr>
			<td>Nama Sekolah</td>
			<td> : <?php echo $school_name; ?></td>
			<td>Kelas</td>
			<td> : <?php echo $siswa[$z]->class->name;?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td> : <?php echo $address; ?></td>
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / Genap</td>
		</tr>
		<tr >
			<td>Nama</td>
			<td> : <?php echo $siswa[$z]->display_name;?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr colspan="2">
			<td>NIS/NISN</td>
			<td> : <?php
			if(!empty($siswa[$z]->username)){
				echo $siswa[$z]->username;
			}else{
				echo "-";
			}
			echo " / ";
			if (!empty($profil[$z])) {
				echo $profil[$z]->nisn;
			}else{
				echo "-";
			}
			?></td>
		</tr>
	</table>
	<hr style="width:100%;border: 1px solid black;"/>

	<center><h5>CAPAIAN HASIL BELAJAR</h5></center>

	<b>A. Sikap</b><br>
	<b>1. Sikap Spiritual</b>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th width="15%">Predikat</th>
			<th>Deskripsi</th>
		</tr>
		<tr valign="top">
			<?php if(!empty($peluas2[$z]['Sikap Spiritual - Predikat']) && $peluas2[$z]['Sikap Spiritual - Predikat']!="-") { ?>
				<td height="40px" align="center" valign="middle"><b><?php echo $peluas2[$z]['Sikap Spiritual - Predikat']; ?></b></td>
			<?php }else{ ?>
				<td height="40px" align="center" valign="middle">-</td>
			<?php } ?>
			<?php if(!empty($peluas2[$z]['Sikap Spiritual - Deskripsi']) && $peluas2[$z]['Sikap Spiritual - Deskripsi']!="-") { ?>
				<td height="40px" valign="middle"><?php echo $peluas2[$z]['Sikap Spiritual - Deskripsi']; ?></td>
			<?php }else{ ?>
				<td height="40px" align="center" valign="middle">-</td>
			<?php } ?>
		</tr>
	</table>
	<br/>
	<b>2. Sikap Sosial</b>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th width="15%">Predikat</th>
			<th>Deskripsi</th>
		</tr>
		<tr valign="top">
			<?php if(!empty($peluas2[$z]['Sikap Sosial - Predikat']) && $peluas2[$z]['Sikap Sosial - Predikat']!="-") { ?>
				<td height="45px" align="center" valign="middle"><b><?php echo $peluas2[$z]['Sikap Sosial - Predikat']; ?></b></td>
			<?php }else{ ?>
				<td height="45px" align="center" valign="middle">-</td>
			<?php } ?>
			<?php if(!empty($peluas2[$z]['Sikap Sosial - Deskripsi']) && $peluas2[$z]['Sikap Sosial - Deskripsi']!="-") { ?>
				<td height="45px" valign="middle"><?php echo $peluas2[$z]['Sikap Sosial - Deskripsi']; ?></td>
			<?php }else{ ?>
				<td height="45px" align="center" valign="middle">-</td>
			<?php } ?>
		</tr>
	</table>
	<br/>

	<b>B. Pengetahuan dan Keterampilan</b>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th rowspan="2" align="center" width="5%">No.</th>
			<th rowspan="2" align="center" width="30%">Mata Pelajaran</th>
			<th rowspan="2" width="10%">KKM</th>
			<th colspan="2" align="center">Pengetahuan</th>
			<th colspan="2" align="center">Keterampilan</th>
		</tr>
		<tr>
			<th width="10%" align="center">Nilai</th>
			<th align="center">Predikat</th>
			<th width="10%" align="center">Nilai</th>
			<th align="center">Predikat</th>
		</tr>
		<tr>
			<td colspan="7"><b>Kelompok A (Umum)</b></td>
		</tr>
		<?php
			$no = 1;
			if(!empty($peluas1[$z])){
				foreach ($peluas1[$z] as $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-pengetahuan']) && !empty($rowpeluas1['nilai-keterampilan'])){
						if(!empty($rowpeluas1['nilai-pengetahuan'])){
							if($rowpeluas1['nilai-pengetahuan']<75){
								$rowpeluas1['p-np'] = "D";
							}elseif($rowpeluas1['nilai-pengetahuan']>=75 && $rowpeluas1['nilai-pengetahuan']<80){
								$rowpeluas1['p-np'] = "C";
							}elseif($rowpeluas1['nilai-pengetahuan']>=80 && $rowpeluas1['nilai-pengetahuan']<90){
								$rowpeluas1['p-np'] = "B";
							}elseif($rowpeluas1['nilai-pengetahuan']>=90 && $rowpeluas1['nilai-pengetahuan']<=100){
								$rowpeluas1['p-np'] = "A";
							}else{
								$rowpeluas1['p-np'] = "-";
							}
						}
						if(!empty($rowpeluas1['nilai-keterampilan'])){
							if($rowpeluas1['nilai-keterampilan']<75){
								$rowpeluas1['p-nk'] = "D";
							}elseif($rowpeluas1['nilai-keterampilan']>=75 && $rowpeluas1['nilai-keterampilan']<80){
								$rowpeluas1['p-nk'] = "C";
							}elseif($rowpeluas1['nilai-keterampilan']>=80 && $rowpeluas1['nilai-keterampilan']<90){
								$rowpeluas1['p-nk'] = "B";
							}elseif($rowpeluas1['nilai-keterampilan']>=90 && $rowpeluas1['nilai-keterampilan']<=100){
								$rowpeluas1['p-nk'] = "A";
							}else{
								$rowpeluas1['p-nk'] = "-";
							}
						}

						if($rowpeluas1['kelompok']==1){
		?>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;"><?php echo $no; ?></td>
			<td><?php echo $rowpeluas1['name']; ?></td>
			<td align="center">75</td>
			<?php if(!empty($rowpeluas1['nilai-pengetahuan']) && $rowpeluas1['nilai-pengetahuan']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-pengetahuan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-np']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['nilai-keterampilan']) && $rowpeluas1['nilai-keterampilan']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-keterampilan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-nk']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<?php
							$no++;
						}
					}
				}
			}
		?>
		<tr>
			<td colspan="7"><b>Kelompok B (Umum)</b></td>
		</tr>
		<?php
			$no = 1;
			if(!empty($peluas1[$z])){
				foreach ($peluas1[$z] as $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-pengetahuan']) && !empty($rowpeluas1['nilai-keterampilan'])){
						if(!empty($rowpeluas1['nilai-pengetahuan'])){
							if($rowpeluas1['nilai-pengetahuan']<75){
								$rowpeluas1['p-np'] = "D";
							}elseif($rowpeluas1['nilai-pengetahuan']>=75 && $rowpeluas1['nilai-pengetahuan']<80){
								$rowpeluas1['p-np'] = "C";
							}elseif($rowpeluas1['nilai-pengetahuan']>=80 && $rowpeluas1['nilai-pengetahuan']<90){
								$rowpeluas1['p-np'] = "B";
							}elseif($rowpeluas1['nilai-pengetahuan']>=90 && $rowpeluas1['nilai-pengetahuan']<=100){
								$rowpeluas1['p-np'] = "A";
							}else{
								$rowpeluas1['p-np'] = "-";
							}
						}
						if(!empty($rowpeluas1['nilai-keterampilan'])){
							if($rowpeluas1['nilai-keterampilan']<75){
								$rowpeluas1['p-nk'] = "D";
							}elseif($rowpeluas1['nilai-keterampilan']>=75 && $rowpeluas1['nilai-keterampilan']<80){
								$rowpeluas1['p-nk'] = "C";
							}elseif($rowpeluas1['nilai-keterampilan']>=80 && $rowpeluas1['nilai-keterampilan']<90){
								$rowpeluas1['p-nk'] = "B";
							}elseif($rowpeluas1['nilai-keterampilan']>=90 && $rowpeluas1['nilai-keterampilan']<=100){
								$rowpeluas1['p-nk'] = "A";
							}else{
								$rowpeluas1['p-nk'] = "-";
							}
						}

						if($rowpeluas1['kelompok']==2){
		?>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;"><?php echo $no; ?></td>
			<td><?php echo $rowpeluas1['name']; ?></td>
			<td align="center">75</td>
			<?php if(!empty($rowpeluas1['nilai-pengetahuan']) && $rowpeluas1['nilai-pengetahuan']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-pengetahuan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-np']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['nilai-keterampilan']) && $rowpeluas1['nilai-keterampilan']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-keterampilan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-nk']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<?php
							$no++;
						}
					}
				}
			}
		?>
		<tr>
			<td colspan="7"><b>Kelompok C (Peminatan)</b></td>
		</tr>
		<?php
			$no = 1;
			if(!empty($peluas1[$z])){
				foreach ($peluas1[$z] as $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-pengetahuan']) && !empty($rowpeluas1['nilai-keterampilan'])){
						if(!empty($rowpeluas1['nilai-pengetahuan'])){
							if($rowpeluas1['nilai-pengetahuan']<75){
								$rowpeluas1['p-np'] = "D";
							}elseif($rowpeluas1['nilai-pengetahuan']>=75 && $rowpeluas1['nilai-pengetahuan']<80){
								$rowpeluas1['p-np'] = "C";
							}elseif($rowpeluas1['nilai-pengetahuan']>=80 && $rowpeluas1['nilai-pengetahuan']<90){
								$rowpeluas1['p-np'] = "B";
							}elseif($rowpeluas1['nilai-pengetahuan']>=90 && $rowpeluas1['nilai-pengetahuan']<=100){
								$rowpeluas1['p-np'] = "A";
							}else{
								$rowpeluas1['p-np'] = "-";
							}
						}
						if(!empty($rowpeluas1['nilai-keterampilan'])){
							if($rowpeluas1['nilai-keterampilan']<75){
								$rowpeluas1['p-nk'] = "D";
							}elseif($rowpeluas1['nilai-keterampilan']>=75 && $rowpeluas1['nilai-keterampilan']<80){
								$rowpeluas1['p-nk'] = "C";
							}elseif($rowpeluas1['nilai-keterampilan']>=80 && $rowpeluas1['nilai-keterampilan']<90){
								$rowpeluas1['p-nk'] = "B";
							}elseif($rowpeluas1['nilai-keterampilan']>=90 && $rowpeluas1['nilai-keterampilan']<=100){
								$rowpeluas1['p-nk'] = "A";
							}else{
								$rowpeluas1['p-nk'] = "-";
							}
						}

						if($rowpeluas1['kelompok']==3){
							if(strpos(strtolower($rowpeluas1['name']), "peminatan") !== false){
								$tmp_nama = explode(" ", $rowpeluas1['name']);
								$tmp_nama_count = count($tmp_nama);

								$rowpeluas1['name'] = "";
								for($i=0; $i<$tmp_nama_count; $i++){
									if(strtolower($tmp_nama[$i]) != "peminatan"){
										$rowpeluas1['name'] = $rowpeluas1['name']." ".$tmp_nama[$i];
									}
								}
							}elseif(strpos(strtolower($rowpeluas1['name']), "lintas minat") !== false){
								$tmp_nama = explode(" ", $rowpeluas1['name']);
								$tmp_nama_count = count($tmp_nama);

								$rowpeluas1['name'] = "";
								for($i=0; $i<$tmp_nama_count; $i++){
									if(strtolower($tmp_nama[$i]) != "lintas"){
										if(!empty($tmp_nama[$i+1])){
											if(strtolower($tmp_nama[$i+1]) != "minat"){
												$rowpeluas1['name'] = $rowpeluas1['name']." ".$tmp_nama[$i+1];
											}
										}
									}
								}
								$rowpeluas1['name'] = $rowpeluas1['name']." "."(Lintas Minat)";
							}
		?>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;"><?php echo $no; ?></td>
			<td><?php echo $rowpeluas1['name']; ?></td>
			<td align="center">75</td>
			<?php if(!empty($rowpeluas1['nilai-pengetahuan']) && $rowpeluas1['nilai-pengetahuan']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-pengetahuan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-np']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['nilai-keterampilan']) && $rowpeluas1['nilai-keterampilan']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-keterampilan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-nk']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<?php
							$no++;
						}
					}
				}
			}
		?>
	</table>
	<br/>
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
					................................
				</p>
			</td>
			<td width="30%">
				<p class="text-center">
					<br/>
					Kepala Sekolah
					<br>
					<br>
					<br>
					<br>
					<br>
					<b><?php echo $kepsek;?></b>
					<br>
					<b>NIP. <?php echo $nip = str_replace('-', ' ', $nik);?></b>
				</p>
			</td>
			<td width="30%">
				<p class="text-center">
					Bandung, <?php echo(DateToIndo(date('2016-06-18')));?><br/>
					Wali Kelas
					<br>
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
		</tr>
	</table>
</section>


<!-- Halaman 2 -->
<section id="typography" class="break-after" style="margin-top:10px;">
<!-- 	<sub>
		REG.
		<?php
			$x = $z + 1;
			echo $x;
		?>
	</sub> -->
	<hr style="width:100%;border: 1px solid black;"/>
	<table border="0" style="width:100%;">
		<tr>
			<td>Nama Sekolah</td>
			<td> : <?php echo $school_name; ?></td>
			<td>Kelas</td>
			<td> : <?php echo $siswa[$z]->class->name;?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td> : <?php echo $address; ?></td>
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / Genap</td>
		</tr>
		<tr >
			<td>Nama</td>
			<td> : <?php echo $siswa[$z]->display_name;?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr colspan="2">
			<td>NIS/NISN</td>
			<td> : <?php
			if(!empty($siswa[$z]->username)){
				echo $siswa[$z]->username;
			}else{
				echo "-";
			}
			echo " / ";
			if (!empty($profil[$z])) {
				echo $profil[$z]->nisn;
			}else{
				echo "-";
			}
			?></td>
		</tr>
	</table>
	<hr style="width:100%;border: 1px solid black;"/>

	<b>Deskripsi Pengetahuan dan Keterampilan</b>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th align="center" width="5%">No.</th>
			<th align="center" width="20%">Mata Pelajaran</th>
			<th align="center" width="15%">Aspek</th>
			<th align="center">Deskripsi</th>
		</tr>
		<tr>
			<td colspan="4"><b>Kelompok A (Umum)</b></td>
		</tr>
		<?php
			$no = 1;
			if(!empty($peluas1[$z])){
				foreach ($peluas1[$z] as $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-pengetahuan']) && !empty($rowpeluas1['nilai-keterampilan'])){
						if($rowpeluas1['kelompok']==1) {
		?>
		<tr>
			<td rowspan="2" align="center"><?php echo $no; ?></td>
			<?php if(!empty($rowpeluas1['name']) && $rowpeluas1['name']!="-"){ ?>
				<td rowspan="2"><?php echo $rowpeluas1['name']; ?></td>
			<?php }else{ ?>
				<td>-</td>
			<?php } ?>
			<td align="center">Pengetahuan</td>
			<?php if(!empty($rowpeluas1['desc-desc_pengetahuan']) && $rowpeluas1['desc-desc_pengetahuan']!="-") { ?>
				<td><?php echo $rowpeluas1['desc-desc_pengetahuan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<tr>
			<td align="center">Keterampilan</td>
			<?php if(!empty($rowpeluas1['desc-desc_keterampilan']) && $rowpeluas1['desc-desc_keterampilan']!="-") { ?>
				<td><?php echo $rowpeluas1['desc-desc_keterampilan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<?php
							$no++;
						}
					}
				}
			}
		?>
		<tr>
			<td colspan="4"><b>Kelompok B (Umum)</b></td>
		</tr>
		<?php
			$no = 1;
			if(!empty($peluas1[$z])){
				foreach ($peluas1[$z] as $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-pengetahuan']) && !empty($rowpeluas1['nilai-keterampilan'])){
						if($rowpeluas1['kelompok']==2) {
							if($no==1){
		?>
		<tr>
			<td rowspan="2" align="center"><?php echo $no; ?></td>
			<?php if(!empty($rowpeluas1['name']) && $rowpeluas1['name']!="-"){ ?>
				<td rowspan="2"><?php echo $rowpeluas1['name']; ?></td>
			<?php }else{ ?>
				<td>-</td>
			<?php } ?>
			<td align="center">Pengetahuan</td>
			<?php if(!empty($rowpeluas1['desc-desc_pengetahuan']) && $rowpeluas1['desc-desc_pengetahuan']!="-") { ?>
				<td><?php echo $rowpeluas1['desc-desc_pengetahuan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<tr>
			<td align="center">Keterampilan</td>
			<?php if(!empty($rowpeluas1['desc-desc_keterampilan']) && $rowpeluas1['desc-desc_keterampilan']!="-") { ?>
				<td><?php echo $rowpeluas1['desc-desc_keterampilan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<?php
							}
							$no++;
						}
					}
				}
			}
		?>
	</table>
</section>


<!-- Halaman 3 -->
<section id="typography" style="margin-top:10px;">
<!-- 	<sub>
		REG.
		<?php
			$x = $z + 1;
			echo $x;
		?>
	</sub> -->
<!-- 	<hr style="width:100%;border: 1px solid black;"/>
	<table border="0" style="width:100%;">
		<tr>
			<td>Nama Sekolah</td>
			<td> : <?php echo $school_name; ?></td>
			<td>Kelas</td>
			<td> : <?php echo $siswa[$z]->class->name;?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td> : <?php echo $address; ?></td>
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / Genap</td>
		</tr>
		<tr >
			<td>Nama</td>
			<td> : <?php echo $siswa[$z]->display_name;?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr colspan="2">
			<td>NIS/NISN</td>
			<td> : <?php
			if(!empty($siswa[$z]->username)){
				echo $siswa[$z]->username;
			}else{
				echo "-";
			}
			echo " / ";
			if (!empty($profil[$z])) {
				echo $profil[$z]->nisn;
			}else{
				echo "-";
			}
			?></td>
		</tr>
	</table>
	<hr style="width:100%;border: 1px solid black;"/> -->

<!-- 	<br/> -->

	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th align="center" width="5%">No.</th>
			<th align="center" width="20%">Mata Pelajaran</th>
			<th align="center" width="15%">Aspek</th>
			<th align="center">Deskripsi</th>
		</tr>
		<?php
			$no = 1;
			if(!empty($peluas1[$z])){
				foreach ($peluas1[$z] as $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-pengetahuan']) && !empty($rowpeluas1['nilai-keterampilan'])){
						if($rowpeluas1['kelompok']==2) {
							if($no!=1){
		?>
		<tr>
			<td rowspan="2" align="center"><?php echo $no; ?></td>
			<?php if(!empty($rowpeluas1['name']) && $rowpeluas1['name']!="-"){ ?>
				<td rowspan="2"><?php echo $rowpeluas1['name']; ?></td>
			<?php }else{ ?>
				<td>-</td>
			<?php } ?>
			<td align="center">Pengetahuan</td>
			<?php if(!empty($rowpeluas1['desc-desc_pengetahuan']) && $rowpeluas1['desc-desc_pengetahuan']!="-") { ?>
				<td><?php echo $rowpeluas1['desc-desc_pengetahuan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<tr>
			<td align="center">Keterampilan</td>
			<?php if(!empty($rowpeluas1['desc-desc_keterampilan']) && $rowpeluas1['desc-desc_keterampilan']!="-") { ?>
				<td><?php echo $rowpeluas1['desc-desc_keterampilan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<?php
							}
							$no++;
						}
					}
				}
			}
		?>
		<tr>
			<td colspan="4"><b>Kelompok C (Peminatan)</b></td>
		</tr>
		<?php
			$no = 1;
			if(!empty($peluas1[$z])){
				foreach ($peluas1[$z] as $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-pengetahuan']) && !empty($rowpeluas1['nilai-keterampilan'])){
						if($rowpeluas1['kelompok']==3) {
							if(strpos(strtolower($rowpeluas1['name']), "peminatan") !== false){
								$tmp_nama = explode(" ", $rowpeluas1['name']);
								$tmp_nama_count = count($tmp_nama);

								$rowpeluas1['name'] = "";
								for($i=0; $i<$tmp_nama_count; $i++){
									if(strtolower($tmp_nama[$i]) != "peminatan"){
										$rowpeluas1['name'] = $rowpeluas1['name']." ".$tmp_nama[$i];
									}
								}
							}elseif(strpos(strtolower($rowpeluas1['name']), "lintas minat") !== false){
								$tmp_nama = explode(" ", $rowpeluas1['name']);
								$tmp_nama_count = count($tmp_nama);

								$rowpeluas1['name'] = "";
								for($i=0; $i<$tmp_nama_count; $i++){
									if(strtolower($tmp_nama[$i]) != "lintas"){
										if(!empty($tmp_nama[$i+1])){
											if(strtolower($tmp_nama[$i+1]) != "minat"){
												$rowpeluas1['name'] = $rowpeluas1['name']." ".$tmp_nama[$i+1];
											}
										}
									}
								}
								$rowpeluas1['name'] = $rowpeluas1['name']." "."(Lintas Minat)";
							}
		?>
		<tr>
			<td rowspan="2" align="center"><?php echo $no; ?></td>
			<?php if(!empty($rowpeluas1['name']) && $rowpeluas1['name']!="-"){ ?>
				<td rowspan="2"><?php echo $rowpeluas1['name']; ?></td>
			<?php }else{ ?>
				<td>-</td>
			<?php } ?>
			<td align="center">Pengetahuan</td>
			<?php if(!empty($rowpeluas1['desc-desc_pengetahuan']) && $rowpeluas1['desc-desc_pengetahuan']!="-") { ?>
				<td><?php echo $rowpeluas1['desc-desc_pengetahuan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<tr>
			<td align="center">Keterampilan</td>
			<?php if(!empty($rowpeluas1['desc-desc_keterampilan']) && $rowpeluas1['desc-desc_keterampilan']!="-") { ?>
				<td><?php echo $rowpeluas1['desc-desc_keterampilan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<?php
							$no++;
						}
					}
				}
			}
		?>
	</table>
</section>


<!-- Halaman 4 -->
<section id="typography" class="break-after break-before" style="margin-top:10px;">
<!-- 	<sub>
		REG.
		<?php
			$x = $z + 1;
			echo $x;
		?>
	</sub> -->
	<hr style="width:100%;border: 1px solid black;"/>
	<table border="0" style="width:100%;">
		<tr>
			<td>Nama Sekolah</td>
			<td> : <?php echo $school_name; ?></td>
			<td>Kelas</td>
			<td> : <?php echo $siswa[$z]->class->name;?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td> : <?php echo $address; ?></td>
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / Genap</td>
		</tr>
		<tr >
			<td>Nama</td>
			<td> : <?php echo $siswa[$z]->display_name;?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr colspan="2">
			<td>NIS/NISN</td>
			<td> : <?php
			if(!empty($siswa[$z]->username)){
				echo $siswa[$z]->username;
			}else{
				echo "-";
			}
			echo " / ";
			if (!empty($profil[$z])) {
				echo $profil[$z]->nisn;
			}else{
				echo "-";
			}
			?></td>
		</tr>
	</table>
	<hr style="width:100%;border: 1px solid black;"/>

	<b>C. Ekstra Kurikuler</b>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th align="center" width="5%">No.</th>
			<th align="center" width="35%">Kegiatan Ekstrakurikuler</th>
			<th align="center">Nilai / Deskripsi</th>
		</tr>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;">1</td>
			<?php if(!empty($peluas2[$z]['Ekstrakurikuler 1 - Nama']) && $peluas2[$z]['Ekstrakurikuler 1 - Nama']!="-"){ ?>
				<td><?php echo strtoupper($peluas2[$z]['Ekstrakurikuler 1 - Nama']); ?></td>
			<?php }else{ ?>
				<td>-</td>
			<?php } ?>
			<td align="center">
			<?php
				if(!empty($peluas2[$z]['Ekstrakurikuler 1 - Nilai']) && $peluas2[$z]['Ekstrakurikuler 1 - Nilai']!="-"){
					echo strtoupper($peluas2[$z]['Ekstrakurikuler 1 - Nilai']);
					if(!empty($peluas2[$z]['Ekstrakurikuler 1 - Deskripsi']) && $peluas2[$z]['Ekstrakurikuler 1 - Deskripsi']!="-"){
						echo ", ".$peluas2[$z]['Ekstrakurikuler 1 - Deskripsi'];
					}
				}else{
					if(!empty($peluas2[$z]['Ekstrakurikuler 1 - Deskripsi']) && $peluas2[$z]['Ekstrakurikuler 1 - Deskripsi']!="-"){
						echo $peluas2[$z]['Ekstrakurikuler 1 - Deskripsi'];
					}else{
						echo "-";
					}
				}
			?>
			</td>
		</tr>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;">2</td>
			<?php if(!empty($peluas2[$z]['Ekstrakurikuler 2 - Nama']) && $peluas2[$z]['Ekstrakurikuler 2 - Nama']!="-"){ ?>
				<td><?php echo strtoupper($peluas2[$z]['Ekstrakurikuler 2 - Nama']); ?></td>
			<?php }else{ ?>
				<td>-</td>
			<?php } ?>
			<td align="center">
			<?php
				if(!empty($peluas2[$z]['Ekstrakurikuler 2 - Nilai']) && $peluas2[$z]['Ekstrakurikuler 2 - Nilai']!="-"){
					echo strtoupper($peluas2[$z]['Ekstrakurikuler 2 - Nilai']);
					if(!empty($peluas2[$z]['Ekstrakurikuler 2 - Deskripsi']) && $peluas2[$z]['Ekstrakurikuler 2 - Deskripsi']!="-"){
						echo ", ".$peluas2[$z]['Ekstrakurikuler 2 - Deskripsi'];
					}
				}else{
					if(!empty($peluas2[$z]['Ekstrakurikuler 2 - Deskripsi']) && $peluas2[$z]['Ekstrakurikuler 2 - Deskripsi']!="-"){
						echo $peluas2[$z]['Ekstrakurikuler 2 - Deskripsi'];
					}else{
						echo "-";
					}
				}
			?>
			</td>
		</tr>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;">3</td>
			<?php if(!empty($peluas2[$z]['Ekstrakurikuler 3 - Nama']) && $peluas2[$z]['Ekstrakurikuler 3 - Nama']!="-"){ ?>
				<td><?php echo strtoupper($peluas2[$z]['Ekstrakurikuler 3 - Nama']); ?></td>
			<?php }else{ ?>
				<td>-</td>
			<?php } ?>
			<td align="center">
			<?php
				if(!empty($peluas2[$z]['Ekstrakurikuler 3 - Nilai']) && $peluas2[$z]['Ekstrakurikuler 3 - Nilai']!="-"){
					echo strtoupper($peluas2[$z]['Ekstrakurikuler 3 - Nilai']);
					if(!empty($peluas2[$z]['Ekstrakurikuler 3 - Deskripsi']) && $peluas2[$z]['Ekstrakurikuler 3 - Deskripsi']!="-"){
						echo ", ".$peluas2[$z]['Ekstrakurikuler 3 - Deskripsi'];
					}
				}else{
					if(!empty($peluas2[$z]['Ekstrakurikuler 3 - Deskripsi']) && $peluas2[$z]['Ekstrakurikuler 3 - Deskripsi']!="-"){
						echo $peluas2[$z]['Ekstrakurikuler 3 - Deskripsi'];
					}else{
						echo "-";
					}
				}
			?>
			</td>
		</tr>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;">4</td>
			<?php if(!empty($peluas2[$z]['Ekstrakurikuler 4 - Nama']) && $peluas2[$z]['Ekstrakurikuler 4 - Nama']!="-"){ ?>
				<td><?php echo strtoupper($peluas2[$z]['Ekstrakurikuler 4 - Nama']); ?></td>
			<?php }else{ ?>
				<td>-</td>
			<?php } ?>
			<td align="center">
			<?php
				if(!empty($peluas2[$z]['Ekstrakurikuler 4 - Nilai']) && $peluas2[$z]['Ekstrakurikuler 4 - Nilai']!="-"){
					echo strtoupper($peluas2[$z]['Ekstrakurikuler 4 - Nilai']);
					if(!empty($peluas2[$z]['Ekstrakurikuler 4 - Deskripsi']) && $peluas2[$z]['Ekstrakurikuler 4 - Deskripsi']!="-"){
						echo ", ".$peluas2[$z]['Ekstrakurikuler 4 - Deskripsi'];
					}
				}else{
					if(!empty($peluas2[$z]['Ekstrakurikuler 4 - Deskripsi']) && $peluas2[$z]['Ekstrakurikuler 4 - Deskripsi']!="-"){
						echo $peluas2[$z]['Ekstrakurikuler 4 - Deskripsi'];
					}else{
						echo "-";
					}
				}
			?>
			</td>
		</tr>
	</table>
	<br/>

	<b>D. Prestasi</b>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th align="center" width="5%">No.</th>
			<th align="center" width="30%">Jenis Kegiatan</th>
			<th align="center">Keterangan</th>
		</tr>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;">1</td>
			<?php if(!empty($peluas2[$z]['Prestasi 1 - Jenis Kegiatan']) && $peluas2[$z]['Prestasi 1 - Jenis Kegiatan']!="-"){ ?>
				<td><?php echo $peluas2[$z]['Prestasi 1 - Jenis Kegiatan']; ?></td>
			<?php }else{ ?>
				<td>-</td>
			<?php } ?>
			<?php if(!empty($peluas2[$z]['Prestasi 1 - Keterangan']) && $peluas2[$z]['Prestasi 1 - Keterangan']!="-"){ ?>
				<td align="center"><?php echo $peluas2[$z]['Prestasi 1 - Keterangan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;">2</td>
			<?php if(!empty($peluas2[$z]['Prestasi 2 - Jenis Kegiatan']) && $peluas2[$z]['Prestasi 2 - Jenis Kegiatan']!="-"){ ?>
				<td><?php echo $peluas2[$z]['Prestasi 2 - Jenis Kegiatan']; ?></td>
			<?php }else{ ?>
				<td>-</td>
			<?php } ?>
			<?php if(!empty($peluas2[$z]['Prestasi 2 - Keterangan']) && $peluas2[$z]['Prestasi 2 - Keterangan']!="-"){ ?>
				<td align="center"><?php echo $peluas2[$z]['Prestasi 2 - Keterangan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;">3</td>
			<?php if(!empty($peluas2[$z]['Prestasi 3 - Jenis Kegiatan']) && $peluas2[$z]['Prestasi 3 - Jenis Kegiatan']!="-"){ ?>
				<td><?php echo $peluas2[$z]['Prestasi 3 - Jenis Kegiatan']; ?></td>
			<?php }else{ ?>
				<td>-</td>
			<?php } ?>
			<?php if(!empty($peluas2[$z]['Prestasi 3 - Keterangan']) && $peluas2[$z]['Prestasi 3 - Keterangan']!="-"){ ?>
				<td align="center"><?php echo $peluas2[$z]['Prestasi 3 - Keterangan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;">4</td>
			<?php if(!empty($peluas2[$z]['Prestasi 4 - Jenis Kegiatan']) && $peluas2[$z]['Prestasi 4 - Jenis Kegiatan']!="-"){ ?>
				<td><?php echo $peluas2[$z]['Prestasi 4 - Jenis Kegiatan']; ?></td>
			<?php }else{ ?>
				<td>-</td>
			<?php } ?>
			<?php if(!empty($peluas2[$z]['Prestasi 4 - Keterangan']) && $peluas2[$z]['Prestasi 4 - Keterangan']!="-"){ ?>
				<td align="center"><?php echo $peluas2[$z]['Prestasi 4 - Keterangan']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
	</table>
	<br/>

	<b>E. Ketidakhadiran</b>
	<table border="5" style="width:50%;border: 2px solid black;">
		<tr>
			<td width="60%">Sakit</td>
			<?php if(!empty($peluas2[$z]['Absensi Sakit']) && $peluas2[$z]['Absensi Sakit']!="-") { ?>
				<td align="center"><?php echo $peluas2[$z]['Absensi Sakit']; ?> hari</td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<tr>
			<td width="60%">Izin</td>
			<?php if(!empty($peluas2[$z]['Absensi Izin']) && $peluas2[$z]['Absensi Izin']!="-") { ?>
				<td align="center"><?php echo $peluas2[$z]['Absensi Izin']; ?> hari</td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
		<tr>
			<td width="60%">Tanpa Keterangan</td>
			<?php if(!empty($peluas2[$z]['Absensi Alfa']) && $peluas2[$z]['Absensi Alfa']!="-") { ?>
				<td align="center"><?php echo $peluas2[$z]['Absensi Alfa']; ?> hari</td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
		</tr>
	</table>
	<br/>

	<b>F. Catatan Wali Kelas</b>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<?php if(!empty($peluas2[$z]['Catatan Wali Kelas']) && $peluas2[$z]['Catatan Wali Kelas']!="-"){ ?>
				<td height="50px"><?php echo $peluas2[$z]['Catatan Wali Kelas']; ?></td>
			<?php }else{ ?>
				<td height="50px"></td>
			<?php } ?>
		</tr>
	</table>
	<br/>

	<b>G. Tanggapan Orang tua/Wali</b>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<td height="50px"></td>
		</tr>
	</table>
	<br/>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr valign="center">
			<td height="20px">
				<b>Keterangan Kenaikan Kelas:</b> Naik/Tidak Naik *) Ke Kelas XI/XII *)
			</td>
		</tr>
	</table>
	<sub>*) Coret yang tidak perlu</sub>
	<br/>
	<table border="0" style="width:100%;border: 0px solid black;margin-top: 0.5px;margin-bottom: 0.5px;">
		<tr>
			<td width="50%">
				<p class="text-center">
					Mengetahui<br/>
					Orang Tua/Wali
					<br>
					<br>
					<br>
					<br>
					<br>
					................................
				</p>
			</td>
			<td width="50%">
				<p class="text-center">
					Bandung, <?php echo(DateToIndo(date('2016-06-18')));?><br/>
					Wali Kelas
					<br>
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
		</tr>
		<tr>
			<td width="100%" colspan="2">
				<p class="text-center">
					Kepala Sekolah
					<br>
					<br>
					<br>
					<br>
					<br>
					<b><?php echo $kepsek;?></b>
					<br>
					<b>NIP. <?php echo $nip = str_replace('-', ' ', $nik);?></b>
				</p>
			</td>
		</tr>
	</table>
</section>
<?php } ?>
<script>print();</script>
</body>
</html>
