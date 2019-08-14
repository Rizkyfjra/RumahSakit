<?php
/* @var $this UserProfileController */
/* @var $model UserProfile */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nisn'); ?>
		<?php echo $form->textField($model,'nisn',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'j_kelamin'); ?>
		<?php echo $form->textField($model,'j_kelamin',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'no_seri_ijazah_smp'); ?>
		<?php echo $form->textField($model,'no_seri_ijazah_smp',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'no_seri_skhun'); ?>
		<?php echo $form->textField($model,'no_seri_skhun',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'no_un_smp'); ?>
		<?php echo $form->textField($model,'no_un_smp',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'nik'); ?>
		<?php echo $form->textField($model,'nik',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tempat_lahir'); ?>
		<?php echo $form->textField($model,'tempat_lahir',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tgl_lahir'); ?>
		<?php echo $form->textField($model,'tgl_lahir'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'agama'); ?>
		<?php echo $form->textField($model,'agama',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'berkebutuhan_khusus'); ?>
		<?php echo $form->textField($model,'berkebutuhan_khusus',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alamat_tinggal'); ?>
		<?php echo $form->textField($model,'alamat_tinggal',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alamat_dusun'); ?>
		<?php echo $form->textField($model,'alamat_dusun',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alamat_rt'); ?>
		<?php echo $form->textField($model,'alamat_rt',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alamat_rw'); ?>
		<?php echo $form->textField($model,'alamat_rw',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alamat_kelurahan'); ?>
		<?php echo $form->textField($model,'alamat_kelurahan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alamat_kodepos'); ?>
		<?php echo $form->textField($model,'alamat_kodepos',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alamat_kecamatan'); ?>
		<?php echo $form->textField($model,'alamat_kecamatan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alamat_kota'); ?>
		<?php echo $form->textField($model,'alamat_kota',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alamat_provinsi'); ?>
		<?php echo $form->textField($model,'alamat_provinsi',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'alat_transportasi'); ?>
		<?php echo $form->textField($model,'alat_transportasi',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jenis_tinggal'); ?>
		<?php echo $form->textField($model,'jenis_tinggal',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'no_telpon'); ?>
		<?php echo $form->textField($model,'no_telpon',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'penerima_kps'); ?>
		<?php echo $form->textField($model,'penerima_kps',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'no_kps'); ?>
		<?php echo $form->textField($model,'no_kps',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ayah_nama'); ?>
		<?php echo $form->textField($model,'ayah_nama',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ayah_thn_lahir'); ?>
		<?php echo $form->textField($model,'ayah_thn_lahir',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ayah_berkebutuhan_khusus'); ?>
		<?php echo $form->textField($model,'ayah_berkebutuhan_khusus',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ayah_pekerjaan'); ?>
		<?php echo $form->textField($model,'ayah_pekerjaan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ayah_pendidikan'); ?>
		<?php echo $form->textField($model,'ayah_pendidikan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ayah_penghasilan'); ?>
		<?php echo $form->textField($model,'ayah_penghasilan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ibu_nama'); ?>
		<?php echo $form->textField($model,'ibu_nama',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ibu_thn_lahir'); ?>
		<?php echo $form->textField($model,'ibu_thn_lahir',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ibu_berkebutuhan_khusus'); ?>
		<?php echo $form->textField($model,'ibu_berkebutuhan_khusus',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ibu_pekerjaan'); ?>
		<?php echo $form->textField($model,'ibu_pekerjaan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ibu_pendidikan'); ?>
		<?php echo $form->textField($model,'ibu_pendidikan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'ibu_penghasilan'); ?>
		<?php echo $form->textField($model,'ibu_penghasilan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wali_nama'); ?>
		<?php echo $form->textField($model,'wali_nama',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wali_thn_lahir'); ?>
		<?php echo $form->textField($model,'wali_thn_lahir',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wali_berkebutuhan_khusus'); ?>
		<?php echo $form->textField($model,'wali_berkebutuhan_khusus',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wali_pekerjaan'); ?>
		<?php echo $form->textField($model,'wali_pekerjaan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wali_pendidikan'); ?>
		<?php echo $form->textField($model,'wali_pendidikan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wali_penghasilan'); ?>
		<?php echo $form->textField($model,'wali_penghasilan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tinggi_badan'); ?>
		<?php echo $form->textField($model,'tinggi_badan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'berat_badan'); ?>
		<?php echo $form->textField($model,'berat_badan',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jarak_tempat_tgl_ke_sekolah'); ?>
		<?php echo $form->textField($model,'jarak_tempat_tgl_ke_sekolah',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'waktu_tempuh_ke_sekolah'); ?>
		<?php echo $form->textField($model,'waktu_tempuh_ke_sekolah',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jumlah_saudara_kandung'); ?>
		<?php echo $form->textField($model,'jumlah_saudara_kandung',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->