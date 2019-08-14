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

	.border_top {
	  border-top:2pt solid black;
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
			<td rowspan="4"><img src="<?php echo Yii::app()->getBaseUrl(); ?>/images/jawabarat.png" width="100px" height="83px"></td>
			<td colspan="2"  style="text-align:center; padding-bottom:1px;">PEMERINTAH PROVINSI JAWA BARAT </br></td>
			<td align="right" rowspan="4"><img src="<?php echo Yii::app()->getBaseUrl(); ?>/images/logo.png" width="65px" height="65px"></td>
		</tr>
		<tr>
			<td colspan="2"  style="text-align:center; padding-bottom:5px;">DINAS PENDIDIKAN </br></td>
		</tr>
		<tr>
			<td colspan="2"  style="text-align:center; padding-bottom:5px;"><b><?php echo $nama_sekolah[0]->value; ?></b> </br></td>
		</tr>
		<tr>
			<td colspan="2"  style="text-align:center; padding-bottom:5px;"><?php echo $alamat_sekolah[0]->value;?></br></td>
		</tr>
	</table>
	<table border="0" style="width:100%;">
		<tr class="border_top">
			<td>Lampiran</td>
			<td>: - </td>
			<td></td>
			<td>Bandung,23 Oktober 2018</td>
		</tr>
		<tr >
			<td>Perihal </td>
			<td> : Informasi akun edueye</td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td></td>
		</tr>
		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td>Kepada yth,</td>
		</tr>

		<tr>
			<td></td>
			<td></td>
			<td></td>
			<td>Bapak/Ibu Orang Tua/Wali</td>
		</tr>
			<td></td>
			<td></td>
			<td></td>
			<td>di -</td>
		</tr>
			</tr>
			<td></td>
			<td></td>
			<td></td>
			<td>Tempat</td>
		</tr>
	</table>
	</br>
	<p>Dengan hormat,</p>
	<p>Sehubugngan dengan adanya aplikasi edueye yaitu aplikasi untuk memantau nilai dan absensi siswa disekolah untuk orang tua, Bapak/Ibu bisa menggunakan akun berikut ini untuk login ke aplikasi tersebut</p>	
	</br>	
	<table>
		<tr>
			<td>
				Username
			</td>
			<td>
				: <?php echo $ortu->username;?>
			</td>
		</tr>
		<tr>
			<td>
				Password
			</td>
			<td>
				: edubox
			</td>
		</tr>
	</table>
	</br>
	</br>
	<p> Berikut langakah-langkah untuk mengakses aplikasi edueye :</p>
	<ul>
		<li>Masuk ke browser, Google Chrome</li>
		<li>Buka halaman https://ortu.edubox.cloud</li>
		<li>Jika sudah terbuka halaman nya akan muncul popup di bawah yang bertulisan Add to Home Screen</li>
			<ul>
				<li>Jika popup tidak muncul :</li>
				<li>Chrome = klik settings -> Add to home screen</li>
				<li>Samsung browser = klik logo add to home screen di kanan atas</li> 
				<li>Safari = swipe ke atas -> cari add to home screen</li>
			</ul>
		<li>Klik tulisan tersebut , lalu EduEye akan ada di Home Screen Hp</li>
		<li>Kemudian login menggunakan akun diatas</li>
	</ul>
	<p> Demikianlah kami sampaikan kepada Bapak/Ibu, atas perhatiannya kami ucapkan terima kasih.</p>
</section>	
</body>
</html>
