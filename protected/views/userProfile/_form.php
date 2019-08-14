<?php
/* @var $this UserProfileController */
/* @var $model UserProfile */
/* @var $form CActiveForm */
	$year_now = date("Y");
	$year_below = $year_now - 5;

	$list_j_kelamin = array('Laki-Laki' => "Laki-laki", 'Perempuan' => "Perempuan");
	$list_agama = array('Islam' => "Islam", 'Katholik' => "Katholik" , 'Protestan' => "Protestan" , 'Hindu' => "Hindu" , 'Buddha' => "Buddha" , 'Konghucu' => "Konghucu" , 'Lainnya' => "Lainnya" );
	$list_jenis_tinggal = array('Menetap Bersama Orang Tua' => "Menetap Bersama Orang Tua", 'Menetap Bersama Wali' => "Menetap Bersama Wali", 'Mengontrak Bersama Orang Tua' => "Mengontrak Bersama Orang Tua", 'Mengontrak Bersama Wali' => "Mengontrak Bersama Wali", 'Mengontrak Sendiri' => "Mengontrak Sendiri", 'Menumpang Di Rumah Saudara' => "Menumpang Di Rumah Saudara");
	$list_penerima_kps = array('Ya' => "Ya", 'Tidak' => "Tidak");
	$list_penerima_kip = array('Ya' => "Ya", 'Tidak' => "Tidak");
	$list_penerima_kks = array('Ya' => "Ya", 'Tidak' => "Tidak");
	$list_penerima_kis = array('Ya' => "Ya", 'Tidak' => "Tidak");
	$list_tahun_lahir = array_combine(range(1940,$year_below),range(1940,$year_below));
	$list_pendidikan = array('SD/MI/Sederajat' => "SD/MI/Sederajat", 'SMP/MTs/Sederajat' => "SMP/MTs/Sederajat", 'SMA/SMK/Sederajat' => "SMA/SMK/Sederajat", 'DIPLOMA 1 (D1)' => "DIPLOMA 1 (D1)", 'DIPLOMA 2 (D2)' => "DIPLOMA 2 (D2)", 'DIPLOMA 3 (D3)' => "DIPLOMA 3 (D3)", 'SARJANA (S1)' => "SARJANA (S1)", 'MAGISTER (S2)' => "MAGISTER (S2)", 'DOKTOR (S3)' => "DOKTOR (S3)");
	$list_tahun_prestasi = array_combine(range(1990,$year_now),range(1990,$year_now));
	$list_status_keluarga = array('Anak Kandung' => "Anak Kandung", 'Anak Angkat' => "Anak Angkat");
	if(!$model->isNewRecord){
		if(!empty($model->peminatan)){
			$peminatan = $model->peminatan;
		} else {
			$peminatan = NULL;
		}

		if(!empty($model->lintas_minat_01)){
			$lintas_minat_01 = $model->lintas_minat_01;
		} else {
			$lintas_minat_01 = NULL;
		}

		if(!empty($model->ekskul_01)){
			$ekskul_01 = $model->ekskul_01;
		} else {
			$ekskul_01 = NULL;
		}

		if(!empty($model->lintas_minat_02)){
			$lintas_minat_02 = $model->lintas_minat_02;
		} else {
			$lintas_minat_02 = NULL;
		}

		if(!empty($model->ekskul_02)){
			$ekskul_02 = $model->ekskul_02;
		} else {
			$ekskul_02 = NULL;
		}
	}else{
		$peminatan = NULL;
		$lintas_minat_01 = NULL;
		$lintas_minat_02 = NULL;
	}

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-profile-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Kolom dengan tanda <span class="required">*</span> wajib di isi.</p>

	<?php echo $form->errorSummary($model); ?>

	<br/><br/>
	<div class="form-group">
		<div class="alert alert-info">
			<p><strong>IDENTITAS PASIEN</strong></p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'j_kelamin'); ?>
				<?php echo $form->dropDownList($model,'j_kelamin',$list_j_kelamin,array('empty' => '-- Pilih Jenis Kelamin --','class'=>'form-control')); ?>
				<?php echo $form->error($model,'j_kelamin'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<?php echo $form->labelEx($model,'nisn'); ?>
				<?php echo $form->textField($model,'nisn',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'nisn'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'no_seri_ijazah'); ?>
				<?php echo $form->textField($model,'no_seri_ijazah',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'no_seri_ijazah'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'no_seri_skhun'); ?>
				<?php echo $form->textField($model,'no_seri_skhun',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'no_seri_skhun'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'no_un'); ?>
				<?php echo $form->textField($model,'no_un',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'no_un'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'nik'); ?>
				<?php echo $form->textField($model,'nik',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'nik'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-5">
			<div class="form-group">
				<?php echo $form->labelEx($model,'tempat_lahir'); ?>
				<?php echo $form->textField($model,'tempat_lahir',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'tempat_lahir'); ?>
			</div>
		</div>
		<div class="col-sm-3">
			<div class="form-group">
				<?php echo $form->labelEx($model,'tgl_lahir'); ?>
				<?php
					$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						    'model'=>$model,
						    'attribute' => 'tgl_lahir',
					    	// additional javascript options for the date picker plugin
					    	'options'=>array(
					        'showAnim'=>'slide',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
					        'dateFormat'=>'yy-mm-dd',//Date format 'mm/dd/yy','yy-mm-dd','d M, y','d MM, 
					        'changeMonth'=>true,
					        'changeYear'=>true,
					        'yearRange'=>'1900:2099'
					        ),
				    	'htmlOptions'=>array(
				        'class'=>'form-control',
				    	),
					));
				?>
				<?php echo $form->error($model,'tgl_lahir'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'agama'); ?>
				<?php echo $form->dropDownList($model,'agama',$list_agama,array('empty' => '-- Pilih Agama --','class'=>'form-control')); ?>
				<?php echo $form->error($model,'agama'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'berkebutuhan_khusus'); ?>
				<?php echo $form->textField($model,'berkebutuhan_khusus',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'berkebutuhan_khusus'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_tinggal'); ?>
				<?php echo $form->textArea($model,'alamat_tinggal',array('cols'=>60,'rows'=>6,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_tinggal'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_dusun'); ?>
				<?php echo $form->textField($model,'alamat_dusun',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_dusun'); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_rt'); ?>
				<?php echo $form->textField($model,'alamat_rt',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_rt'); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_rw'); ?>
				<?php echo $form->textField($model,'alamat_rw',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_rw'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_kelurahan'); ?>
				<?php echo $form->textField($model,'alamat_kelurahan',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_kelurahan'); ?>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_kodepos'); ?>
				<?php echo $form->textField($model,'alamat_kodepos',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_kodepos'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_kecamatan'); ?>
				<?php echo $form->textField($model,'alamat_kecamatan',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_kecamatan'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_kota'); ?>
				<?php echo $form->textField($model,'alamat_kota',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_kota'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_provinsi'); ?>
				<?php echo $form->textField($model,'alamat_provinsi',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_provinsi'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'alat_transportasi'); ?>
				<?php echo $form->textField($model,'alat_transportasi',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alat_transportasi'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<?php echo $form->labelEx($model,'jenis_tinggal'); ?>
				<?php echo $form->dropDownList($model,'jenis_tinggal',$list_jenis_tinggal,array('empty' => '-- Pilih Jenis Tinggal --','class'=>'form-control')); ?>
				<?php echo $form->error($model,'jenis_tinggal'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-5">
			<div class="form-group">
				<?php echo $form->labelEx($model,'no_telpon'); ?>
				<?php echo $form->textField($model,'no_telpon',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'no_telpon'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-5">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-3">
			<div class="form-group">
				<?php echo $form->labelEx($model,'penerima_kps'); ?>
				<?php echo $form->dropDownList($model,'penerima_kps',$list_penerima_kps,array('empty' => '-- Pilih --','class'=>'form-control')); ?>				
				<?php echo $form->error($model,'penerima_kps'); ?>
			</div>
		</div>
		<div class="col-sm-5">
			<div class="form-group">
				<?php echo $form->labelEx($model,'no_kps'); ?>
				<?php echo $form->textField($model,'no_kps',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'no_kps'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-3">
			<div class="form-group">
				<?php echo $form->labelEx($model,'penerima_kip'); ?>
				<?php echo $form->dropDownList($model,'penerima_kip',$list_penerima_kip,array('empty' => '-- Pilih --','class'=>'form-control')); ?>				
				<?php echo $form->error($model,'penerima_kip'); ?>
			</div>
		</div>
		<div class="col-sm-5">
			<div class="form-group">
				<?php echo $form->labelEx($model,'no_kip'); ?>
				<?php echo $form->textField($model,'no_kip',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'no_kip'); ?>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-sm-3">
			<div class="form-group">
				<?php echo $form->labelEx($model,'penerima_kks'); ?>
				<?php echo $form->dropDownList($model,'penerima_kks',$list_penerima_kks,array('empty' => '-- Pilih --','class'=>'form-control')); ?>				
				<?php echo $form->error($model,'penerima_kks'); ?>
			</div>
		</div>
		<div class="col-sm-5">
			<div class="form-group">
				<?php echo $form->labelEx($model,'no_kks'); ?>
				<?php echo $form->textField($model,'no_kks',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'no_kks'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-3">
			<div class="form-group">
				<?php echo $form->labelEx($model,'penerima_kis'); ?>
				<?php echo $form->dropDownList($model,'penerima_kis',$list_penerima_kis,array('empty' => '-- Pilih --','class'=>'form-control')); ?>				
				<?php echo $form->error($model,'penerima_kis'); ?>
			</div>
		</div>
		<div class="col-sm-5">
			<div class="form-group">
				<?php echo $form->labelEx($model,'no_kis'); ?>
				<?php echo $form->textField($model,'no_kis',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'no_kis'); ?>
			</div>
		</div>
	</div>



	<br/><br/>
	<div class="form-group">
		<div class="alert alert-info">
			<p><strong>DATA AYAH KANDUNG (WAJIB DI ISI)</strong></p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<?php echo $form->labelEx($model,'ayah_nama'); ?>
				<?php echo $form->textField($model,'ayah_nama',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'ayah_nama'); ?>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<?php echo $form->labelEx($model,'ayah_thn_lahir'); ?>
				<?php echo $form->dropDownList($model,'ayah_thn_lahir',$list_tahun_lahir,array('empty' => '-- Pilih Tahun --','class'=>'form-control')); ?>
				<?php echo $form->error($model,'ayah_thn_lahir'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'ayah_berkebutuhan_khusus'); ?>
				<?php echo $form->textField($model,'ayah_berkebutuhan_khusus',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'ayah_berkebutuhan_khusus'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'ayah_pekerjaan'); ?>
				<?php echo $form->textField($model,'ayah_pekerjaan',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'ayah_pekerjaan'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'ayah_pendidikan'); ?>
				<?php echo $form->dropDownList($model,'ayah_pendidikan',$list_pendidikan,array('empty' => '-- Pilih Jenjang Pendidikan --','class'=>'form-control')); ?>				
				<?php echo $form->error($model,'ayah_pendidikan'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'ayah_penghasilan'); ?>
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>				
					<?php echo $form->textField($model,'ayah_penghasilan',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				</div>
				<p>*) Isi tanpa menggunakan titik ataupun koma</p>
				<?php echo $form->error($model,'ayah_penghasilan'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_ayah_tinggal'); ?>
				<?php echo $form->textArea($model,'alamat_ayah_tinggal',array('cols'=>60,'rows'=>6,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_ayah_tinggal'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_ayah_dusun'); ?>
				<?php echo $form->textField($model,'alamat_ayah_dusun',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_ayah_dusun'); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_ayah_rt'); ?>
				<?php echo $form->textField($model,'alamat_ayah_rt',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_ayah_rt'); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_ayah_rw'); ?>
				<?php echo $form->textField($model,'alamat_ayah_rw',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_ayah_rw'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_ayah_kelurahan'); ?>
				<?php echo $form->textField($model,'alamat_ayah_kelurahan',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_ayah_kelurahan'); ?>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_ayah_kodepos'); ?>
				<?php echo $form->textField($model,'alamat_ayah_kodepos',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_ayah_kodepos'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_ayah_kecamatan'); ?>
				<?php echo $form->textField($model,'alamat_ayah_kecamatan',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_ayah_kecamatan'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_ayah_kota'); ?>
				<?php echo $form->textField($model,'alamat_ayah_kota',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_ayah_kota'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_ayah_provinsi'); ?>
				<?php echo $form->textField($model,'alamat_ayah_provinsi',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_ayah_provinsi'); ?>
			</div>
		</div>
	</div>

	<br/><br/>
	<div class="form-group">
		<div class="alert alert-info">
			<p><strong>DATA WALI</strong></p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<?php echo $form->labelEx($model,'wali_nama'); ?>
				<?php echo $form->textField($model,'wali_nama',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'wali_nama'); ?>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<?php echo $form->labelEx($model,'wali_thn_lahir'); ?>
				<?php echo $form->dropDownList($model,'wali_thn_lahir',$list_tahun_lahir,array('empty' => '-- Pilih Tahun --','class'=>'form-control')); ?>
				<?php echo $form->error($model,'wali_thn_lahir'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'wali_berkebutuhan_khusus'); ?>
				<?php echo $form->textField($model,'wali_berkebutuhan_khusus',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'wali_berkebutuhan_khusus'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'wali_pekerjaan'); ?>
				<?php echo $form->textField($model,'wali_pekerjaan',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'wali_pekerjaan'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'wali_pendidikan'); ?>
				<?php echo $form->dropDownList($model,'wali_pendidikan',$list_pendidikan,array('empty' => '-- Pilih Jenjang Pendidikan --','class'=>'form-control')); ?>
				<?php echo $form->error($model,'wali_pendidikan'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'wali_penghasilan'); ?>
				<div class="input-group">
					<span class="input-group-addon">Rp. </span>
					<?php echo $form->textField($model,'wali_penghasilan',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				</div>
				<p>*) Isi tanpa menggunakan titik ataupun koma</p>
				<?php echo $form->error($model,'wali_penghasilan'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_wali_tinggal'); ?>
				<?php echo $form->textArea($model,'alamat_wali_tinggal',array('cols'=>60,'rows'=>6,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_wali_tinggal'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_wali_dusun'); ?>
				<?php echo $form->textField($model,'alamat_wali_dusun',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_wali_dusun'); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_wali_rt'); ?>
				<?php echo $form->textField($model,'alamat_wali_rt',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_wali_rt'); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_wali_rw'); ?>
				<?php echo $form->textField($model,'alamat_wali_rw',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_wali_rw'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-6">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_wali_kelurahan'); ?>
				<?php echo $form->textField($model,'alamat_wali_kelurahan',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_wali_kelurahan'); ?>
			</div>
		</div>
		<div class="col-sm-2">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_wali_kodepos'); ?>
				<?php echo $form->textField($model,'alamat_wali_kodepos',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_wali_kodepos'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_wali_kecamatan'); ?>
				<?php echo $form->textField($model,'alamat_wali_kecamatan',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_wali_kecamatan'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_wali_kota'); ?>
				<?php echo $form->textField($model,'alamat_wali_kota',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_wali_kota'); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-8">
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_wali_provinsi'); ?>
				<?php echo $form->textField($model,'alamat_wali_provinsi',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_wali_provinsi'); ?>
			</div>
		</div>
	</div>

	<br/><br/>
	<div class="form-group">
		<div class="alert alert-info">
			<p><strong>DATA PERIODIK (WAJIB DI ISI)</strong></p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->labelEx($model,'tinggi_badan'); ?>
				<div class="input-group">
					<?php echo $form->textField($model,'tinggi_badan',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
					<span class="input-group-addon">Cm</span>				
				</div>
				<p>*) Isi tanpa menggunakan titik ataupun koma</p>
				<?php echo $form->error($model,'tinggi_badan'); ?>
			</div>
		</div>
		<div class="col-sm-1">
			&nbsp;
		</div>
		<div class="col-sm-4">
			<div class="form-group">
				<?php echo $form->labelEx($model,'berat_badan'); ?>
				<div class="input-group">
					<?php echo $form->textField($model,'berat_badan',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
					<span class="input-group-addon">Kg</span>
				</div>
				<p>*) Isi tanpa menggunakan titik ataupun koma</p>
				<?php echo $form->error($model,'berat_badan'); ?>
			</div>
		</div>
	</div>


	<div class="row">
		<div class="col-sm-4">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'jumlah_saudara_kandung'); ?>
				<?php echo $form->textField($model,'jumlah_saudara_kandung',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'jumlah_saudara_kandung'); ?>
			</div>
		</div>
	</div>

	<br/><br/>


	<br/><br/>
	

	<br/><br/>
	<div class="form-group">
		<div class="alert alert-info">
			<p><strong>DATA TAMBAHAN</strong></p>
		</div>
	</div>

		<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<label>Status Keluarga</label>
				<?php echo $form->dropDownList($model,'status_keluarga',$list_status_keluarga,array('empty' => '-- Status Keluarga --','class'=>'form-control')); ?>
			</div>
		</div>
		</div>

		<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'anak_ke'); ?>
				<?php echo $form->textField($model,'anak_ke',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'anak_ke'); ?>
			</div>
		</div>
		</div>

		<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'sekolah_asal'); ?>
				<?php echo $form->textField($model,'sekolah_asal',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'sekolah_asal'); ?>
			</div>
		</div>
		</div>

		<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'kelas_diterima'); ?>
				<?php echo $form->textField($model,'kelas_diterima',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'kelas_diterima'); ?>
			</div>
		</div>
		</div>

		<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'tanggal_diterima'); ?>
				<?php
					$this->widget('zii.widgets.jui.CJuiDatePicker',array(
						    'model'=>$model,
						    'attribute' => 'tanggal_diterima',
					    	// additional javascript options for the date picker plugin
					    	'options'=>array(
					        'showAnim'=>'slide',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
					        'dateFormat'=>'yy-mm-dd',//Date format 'mm/dd/yy','yy-mm-dd','d M, y','d MM, 
					        'changeMonth'=>true,
					        'changeYear'=>true,
					        'yearRange'=>'1900:2099'
					        ),
				    	'htmlOptions'=>array(
				        'class'=>'form-control',
				    	),
					));
				?>
				<?php echo $form->error($model,'tanggal_diterima'); ?>
			</div>
		</div>
		</div>


		<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_`'); ?>
				<?php echo $form->textField($model,'alamat_ortu',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_ortu'); ?>
			</div>
		</div>
		</div>


		<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'no_telp_ortu'); ?>
				<?php echo $form->textField($model,'no_telp_ortu',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'no_telp_ortu'); ?>
			</div>
		</div>
		</div>


		<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'alamat_wali'); ?>
				<?php echo $form->textField($model,'alamat_wali',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'alamat_wali'); ?>
			</div>
		</div>
		</div>



		<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'no_telp_wali'); ?>
				<?php echo $form->textField($model,'no_telp_wali',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'no_telp_wali'); ?>
			</div>
		</div>
		</div>


		<div class="row">
		<div class="col-sm-8">	
			<div class="form-group">
				<?php echo $form->labelEx($model,'pekerjaan_wali'); ?>
				<?php echo $form->textField($model,'pekerjaan_wali',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
				<?php echo $form->error($model,'pekerjaan_wali'); ?>
			</div>
		</div>
		</div>

	

	<br/>
	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Perbaharui',array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->