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
		if($penanda == 2 && date('n') < 8){
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
for ($i=0; $i < $count_siswa; $i++) { 
$nama_kelas = strtolower($siswa[$i]->class->name);


	if (strpos(strtolower($nama_kelas),'xii') !== false) {
		$kkm = 80;
	} elseif (strpos(strtolower($nama_kelas),'11') !== false) {
		$kkm = 75;
	} elseif (strpos(strtolower($nama_kelas),'10') !== false) {
		$kkm = 75;
	} else {
		$kkm = ".....";
	}

	?>
<?php if (empty($type)){ ?>
<section id="typography" style="font-size:14px;margin-top:10px; margin-bottom:130px;">
<?php } else { ?>
<section id="typography" style="font-size:14px;margin-top:100px;">
<?php } ?>
	<table border="0" style="width:100%;">
		<tr>
			<td rowspan="4"><img src="<?php echo Yii::app()->getBaseUrl(); ?>/images/pemda.png" width="100px" height="83px"></td>
			<td colspan="3" rowspan="1" style="text-align:center; padding-bottom:1px;">PEMERINTAH KOTA BANDUNG </br></td>
		</tr>
		<tr>
			<td colspan="3" rowspan="1" style="text-align:center; padding-bottom:5px;">DINAS PENDIDIKAN </br></td>
		</tr>
		<tr>
			<td colspan="3" rowspan="1" style="text-align:center; padding-bottom:5px;"><b>SMA NEGERI 24 BANDUNG</b> </br></td>
		</tr>
		<tr class="border_bottom">
			<td colspan="3" rowspan="1" style="text-align:center; padding-bottom:5px;">Jl. A.H. Nasution No. 27 Ujungberung Kota Bandung </br></td>
		</tr>
		<tr>
			<td colspan="4" rowspan="2" style="text-align:center; padding-bottom:40px; padding-top:20px;"><b>Capaian Hasil Belajar Tengah Semester</b> </br></td>
		</tr>

		<tr>

		</tr>
		<tr>
			<td>Nama</td>
			<td> : <?php echo $siswa[$i]->display_name;?></td>
			<td>Kelas</td>
			<td> : <b><?php echo $siswa[$i]->class->name;?></b></td>
		</tr>
		<tr >
			<td>NIS</td>
			<td> : <?php echo $siswa[$i]->username;?></td>
			<td>Semester</td>
			<td> : <?php echo $smt;?></td>
		</tr>
		<tr >
			<td>NISN</td>
			<td> : <?php 
			if (!empty($profil[$i])) {
				echo $profil[$i]->nisn;
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
			<th rowspan="3" colspan="1" style="text-align: center;" width="5%" >KKM</th>
			<th rowspan="2" colspan="2" style="text-align: center;">Pengetahuan</th>
			<!-- <th rowspan="2" colspan="2" style="text-align: center;">Keterampilan</th> -->
		</tr>
		<tr>

		</tr>
		<tr>
			<th width="7%"><center>Nilai Harian</center></th>
			<th width="7%"><center>UTS</center></th>
			<!-- <th width="7%"><center>Angka</center></th>
			<th width="7%"><center>Predikat</center></th> -->
		</tr>

		<tr>
			<td colspan="5">Kelompok A (Umum)</td>
			
		</tr>

		<?php 
		//ksort($klmA);
		$no = 1;
		if(!empty($peluts[$i])){
			foreach ($peluts[$i] as $rowpeluts1) { 
				if ($rowpeluts1['kelompok']==1) { ?>
			<tr>
				<td style="text-align: center;" width="3%"><?php echo $no;?></td>
				<td width="40%">
				 		<?php echo $rowpeluts1['name'];?>
					
				</td>
				<td style="font-size:15px;"><center><?php echo $kkm; ?></center></td>
				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts1['nilai-rnh'])) {
								if($rowpeluts1['nilai-rnh']=='1'){
									echo "0";
								} else {
									echo $rowpeluts1['nilai-rnh'];												
								}
								
							}
						?>
					</center>
				</td>
				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts1['nilai-uts'])) {

								if($rowpeluts1['nilai-uts']=='1'){
									echo "0";
								} else {
									echo $rowpeluts1['nilai-uts'];											
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
			<td colspan="5">Kelompok B (Umum)</td>
		</tr>

		<?php 
		//ksort($klmB);
		$no = 1;
		if(!empty($peluts[$i])){
			foreach ($peluts[$i] as $rowpeluts2) { 
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
							if (isset($rowpeluts2['nilai-rnh'])) {
								if($rowpeluts2['nilai-rnh']=='1'){
									echo "0";
								}
								else {
								echo $rowpeluts2['nilai-rnh'];
								}											
							}
						?>
					</center>
				</td>
				<td style="font-size:15px;">
					<center>
						<?php 
							if (isset($rowpeluts2['nilai-uts'])) {
								if($rowpeluts2['nilai-uts']=='1'){
									echo "0";
								} else {
								echo $rowpeluts2['nilai-uts'];
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
			<td colspan="6">Kelompok C (Peminatan dan Lintas Minat)</td>
		</tr>

		<?php
			
			$no = 1;
			if(!empty($peluts[$i])){
				foreach ($peluts[$i] as $rowpeluts3) { 
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
								if (isset($rowpeluts3['nilai-rnh'])) {
									if($rowpeluts3['nilai-rnh']=='1'){
									echo "0";
									} else {
									echo $rowpeluts3['nilai-rnh'];	
									}										
								}
							?>
						</center>
					</td>
					<td style="font-size:15px;">
						<center>
							<?php 
								if (isset($rowpeluts3['nilai-uts'])) {
									if($rowpeluts3['nilai-uts']=='1'){
									echo "0";
									} else{
									echo $rowpeluts3['nilai-uts'];
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
					<!-- Bandung, <?php //echo date('d F Y');?> -->
					Bandung, <?php echo(DateToIndo(date('2016-04-22')));?>
					<br>Kepala Sekolah
					<br>
					<img style="margin-bottom: 5px; margin-top: 9px;" src="<?php echo Yii::app()->getBaseUrl(); ?>/images/taman2.png" width="175px" height="100px">	
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
<?php }?>
</body>
</html>
