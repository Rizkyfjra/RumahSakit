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

	// if (strpos(strtolower($nama_kelas),'9-')!==false ) {
	// 	$kkm = 80;
	// } elseif (strpos(strtolower($nama_kelas),'8-')!==false ) {
	// 	$kkm = 78;
	// } elseif (strpos(strtolower($nama_kelas),'7-')!==false ) {
	// 	$kkm = 75;
	// } else {
	// 	$kkm = "....";
	// }

	$kkm = 75;

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


<!-- Halaman 1 -->
<section id="typography" class="break-after" style="margin-top:10px;">		
	<table border="0" style="width:100%;">
		<tr>
			<td rowspan="4">
				<img style="margin-bottom: 5px; margin-top: 9px;" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo_dh.jpg" width="100px" height="100px">	
			</td>
			<td><h3 style="margin: 0px;">LAPORAN PENILAIAN TENGAH SEMESTER GENAP</h3>
			<h3 style="margin-top: -13px; margin-bottom: 0px;">SEKOLAH MENENGAH PERTAMA DARUL HIKAM </h3>
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
	
	<table border="0" style="width:50%;">
	
		<tr>
			<td>Tahun Ajaran</td>
			<td> : <?php echo $ta; ?></td>
		</tr>
		<tr >
			<td>Semester</td>
			<td> : <?php echo $smt; ?> / Genap</td>
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
	<center><h5>MUATAN DINAS</h5></center>
	<p>A. PENGETAHUAN</p>
	<left>KKM : 75</left>
	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th rowspan="2" align="center" width="5%">No.</th>
			<th rowspan="2" align="center" width="30%">Mata Pelajaran</th>
			
			<th colspan="3"  align="center">Nilai PTS</th>
			<th rowspan="2" align="center">Predikat</th>
			<th rowspan="2" align="center">Deskripsi</th>
		</tr>
		<tr>
			
			<th  align="center">Murni</th>
			<th  align="center">R/P</th>
			<th  align="center">Akhir</th>
		</tr>
		<?php
			$no = 1;
			if(!empty($peluas1)){
				foreach ($peluas1 as $key => $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-uts_p']) && !empty($rowpeluas1['nilai-uts_k'])){
								
								if(!empty($rowpeluas1['nilai-uts_p'])){
									$rowpeluas1['p-np'] = Clases::model()->predikat($rowpeluas1['nilai-uts_p'],$kkm); 
								}

								if(!empty($rowpeluas1['nilai-uts_k'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-uts_k'],$kkm); 
								}

						if($rowpeluas1['kelompok']!=4){
		?>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;"><?php echo $no; ?></td>
			<td><?php echo $rowpeluas1['name']; ?></td>
			
			
			<?php if(!empty($rowpeluas1['nilai-kd2']) && $rowpeluas1['nilai-kd2']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-kd2']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['nilai-kd3']) && $rowpeluas1['nilai-kd3']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-kd3']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['nilai-uts_p']) && $rowpeluas1['nilai-uts_p']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-uts_p']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-np']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			
	
				<?php if(!empty($rowpeluas1['nilai-kddescription']) && $rowpeluas1['nilai-kddescription']!="-") { ?>
				<td align="left">
					<?php 
					if (!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="A") {
						echo "Memiliki kemampuan sangat baik dalam  ".implode(", ", $rowpeluas1['nilai-kddescription']);
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="B"){
						echo "Memiliki kemampuan baik dalam  ".implode(", ", $rowpeluas1['nilai-kddescription']);
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="C"){
						echo "Memiliki kemampuan cukup dalam  ".implode(", ", $rowpeluas1['nilai-kddescription']);
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="D"){
						echo "Memiliki kemampuan kurang dalam  ".implode(", ", $rowpeluas1['nilai-kddescription']);
					}?>
				</td>
				<?php } else {?>
				<td align="left"><?php 
					if (!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="A") {
						echo "Memiliki kemampuan sangat baik dalam semua materi";
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="B"){
						echo "Memiliki kemampuan baik dalam semua materi";
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="C"){
						echo "Memiliki kemampuan cukup dalam semua materi";
					} else if(!empty($rowpeluas1['p-np']) && $rowpeluas1['p-np']=="D"){
						echo "Memiliki kemampuan kurang dalam semua materi";
					}?></td>
				<?php }?>
		
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

<!-- Halaman 1.2 -->
<section id="typography" class="break-after" style="margin-top:10px;">		
	
	<p>A. KETERAMPILAN</p>
	<left>KKM : 75</left>
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
					if(!empty($rowpeluas1['nilai-uts_p']) && !empty($rowpeluas1['nilai-uts_k'])){
								
								if(!empty($rowpeluas1['nilai-uts_p'])){
									$rowpeluas1['p-np'] = Clases::model()->predikat($rowpeluas1['nilai-uts_p'],$kkm); 
								}

								if(!empty($rowpeluas1['nilai-uts_k'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-uts_k'],$kkm); 
								}

						if($rowpeluas1['kelompok']!=4){
		?>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;"><?php echo $no; ?></td>
			<td><?php echo $rowpeluas1['name']; ?></td>
			
			<?php if(!empty($rowpeluas1['nilai-uts_k']) && $rowpeluas1['nilai-uts_k']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-uts_k']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-nk']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			
			<?php if(!empty($rowpeluas1['nilai-kddescription-ket']) && $rowpeluas1['nilai-kddescription-ket']!="-") { ?>
				<td align="left">
					<?php 
					if (!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="A") {
						echo "Memiliki keterampilan sangat baik dalam  ".implode(", ", $rowpeluas1['nilai-kddescription-ket']);
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="B"){
						echo "Memiliki keterampilan baik dalam  ".implode(", ", $rowpeluas1['nilai-kddescription-ket']);
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="C"){
						echo "Memiliki keterampilan cukup dalam  ".implode(", ", $rowpeluas1['nilai-kddescription-ket']);
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="D"){
						echo "Memiliki keterampilan kurang dalam  ".implode(", ", $rowpeluas1['nilai-kddescription-ket']);
					}?>
				</td>
				<?php } else {?>
				<td align="left"><?php 
					if (!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="A") {
						echo "Memiliki keterampilan sangat baik dalam semua materi";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="B"){
						echo "Memiliki keterampilan baik dalam semua materi";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="C"){
						echo "Memiliki keterampilan cukup dalam semua materi";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="D"){
						echo "Memiliki keterampilan kurang dalam semua materi";
					}?></td>
				<?php }?>
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


<!-- Halaman 2 -->
<section id="typography" class="break-after" style="margin-top:10px;">		
	
	
	<center><h5>MUATAN KHAS DARUL HIKAM</h5></center>

	<table border="5" style="width:100%;border: 2px solid black;">
		<tr>
			<th align="center" width="5%">No.</th>
			<th align="center" width="30%">Mata Pelajaran</th>
			
			<th  align="center">KKM</th>
			<th  align="center">Nilai</th>
			<th  align="center">Predikat</th>
			<th  align="center">Deskripsi</th>
		</tr>
		<?php
			$no = 1;
			if(!empty($peluas1)){
				foreach ($peluas1 as $key => $rowpeluas1) {
					if(!empty($rowpeluas1['nilai-uts_k'])){

					    if ( $rowpeluas1['name'] =='Al Quran' ) {
							$kkm = 76;
						} else if ( $rowpeluas1['name'] =='ICT' ) {
							$kkm = 78;
						} else {
							$kkm = 75;
						}
			 							

								if(!empty($rowpeluas1['nilai-uts_k'])){
									$rowpeluas1['p-nk'] = Clases::model()->predikat($rowpeluas1['nilai-uts_k'],$kkm); 
								}

						if($rowpeluas1['kelompok']==4){
		?>
		<tr>
			<td align="center" style="padding-top:2px;padding-bottom:2px;"><?php echo $no; ?></td>
			<td><?php echo $rowpeluas1['name']; ?></td>
			<td align="center" ><?php echo $kkm; ?></td>
			
			<?php if(!empty($rowpeluas1['nilai-uts_k']) && $rowpeluas1['nilai-uts_k']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['nilai-uts_k']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			<?php if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']!="-") { ?>
				<td align="center"><?php echo $rowpeluas1['p-nk']; ?></td>
			<?php }else{ ?>
				<td align="center">-</td>
			<?php } ?>
			
			<?php if(!empty($rowpeluas1['nilai-kddescription-ket']) && $rowpeluas1['nilai-kddescription-ket']!="-") { ?>
				<td align="left">
					<?php 
					if (!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="A") {
						echo "Memiliki keterampilan sangat baik dalam  ".implode(", ", $rowpeluas1['nilai-kddescription-ket']);
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="B"){
						echo "Memiliki keterampilan baik dalam  ".implode(", ", $rowpeluas1['nilai-kddescription-ket']);
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="C"){
						echo "Memiliki keterampilan cukup dalam  ".implode(", ", $rowpeluas1['nilai-kddescription-ket']);
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="D"){
						echo "Memiliki keterampilan kurang dalam  ".implode(", ", $rowpeluas1['nilai-kddescription-ket']);
					}?>
				</td>
				<?php } else {?>
				<td align="left"><?php 
					if (!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="A") {
						echo "Memiliki keterampilan sangat baik dalam semua materi";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="B"){
						echo "Memiliki keterampilan baik dalam semua materi";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="C"){
						echo "Memiliki keterampilan cukup dalam semua materi";
					} else if(!empty($rowpeluas1['p-nk']) && $rowpeluas1['p-nk']=="D"){
						echo "Memiliki keterampilan kurang dalam semua materi";
					}?></td>
				<?php }?>
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
					<img style="margin-bottom: 5px; margin-top: 9px;" src="<?php echo Yii::app()->getBaseUrl(); ?>/images/ttd_kepsek.jpg" width="250px" height="100px">	
					<br>
					<b><?php echo $kepsek;?></b>
					<br>
					<b>NIP. <?php echo $nip = str_replace('-', ' ', $nik);?></b>
				</p>
			</td>
			<td width="30%">
				<p class="text-center">
					Bandung, <?php echo(DateToIndo(date('2019-03-22')));?><br/>
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
<script>
print();</script>
</body>
</html>

