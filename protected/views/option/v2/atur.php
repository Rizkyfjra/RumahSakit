<?php

	$guru = User::model()->findAll(array('condition'=>'role_id != 2'));

	$nama_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_name%"'));
	$kepala_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%kepsek_id%"'));
	$alamat_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%school_address%"'));
	$kurikulum_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%kurikulum%"'));
	$ulangan = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_ulangan%"'));
	$tugas = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_tugas%"'));
	$materi = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_materi%"'));
	$rekap = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_rekap%"'));
	$semester = Option::model()->findAll(array('condition'=>'key_config LIKE "%semester%"'));
	$tahun_ajaran = Option::model()->findAll(array('condition'=>'key_config LIKE "%tahun_ajaran%"'));
	$kd_sikap = Option::model()->findAll(array('condition'=>'key_config LIKE "%kd_sikap%"'));
	$tahun_url = Option::model()->findAll(array('condition'=>'key_config LIKE "%tahun_url%"'));
	$npsn = Option::model()->findAll(array('condition'=>'key_config LIKE "%npsn%"'));
	$nss = Option::model()->findAll(array('condition'=>'key_config LIKE "%nss%"'));
	$kelurahan = Option::model()->findAll(array('condition'=>'key_config LIKE "%kelurahan%"'));
	$kecamatan = Option::model()->findAll(array('condition'=>'key_config LIKE "%kecamatan%"'));
	$kota_kabupaten = Option::model()->findAll(array('condition'=>'key_config LIKE "%kota_kabupaten%"'));
	$provinsi = Option::model()->findAll(array('condition'=>'key_config LIKE "%provinsi%"'));
	$website = Option::model()->findAll(array('condition'=>'key_config LIKE "%website%"'));
	$email = Option::model()->findAll(array('condition'=>'key_config LIKE "%email%"'));
	$server = Option::model()->findAll(array('condition'=>'key_config LIKE "%server%"'));

	if (!empty($kd_sikap)) {
		$kd_sikap = $kd_sikap[0]->value;	
	}

	if (!empty($tahun_url)) {
		$tahun_url = $tahun_url[0]->value;	
	}
	$nilai_harian = Option::model()->findAll(array('condition'=>'key_config LIKE "%nilai_harian%"'));
	$nilai_uts = Option::model()->findAll(array('condition'=>'key_config LIKE "%nilai_uts%"'));
	$nilai_uas = Option::model()->findAll(array('condition'=>'key_config LIKE "%nilai_uas%"'));

	$kurtilas = '';
	$ktsp = '';
	$rekapOn = '';
	$rekapOff = '';
	$ulanganOn = '';
	$ulanganOff = '';
	$tugasOn = '';
	$tugasOff = '';
	$materiOn = '';
	$materiOff = '';
	$semesterOn = '';
	$semesterOff = '';

	if(!empty($kurikulum_sekolah[0]->value)){
		if($kurikulum_sekolah[0]->value == '2013'){
			$kurtilas = 'selected';
		}else{
			$ktsp = 'selected';
		}
	}

	if(!empty($ulangan[0]->value)){
		if($ulangan[0]->value == '1'){
			$ulanganOn = 'selected';
		}else{
			$ulanganOff = 'selected';
		}
	}

	if(!empty($tugas[0]->value)){
		if($tugas[0]->value == '1'){
			$tugasOn = 'selected';
		}else{
			$tugasOff = 'selected';
		}
	}

	if(!empty($materi[0]->value)){
		if($materi[0]->value == '1'){
			$materiOn = 'selected';
		}else{
			$materiOff = 'selected';
		}
	}

	if(!empty($rekap[0]->value)){
		if($rekap[0]->value == '1'){
			$rekapOn = 'selected';
		}else{
			$rekapOff = 'selected';
		}
	}

	if(!empty($semester[0]->value)){
		if($semester[0]->value == '1'){
			$semesterOn = 'selected';
		}else{
			$semesterOff = 'selected';
		}
	}
?>
<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_option_atur', array(

      // ));
    ?>
    <div class="col-md-12">
	  <div id="bc1" class="btn-group btn-breadcrumb">
		<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>Konfigurasi</div>',array('/option'), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>Atur Konfigurasi</div>',array('#'), array('class'=>'btn btn-success')); ?>
	  </div>
	</div>

    <div class="col-lg-12">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'option-form',
			'enableAjaxValidation'=>false,
		)); ?>
        <h3>Atur Konfigurasi
          <small class="hidden-xs">Sistem Rumkit</small>        
		</h3>
	    <div class="row">
			<div class="col-md-12">
	        	<div class="col-card">
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<div class="text-center">
									<label for="header-konfigurasi" class="control-label">Konfigurasi</label>
								</div>
							</div>
							<div class="col-md-7">
								<div class="text-center">
									<label for="header-keterangan" class="control-label">Keterangan</label>
								</div>
							</div>
						</div>
	           		</div>
	           		<hr/>
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_school_name" class="control-label">Nama Rumah Sakit</label>
							</div>
							<div class="col-md-7">
								<input type="text" class="form-control input-pn input-lg" name="school_name" value="<?php echo '',(!empty($nama_sekolah[0]->value) ? $nama_sekolah[0]->value : '') ?>">
							</div>
						</div>
	           		</div>
	           		<br/>
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_tahun_ajaran" class="control-label">NPSN</label>
							</div>
							<div class="col-md-7">
								<input type="text" class="form-control input-pn input-lg" name="npsn" value="<?php echo '',(!empty($npsn[0]->value) ? $npsn[0]->value : '') ?>">
							</div>
						</div>
	           		</div>
	           		<br/>
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_tahun_ajaran" class="control-label">NSS</label>
							</div>
							<div class="col-md-7">
								<input type="text" class="form-control input-pn input-lg" name="nss" value="<?php echo '',(!empty($nss[0]->value) ? $nss[0]->value : '') ?>">
							</div>
						</div>
	           		</div>
	           		<br/>
	           		
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_kepsek_id" class="control-label">Direktur Utama</label>
							</div>
							<div class="col-md-7">
								<select name="kepsek_id" class="selectpicker form-control" data-style="btn-default input-lg" data-live-search="true">
									<?php foreach ($guru as $value) { ?>
										<?php if(!empty($kepala_sekolah)){ ?>
											<?php if($kepala_sekolah[0]->value == $value->id){ ?>
												<option value="<?php echo $value->id;?>" selected><?php echo $value->display_name;?></option>
											<?php }else{ ?> 
												<option value="<?php echo $value->id;?>"><?php echo $value->display_name;?></option>
											<?php } ?>
										<?php }else{ ?>
											<option value="<?php echo $value->id;?>"><?php echo $value->display_name;?></option>
										<?php } ?>
									<?php } ?>
								</select>
							</div>
						</div>
	           		</div>
	           		<br/>	           		
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_school_address" class="control-label">Alamat Rumah Sakit</label>
							</div>
							<div class="col-md-7">
								<textarea name="school_address" class="form-control input-pn input-lg"><?php echo '',(!empty($alamat_sekolah[0]->value) ? $alamat_sekolah[0]->value : '') ?></textarea>
							</div>
						</div>
	           		</div>
	           		<br/>	 
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_tahun_ajaran" class="control-label">Kelurahan</label>
							</div>
							<div class="col-md-7">
								<input type="text" class="form-control input-pn input-lg" name="kelurahan" value="<?php echo '',(!empty($kelurahan[0]->value) ? $kelurahan[0]->value : '') ?>">
							</div>
						</div>
	           		</div>
	           		<br/> 
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_tahun_ajaran" class="control-label">Kecamatan</label>
							</div>
							<div class="col-md-7">
								<input type="text" class="form-control input-pn input-lg" name="kecamatan" value="<?php echo '',(!empty($kecamatan[0]->value) ? $kecamatan[0]->value : '') ?>">
							</div>
						</div>
	           		</div>
	           		<br/> 
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_tahun_ajaran" class="control-label">Kota/Kabupaten</label>
							</div>
							<div class="col-md-7">
								<input type="text" class="form-control input-pn input-lg" name="kota_kabupaten" value="<?php echo '',(!empty($kota_kabupaten[0]->value) ? $kota_kabupaten[0]->value : '') ?>">
							</div>
						</div>
	           		</div>
	           		<br/> 
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_tahun_ajaran" class="control-label">Provinsi</label>
							</div>
							<div class="col-md-7">
								<input type="text" class="form-control input-pn input-lg" name="provinsi" value="<?php echo '',(!empty($provinsi[0]->value) ? $provinsi[0]->value : '') ?>">
							</div>
						</div>
	           		</div>
	           		<br/> 
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_tahun_ajaran" class="control-label">Website</label>
							</div>
							<div class="col-md-7">
								<input type="text" class="form-control input-pn input-lg" name="website" value="<?php echo '',(!empty($website[0]->value) ? $website[0]->value : '') ?>">
							</div>
						</div>
	           		</div>
	           		<br/> 
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_tahun_ajaran" class="control-label">Email</label>
							</div>
							<div class="col-md-7">
								<input type="text" class="form-control input-pn input-lg" name="email" value="<?php echo '',(!empty($email[0]->value) ? $email[0]->value : '') ?>">
							</div>
						</div>
	           		</div>
	           		<br/> 
                    <br />
                    <div class="row">
                        <div class="form-group">
                            <div class="col-md-5">
                                <label for="Option_server_host" class="control-label">Server Host</label>
                            </div>
                            <div class="col-md-7">
                                <input type="text" class="form-control input-pn input-lg" name="server" value="<?php echo '',(!empty($server[0]->value) ? $server[0]->value : '') ?>">
                            </div>
                        </div>
                    </div>
	           		
				           		<div class="row">
									<div class="form-group">
										<div class="col-md-5">
											<input type="text" class="form-control input-pn input-lg" name="kd_sikap[name][]" value="<?php echo $value->name; ?>">
										</div>
										<div class="col-md-7">
											<textarea name="kd_sikap[indikator][]" class="form-control input-pn input-lg"><?php echo(implode(",", $value->indikator)); ?></textarea>
										</div>
									</div>
				           		</div>
			           	
	           		</div>
	           		<br/>
	           		<script type="text/javascript">
					$("#tambah").click(function(){
						$('#initial').append(' </br> <div class="row"><div class="form-group"><div class="col-md-5"><input type="text" class="form-control input-pn input-lg" name="kd_sikap[name][]" ></div><div class="col-md-7"><textarea name="kd_sikap[indikator][]" class="form-control input-pn input-lg"></textarea></div></div></div>');
						return false;
					});
					</script>




						</div>
	           		</div>
					<hr/>
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-12">
								<?php echo CHtml::submitButton('Simpan Konfigurasi',array('class'=>'btn btn-pn-primary btn-lg btn-pn-round btn-block next-step')); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php $this->endWidget(); ?>
    </div>
  </div>
</div>							
<script>
	$("#persen").hide();
	
	$('#kur').on('change', function() {
	  if( this.value == "2006"){
	  	$("#persen").show();
	  } else {
	  	$("#persen").hide();
	  }
	});
</script>