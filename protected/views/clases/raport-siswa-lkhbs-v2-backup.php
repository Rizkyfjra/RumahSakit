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

	if (strpos(strtolower($nama_kelas),'9-')!==false ) {
		$kkm = 80;
	} elseif (strpos(strtolower($nama_kelas),'8-')!==false ) {
		$kkm = 78;
	} elseif (strpos(strtolower($nama_kelas),'7-')!==false ) {
		$kkm = 75;
	} else {
		$kkm = "....";
	}

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

	function kekata($x) {
		    $x = abs($x);
		    $angka = array("", "satu", "dua", "tiga", "empat", "lima",
		    "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
		    $temp = "";
		    if ($x <12) {
		        $temp = " ". $angka[$x];
		    } else if ($x <20) {
		        $temp = kekata($x - 10). " belas";
		    } else if ($x <100) {
		        $temp = kekata($x/10)." puluh". kekata($x % 10);
		    } else if ($x <200) {
		        $temp = " seratus" . kekata($x - 100);
		    } else if ($x <1000) {
		        $temp = kekata($x/100) . " ratus" . kekata($x % 100);
		    } else if ($x <2000) {
		        $temp = " seribu" . kekata($x - 1000);
		    } else if ($x <1000000) {
		        $temp = kekata($x/1000) . " ribu" . kekata($x % 1000);
		    } else if ($x <1000000000) {
		        $temp = kekata($x/1000000) . " juta" . kekata($x % 1000000);
		    } else if ($x <1000000000000) {
		        $temp = kekata($x/1000000000) . " milyar" . kekata(fmod($x,1000000000));
		    } else if ($x <1000000000000000) {
		        $temp = kekata($x/1000000000000) . " trilyun" . kekata(fmod($x,1000000000000));
		    }     
		        return ucfirst("$temp");
		}


		function terbilang($x, $style=4) {
		    if($x<0) {
		        $hasil = "minus ". trim(kekata($x));
		    } else {
		        $hasil = trim(kekata($x));
		    }     
		    switch ($style) {
		        case 1:
		            $hasil = strtoupper($hasil);
		            break;
		        case 2:
		            $hasil = strtolower($hasil);
		            break;
		        case 3:
		            $hasil = ucwords($hasil);
		            break;
		        default:
		            $hasil = ucfirst($hasil);
		            break;
		    }     
		    return $hasil;
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
	<table border="0" style="width:100%;">
		<tr>
			<td rowspan="4">
				<img style="margin-bottom: 5px; margin-top: 9px;" src="<?php echo Yii::app()->getBaseUrl(); ?>/images/smpdh.png" width="100px" height="100px">	
			</td>
			<td><h3 style="margin: 0px;">SEKOLAH MENENGAH PERTAMA DARUL HIKAM </h3>
    <h4 style="margin: 0px;" >LAPORAN KEMAJUAN HASIL BELAJAR SISWA BULAN AGUSTUS</h4></td>
		</tr>
		<tr>
			<td><?php echo $alamat_sekolah[0]->value; ?></td>
		</tr>
		<tr >
			<td>Telp. 022-2501375 Fax. 022-2501375</td>
		</tr>
		<tr>
			<td>Website: www.smpdh.com Email: smp@darulhikam.com</td>
		</tr>
	</table>
	<hr style="width:100%;border: 1px solid black;"/>
	<center><h5>NILAI HASIL BELAJAR</h5></center>
	<table border="0" style="width:50%;">
	
		<tr>
			<td>Tahun Ajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr >
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / Ganjil</td>
		</tr>
		<tr>
			<td>Kelas</td>
			<td> : <?php echo $siswa->class->name;?></td>
		</tr>
		<tr>
			<td>NIS</td>
			<td> : <?php
			if(!empty($siswa->username)){
				echo $siswa->username;
			}else{
				echo "-";
			}
			?></td>
		</tr>
		<tr >
			<td>Siswa</td>
			<td> : <?php echo $siswa->display_name;?></td>
		</tr>
	</table>

	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th rowspan="2"  align="center" width="5%">No.</th>
			<th rowspan="2"  align="center" width="30%">Mata Pelajaran</th>
			<th rowspan="2"  align="center">kkm</th>
			<th rowspan="2"  align="center">Aspek Penilaian</th>
			<th colspan="2"  align="center">Nilai</th>
		</tr>
		<tr>
			<th  align="center">Angka</th>
			<th  align="center">Terbilang</th>
		</tr>
	
	
		<?php
			$no = 1;
			if(!empty($peluas1)){
				foreach ($peluas1 as $key => $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-kd1'])){
						if(!empty($rowpeluas1['nilai-kddescription'])){
							$rowpeluas1['p-np'] = kekata($rowpeluas1['nilai-kd1']);
						}
						if(!empty($rowpeluas1['nilai-kddescription-ket'])){
							$rowpeluas1['p-nk'] = kekata($rowpeluas1['nilai-kd1']);
						}

						// if($rowpeluas1['kelompok']==1){
		?>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;"><?php echo $no; ?></td>
			<td><?php echo $rowpeluas1['name']; ?></td>
			<td align="center"><?php 

				

					if (strpos(strtolower($nama_kelas),'9-')!==false ) {
						if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika'  or $rowpeluas1['name'] =='Pendidikan Jasmani, Olah Raga, dan Kesehatan') {
							echo "78";
						}  else {
							echo "75";
						}
					} elseif (strpos(strtolower($nama_kelas),'8-')!==false ) {
						if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Ilmu Pengetahuan Sosial' or $rowpeluas1['name'] =='Teknik Komputer Dan Informatika' or $rowpeluas1['name'] =='Fisika') {
							echo "78";
						}  else {
							echo "75";
						}
					} elseif (strpos(strtolower($nama_kelas),'7-')!==false ) {
						if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
							echo "78";
						}  else {
							echo "75";
						}
					} else {
						if ( $rowpeluas1['name'] =='Bahasa Inggris'  or $rowpeluas1['name'] =='Pendidikan Agama dan Budi Pekerti' or $rowpeluas1['name'] =='Seni Budaya') {
							echo "78";
						}  else {
							echo "75";
						}
					}
			 ?>
			</td>
		
			<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
				<td align="left">Pengetahuan</td>
			<?php }else{ ?>
				<?php if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']!="-") { ?>
				<td align="left">Praktik</td>
				<?php }else{ ?>
					<td align="left">-</td>
				<?php } ?>
			<?php } ?>


			<?php if(!empty($rowpeluas1['nilai-kd1']) && $rowpeluas1['nilai-kd1']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-kd1']; ?></td>
			<?php }else{ ?>
				<?php if(!empty($rowpeluas1['nilai-keterampilan']) && $rowpeluas1['nilai-keterampilan']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-keterampilan']; ?></td>
				<?php }else{ ?>
					<td align="center">-</td>
				<?php } ?>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
				<td align="left"><?php echo $rowpeluas1['p-np']; ?></td>
			<?php }else{ ?>
				<?php if(!empty($rowpeluas1['nilai-kddescription-ket']) && $rowpeluas1['nilai-kddescription-ket']!="-") { ?>
				<td align="left"><?php echo $rowpeluas1['p-nk']; ?></td>
				<?php }else{ ?>
					<td align="left">-</td>
				<?php } ?>
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

	<?php if ((strpos(strtolower($nama_kelas),'7-')!==false)  or ( strpos(strtolower($nama_kelas),'8-')!==false )  ) { ?>
	<b>Muatan Khas Darul Hikam</b>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th align="center" width="5%">No.</th>
			<th width="25%">Nama</th>
			<th>Nilai</th>
		</tr>
		<tr valign="top">
				<td valign="middle" align="center">1</td>
				<td height="40px" align="center" valign="middle">TCB Spiritual & Sosial</td>
			
			<?php if(!empty($peluas2['Sikap Spiritual - Predikat']) && $peluas2['Sikap Spiritual - Predikat']!="-") { ?>
				<td align="center" height="40px" valign="middle">
					
					<?php 
					
					switch ($peluas2['Sikap Spiritual - Predikat']) {
								case 'A':
									echo "PIONIR";
									break;
								case 'B':
									echo "MAJU";
									break;	

								case 'C':
									echo "BERKEMBANG";
									break;	
								
								default:
									echo "-";
									break;
							}

					?>


				</td>
			<?php }else{ ?>
								
				<td align="center" height="40px" valign="middle">-</td>
								
			<?php } ?>
		</tr>
		<tr valign="top">
				<td valign="middle" align="center">2</td>
				<td height="40px" align="center" valign="middle">TKK PAI</td>
			
			<?php if(!empty($peluas2['Ekstrakurikuler 1 - Nilai']) && $peluas2['Ekstrakurikuler 1 - Nilai']!="-") { ?>
				<td align="center" height="40px" valign="middle">
					
					<?php 
					
					switch ($peluas2['Ekstrakurikuler 1 - Nilai']) {
								case 'A':
									echo "SANGAT BAIK";
									break;
								case 'B':
									echo "BAIK";
									break;	

								case 'C':
									echo "CUKUP";
									break;	
								
								default:
									echo "-";
									break;
							}

					?>


				</td>
			<?php }else{ ?>
								
				<td align="center" height="40px" valign="middle">-</td>
								
			<?php } ?>
		</tr>
	</table>
	<br/>
	<?php } ?>
	
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
					Bandung, <?php echo(DateToIndo(date('2017-8-25')));?><br/>
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

<!-- Halaman 2 -->
<section id="typography" class="break-after" style="margin-top:10px;">		
	<center><h5>Komentar Hasil Belajar</h5></center>
	<br/>
	<table border="0" style="width:50%;">
	
		<tr>
			<td>Tahun Ajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr >
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / Ganjil</td>
		</tr>
		<tr>
			<td>Kelas</td>
			<td> : <?php echo $siswa->class->name;?></td>
		</tr>
		<tr>
			<td>NIS</td>
			<td> : <?php
			if(!empty($siswa->username)){
				echo $siswa->username;
			}else{
				echo "-";
			}
			?></td>
		</tr>
		<tr >
			<td>Siswa</td>
			<td> : <?php echo $siswa->display_name;?></td>
		</tr>
	</table>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th align="center" width="5%">No.</th>
			<th align="center" width="30%">Pelajaran</th>
			<th  align="center">Komentar</th>
		</tr>
	
	
		<?php
			$no = 1;
			if(!empty($peluas1)){
				foreach ($peluas1 as $key => $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-kd1']) or !empty($rowpeluas1['nilai-keterampilan'])){
						if(!empty($rowpeluas1['nilai-kd1'])){
							if($rowpeluas1['nilai-kd1']<75){
								$rowpeluas1['p-np'] = "D";
								$peluas1[$key]['p-np'] = "D";
							}elseif($rowpeluas1['nilai-kd1']>=75 && $rowpeluas1['nilai-kd1']<84){
								$rowpeluas1['p-np'] = "C";
								$peluas1[$key]['p-np'] = "C";
							}elseif($rowpeluas1['nilai-kd1']>=84 && $rowpeluas1['nilai-kd1']<93){
								$rowpeluas1['p-np'] = "B";
								$peluas1[$key]['p-np'] = "B";
							}elseif($rowpeluas1['nilai-kd1']>=93 && $rowpeluas1['nilai-kd1']<=100){
								$rowpeluas1['p-np'] = "A";
								$peluas1[$key]['p-np'] = "A";
							}else{
								$rowpeluas1['p-np'] = "-";
								$peluas1[$key]['p-np'] = "-";
							}
						}
						if(!empty($rowpeluas1['nilai-kd1'])){
							if($rowpeluas1['nilai-kd1']<75){
								$rowpeluas1['p-nk'] = "D";
								$peluas1[$key]['p-nk'] = "D";
							}elseif($rowpeluas1['nilai-kd1']>=75 && $rowpeluas1['nilai-kd1']<84){
								$rowpeluas1['p-nk'] = "C";
								$peluas1[$key]['p-nk'] = "C";
							}elseif($rowpeluas1['nilai-kd1']>=84 && $rowpeluas1['nilai-kd1']<93){
								$rowpeluas1['p-nk'] = "B";
								$peluas1[$key]['p-nk'] = "B";
							}elseif($rowpeluas1['nilai-kd1']>=93 && $rowpeluas1['nilai-kd1']<=100){
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
			<?php if(!empty($rowpeluas1['desc-desc_pengetahuan']) && $rowpeluas1['desc-desc_pengetahuan']!="-") { ?>
				<td align="left"><?php echo $rowpeluas1['desc-desc_pengetahuan']; ?></td>
			<?php }else{ ?>

				<?php if(!empty($rowpeluas1['nilai-kddescription']) && $rowpeluas1['nilai-kddescription']!="-") { ?>

						<?php if(!empty($rowpeluas1['nilai-kddescription']) && $rowpeluas1['nilai-kddescription']!="-") { ?>
						<td align="left">
							<?php 
							if (!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="A") {
								echo "Memahami, menerapkan, menganalisis pengetahuan tentang  ".implode(",", $rowpeluas1['nilai-kddescription'])." dengan sangat baik";
							} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="B"){
								echo "Memahami, menerapkan, menganalisis pengetahuan tentang  ".implode(",", $rowpeluas1['nilai-kddescription'])." dengan baik";
							} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="C"){
								echo "Memahami, menerapkan, menganalisis pengetahuan tentang  ".implode(",", $rowpeluas1['nilai-kddescription'])." dengan cukup baik";
							} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="D"){
								echo "Memahami, menerapkan, menganalisis pengetahuan tentang  ".implode(",", $rowpeluas1['nilai-kddescription'])." perlu ditingkatkan";
							}?>
						</td>
						<?php } else {?>
						<td align="center">-</td>
						<?php }?>

				<?php }else{ ?>
						<?php if(!empty($rowpeluas1['nilai-kddescription-ket']) && $rowpeluas1['nilai-kddescription-ket']!="-") { ?>
						<td align="left">
							<?php 
							if (!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="A") {
								echo "Sangat terampil dalam praktik ".implode(",", $rowpeluas1['nilai-kddescription-ket'])."";
							} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="B"){
								echo "Terampil dalam praktik ".implode(",", $rowpeluas1['nilai-kddescription-ket'])."";
							} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="C"){
								echo "Cukup terampil dalam praktik ".implode(",", $rowpeluas1['nilai-kddescription-ket'])."";
							} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="D"){
								echo "Kurang terampil dalam praktik ".implode(",", $rowpeluas1['nilai-kddescription-ket'])."";
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

	<b>Catatan Wali Kelas</b>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<?php if(!empty($peluas2['Catatan Wali Kelas']) && $peluas2['Catatan Wali Kelas']!="-"){ ?>
				<td height="50px"><?php echo $peluas2['Catatan Wali Kelas']; ?></td>
			<?php }else{ ?>
				<td height="50px"></td>
			<?php } ?>
		</tr>
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
					Bandung, <?php echo(DateToIndo(date('2017-8-25')));?><br/>
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
<script>print();</script>
</body>
</html>

