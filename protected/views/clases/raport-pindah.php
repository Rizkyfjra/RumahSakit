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
		case $jk == 1:
			return "Laki-Laki";
			break;

		case $jk == 2:
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

// if(!empty($profil)){
// 	$tempat_lahir = ucwords($profil->tempat_lahir);
// 	// $tanggal_lahir = DateToIndo($profil->tgl_lahir);
// 	 if (!empty($profil->tgl_lahir)) {
// 		$tanggal_lahir = DateToIndo($profil->tgl_lahir);
//      } else {
// 		$tanggal_lahir = "";
// 	 }

// 	  if (!empty($profil->nisn)) {
// 		$nisn = $profil->nisn;
//      } else {
// 		$nisn = "";
// 	 }

// 	$jenis_kelamin = ChangeJenisKelamin($profil->j_kelamin);
// 	$agama = ChangeAgama($profil->agama);
// 	$status_keluarga = ChangeStatusKeluarga($profil->status_keluarga);
// 	$anak_ke = $profil->anak_ke;
// 	$alamat_siswa = $profil->alamat_tinggal;
// 	$no_telp = $profil->no_telpon;
// 	$sekolah_asal = ucwords($profil->sekolah_asal);
// 	$kelas_diterima = $profil->kelas_diterima;
// 	 if (!empty($profil->$tanggal_diterima)) {
// 		$tanggal_diterima = DateToIndo($profil->tanggal_diterima);	
//      } else {
// 		$tanggal_diterima = "";	
// 	 }
	
// 	$nama_ayah = $profil->ayah_nama;
// 	$pekerjaan_ayah = $profil->ayah_pekerjaan;
// 	$alamat_ortu = $profil->alamat_ortu;
// 	$no_telp_ortu = $profil->no_telp_ortu;
// 	$nama_ibu = $profil->ibu_nama;
// 	$pekerjaan_ibu = $profil->ibu_pekerjaan;
// 	if (!empty($wali_nama)) {
			
// 		$nama_wali = $profil->wali_nama;
// 		$alamat_wali = $profil->alamat_wali;
// 		$no_telp_wali = $profil->no_telp_wali;
// 		$pekerjaan_wali = $profil->pekerjaan_wali;
// 	} else {
// 		$nama_wali = "";
// 		$alamat_wali = "";
// 		$no_telp_wali = "";
// 		$pekerjaan_wali = "";
// 	}
	
// }

?>

<!-- Halaman 1 -->
<section id="typography" class="break-after" style="margin-top:10px;">
	<table style="width:100%;margin-top:50px;">
		<tr>
			<td align="center"><b>RAPOR SISWA</b></td>
		</tr>
		<tr>
			<td align="center"><b>SEKOLAH MENANGAH ATAS</b></td>
		</tr>
		<tr>
			<td align="center"><b>(SMA)</b></td>
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

	<table style="width:100%;margin-top:50px;">
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
