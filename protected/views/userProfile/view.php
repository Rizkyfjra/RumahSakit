<?php
/* @var $this UserProfileController */
/* @var $model UserProfile */

$this->breadcrumbs=array(
	$nama=>array('user/view','id'=>$user_id),	
	'Profil Siswa',
);

?>

<h1>Lihat Profil Siswa : <?php echo $nama; ?></h1>

<br/><br/>
<div class="alert alert-info">
	<p><strong>IDENTITAS PESERTA DIDIK</strong></p>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'nisn',
		'j_kelamin',
		'no_seri_ijazah',
		'no_seri_skhun',
		'no_un',
		'nik',
		'tempat_lahir',
		'tgl_lahir',
		'agama',
		'berkebutuhan_khusus',
		'alamat_tinggal',
		'alamat_dusun',
		'alamat_rt',
		'alamat_rw',
		'alamat_kelurahan',
		'alamat_kodepos',
		'alamat_kecamatan',
		'alamat_kota',
		'alamat_provinsi',
		'alat_transportasi',
		'jenis_tinggal',
		'no_telpon',
		'email',
		'penerima_kps',
		'no_kps',
	),
)); ?>



<br/><br/>
<div class="alert alert-info">
	<p><strong>DATA AYAH KANDUNG</strong></p>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'ayah_nama',
		'ayah_thn_lahir',
		'ayah_berkebutuhan_khusus',
		'ayah_pekerjaan',
		'ayah_pendidikan',
		'ayah_penghasilan',
	),
)); ?>

<br/><br/>


<br/><br/>
<div class="alert alert-info">
	<p><strong>DATA WALI</strong></p>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'wali_nama',
		'wali_thn_lahir',
		'wali_berkebutuhan_khusus',
		'wali_pekerjaan',
		'wali_pendidikan',
		'wali_penghasilan',
	),
)); ?>

<br/><br/>
<div class="alert alert-info">
	<p><strong>DATA PERIODIK</strong></p>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'tinggi_badan',
		'berat_badan',
		'jumlah_saudara_kandung',
	),
)); ?>

<br/><br/>

<br/><br/>
<div class="alert alert-info">
	<p><strong>DATA TAMBAHAN</strong></p>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'status_keluarga',
		'anak_ke',
		'sekolah_asal',
		'kelas_diterima',
		'tanggal_diterima',
		'alamat_ortu',
		'no_telp_ortu',
		'alamat_wali',
		'no_telp_wali',
		'pekerjaan_wali',
	),
)); ?>
<br/>
