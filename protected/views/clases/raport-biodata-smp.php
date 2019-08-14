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
			padding-top: 5px;
			padding-bottom: 5px;
			padding-left: 5px;
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
	
	$kepala_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%kepsek_id%"'));

	            $nama_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_name%"'));
                // $kepala_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%kepsek_id%"'));
                $alamat_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_address%"'));
                $kurikulum_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%kurikulum%"'));
                $ulangan = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_ulangan%"'));
                $tugas = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_tugas%"'));
                $materi = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_materi%"'));
                $rekap = Option::model()->findAll(array('condition' => 'key_config LIKE "%fitur_rekap%"'));
                $semester = Option::model()->findAll(array('condition' => 'key_config LIKE "%semester%"'));
                $tahun_ajaran = Option::model()->findAll(array('condition' => 'key_config LIKE "%tahun_ajaran%"'));
                $nilai_harian = Option::model()->findAll(array('condition' => 'key_config LIKE "%nilai_harian%"'));
                $nilai_uts = Option::model()->findAll(array('condition' => 'key_config LIKE "%nilai_uts%"'));
                $nilai_uas = Option::model()->findAll(array('condition' => 'key_config LIKE "%nilai_uas%"'));
                $kd_sikap = Option::model()->findAll(array('condition' => 'key_config LIKE "%kd_sikap%"'));
                $npsn = Option::model()->findAll(array('condition' => 'key_config LIKE "%npsn%"'));
                $nss = Option::model()->findAll(array('condition' => 'key_config LIKE "%nss%"'));
                $kelurahan = Option::model()->findAll(array('condition' => 'key_config LIKE "%kelurahan%"'));
                $kecamatan = Option::model()->findAll(array('condition' => 'key_config LIKE "%kecamatan%"'));
                $kota_kabupaten = Option::model()->findAll(array('condition' => 'key_config LIKE "%kota_kabupaten%"'));
                $provinsi = Option::model()->findAll(array('condition' => 'key_config LIKE "%provinsi%"'));
                $website = Option::model()->findAll(array('condition' => 'key_config LIKE "%website%"'));
                $email = Option::model()->findAll(array('condition' => 'key_config LIKE "%email%"'));
                $server = Option::model()->findAll(array('condition' => 'key_config LIKE "%server%"'));

	
	if(!empty($kepala_sekolah) and !empty($kepala_sekolah[0]->value)){
		$user_kepsek = User::model()->findByPk($kepala_sekolah[0]->value);
		$kepsek = $user_kepsek->display_name;
		$nik = $user_kepsek->username;
	}else{
		$kepsek = "Kepala Sekolah";
		$nik = "022";
	}

	$nama_kelas = strtolower($siswa->class->name);
	if (strpos($nama_kelas,'ipa') !== false) {
	    $kode_rapot = "A";
	} elseif (strpos($nama_kelas,'ips') !== false) {
		$kode_rapot = "S";
	} else {
		$kode_rapot = "B";
	}

	if (strpos(strtolower($nama_kelas),'12 ')!==false || strpos(strtolower($nama_kelas),'xii ')!==false) {
		$kkm = 80;
	} elseif (strpos(strtolower($nama_kelas),'11 ')!==false || strpos(strtolower($nama_kelas),'xi ')!==false) {
		$kkm = 78;
	} elseif (strpos(strtolower($nama_kelas),'10 ')!==false || strpos(strtolower($nama_kelas),'x ')!==false) {
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
		$tgl   = substr($date, 8, 2);
		
		$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun;
		return($result);
}

function ChangeJenisKelamin($jk){
	switch ($jk) {
		case $jk == "Laki-Laki":
			return "Laki-Laki";
			break;

		case $jk == "Perempuan":
			return "Perempuan";
			break;
		
		default:
			return "Laki-Laki";
			break;
	}
}

function ChangeStatusKeluarga($sk){
	switch ($sk) {
		case $sk == 1:
			return "Anak Kandung";
			break;

		case $sk == 2:
			return "Anak Angkat";
			break;
			
		case $sk == 3:
			return "Anak Tiri";
			break;
		
		case $sk == 4:
			return "Lainnya";
			break;			
		
		default:
			return "Anak Kandung";
			break;
	}
}

function ChangeAgama($agama){
	switch ($agama) {
		case $agama == 1:
			return "Islam";
			break;

		case $agama == 2:
			return "Katholik";
			break;
			
		case $agama == 3:
			return "Protestan";
			break;
		
		case $agama == 4:
			return "Hindu";
			break;

		case $agama == 5:
			return "Buddha";
			break;
			
		case $agama == 6:
			return "Konghucu";
			break;
			
		case $agama == 99:
			return "Lainnya";
			break;						
		
		default:
			return "Lainnya";
			break;
	}
}

$tempat_lahir = '';
$nisn = '';
$tanggal_lahir = '';
$jenis_kelamin = '';
$agama = '';
$status_keluarga = '';
$anak_ke = '';
$alamat_siswa = '';
$no_telp = '';
$sekolah_asal = '';
$kelas_diterima = '';
$tanggal_diterima = '';
$nama_ayah = '';
$pekerjaan_ayah = '';
$alamat_ortu = '';
$no_telp_ortu = '';
$nama_ibu = '';
$pekerjaan_ibu = '';
$nama_wali = '';
$no_telp_wali = '';
$pekerjaan_wali = '';
$alamat_wali = '';

if(!empty($profil)){
	$tempat_lahir = ucwords($profil->tempat_lahir);
	// $tanggal_lahir = DateToIndo($profil->tgl_lahir);
	 if (!empty($profil->tgl_lahir) && $profil->tgl_lahir != "0000-00-00") {
		$tanggal_lahir = DateToIndo($profil->tgl_lahir);
     } else {
		$tanggal_lahir = "";
	 }

	  if (!empty($profil->nisn)) {
		$nisn = $profil->nisn;
     } else {
		$nisn = "";
	 }

	// $jenis_kelamin = ChangeJenisKelamin($profil->j_kelamin);
	 $jenis_kelamin = $profil->j_kelamin;
	// $agama = ChangeAgama($profil->agama);
	$agama = $profil->agama;
	$status_keluarga = ChangeStatusKeluarga($profil->status_keluarga);
	$anak_ke = $profil->anak_ke;
	$alamat_siswa = $profil->alamat_tinggal;
	$no_telp = $profil->no_telpon;
	$sekolah_asal = ucwords($profil->sekolah_asal);
	$kelas_diterima = $profil->kelas_diterima;
	 if (!empty($profil->tanggal_diterima) && $profil->tanggal_diterima != "0000-00-00") {
		$tanggal_diterima = DateToIndo($profil->tanggal_diterima);	
     } else {
		$tanggal_diterima = "";	
	 }
	
	$nama_ayah = $profil->ayah_nama;
	$pekerjaan_ayah = $profil->ayah_pekerjaan;
	$alamat_ortu = $profil->alamat_ortu;
	$no_telp_ortu = $profil->no_telp_ortu;
	$nama_ibu = $profil->ibu_nama;
	$pekerjaan_ibu = $profil->ibu_pekerjaan;
	if (!empty($wali_nama)) {
			
		$nama_wali = $profil->wali_nama;
		$alamat_wali = $profil->alamat_wali;
		$no_telp_wali = $profil->no_telp_wali;
		$pekerjaan_wali = $profil->pekerjaan_wali;
	} else {
		$nama_wali = "";
		$alamat_wali = "";
		$no_telp_wali = "";
		$pekerjaan_wali = "";
	}
	
}

?>

<!-- Halaman 1 -->
<section id="typography" class="break-after" style="margin-top:10px;">
	<table style="width:100%;margin-top:10px;">
		<tr>
			<td align="center"><b>RAPOR SISWA</b></td>
		</tr>
		<tr>
			<td align="center"><b>SEKOLAH MENANGAH PERTAMA</b></td>
		</tr>
		<tr>
			<td align="center"><b>(SMP)</b></td>
		</tr>
	</table>

	<table style="width:100%;margin-top:100px;">
		<tr>
			<td align="center"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/images/logo-tut-wuri-handayani.jpg" width="200px" height="200px"></td>
		</tr>
	</table>
	<div>
	<table style="width:100%;margin-top:100px;">
		<tr>
			<td align="center"><b>Nama Siswa:</b></td>
		</tr>
		<tr>
			<td align="center"><?php //echo $siswa->display_name;?><input type="text" name="" id="input" class="form-control" value="<?php echo $siswa->display_name;?>" style="text-align:center;" size="40"></td>
		</tr>
	</table>

	<table style="width:100%;margin-top:10px;">
		<tr>
			<td align="center"><b>NISN:</b></td>
		</tr>
		<tr>
			<td align="center"><?php //echo $profil->nisn;?><input type="text" name="" id="input" class="text-center form-control" value="<?php echo $nisn;?>" style="text-align:center;" size="40"></td>
		</tr>
	</table>

	<table style="width:100%;margin-top:200px;">
		<tr>
			<td align="center"><b>KEMENTERIAN PENDIDIKAN DAN KEBUDAYAAN</b></td>
		</tr>
		<tr>
			<td align="center"><b>REPUBLIK INDONESIA</b></td>
		</tr>
	</table>

</section>


<!-- Halaman 2 -->
<section id="typography" class="break-after" style="font-size:12px;margin-top:10px;">
	<table style="width:100%;margin-top:10px;">
		<tr>
			<td align="center"><b>RAPOR SISWA</b></td>
		</tr>
		<tr>
			<td align="center"><b>SEKOLAH MENANGAH PERTAMA</b></td>
		</tr>
		<tr>
			<td align="center"><b>(SMP)</b></td>
		</tr>
	</table>

	<table style="font-size:15px;width:100%;margin-top:100px;">
		<tr>
			<td width="10%" style="padding-bottom: 3em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 3em;">Nama Sekolah</td>
			<td align="center" style="padding-bottom: 3em;">:</td>
			<td style="padding-bottom: 3em;">
				<?php if (!empty($nama_sekolah)) {
					echo $nama_sekolah[0]->value;
				} else {
					echo "-";
				}?>
			</td>
		</tr>
		<tr >
			<td width="10%" style="padding-bottom: 3em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 3em;">NPSN</td>
			<td align="center" style="padding-bottom: 3em;">:</td>
			<td style="padding-bottom: 3em;">
				<?php if (!empty($npsn)) {
					echo $npsn[0]->value;
				} else {
					echo "-";
				}?>
			</td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 3em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 3em;">NSS</td>
			<td align="center" style="padding-bottom: 3em;">:</td>
			<td style="padding-bottom: 3em;">
				<?php if (!empty($nss)) {
					echo $nss[0]->value;
				} else {
					echo "-";
				}?>
			</td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 3em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 3em;">Alamat Sekolah</td>
			<td align="center" style="padding-bottom: 3em;">:</td>
			<td style="padding-bottom: 3em;">
				<?php if (!empty($alamat_sekolah)) {
					echo $alamat_sekolah[0]->value;
				} else {
					echo "-";
				}?>
			</td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 3em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 3em;">Kelurahan</td>
			<td align="center" style="padding-bottom: 3em;">:</td>
			<td style="padding-bottom: 3em;">
				<?php if (!empty($kelurahan)) {
					echo $kelurahan[0]->value;
				} else {
					echo "-";
				}?>
			</td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 3em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 3em;">Kecamatan</td>
			<td align="center" style="padding-bottom: 3em;">:</td>
			<td style="padding-bottom: 3em;">
				<?php if (!empty($kecamatan)) {
					echo $kecamatan[0]->value;
				} else {
					echo "-";
				}?>
			</td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 3em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 3em;">Kota/Kabupaten</td>
			<td align="center" style="padding-bottom: 3em;">:</td>
			<td style="padding-bottom: 3em;">
				<?php if (!empty($kota_kabupaten)) {
					echo $kota_kabupaten[0]->value;
				} else {
					echo "-";
				}?>
			</td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 3em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 3em;">Provinsi</td>
			<td align="center" style="padding-bottom: 3em;">:</td>
			<td style="padding-bottom: 3em;">
				<?php if (!empty($provinsi)) {
					echo $provinsi[0]->value;
				} else {
					echo "-";
				}?>
			</td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 3em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 3em;">Website</td>
			<td align="center" style="padding-bottom: 3em;">:</td>
			<td style="padding-bottom: 3em;">
				<?php if (!empty($website)) {
					echo $website[0]->value;
				} else {
					echo "-";
				}?>
			</td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 3em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 3em;">E-mail</td>
			<td align="center" style="padding-bottom: 3em;">:</td>
			<td style="padding-bottom: 3em;">
				<?php if (!empty($email)) {
					echo $email[0]->value;
				} else {
					echo "-";
				}?>
			</td>
		</tr>
	</table>
</section>


<!-- Halaman 3 -->
<section id="typography" class="break-after" style="margin-top:10px;">
	<table style="width:100%;margin-top:10px;">
		<tr>
			<td align="center"><b>IDENTITAS SISWA</b></td>
		</tr>
	</table>

	<table style="font-size:15px;width:100%;margin-top:50px;">
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">Nama Lengkap Siswa</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $siswa->display_name;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">NISS/NISN</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $siswa->username." / ".$nisn;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">Tempat, Tanggal Lahir</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;">

				<?php echo $tempat_lahir.", ".$tanggal_lahir;?>
			</td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">Jenis Kelamin</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $jenis_kelamin;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">Agama</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $agama;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">Status Dalam Keluarga</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $status_keluarga;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">Anak Ke</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $anak_ke;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">Alamat Siswa</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $alamat_siswa;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">Nomor Telepon Rumah/HP</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $no_telp;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">Sekolah Asal</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $sekolah_asal;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">Diterima di sekolah ini</td>
			<td align="center" style="padding-bottom: 0.5em;"></td>
			<td style="padding-bottom: 0.5em;"></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;Di kelas</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $kelas_diterima;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;Pada tanggal</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $tanggal_diterima;?></td>
		</tr>

		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">Orang Tua / Wali</td>
			<td align="center" style="padding-bottom: 0.5em;"></td>
			<td style="padding-bottom: 0.5em;"></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;a) Ayah</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;&nbsp;&nbsp;1) Nama</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $nama_ayah;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;&nbsp;&nbsp;2) Alamat</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $alamat_ortu;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;&nbsp;&nbsp;3) Pekerjaan Orang Tua</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $pekerjaan_ayah;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;&nbsp;&nbsp;4) Nomor Telepon Rumah/HP</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $no_telp_ortu;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;b) Ibu</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;&nbsp;&nbsp;1) Nama</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $nama_ibu;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;&nbsp;&nbsp;2) Alamat</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $alamat_ortu;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;&nbsp;&nbsp;3) Pekerjaan Orang Tua</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $pekerjaan_ibu;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;&nbsp;&nbsp;4) Nomor Telepon Rumah/HP</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $no_telp_ortu;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;c) Wali</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;&nbsp;&nbsp;1) Nama</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $nama_wali;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;&nbsp;&nbsp;2) Alamat</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $alamat_wali;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;&nbsp;&nbsp;3) Pekerjaan Wali</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $pekerjaan_wali;?></td>
		</tr>
		<tr>
			<td width="10%" style="padding-bottom: 0.5em;">&nbsp;</td>
			<td width="40%" style="padding-bottom: 0.5em;">&nbsp;&nbsp;&nbsp;&nbsp;4) Nomor Telepon Rumah/HP</td>
			<td align="center" style="padding-bottom: 0.5em;">:</td>
			<td style="padding-bottom: 0.5em;"><?php echo $no_telp_wali;?></td>
		</tr>
	</table>
	<br/><br/>
	<table style="margin-top: 0.5px;margin-bottom: 0.5px;width:100%;">
		<tr>
			<td widht="40%">
				&nbsp;
				<br>
				
				&nbsp;
			</td>
			<td width="10%">
				<br>
				<p class="text-center" style="font-size:12px;padding: 1em;">
					<br>
					Pas Foto
					<br>
					3 x 4
					<br>
					<br>
					
				</p>
				
			</td>
			<td width="50%">
				<p class="text-center" style="font-size:12px;">
					<!-- Bandung, <?php echo date('d F Y');?> -->
					Bandung, <?php 
					if(Yii::app()->session['titimangsa']){
						echo Yii::app()->session['titimangsa'];
					}else {
						echo(DateToIndo(date('2016-06-18')));
					}
					?>
					<br>Kepala Sekolah,
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
<script>print();</script>
</body>
</html>
