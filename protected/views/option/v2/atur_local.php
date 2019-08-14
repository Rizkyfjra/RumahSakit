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

	if(Yii::app()->session['semester']){
			if(Yii::app()->session['semester'] == '1'){
				$semesterOn = 'selected';
			}else{
				$semesterOff = 'selected';
			}
			
	} else {
		if(!empty($semester[0]->value)){
			if($semester[0]->value == '1'){
				$semesterOn = 'selected';
			}else{
				$semesterOff = 'selected';
			}
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
        <h3>Atur Konfigurasi Lokal
          <small class="hidden-xs">Edubox</small>        
		</h3>
	    <div class="row">
			<div class="col-md-12">
	        	<div class="col-card">           		
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_semester" class="control-label">Semester</label>
							</div>
							<div class="col-md-7">
								<select name="semester" class="form-control input-pn input-lg">
									<option value="1" <?php echo $semesterOn;?> >1</option>
									<option value="2" <?php echo $semesterOff;?> >2</option>
								</select>
							</div>
						</div>
	           		</div>
	           		<br/>	           		
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_tahun_ajaran" class="control-label">Tahun Ajaran</label>
							</div>
							<div class="col-md-7">
								<?php if(Yii::app()->session['semester']){?>
											
											<select name="tahun_ajaran" class="form-control input-pn input-lg">
												<option value="2016" <?php echo (Yii::app()->session['tahun_ajaran'] == 2016 ?  'selected' : '');?> >2015-2016</option>
												<option value="2017" <?php echo (Yii::app()->session['tahun_ajaran'] == 2017 ?  'selected' : '');?> >2016-2017</option>
												<option value="2018" <?php echo (Yii::app()->session['tahun_ajaran'] == 2018 ?  'selected' : '');?> >2017-2018</option>
												<option value="2019" <?php echo (Yii::app()->session['tahun_ajaran'] == 2019 ?  'selected' : '');?> >2018-2019</option>
												<option value="2020" <?php echo (Yii::app()->session['tahun_ajaran'] == 2020 ?  'selected' : '');?> >2019-2020</option>
											</select>

								<?php } else { ?>

											<?php if (!empty($tahun_ajaran[0]->value)){ ?>
											<select name="tahun_ajaran" class="form-control input-pn input-lg">
												<option value="2016" <?php echo ($tahun_ajaran[0]->value == 2016 ?  'selected' : '');?> >2015-2016</option>
												<option value="2017" <?php echo ($tahun_ajaran[0]->value == 2017 ?  'selected' : '');?> >2016-2017</option>
												<option value="2018" <?php echo ($tahun_ajaran[0]->value == 2018 ?  'selected' : '');?> >2017-2018</option>
												<option value="2019" <?php echo ($tahun_ajaran[0]->value == 2019 ?  'selected' : '');?> >2018-2019</option>
												<option value="2020" <?php echo ($tahun_ajaran[0]->value == 2020 ?  'selected' : '');?> >2019-2020</option>
											</select>
											<?php }else{ ?> 
											<select name="tahun_ajaran" class="form-control input-pn input-lg">
												<option value="2016" 'selected'>2015-2016</option>
												<option value="2017" >2016-2017</option>
												<option value="2018" >2017-2018</option>
												<option value="2019" >2018-2019</option>
												<option value="2020" >2019-2020</option>
											</select>
											<?php } ?>
								<?php } ?>
							</div>
						</div>
	           		</div>
	           		<br/>	           		
	           		<br/>	           		
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_school_address" class="control-label">Titimangsa Rapor</label>
							</div>
							<div class="col-md-7">
								<?php if(Yii::app()->session['titimangsa']){?>
									<input value="<?php echo Yii::app()->session['titimangsa']; ?>" type="text" name="titimangsa" class="form-control input-pn input-lg"/>
								<?php } else { ?>
									<input type="text" name="titimangsa" class="form-control input-pn input-lg"/>
								<?php } ?>
							</div>
						</div>
	           		</div>

	           		<br/>	           		
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_school_address" class="control-label">Wali Kelas Rapor</label>
							</div>
							<div class="col-md-7">
								<?php if(Yii::app()->session['walikelas']){?>
									<input value="<?php echo Yii::app()->session['walikelas']; ?>" type="text" name="walikelas" class="form-control input-pn input-lg"/>
								<?php } else { ?>
									<input type="text" name="walikelas" class="form-control input-pn input-lg"/>
								<?php } ?>
							</div>
						</div>
	           		</div>

	           		<br/>	           		
	           		<div class="row">
						<div class="form-group">
							<div class="col-md-5">
								<label for="Option_school_address" class="control-label">NIP Kelas Rapor</label>
							</div>
							<div class="col-md-7">
								<?php if(Yii::app()->session['nipwali']){?>
									<input value="<?php echo Yii::app()->session['nipwali']; ?>" type="text" name="nipwali" class="form-control input-pn input-lg"/>
								<?php } else { ?>
									<input type="text" name="nipwali" class="form-control input-pn input-lg"/>
								<?php } ?>
							</div>
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