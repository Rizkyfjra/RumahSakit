<?php
/* @var $this UserProfileController */
/* @var $data UserProfile */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('user_id')); ?>:</b>
	<?php echo CHtml::encode($data->user_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('nisn')); ?>:</b>
	<?php echo CHtml::encode($data->nisn); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('j_kelamin')); ?>:</b>
	<?php echo CHtml::encode($data->j_kelamin); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('no_seri_ijazah_smp')); ?>:</b>
	<?php echo CHtml::encode($data->no_seri_ijazah_smp); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('no_seri_skhun')); ?>:</b>
	<?php echo CHtml::encode($data->no_seri_skhun); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('no_un_smp')); ?>:</b>
	<?php echo CHtml::encode($data->no_un_smp); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('nik')); ?>:</b>
	<?php echo CHtml::encode($data->nik); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tempat_lahir')); ?>:</b>
	<?php echo CHtml::encode($data->tempat_lahir); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tgl_lahir')); ?>:</b>
	<?php echo CHtml::encode($data->tgl_lahir); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('agama')); ?>:</b>
	<?php echo CHtml::encode($data->agama); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('berkebutuhan_khusus')); ?>:</b>
	<?php echo CHtml::encode($data->berkebutuhan_khusus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alamat_tinggal')); ?>:</b>
	<?php echo CHtml::encode($data->alamat_tinggal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alamat_dusun')); ?>:</b>
	<?php echo CHtml::encode($data->alamat_dusun); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alamat_rt')); ?>:</b>
	<?php echo CHtml::encode($data->alamat_rt); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alamat_rw')); ?>:</b>
	<?php echo CHtml::encode($data->alamat_rw); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alamat_kelurahan')); ?>:</b>
	<?php echo CHtml::encode($data->alamat_kelurahan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alamat_kodepos')); ?>:</b>
	<?php echo CHtml::encode($data->alamat_kodepos); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alamat_kecamatan')); ?>:</b>
	<?php echo CHtml::encode($data->alamat_kecamatan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alamat_kota')); ?>:</b>
	<?php echo CHtml::encode($data->alamat_kota); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alamat_provinsi')); ?>:</b>
	<?php echo CHtml::encode($data->alamat_provinsi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('alat_transportasi')); ?>:</b>
	<?php echo CHtml::encode($data->alat_transportasi); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('jenis_tinggal')); ?>:</b>
	<?php echo CHtml::encode($data->jenis_tinggal); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('no_telpon')); ?>:</b>
	<?php echo CHtml::encode($data->no_telpon); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('email')); ?>:</b>
	<?php echo CHtml::encode($data->email); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('penerima_kps')); ?>:</b>
	<?php echo CHtml::encode($data->penerima_kps); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('no_kps')); ?>:</b>
	<?php echo CHtml::encode($data->no_kps); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ayah_nama')); ?>:</b>
	<?php echo CHtml::encode($data->ayah_nama); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ayah_thn_lahir')); ?>:</b>
	<?php echo CHtml::encode($data->ayah_thn_lahir); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ayah_berkebutuhan_khusus')); ?>:</b>
	<?php echo CHtml::encode($data->ayah_berkebutuhan_khusus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ayah_pekerjaan')); ?>:</b>
	<?php echo CHtml::encode($data->ayah_pekerjaan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ayah_pendidikan')); ?>:</b>
	<?php echo CHtml::encode($data->ayah_pendidikan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ayah_penghasilan')); ?>:</b>
	<?php echo CHtml::encode($data->ayah_penghasilan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ibu_nama')); ?>:</b>
	<?php echo CHtml::encode($data->ibu_nama); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ibu_thn_lahir')); ?>:</b>
	<?php echo CHtml::encode($data->ibu_thn_lahir); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ibu_berkebutuhan_khusus')); ?>:</b>
	<?php echo CHtml::encode($data->ibu_berkebutuhan_khusus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ibu_pekerjaan')); ?>:</b>
	<?php echo CHtml::encode($data->ibu_pekerjaan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ibu_pendidikan')); ?>:</b>
	<?php echo CHtml::encode($data->ibu_pendidikan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('ibu_penghasilan')); ?>:</b>
	<?php echo CHtml::encode($data->ibu_penghasilan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wali_nama')); ?>:</b>
	<?php echo CHtml::encode($data->wali_nama); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wali_thn_lahir')); ?>:</b>
	<?php echo CHtml::encode($data->wali_thn_lahir); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wali_berkebutuhan_khusus')); ?>:</b>
	<?php echo CHtml::encode($data->wali_berkebutuhan_khusus); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wali_pekerjaan')); ?>:</b>
	<?php echo CHtml::encode($data->wali_pekerjaan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wali_pendidikan')); ?>:</b>
	<?php echo CHtml::encode($data->wali_pendidikan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('wali_penghasilan')); ?>:</b>
	<?php echo CHtml::encode($data->wali_penghasilan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('tinggi_badan')); ?>:</b>
	<?php echo CHtml::encode($data->tinggi_badan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('berat_badan')); ?>:</b>
	<?php echo CHtml::encode($data->berat_badan); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('jarak_tempat_tgl_ke_sekolah')); ?>:</b>
	<?php echo CHtml::encode($data->jarak_tempat_tgl_ke_sekolah); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('waktu_tempuh_ke_sekolah')); ?>:</b>
	<?php echo CHtml::encode($data->waktu_tempuh_ke_sekolah); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('jumlah_saudara_kandung')); ?>:</b>
	<?php echo CHtml::encode($data->jumlah_saudara_kandung); ?>
	<br />

	*/ ?>

</div>