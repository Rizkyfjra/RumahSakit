<html>
<head>
<style type="text/css">
	<?php echo $inline_style ?>
	@page {
            margin-top: 30px;
            margin-right: 100px;
            margin-left: 100px;
            margin-bottom: 5px;
            
        }

	.border_bottom {
	  border-bottom:2pt solid black;
	}

    /*@media all {
		.page-break	{ display: none; }
	}

	@media print {
		.page-break	{ display: block; page-break-before: always; }
	}*/
</style>
</head>
<body>
<?php  
	$nama_kelas = strtolower($siswa->class->name);
	$sakit = "-";
	$izin = "-";
	$alfa = "-";

	if (strpos(strtolower($nama_kelas),'xii ') !== false) {
		$kkm = 80;
		$rangeC_b = 80;
		$rangeC_u = 87;

		$rangeB_b = 87;
		$rangeB_u = 95;

		$rangeA_b = 95;
		$rangeA_u = 100; 
	} elseif (strpos(strtolower($nama_kelas),'xi ') !== false) {
		$kkm = 78;

		$rangeC_b = 78;
		$rangeC_u = 85;

		$rangeB_b = 85;
		$rangeB_u = 93;

		$rangeA_b = 93;
		$rangeA_u = 100; 

	} elseif (strpos(strtolower($nama_kelas),'x ') !== false) {
		$kkm = 75;

		$rangeC_b = 75;
		$rangeC_u = 83;

		$rangeB_b = 83;
		$rangeB_u = 92;

		$rangeA_b = 92;
		$rangeA_u = 100; 
	} else {
		$kkm = ".....";
	}


function DateToIndo($date) { // fungsi atau method untuk mengubah tanggal ke format indonesia

		$BulanIndo = array("Januari", "Februari", "Maret",
						   "April", "Mei", "Juni",
						   "Juli", "Agustus", "September",
						   "Oktober", "November", "Desember");
	
		$tahun = substr($date, 0, 4); // memisahkan format tahun menggunakan substring
		$bulan = substr($date, 5, 2); // memisahkan format bulan menggunakan substring
		$tgl   = substr($date, 8, 2); // memisahkan format tanggal menggunakan substring
		
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
		$kepsek = "kepsek";
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
			$ta = ($tahun_ajaran[0]->value-1)."/".$tahun_ajaran[0]->value;	
	}else{
		$ta = "";
	}
?>
<?php if (empty($type)){ ?>
<section id="typography" style="font-size:14px;margin-top:10px;">
<?php } else { ?>
<section id="typography" style="font-size:14px;margin-top:100px;">
<?php } ?>
	<table border="0" style="width:100%;">
		<tr>
			<td rowspan="4"><img src="<?php echo Yii::app()->getBaseUrl(); ?>/images/pemda.png" width="100px" height="83px"></td>
			<td colspan="2"  style="text-align:center; padding-bottom:1px;">PEMERINTAH PROVINSI JAWA BARAT </br></td>
			<td align="right" rowspan="4"><img src="<?php echo Yii::app()->getBaseUrl(); ?>/images/logo.png" width="65px" height="65px"></td>
		</tr>
		<tr>
			<td colspan="2"  style="text-align:center; padding-bottom:5px;">DINAS PENDIDIKAN </br></td>
		</tr>
		<tr>
			<td colspan="2"  style="text-align:center; padding-bottom:5px;"><b><?php echo $nama_sekolah[0]->value; ?></b> </br></td>
		</tr>
		<tr class="border_bottom">
			<td colspan="2"  style="text-align:center; padding-bottom:5px;"><?php echo $alamat_sekolah[0]->value;?></br></td>
		</tr>
		<tr>
			<td colspan="4" rowspan="2" style="text-align:center; padding-bottom:40px; padding-top:20px;"><b>LAPORAN CAPAIAN KOMPETENSI TENGAH SEMESTER GENAP</b> </br></td>
		</tr>

		<tr>

		</tr>
		<tr>
			<td>Nama</td>
			<td> : <?php echo $siswa->display_name;?></td>
			<td>Kelas</td>
			<td> : <b><?php echo $siswa->class->name;?></b></td>
		</tr>
		<tr >
			<td>NIS</td>
			<td> : <?php echo $siswa->username;?></td>
			<td>Semester</td>
			<td> : <?php echo $smt;?></td>
		</tr>
		<tr >
			<td>NISN</td>
			<td> : <?php 
			if (!empty($profil)) {
				echo $profil->nisn;
			}
			
			?></td>
			<td>Tahun Pelajaran</td>
			<td> : <?php echo $ta;?></td>
		</tr>
	</table>
	</br>	
	<table border="5" style="width:100%;border: 2px solid black;">
		
		<tr >
			<th rowspan="3">No</th>
			<th rowspan="3" colspan="1" style="text-align: center;" width="45%">Mata Pelajaran</th>
			<th rowspan="3" colspan="1" style="text-align: center;" width="5%">KKM</th>
			<th rowspan="2" colspan="2" style="text-align: center;">Pengetahuan</th>
			<th rowspan="2" colspan="2" style="text-align: center;">Keterampilan</th>
		</tr>
		<tr>

		</tr>
		<tr>
			<th width="7%"><center>Nilai</center></th>
			<th width="7%"><center>Predikat</center></th>
			<th width="7%"><center>Nilai</center></th>
			<th width="7%"><center>Predikat</center></th>
		</tr>

		<tr>
			<td colspan="7">Kelompok A (Umum)</td>
			
		</tr>

		<?php 
		//ksort($klmA);
		$no = 1;
		if(!empty($peluts)){
			foreach ($peluts as $rowpeluts1) { 
				if ($rowpeluts1['kelompok']==1) { 
					
						    if (isset($rowpeluts1['presensi_sakit']) ) {

								if($rowpeluts1['presensi_sakit']=='1'){
									$sakit = "1 hari";
								} else {
									$sakit = $rowpeluts1['presensi_sakit']." hari";											
								}
								
							} elseif ($sakit != "-") {
								//donothing
							} else {
								$sakit = "-";
							}


							if (isset($rowpeluts1['presensi_izin'])) {

								if($rowpeluts1['presensi_izin']=='1'){
									$izin = "1 hari";
								} else {
									$izin = $rowpeluts1['presensi_izin']." hari";											
								}
								
							}  elseif ($sakit != "-") {
								//donothing
							} else {
								$izin = "-";
							}


							if (isset($rowpeluts1['presensi_alfa'])) {

								if($rowpeluts1['presensi_alfa']=='1'){
									$alfa = "1 hari";
								} else {
									$alfa = $rowpeluts1['presensi_alfa']." hari";											
								}
								
							}  elseif ($sakit != "-") {
								//donothing
							} else {
								$alfa = "-";
							}
					

					?>
			<tr>
				<td style="text-align: center;" width="3%"><?php echo $no;?></td>
				<td width="40%">
				 		<?php echo $rowpeluts1['name'];?>
					
				</td>
				<td style="font-size:15px;"><center><?php echo $kkm; ?></center></td>
				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts1['nilai-uts_p'])) {
								if($rowpeluts1['nilai-uts_p']=='1'){
									echo " ";
								} else {
									echo $rowpeluts1['nilai-uts_p'];												
								}
								
							}
						?>
					</center>
				</td>
				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts1['nilai-uts_p'])) {
								if($rowpeluts1['nilai-uts_p']=='1'){
									echo " ";
								} else {
									// echo $rowpeluts1['nilai-uts_p'];
									if($rowpeluts1['nilai-uts_p']<$kkm){
										echo "D";
									}elseif($rowpeluts1['nilai-uts_p']>=$rangeC_b && $rowpeluts1['nilai-uts_p']<$rangeC_u){
										echo "C";
									}elseif($rowpeluts1['nilai-uts_p']>=$rangeB_b && $rowpeluts1['nilai-uts_p']<$rangeB_u){
										echo "B";
									}elseif($rowpeluts1['nilai-uts_p']>=$rangeA_b && $rowpeluts1['nilai-uts_p']<=$rangeA_u){
										echo "A";
									}else{
										echo " ";
									}											
								}
								
							}
						?>
					</center>
				</td>

				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts1['nilai-uts_k'])) {

								if($rowpeluts1['nilai-uts_k']=='1'){
									echo " ";
								} else {
									echo $rowpeluts1['nilai-uts_k'];											
								}
								
							}
						?>
					</center>
				</td>

				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts1['nilai-uts_k'])) {

								if($rowpeluts1['nilai-uts_k']=='1'){
									echo " ";
								} else {
									// echo $rowpeluts1['nilai-uts_k'];
									// echo $rowpeluts1['nilai-uts_p'];
									if($rowpeluts1['nilai-uts_k']<$kkm){
										echo "D";
									}elseif($rowpeluts1['nilai-uts_k']>=$rangeC_b && $rowpeluts1['nilai-uts_k']<$rangeC_u){
										echo "C";
									}elseif($rowpeluts1['nilai-uts_k']>=$rangeB_b && $rowpeluts1['nilai-uts_k']<$rangeB_u){
										echo "B";
									}elseif($rowpeluts1['nilai-uts_k']>=$rangeA_b && $rowpeluts1['nilai-uts_k']<=$rangeA_u){
										echo "A";
									}else{
										echo " ";
									}											
								}
								
							}
						?>
					</center>
				</td>
				
				<!-- <td style="font-size:15px;"><center><?php //echo $nilaiKeterampilan1;?></center></td>
				<td style="font-size:15px;">
					<center>
						<?php
							//echo $predikatKeterampilan1;
						?>
					</center>
				</td> -->
			</tr>

		<?php 
			$no++;

			}
		  } 
		}
		?>

		<tr>
			<td colspan="7">Kelompok B (Umum)</td>
		</tr>

		<?php 
		//ksort($klmB);
		$no = 1;
		if(!empty($peluts)){
			foreach ($peluts as $rowpeluts2) { 
				if ($rowpeluts2['kelompok']==2) { ?>
			<tr>
				<td style="text-align: center;" width="3%"><?php echo $no;?></td>
				<td width="40%">
				 		<?php echo $rowpeluts2['name'];?>
					
				</td>
				<td style="font-size:15px;"><center><?php echo $kkm; ?></center></td>
				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts2['nilai-uts_p'])) {
								if($rowpeluts2['nilai-uts_p']=='1'){
									echo " ";
								}
								else {
								echo $rowpeluts2['nilai-uts_p'];
								}											
							}
						?>
					</center>
				</td>
				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts2['nilai-uts_p'])) {
								if($rowpeluts2['nilai-uts_p']=='1'){
									echo " ";
								} else {
									// echo $rowpeluts2['nilai-uts_p'];
									if($rowpeluts2['nilai-uts_p']<$kkm){
										echo "D";
									}elseif($rowpeluts2['nilai-uts_p']>=$rangeC_b && $rowpeluts2['nilai-uts_p']<$rangeC_u){
										echo "C";
									}elseif($rowpeluts2['nilai-uts_p']>=$rangeB_b && $rowpeluts2['nilai-uts_p']<$rangeB_u){
										echo "B";
									}elseif($rowpeluts2['nilai-uts_p']>=$rangeA_b && $rowpeluts2['nilai-uts_p']<=$rangeA_u){
										echo "A";
									}else{
										echo " ";
									}											
								}
								
							}
						?>
					</center>
				</td>

				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts2['nilai-uts_k'])) {

								if($rowpeluts2['nilai-uts_k']=='1'){
									echo " ";
								} else {
									echo $rowpeluts2['nilai-uts_k'];											
								}
								
							}
						?>
					</center>
				</td>

				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts2['nilai-uts_k'])) {

								if($rowpeluts2['nilai-uts_k']=='1'){
									echo " ";
								} else {
									// echo $rowpeluts2['nilai-uts_k'];
									// echo $rowpeluts2['nilai-uts_p'];
									if($rowpeluts2['nilai-uts_k']<$kkm){
										echo "D";
									}elseif($rowpeluts2['nilai-uts_k']>=$rangeC_b && $rowpeluts2['nilai-uts_k']<$rangeC_u){
										echo "C";
									}elseif($rowpeluts2['nilai-uts_k']>=$rangeB_b && $rowpeluts2['nilai-uts_k']<$rangeB_u){
										echo "B";
									}elseif($rowpeluts2['nilai-uts_k']>=$rangeA_b && $rowpeluts2['nilai-uts_k']<=$rangeA_u){
										echo "A";
									}else{
										echo " ";
									}											
								}
								
							}
						?>
					</center>
				</td>
				
			</tr>

		<?php 
			$no++;
				}	
			} 
		}
		?>

		

		<tr>
			<td colspan="7">Kelompok C (Peminatan dan Lintas Minat)</td>
		</tr>

		<?php
			
			$no = 1;
			if(!empty($peluts)){
				foreach ($peluts as $rowpeluts3) { 
					if ($rowpeluts3['kelompok']==3) { ?>
				<tr>
					<td style="text-align: center;" width="3%"><?php echo $no;?></td>
					<td width="40%">
					 		<?php echo $rowpeluts3['name'];?>
						
					</td>
					<td style="font-size:15px;"><center><?php echo $kkm; ?></center></td>
					<td style="font-size:15px;">
						<center>
							<?php 
								if (isset($rowpeluts3['nilai-uts_p'])) {
									if($rowpeluts3['nilai-uts_p']=='1'){
									echo " ";
									} else {
									echo $rowpeluts3['nilai-uts_p'];	
									}										
								}
							?>
						</center>
					</td>
					<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts3['nilai-uts_p'])) {
								if($rowpeluts3['nilai-uts_p']=='1'){
									echo " ";
								} else {
									// echo $rowpeluts3['nilai-uts_p'];
									if($rowpeluts3['nilai-uts_p']<$kkm){
										echo "D";
									}elseif($rowpeluts3['nilai-uts_p']>=$rangeC_b && $rowpeluts3['nilai-uts_p']<$rangeC_u){
										echo "C";
									}elseif($rowpeluts3['nilai-uts_p']>=$rangeB_b && $rowpeluts3['nilai-uts_p']<$rangeB_u){
										echo "B";
									}elseif($rowpeluts3['nilai-uts_p']>=$rangeA_b && $rowpeluts3['nilai-uts_p']<=$rangeA_u){
										echo "A";
									}else{
										echo " ";
									}											
								}
								
							}
						?>
					</center>
				</td>


				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts3['nilai-uts_k'])) {

								if($rowpeluts3['nilai-uts_k']=='1'){
									echo " ";
								} else {
									echo $rowpeluts3['nilai-uts_k'];											
								}
								
							}
						?>
					</center>
				</td>

				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts3['nilai-uts_k'])) {

								if($rowpeluts3['nilai-uts_k']=='1'){
									echo " ";
								} else {
									// echo $rowpeluts3['nilai-uts_k'];
									// echo $rowpeluts3['nilai-uts_p'];
									if($rowpeluts3['nilai-uts_k']<$kkm){
										echo "D";
									}elseif($rowpeluts3['nilai-uts_k']>=$rangeC_b && $rowpeluts3['nilai-uts_k']<$rangeC_u){
										echo "C";
									}elseif($rowpeluts3['nilai-uts_k']>=$rangeB_b && $rowpeluts3['nilai-uts_k']<$rangeB_u){
										echo "B";
									}elseif($rowpeluts3['nilai-uts_k']>=$rangeA_b && $rowpeluts3['nilai-uts_k']<=$rangeA_u){
										echo "A";
									}else{
										echo " ";
									}											
								}
								
							}
						?>
					</center>
				</td>
					
				</tr>

			<?php 
				$no++;
					}
				} 
			}
			?>
	</table>
	<br>
	Tabel Interval
	<table border="5"  style="width:60%;border: 2px solid black;">
		<tr>
			<th rowspan="2" style="font-size:15px;  text-align: center; padding: 2px;">
				KKM
			</th>
			<th colspan="4" style="font-size:15px; text-align: center; padding: 2px;">
				Predikat
			</th>
		</tr>
		<tr>
			<th style="font-size:15px; text-align: center; padding: 2px;">
				D = Kurang
			</th>
			<th style="font-size:15px; text-align: center; padding: 2px;">
				C = Cukup
			</th>
			<th style="font-size:15px; text-align: center; padding: 2px;">
				B = Baik
			</th>
			<th style="font-size:15px; text-align: center; padding: 2px;">
				A = Sangat Baik
			</th>
		</tr>
		<tr>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				75
			</td>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				&#060; 75
			</td>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				75 &#8804; C &#060; 83 
			</td>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				83 &#8804; C &#060; 92
			</td>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				92 &#8804; C &#060; 100
			</td>
		</tr>
		<tr>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				78
			</td>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				&#060; 78
			</td>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				78 &#8804; C &#060; 85 
			</td>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				85 &#8804; C &#060; 93
			</td>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				93 &#8804; C &#060; 100
			</td>
		</tr>
		<tr>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				80
			</td>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				&#060; 80
			</td>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				80 &#8804; C &#060; 87 
			</td>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				87 &#8804; C &#060; 95
			</td>
			<td style="font-size:15px; text-align: center; padding: 2px;">
				95 &#8804; C &#060; 100
			</td>
		</tr>
	</table>
	</br>
	Ketidakhadiran
			<table border="5" style="width:50%;border: 2px solid black;">
				<tr>
					<td width="60%">Sakit</td>
					<?php if(!empty($peluts2['Absensi Sakit']) && $peluts2['Absensi Sakit']!="-") { ?>
						<td align="center"><?php echo $peluts2['Absensi Sakit']; ?> hari</td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
				</tr>
				<tr>
					<td width="60%">Izin</td>
					<?php if(!empty($peluts2['Absensi Izin']) && $peluts2['Absensi Izin']!="-") { ?>
						<td align="center"><?php echo $peluts2['Absensi Izin']; ?> hari</td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
				</tr>
				<tr>
					<td width="60%">Tanpa Keterangan</td>
					<?php if(!empty($peluts2['Absensi Alfa']) && $peluts2['Absensi Alfa']!="-") { ?>
						<td align="center"><?php echo $peluts2['Absensi Alfa']; ?> hari</td>
					<?php }else{ ?>
						<td align="center">-</td>
					<?php } ?>
				</tr>
			</table>
	<!-- <br/> -->
	<!-- <p>Ketidakhadiran</p>
	<table border="1"  style="margin-top: 0.5px;margin-bottom: 0.5px;">
		<tr>
			<td style="text-align: left; padding: 10px;">
				Sakit
			</td>
			<td style="text-align: center; padding: 10px;">
				<?php //echo $sakit; ?>
			</td>
		</tr>
		<tr>
			<td style="text-align: left; padding: 10px;">
				Izin
			</td>
			<td style="text-align: center; padding: 10px;">
				<?php //echo $izin; ?>
			</td>
		</tr>
		<tr>
			<td style="text-align: left; padding: 10px;">
				Tanpa Keterangan
			</td>
			<td style="text-align: center; padding: 10px;">
				<?php //echo $alfa; ?>
			</td>
		</tr>
	</table> -->
	<br/>
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
					<br>
					...................
				</p>

			</td>
			<td>
				<p class="text-center" style="font-size:15px;">
					Bandung, 		
							<?php 
							if(Yii::app()->session['titimangsa']){
								echo Yii::app()->session['titimangsa'];
							}else {
								echo(DateToIndo(date('2016-06-18')));
							}
							?><br/>
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
		</tr>
	</table>
</section>	
</body>
</html>
