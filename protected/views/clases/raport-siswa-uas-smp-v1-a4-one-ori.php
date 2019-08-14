<html>
<head>
	<style type="text/css">
		<?php echo $inline_style ?>

		* {
			font-size: 14px;
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
	// 	$kkm = 78;
	// } elseif (strpos(strtolower($nama_kelas),'10 ')!==false || strpos(strtolower($nama_kelas),'x ')!==false) {
		// $kkm = 75;
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
	$semester_db = Option::model()->findAll(array('condition'=>'key_config LIKE "%semester%"'));
	$tahun_ajaran_db = Option::model()->findAll(array('condition'=>'key_config LIKE "%tahun_ajaran%"'));

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

	if(Yii::app()->session['semester']){
		$semester = Yii::app()->session['semester'];

			if(!empty($semester)){
				if($semester == 1){
					$smt = "1 (Satu)";
					$penanda = 1;
					$gage = "Ganjil";
				}else{
					$smt = "2 (Dua)";
					$penanda = 2;
					$gage = "Genap";
				}
			}
	} else {
		$semester = Option::model()->findAll(array('condition'=>'key_config LIKE "%semester%"'));
	
			if(!empty($semester) and !empty($semester[0]->value)){
				if($semester == 1){
					$smt = "1 (Satu)";
					$penanda = 1;
					$gage = "Ganjil";
				}else{
					$smt = "2 (Dua)";
					$penanda = 2;
					$gage = "Genap";
				}
			}
	}

	if(Yii::app()->session['tahun_ajaran']){
		$tahun_ajaran = Yii::app()->session['tahun_ajaran'];

			if(!empty($tahun_ajaran)){
				// if($penanda == 2){
					$ta = ($tahun_ajaran-1)."/".$tahun_ajaran;
				// }else{
					// $ta = $tahun_ajaran."/".($tahun_ajaran+1);
				// }
			}else{
				$ta = "";
			}


	} else {
		$tahun_ajaran = Option::model()->findAll(array('condition'=>'key_config LIKE "%tahun_ajaran%"'));
	
			if(!empty($tahun_ajaran) and !empty($tahun_ajaran[0]->value)){
				// if($penanda == 2){
					$ta = ($tahun_ajaran[0]->value-1)."/".$tahun_ajaran[0]->value;
				// }else{
					// $ta = $tahun_ajaran[0]->value."/".($tahun_ajaran[0]->value+1);
				// }
			}else{
				$ta = "";
			}

	}


	if ($optSchoolType=="SMP NEGERI 43 BANDUNG") {
							$kkm = 65;
						}	else {
							$kkm = 75;
						}
?>


<section id="typography" class="break-after" style="margin-top:10px;">
	<sub>
		
	</sub>
	
	<br/>

	<center><h4>PENCAPAIAN KOMPETENSI PESERTA DIDIK</h4></center>
	<br/>

	<table border="0" style="width:100%;">
		<tr>
			<td>Nama Sekolah</td>
			<td> : <?php echo $school_name; ?></td>
			<td>Kelas</td>
			<td> : <?php 

			if( $tahun_ajaran != $tahun_ajaran_db[0]->value ) {
					$kelasnya = ClassHistory::model()->findAll(array('condition'=>'user_id = "'.$siswa->id.'"')); 
					echo $kelasnya[0]->class->name;
				} else {
					echo $siswa->class->name;
				}
			?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td> : <?php echo $address; ?></td>
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / <?php echo $gage; ?></td>
		</tr>
		<tr >
			<td>Nama</td>
			<td> : <?php echo $siswa->display_name;?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr colspan="2">
			<td>NIS</td>
			<td> : <?php
			if(!empty($siswa->username)){
				echo $siswa->username;
			}else{
				echo "-";
			}
			
			?></td>
		</tr>
	</table>
	
	<br/>

	<ol type="A">
		<b><li>
			Sikap
			<ol type="1">
				<b><li>
					Sikap Spiritual
					<table border="5" style="width:100%;border: 2px solid black;">
						<tr>
							<th width="20%">Predikat</th>
							<th width="60%">Deskripsi</th>
						</tr>
						<tr valign="top">
							<?php if(!empty($peluas2['Sikap Spiritual - Predikat']) && $peluas2['Sikap Spiritual - Predikat']!="-") { ?>
								<td height="150px" align="center" valign="middle"><b><?php echo $peluas2['Sikap Spiritual - Predikat']; ?></b></td>
							<?php }else{ ?>
								<td height="150px" align="center" valign="middle">-</td>
							<?php } ?>
							<?php if(!empty($peluas2['Sikap Spiritual - Deskripsi']) && $peluas2['Sikap Spiritual - Deskripsi']!="-") { ?>
								<td height="150px" valign="middle"><?php 
								$spiritualDesc = $peluas2['Sikap Spiritual - Deskripsi'];
								$spiritualDesc = str_replace(' , ',', ',$spiritualDesc);
								$spiritualDesc = str_replace(' .. ','. ',$spiritualDesc);
								$spiritualDesc = str_replace(' .','. ',$spiritualDesc);
								$spiritualDesc = str_replace('.','. ',$spiritualDesc);
								$spiritualDesc = str_replace('.  ','. ',$spiritualDesc);
								$spiritualDesc = str_replace('. . ','. ',$spiritualDesc);

								echo $spiritualDesc."."; 


								?></td>
							<?php }else{ ?>
								<td height="150px" align="center" valign="middle">-</td>
							<?php } ?>
						</tr>
					</table>
					<br/><br/><br/>
				</li></b>
				<b><li>
					Sikap Sosial
					<table border="5" style="width:100%;border: 2px solid black;">
						<tr>
							<th width="20%">Predikat</th>
							<th width="60%">Deskripsi</th>
						</tr>
						<tr valign="top">
							<?php if(!empty($peluas2['Sikap Sosial - Predikat']) && $peluas2['Sikap Sosial - Predikat']!="-") { ?>
								<td height="150px" align="center" valign="middle"><b><?php echo $peluas2['Sikap Sosial - Predikat']; ?></b></td>
							<?php }else{ ?>
								<td height="150px" align="center" valign="middle">-</td>
							<?php } ?>
							<?php if(!empty($peluas2['Sikap Sosial - Deskripsi']) && $peluas2['Sikap Sosial - Deskripsi']!="-") { ?>
								<td height="150px" valign="middle"><?php 
								$sosialDesc = $peluas2['Sikap Sosial - Deskripsi'];
								$sosialDesc = str_replace(' , ',', ',$sosialDesc);
								$sosialDesc = str_replace(' .. ','. ',$sosialDesc);
								$sosialDesc = str_replace(' .','. ',$sosialDesc);
								$sosialDesc = str_replace('.','. ',$sosialDesc);
								$sosialDesc = str_replace('.  ','. ',$sosialDesc);
								$sosialDesc = str_replace('. . ','. ',$sosialDesc);

								echo $sosialDesc.".";

								?></td>
							<?php }else{ ?>
								<td height="150px" align="center" valign="middle">-</td>
							<?php } ?>
						</tr>
					</table>
					<br/><br/><br/>
				</li></b>
			</ol>
		</li></b>
	</ol>
</section>


<!-- Halaman 2 -->
<section id="typography" class="break-after" style="margin-top:10px;">
	<sub>
		
	</sub>
	
	<table border="0" style="width:100%;">
		<tr>
			<td>Nama Sekolah</td>
			<td> : <?php echo $school_name; ?></td>
			<td>Kelas</td>
			<td> : <?php 

			if( $tahun_ajaran != $tahun_ajaran_db[0]->value ) {
					$kelasnya = ClassHistory::model()->findAll(array('condition'=>'user_id = "'.$siswa->id.'"')); 
					echo $kelasnya[0]->class->name;
				} else {
					echo $siswa->class->name;
				}
			?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td> : <?php echo $address; ?></td>
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / <?php echo $gage; ?></td>
		</tr>
		<tr >
			<td>Nama</td>
			<td> : <?php echo $siswa->display_name;?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr colspan="2">
			<td>NIS</td>
			<td> : <?php
			if(!empty($siswa->username)){
				echo $siswa->username;
			}else{
				echo "-";
			}
			// echo " / ";
			if (!empty($profil)) {
				// echo $profil->nisn;
			}else{
				// echo "-";
			}
			?></td>
		</tr>
	</table>
	
	<br/><br/>

	<ol type="A" start="2">
		<b><li>
			Pengetahuan dan Keterampilan</br>
			Kriteria Ketuntasan Minimal: <?php echo $kkm; ?>
			<table border="5" style="width:100%;border: 2px solid black;">
				<tr>
					<th rowspan="2" align="center" width="5%">No.</th>
					<th rowspan="2" align="center" width="30%">Mata Pelajaran</th>
					<th colspan="3" align="center">Pengetahuan</th>
				</tr>
				<tr>
					<th align="center">Nilai</th>
					
					<th align="center">Predikat</th>
					<th align="center">Deskripsi</th>
				</tr>

				<tr>
					<td colspan="6"><b>Kelompok A (Umum)</b></td>
				</tr>
				<?php
					$no = 1;
					if(!empty($peluas1)){
						
						foreach ($peluas1 as $rowpeluas1) {


					// 		if (strpos(strtolower($nama_kelas),'9-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika' or $rowpeluas1['name'] =='Pendidikan Jasmani, Olah Raga, dan Kesehatan') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'8-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'7-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } else {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// }

						

							

							if($no<5){
							if(!empty($rowpeluas1['nilai-pengetahuan']) && !empty($rowpeluas1['nilai-keterampilan'])){
								if(!empty($rowpeluas1['nilai-pengetahuan'])){
									$rowpeluas1['p-np'] = Clases::model()->predikat($rowpeluas1['nilai-pengetahuan'],$kkm); 
							
								}
								if(!empty($rowpeluas1['nilai-keterampilan'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-keterampilan'],$kkm); 
								}

								if($rowpeluas1['kelompok']==1){
				?>
				<tr>
					<td align="center"><?php echo $no; ?></td>
					<td><?php echo $rowpeluas1['name']; ?></td>
					
					<?php if(!empty($rowpeluas1['nilai-pengetahuan']) && $rowpeluas1['nilai-pengetahuan']!='-') { ?>
						<td align="center"><?php echo $rowpeluas1['nilai-pengetahuan']; ?></td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
					<!-- <td align ="center"><?php //echo $kkm;?></td> -->
					<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
						<td align="center"><?php echo $rowpeluas1['p-np']; ?></td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
	
				<?php if(!empty($rowpeluas1['nilai-kddescription']) && $rowpeluas1['nilai-kddescription']!="-") { ?>
				<td align="left">
					<?php
								$peng_desc = str_replace(' , ',', ',implode(",", $rowpeluas1['nilai-kddescription']));
								$peng_desc = str_replace(' ,',', ',$peng_desc);
								$peng_desc = str_replace(',',', ',$peng_desc);
								$peng_desc = str_replace(',  ',', ',$peng_desc);
								$peng_desc = str_replace(' .. ','. ',$peng_desc);
								$peng_desc = str_replace(' .','. ',$peng_desc);
								$peng_desc = str_replace('.','. ',$peng_desc);
								$peng_desc = str_replace('.  ','. ',$peng_desc);
								$peng_desc = str_replace('. . ','. ',$peng_desc);

					if (!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="A") {
						echo "Memiliki kemampuan sangat baik dalam ".$peng_desc;
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="B"){
						echo "Memiliki kemampuan baik dalam ".$peng_desc;
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="C"){
						echo "Memiliki kemampuan cukup baik dalam ".$peng_desc;
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="D"){
						echo "Memiliki kemampuan kurang dalam ".$peng_desc;
					}
					?>
				</td>
				<?php } else { ?>
				<td align="center">-</td>
				<?php }?>
			
				</tr>
				<?php
									$no++;
									}
								}
							}
						}
					}
				?>
				
			</table>
			<br/>

		</li></b>
	</ol>
</section>


<!-- Halaman 2-2 -->
<section id="typography" class="break-after" style="margin-top:10px;">
	<sub>
		
	</sub>
	
	<table border="0" style="width:100%;">
		<tr>
			<td>Nama Sekolah</td>
			<td> : <?php echo $school_name; ?></td>
			<td>Kelas</td>
			<td> : <?php 

			if( $tahun_ajaran != $tahun_ajaran_db[0]->value ) {
					$kelasnya = ClassHistory::model()->findAll(array('condition'=>'user_id = "'.$siswa->id.'"')); 
					echo $kelasnya[0]->class->name;
				} else {
					echo $siswa->class->name;
				}
			?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td> : <?php echo $address; ?></td>
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / <?php echo $gage; ?></td>
		</tr>
		<tr >
			<td>Nama</td>
			<td> : <?php echo $siswa->display_name;?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr colspan="2">
			<td>NIS</td>
			<td> : <?php
			if(!empty($siswa->username)){
				echo $siswa->username;
			}else{
				echo "-";
			}
			// echo " / ";
			if (!empty($profil)) {
			//	echo $profil->nisn;
			}else{
			//	echo "-";
			}
			?></td>
		</tr>
	</table>
	
	<br/><br/>

	<ol type="A" start="2">
		<b>
			
			Kriteria Ketuntasan Minimal: <?php echo $kkm; ?>
			<table border="5" style="width:100%;border: 2px solid black;">
				<tr>
					<th rowspan="2" align="center" width="5%">No.</th>
					<th rowspan="2" align="center" width="30%">Mata Pelajaran</th>
					<th colspan="3" align="center">Pengetahuan</th>
				</tr>
				<tr>
					<th align="center">Nilai</th>
					<!-- <th align="center">KKM</th -->
					<th align="center">Predikat</th>
					<th align="center">Deskripsi</th>
				</tr>

				<tr>
					<td colspan="6"><b>Kelompok A (Umum)</b></td>
				</tr>
				<?php
					$no = 1;
					if(!empty($peluas1)){
						foreach ($peluas1 as $rowpeluas1) {


					// 		if (strpos(strtolower($nama_kelas),'9-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika' or $rowpeluas1['name'] =='Pendidikan Jasmani, Olah Raga, dan Kesehatan') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'8-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'7-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } else {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// }

							if ($optSchoolType=="SMP NEGERI 43 BANDUNG") {
							$kkm = 65;
						}	else {
							$kkm = 75;
						}

							
							if(!empty($rowpeluas1['nilai-pengetahuan']) && !empty($rowpeluas1['nilai-keterampilan'])){
								if(!empty($rowpeluas1['nilai-pengetahuan'])){
									$rowpeluas1['p-np'] = Clases::model()->predikat($rowpeluas1['nilai-pengetahuan'],$kkm); 
							
								}
								if(!empty($rowpeluas1['nilai-keterampilan'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-keterampilan'],$kkm); 
								}

								if($rowpeluas1['kelompok']==1){
										if($no>4){
				?>
				<tr>
					<td align="center"><?php echo $no; ?></td>
					<td><?php echo $rowpeluas1['name']; ?></td>
					
					<?php if(!empty($rowpeluas1['nilai-pengetahuan']) && $rowpeluas1['nilai-pengetahuan']!='-') { ?>
						<td align="center"><?php echo $rowpeluas1['nilai-pengetahuan']; ?></td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
				<!-- 	<td align ="center"><?php //echo $kkm;?></td> -->
					<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
						<td align="center"><?php echo $rowpeluas1['p-np']; ?></td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
		
				<?php if(!empty($rowpeluas1['nilai-kddescription']) && $rowpeluas1['nilai-kddescription']!="-") { ?>
				<td align="left">
					<?php 

						$peng_desc = str_replace(' , ',', ',implode(",", $rowpeluas1['nilai-kddescription']));
								$peng_desc = str_replace(' ,',', ',$peng_desc);
								$peng_desc = str_replace(',',', ',$peng_desc);
								$peng_desc = str_replace(',  ',', ',$peng_desc);
								$peng_desc = str_replace(' .. ','. ',$peng_desc);
								$peng_desc = str_replace(' .','. ',$peng_desc);
								$peng_desc = str_replace('.','. ',$peng_desc);
								$peng_desc = str_replace('.  ','. ',$peng_desc);
								$peng_desc = str_replace('. . ','. ',$peng_desc);


					if (!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="A") {
						echo "Memiliki kemampuan sangat baik dalam ".$peng_desc;
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="B"){
						echo "Memiliki kemampuan baik dalam ".$peng_desc;
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="C"){
						echo "Memiliki kemampuan cukup baik dalam ".$peng_desc;
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="D"){
						echo "Memiliki kemampuan kurang dalam ".$peng_desc;
					}

					?>
				</td>
				<?php } else { ?>
				<td align="center">-</td>
				<?php }?>
			
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
					<td colspan="6"><b>Kelompok B (Umum)</b></td>
				</tr>
				<?php
					$no = 1;
					if(!empty($peluas1)){
						foreach ($peluas1 as $rowpeluas1) {


					// 		if (strpos(strtolower($nama_kelas),'9-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika' or $rowpeluas1['name'] =='Pendidikan Jasmani, Olah Raga, dan Kesehatan') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'8-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'7-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } else {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// }

							if ($optSchoolType=="SMP NEGERI 43 BANDUNG") {
							$kkm = 65;
						}	else {
							$kkm = 75;
						}

							if(!empty($rowpeluas1['nilai-pengetahuan']) && !empty($rowpeluas1['nilai-keterampilan'])){
								if(!empty($rowpeluas1['nilai-pengetahuan'])){
									$rowpeluas1['p-np'] = Clases::model()->predikat($rowpeluas1['nilai-pengetahuan'],$kkm); 
							
								}
								if(!empty($rowpeluas1['nilai-keterampilan'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-keterampilan'],$kkm); 
								}

								if($rowpeluas1['kelompok']==2){
				?>
				<tr>
					<td align="center"><?php echo $no; ?></td>
					<td><?php echo $rowpeluas1['name']; ?></td>
					
					<?php if(!empty($rowpeluas1['nilai-pengetahuan']) && $rowpeluas1['nilai-pengetahuan']!='-') { ?>
						<td align="center"><?php echo $rowpeluas1['nilai-pengetahuan']; ?></td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
					<!-- <td align ="center"><?php //echo $kkm;?></td> -->
					<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
						<td align="center"><?php echo $rowpeluas1['p-np']; ?></td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
		
				<?php if(!empty($rowpeluas1['nilai-kddescription']) && $rowpeluas1['nilai-kddescription']!="-") { ?>
				<td align="left">
					<?php 
					
						$peng_desc = str_replace(' , ',', ',implode(",", $rowpeluas1['nilai-kddescription']));
								$peng_desc = str_replace(' ,',', ',$peng_desc);
								$peng_desc = str_replace(',',', ',$peng_desc);
								$peng_desc = str_replace(',  ',', ',$peng_desc);
								$peng_desc = str_replace(' .. ','. ',$peng_desc);
								$peng_desc = str_replace(' .','. ',$peng_desc);
								$peng_desc = str_replace('.','. ',$peng_desc);
								$peng_desc = str_replace('.  ','. ',$peng_desc);
								$peng_desc = str_replace('. . ','. ',$peng_desc);

					if (!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="A") {
						echo "Memiliki kemampuan sangat baik dalam ".$peng_desc;
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="B"){
						echo "Memiliki kemampuan baik dalam ".$peng_desc;
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="C"){
						echo "Memiliki kemampuan cukup baik dalam ".$peng_desc;
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="D"){
						echo "Memiliki kemampuan kurang dalam ".$peng_desc;
					}

					?>
				</td>
				<?php } else { ?>
				<td align="center">-</td>
				<?php }?>
			
				</tr>
				<?php
									$no++;
								}
							}
						}
					}
				?>
				
				<?php
					$no = 1;
					if(!empty($peluas1)){
						foreach ($peluas1 as $rowpeluas1) {

					// 		if (strpos(strtolower($nama_kelas),'9-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika' or $rowpeluas1['name'] =='Pendidikan Jasmani, Olah Raga, dan Kesehatan') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'8-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'7-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } else {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// }

							if ($optSchoolType=="SMP NEGERI 43 BANDUNG") {
							$kkm = 65;
						}	else {
							$kkm = 75;
						}
 
							if(!empty($rowpeluas1['nilai-pengetahuan']) && !empty($rowpeluas1['nilai-keterampilan'])){
								if(!empty($rowpeluas1['nilai-pengetahuan'])){
									$rowpeluas1['p-np'] = Clases::model()->predikat($rowpeluas1['nilai-pengetahuan'],$kkm); 
							
								}
								if(!empty($rowpeluas1['nilai-keterampilan'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-keterampilan'],$kkm); 
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
				
				<?php
									$no++;
								}
							}
						}
					}
				?>
			</table>
			<br/>

		</b>
	</ol>
</section>


<!-- Halaman 3 -->
<section id="typography" class="break-after break-before" style="margin-top:10px;">
	<sub>
		
	</sub>
	
	<table border="0" style="width:100%;">
		<tr>
			<td>Nama Sekolah</td>
			<td> : <?php echo $school_name; ?></td>
			<td>Kelas</td>
			<td> : <?php 

			if( $tahun_ajaran != $tahun_ajaran_db[0]->value ) {
					$kelasnya = ClassHistory::model()->findAll(array('condition'=>'user_id = "'.$siswa->id.'"')); 
					echo $kelasnya[0]->class->name;
				} else {
					echo $siswa->class->name;
				}
			?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td> : <?php echo $address; ?></td>
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / <?php echo $gage; ?></td>
		</tr>
		<tr >
			<td>Nama</td>
			<td> : <?php echo $siswa->display_name;?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr colspan="2">
			<td>NIS</td>
			<td> : <?php
			if(!empty($siswa->username)){
				echo $siswa->username;
			}else{
				echo "-";
			}
			
			?></td>
		</tr>
	</table>
	<br/><br/>
	<b>
			<!--Pengetahuan dan Keterampilan-->
			
	<ol type="A" start="2">
			Kriteria Ketuntasan Minimal: <?php echo $kkm; ?>
			<table border="5" style="width:100%;border: 2px solid black;">
				<tr>
					<th rowspan="2" align="center" width="5%">No.</th>
					<th rowspan="2" align="center" width="30%">Mata Pelajaran</th>
					<th colspan="3" align="center">Keterampilan</th>
				</tr>
				<tr>
					<th align="center">Nilai</th>
					<!-- <th align="center">KKM</th> -->
					<th align="center">Predikat</th>
					<th align="center">Deskripsi</th>
				</tr>
				<tr>
					<td colspan="6"><b>Kelompok A (Umum)</b></td>
				</tr>
				<?php
					$no = 1;
					if(!empty($peluas1)){
						foreach ($peluas1 as $rowpeluas1) {

					// 		if (strpos(strtolower($nama_kelas),'9-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika' or $rowpeluas1['name'] =='Pendidikan Jasmani, Olah Raga, dan Kesehatan') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'8-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'7-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } else {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// }

							if ($optSchoolType=="SMP NEGERI 43 BANDUNG") {
							$kkm = 65;
						}	else {
							$kkm = 75;
						}

							if($no<6){
							if(!empty($rowpeluas1['nilai-keterampilan']) && !empty($rowpeluas1['nilai-keterampilan'])){
								if(!empty($rowpeluas1['nilai-keterampilan'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-keterampilan'],$kkm); 
								}
								if(!empty($rowpeluas1['nilai-keterampilan'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-keterampilan'],$kkm); 
								}

								if($rowpeluas1['kelompok']==1){
				?>
				<tr>
					<td align="center"><?php echo $no; ?></td>
					<td><?php echo $rowpeluas1['name']; ?></td>
					
					<?php if(!empty($rowpeluas1['nilai-keterampilan']) && $rowpeluas1['nilai-keterampilan']!='-') { ?>
						<td align="center"><?php echo $rowpeluas1['nilai-keterampilan']; ?></td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
					<!-- <td align ="center"><?php //echo $kkm;?></td> -->
					<?php if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']!="-") { ?>
						<td align="center"><?php echo $rowpeluas1['p-nk']; ?></td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
		
				<?php if(!empty($rowpeluas1['nilai-kddescription-ket']) && $rowpeluas1['nilai-kddescription-ket']!="-") { ?>
				<td align="left">
					<?php 

								$ket_desc = str_replace(' , ',', ',implode(",", $rowpeluas1['nilai-kddescription-ket']));
								$ket_desc = str_replace(' ,',', ',$ket_desc);
								$ket_desc = str_replace(',',', ',$ket_desc);
								$ket_desc = str_replace(',  ',', ',$ket_desc);
								$ket_desc = str_replace(' .. ','. ',$ket_desc);
								$ket_desc = str_replace(' .','. ',$ket_desc);
								$ket_desc = str_replace('.','. ',$ket_desc);
								$ket_desc = str_replace('.  ','. ',$ket_desc);
								$ket_desc = str_replace('. . ','. ',$ket_desc);

					if (!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="A") {
						echo "Sangat terampil dalam ".$ket_desc."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="B"){
						echo "Terampil dalam ".$ket_desc."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="C"){
						echo "Cukup terampil dalam ".$ket_desc."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="D"){
						echo "Kurang terampil dalam ".$ket_desc."";
					}
					?>
				</td>
				<?php } else {?>
				<td align="center">-</td>
				<?php }?>
			
				</tr>
				<?php
									$no++;
									}
								}
							}
						}
					}
				?>
				
			</table>
			<br/>

		</b>
	</ol>
</section>



<!-- Halaman 3-3 -->
<section id="typography" class="break-after break-before" style="margin-top:10px;">
	<br/>
	
	<table border="0" style="width:100%;">
		<tr>
			<td>Nama Sekolah</td>
			<td> : <?php echo $school_name; ?></td>
			<td>Kelas</td>
			<td> : <?php 

			if( $tahun_ajaran != $tahun_ajaran_db[0]->value ) {
					$kelasnya = ClassHistory::model()->findAll(array('condition'=>'user_id = "'.$siswa->id.'"')); 
					echo $kelasnya[0]->class->name;
				} else {
					echo $siswa->class->name;
				}
			?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td> : <?php echo $address; ?></td>
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / <?php echo $gage; ?></td>
		</tr>
		<tr >
			<td>Nama</td>
			<td> : <?php echo $siswa->display_name;?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr colspan="2">
			<td>NIS</td>
			<td> : <?php
			if(!empty($siswa->username)){
				echo $siswa->username;
			}else{
				echo "-";
			}
			
			?></td>
		</tr>
	</table>
	<br/><br/>

	<ol type="A" start="2">
		<b>
			
			Kriteria Ketuntasan Minimal: <?php echo $kkm; ?>
			<table border="5" style="width:100%;border: 2px solid black;">
				<tr>
					<th rowspan="2" align="center" width="5%">No.</th>
					<th rowspan="2" align="center" width="30%">Mata Pelajaran</th>
					<th colspan="3" align="center">Keterampilan</th>
				</tr>
				<tr>
					<th align="center">Nilai</th>
					
					<th align="center">Predikat</th>
					<th align="center">Deskripsi</th>
				</tr>
				<tr>
					<td colspan="6"><b>Kelompok A (Umum)</b></td>
				</tr>
				<?php
					$no = 1;
					if(!empty($peluas1)){
						foreach ($peluas1 as $rowpeluas1) {


					// 		if (strpos(strtolower($nama_kelas),'9-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika' or $rowpeluas1['name'] =='Pendidikan Jasmani, Olah Raga, dan Kesehatan') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'8-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'7-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } else {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// }

							if ($optSchoolType=="SMP NEGERI 43 BANDUNG") {
							$kkm = 65;
						}	else {
							$kkm = 75;
						}

							
							if(!empty($rowpeluas1['nilai-keterampilan']) && !empty($rowpeluas1['nilai-keterampilan'])){
								if(!empty($rowpeluas1['nilai-keterampilan'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-keterampilan'],$kkm); 
								}
								if(!empty($rowpeluas1['nilai-keterampilan'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-keterampilan'],$kkm); 
								}

								if($rowpeluas1['kelompok']==1){
									if($no>5){
				?>
				<tr>
					<td align="center"><?php echo $no; ?></td>
					<td><?php echo $rowpeluas1['name']; ?></td>
					
					<?php if(!empty($rowpeluas1['nilai-keterampilan']) && $rowpeluas1['nilai-keterampilan']!='-') { ?>
						<td align="center"><?php echo $rowpeluas1['nilai-keterampilan']; ?></td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
					<!-- <td align ="center"><?php //echo $kkm;?></td> -->
					<?php if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']!="-") { ?>
						<td align="center"><?php echo $rowpeluas1['p-nk']; ?></td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>

				<?php if(!empty($rowpeluas1['nilai-kddescription-ket']) && $rowpeluas1['nilai-kddescription-ket']!="-") { ?>
				<td align="left">
					<?php 

					$ket_desc = str_replace(' , ',', ',implode(",", $rowpeluas1['nilai-kddescription-ket']));
								$ket_desc = str_replace(' ,',', ',$ket_desc);
								$ket_desc = str_replace(',',', ',$ket_desc);
								$ket_desc = str_replace(',  ',', ',$ket_desc);
								$ket_desc = str_replace(' .. ','. ',$ket_desc);
								$ket_desc = str_replace(' .','. ',$ket_desc);
								$ket_desc = str_replace('.','. ',$ket_desc);
								$ket_desc = str_replace('.  ','. ',$ket_desc);
								$ket_desc = str_replace('. . ','. ',$ket_desc);

					if (!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="A") {
						echo "Sangat terampil dalam ".$ket_desc."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="B"){
						echo "Terampil dalam ".$ket_desc."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="C"){
						echo "Cukup terampil dalam ".$ket_desc."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="D"){
						echo "Kurang terampil dalam ".$ket_desc."";
					}

					?>
				</td>
				<?php } else {?>
				<td align="center">-</td>
				<?php }?>
			
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
					<td colspan="6"><b>Kelompok B (Umum)</b></td>
				</tr>
				<?php
					$no = 1;
					if(!empty($peluas1)){
						foreach ($peluas1 as $rowpeluas1) {

					// 		if (strpos(strtolower($nama_kelas),'9-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika' or $rowpeluas1['name'] =='Pendidikan Jasmani, Olah Raga, dan Kesehatan') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'8-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'7-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } else {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// }

							if ($optSchoolType=="SMP NEGERI 43 BANDUNG") {
							$kkm = 65;
						}	else {
							$kkm = 75;
						}

							if(!empty($rowpeluas1['nilai-keterampilan']) && !empty($rowpeluas1['nilai-keterampilan'])){
								if(!empty($rowpeluas1['nilai-keterampilan'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-keterampilan'],$kkm); 
								}
								if(!empty($rowpeluas1['nilai-keterampilan'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-keterampilan'],$kkm); 
								}

								if($rowpeluas1['kelompok']==2){
				?>
				<tr>
					<td align="center"><?php echo $no; ?></td>
					<td><?php echo $rowpeluas1['name']; ?></td>
					
					<?php if(!empty($rowpeluas1['nilai-keterampilan']) && $rowpeluas1['nilai-keterampilan']!='-') { ?>
						<td align="center"><?php echo $rowpeluas1['nilai-keterampilan']; ?></td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
					<!-- <td align ="center"><?php echo $kkm;?></td> -->
					<?php if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']!="-") { ?>
						<td align="center"><?php echo $rowpeluas1['p-nk']; ?></td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
		
				<?php if(!empty($rowpeluas1['nilai-kddescription-ket']) && $rowpeluas1['nilai-kddescription-ket']!="-") { ?>
				<td align="left">
					<?php 

					$ket_desc = str_replace(' , ',', ',implode(",", $rowpeluas1['nilai-kddescription-ket']));
								$ket_desc = str_replace(' ,',', ',$ket_desc);
								$ket_desc = str_replace(',',', ',$ket_desc);
								$ket_desc = str_replace(',  ',', ',$ket_desc);
								$ket_desc = str_replace(' .. ','. ',$ket_desc);
								$ket_desc = str_replace(' .','. ',$ket_desc);
								$ket_desc = str_replace('.','. ',$ket_desc);
								$ket_desc = str_replace('.  ','. ',$ket_desc);
								$ket_desc = str_replace('. . ','. ',$ket_desc);


					if (!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="A") {
						echo "Sangat terampil dalam ".$ket_desc."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="B"){
						echo "Terampil dalam ".$ket_desc."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="C"){
						echo "Cukup terampil dalam ".$ket_desc."";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="D"){
						echo "Kurang terampil dalam ".$ket_desc."";
					}
					?>
				</td>
				<?php } else {?>
				<td align="center">-</td>
				<?php }?>
			
				</tr>
				<?php
									$no++;
								}
							}
						}
					}
				?>
				
				<?php
					$no = 1;
					if(!empty($peluas1)){
						foreach ($peluas1 as $rowpeluas1) {

					// 		if (strpos(strtolower($nama_kelas),'9-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika' or $rowpeluas1['name'] =='Pendidikan Jasmani, Olah Raga, dan Kesehatan') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'8-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } elseif (strpos(strtolower($nama_kelas),'7-')!==false ) {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// } else {
					// 	if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
					// 		$kkm = 78;
					// 	}  else {
					// 		$kkm = 75;
					// 	}
					// }
							if ($optSchoolType=="SMP NEGERI 43 BANDUNG") {
							$kkm = 65;
						}	else {
							$kkm = 75;
						}

							if(!empty($rowpeluas1['nilai-keterampilan']) && !empty($rowpeluas1['nilai-keterampilan'])){
								if(!empty($rowpeluas1['nilai-keterampilan'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-keterampilan'],$kkm); 
								}
								if(!empty($rowpeluas1['nilai-keterampilan'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-keterampilan'],$kkm); 
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
				
				<?php
									$no++;
								}
							}
						}
					}
				?>
			</table>
			<br/>

		</b>
	</ol>
</section>

<!-- Halaman 5 -->
<section id="typography" class="break-after break-before" style="margin-top:10px;">
	<br/>
	
	<table border="0" style="width:100%;">
		<tr>
			<td>Nama Sekolah</td>
			<td> : <?php echo $school_name; ?></td>
			<td>Kelas</td>
			<td> : <?php 

			if( $tahun_ajaran != $tahun_ajaran_db[0]->value ) {
					$kelasnya = ClassHistory::model()->findAll(array('condition'=>'user_id = "'.$siswa->id.'"')); 
					echo $kelasnya[0]->class->name;
				} else {
					echo $siswa->class->name;
				}
			?></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td> : <?php echo $address; ?></td>
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / <?php echo $gage; ?></td>
		</tr>
		<tr >
			<td>Nama</td>
			<td> : <?php echo $siswa->display_name;?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr colspan="2">
			<td>NIS</td>
			<td> : <?php
			if(!empty($siswa->username)){
				echo $siswa->username;
			}else{
				echo "-";
			}
			
			?></td>
		</tr>
	</table>
	
<br/><br/>
	<ol type="A" start="3">
		<b><li>
			Ekstra Kurikuler
			<table border="5" style="width:100%;border: 2px solid black;">
				<tr>
					<th align="center" width="5%">No.</th>
					<th align="center" width="35%">Kegiatan Ekstrakurikuler</th>
					<th align="center">Nilai / Deskripsi</th>
				</tr>
				<tr>
					<td align="center" style="padding-top:2px;padding-bottom:2px;">1</td>
					<?php if(!empty($peluas2['Ekstrakurikuler 1 - Nama']) && $peluas2['Ekstrakurikuler 1 - Nama']!="-"){ ?>
						<td><?php echo strtoupper($peluas2['Ekstrakurikuler 1 - Nama']); ?></td>
					<?php }else{ ?>
						<td>-</td>
					<?php } ?>
					<td align="center">
					<?php
						if(!empty($peluas2['Ekstrakurikuler 1 - Nilai']) && $peluas2['Ekstrakurikuler 1 - Nilai']!="-"){
							echo strtoupper($peluas2['Ekstrakurikuler 1 - Nilai']);
							if(!empty($peluas2['Ekstrakurikuler 1 - Deskripsi']) && $peluas2['Ekstrakurikuler 1 - Deskripsi']!='-'){
								echo ", ".$peluas2['Ekstrakurikuler 1 - Deskripsi'];
							}
						}else{
							if(!empty($peluas2['Ekstrakurikuler 1 - Deskripsi']) && $peluas2['Ekstrakurikuler 1 - Deskripsi']!="-"){
								echo $peluas2['Ekstrakurikuler 1 - Deskripsi'];
							}else{
								echo "-";
							}
						}
					?>
					</td>
				</tr>
				<tr>
					<td align="center" style="padding-top:2px;padding-bottom:2px;">2</td>
					<?php if(!empty($peluas2['Ekstrakurikuler 2 - Nama']) && $peluas2['Ekstrakurikuler 2 - Nama']!="-"){ ?>
						<td><?php echo strtoupper($peluas2['Ekstrakurikuler 2 - Nama']); ?></td>
					<?php }else{ ?>
						<td>-</td>
					<?php } ?>
					<td align="center">
					<?php
						if(!empty($peluas2['Ekstrakurikuler 2 - Nilai']) && $peluas2['Ekstrakurikuler 2 - Nilai']!="-"){
							echo strtoupper($peluas2['Ekstrakurikuler 2 - Nilai']);
							if(!empty($peluas2['Ekstrakurikuler 2 - Deskripsi']) && $peluas2['Ekstrakurikuler 2 - Deskripsi']!="-"){
								echo ", ".$peluas2['Ekstrakurikuler 2 - Deskripsi'];
							}
						}else{
							if(!empty($peluas2['Ekstrakurikuler 2 - Deskripsi']) && $peluas2['Ekstrakurikuler 2 - Deskripsi']!="-"){
								echo $peluas2['Ekstrakurikuler 2 - Deskripsi'];
							}else{
								echo "-";
							}
						}
					?>
					</td>
				</tr>
				<tr>
					<td align="center" style="padding-top:2px;padding-bottom:2px;">3</td>
					<?php if(!empty($peluas2['Ekstrakurikuler 3 - Nama']) && $peluas2['Ekstrakurikuler 3 - Nama']!="-"){ ?>
						<td><?php echo strtoupper($peluas2['Ekstrakurikuler 3 - Nama']); ?></td>
					<?php }else{ ?>
						<td>-</td>
					<?php } ?>
					<td align="center">
					<?php
						if(!empty($peluas2['Ekstrakurikuler 3 - Nilai']) && $peluas2['Ekstrakurikuler 3 - Nilai']!="-"){
							echo strtoupper($peluas2['Ekstrakurikuler 3 - Nilai']);
							if(!empty($peluas2['Ekstrakurikuler 3 - Deskripsi']) && $peluas2['Ekstrakurikuler 3 - Deskripsi']!="-"){
								echo ", ".$peluas2['Ekstrakurikuler 3 - Deskripsi'];
							}
						}else{
							if(!empty($peluas2['Ekstrakurikuler 3 - Deskripsi']) && $peluas2['Ekstrakurikuler 3 - Deskripsi']!="-"){
								echo $peluas2['Ekstrakurikuler 3 - Deskripsi'];
							}else{
								echo "-";
							}
						}
					?>
					</td>
				</tr>
				<tr>
					<td align="center" style="padding-top:2px;padding-bottom:2px;">4</td>
					<?php if(!empty($peluas2['Ekstrakurikuler 4 - Nama']) && $peluas2['Ekstrakurikuler 4 - Nama']!="-"){ ?>
						<td><?php echo strtoupper($peluas2['Ekstrakurikuler 4 - Nama']); ?></td>
					<?php }else{ ?>
						<td>-</td>
					<?php } ?>
					<td align="center">
					<?php
						if(!empty($peluas2['Ekstrakurikuler 4 - Nilai']) && $peluas2['Ekstrakurikuler 4 - Nilai']!="-"){
							echo strtoupper($peluas2['Ekstrakurikuler 4 - Nilai']);
							if(!empty($peluas2['Ekstrakurikuler 4 - Deskripsi']) && $peluas2['Ekstrakurikuler 4 - Deskripsi']!="-"){
								echo ", ".$peluas2['Ekstrakurikuler 4 - Deskripsi'];
							}
						}else{
							if(!empty($peluas2['Ekstrakurikuler 4 - Deskripsi']) && $peluas2['Ekstrakurikuler 4 - Deskripsi']!="-"){
								echo $peluas2['Ekstrakurikuler 4 - Deskripsi'];
							}else{
								echo "-";
							}
						}
					?>
					</td>
				</tr>
			</table>
		</li></b>
		<br/>
		<br/>
		<b><li>
			Ketidakhadiran
			<table border="5" style="width:50%;border: 2px solid black;">
				<tr>
					<td width="60%">Sakit</td>
					<?php if(!empty($peluas2['Absensi Sakit']) && $peluas2['Absensi Sakit']!="-") { ?>
						<td align="center"><?php echo $peluas2['Absensi Sakit']; ?> hari</td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
				</tr>
				<tr>
					<td width="60%">Izin</td>
					<?php if(!empty($peluas2['Absensi Izin']) && $peluas2['Absensi Izin']!="-") { ?>
						<td align="center"><?php echo $peluas2['Absensi Izin']; ?> hari</td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
				</tr>
				<tr>
					<td width="60%">Tanpa Keterangan</td>
					<?php if(!empty($peluas2['Absensi Alfa']) && $peluas2['Absensi Alfa']!="-") { ?>
						<td align="center"><?php echo $peluas2['Absensi Alfa']; ?> hari</td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
				</tr>
			</table>
		</li></b>
		<br/>
		<br/>
		<b>
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
							Bandung, <?php 
					if(Yii::app()->session['titimangsa']){
						echo Yii::app()->session['titimangsa'];
					}else {
						if ($optSchoolType=="SMP NEGERI 43 BANDUNG") {
							echo(DateToIndo(date('2018-12-14')));
						}	else {
							echo(DateToIndo(date('2018-12-14')));
						}
						// echo(DateToIndo(date('2018-12-14')));
					}
					?><br/>
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
							<?php 	if ($optSchoolType=="SMP NEGERI 43 BANDUNG") { ?>
							    <b>NIP.
								<?php
									if(!empty($model->teacher_id)){
										echo $nip = str_replace('-', ' ', $model->teacher->username);
									}
								?>
							</b>
							<?php	}	else { ?>
									<b>NIY.
								<?php
									if(!empty($model->teacher_id)){
										echo $nip = str_replace('-', ' ', $model->teacher->username);
									}
								?>
							</b>
							<?php 	} ?>

							
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
						<?php 	if ($optSchoolType=="SMP NEGERI 43 BANDUNG") { ?>
							    <b>NIP. <?php echo $nip = str_replace('-', ' ', $nik);?></b>
						<?php	}	else { ?>
								<b>NIY. <?php echo $nip = str_replace('-', ' ', $nik);?></b>
						<?php 	} ?>
							
						</p>
					</td>
				</tr>
			</table>
		</b>
		<br/>
	</ol>
</section>
<script>print();</script>
</body>
</html>