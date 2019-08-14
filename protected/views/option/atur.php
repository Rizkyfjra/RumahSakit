<?php
/* @var $this OptionController */
/* @var $model Option */
/* @var $form CActiveForm */
$this->breadcrumbs=array(
	'Option'=>array('index'),
	'Atur',
);

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

if(!empty($semester[0]->value)){
	if($semester[0]->value == '1'){
		$semesterOn = 'selected';
	}else{
		$semesterOff = 'selected';
	}
}

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'option-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>
	<table class="table table-bordered well">
		<tr>
			<th class="danger">NO</th>
			<th class="danger">KONFIGURASI</th>
			<th class="danger">KETERANGAN</th>
		</tr>
		<tr>
			<td>1</td>
			<td><strong>Nama Sekolah</strong></td>
			<td>
				<?php if(!empty($nama_sekolah[0]->value)){ ?>
					<input type="text" class="form-control" name="school_name" value="<?php echo $nama_sekolah[0]->value;?>">
				<?php }else{ ?> 
					<input type="text" class="form-control" name="school_name">
				<?php } ?>
				
			</td>
		</tr>
		<tr>
			<td>2</td>
			<td><strong>Kepala Sekolah</strong></td>
			<td>
				<select name="kepsek_id" class="form-control">
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
			</td>
		</tr>
		<tr>
			<td>3</td>
			<td><strong>Alamat Sekolah</strong></td>
			<td>
				<?php if(!empty($alamat_sekolah[0]->id)){ ?>
					<textarea name="school_address" class="form-control"><?php echo $alamat_sekolah[0]->value;?></textarea>
				<?php }else{ ?> 
					<textarea name="school_address" class="form-control"></textarea>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td>4</td>
			<td><strong>Kurikulum Yang Dipakai</strong></td>
			<td>
				<select name="kurikulum" class="form-control" id="kur">
						<option value="2013" <?php echo $kurtilas;?> >2013</option>
						<option value="2006" <?php echo $ktsp;?> >2006</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>5</td>
			<td><strong>Fitur Ulangan </strong></td>
			<td>
				<select name="fitur_ulangan" class="form-control">
					<option value="1" <?php echo $ulanganOn;?> >ON</option>
					<option value="2" <?php echo $ulanganOff;?> >OFF</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>6</td>
			<td><strong>Fitur Tugas</strong></td>
			<td>
				<select name="fitur_tugas" class="form-control">
					<option value="1" <?php echo $tugasOn;?> >ON</option>
					<option value="2" <?php echo $tugasOff;?> >OFF</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>7</td>
			<td><strong>Fitur Materi</strong></td>
			<td>
				<select name="fitur_materi" class="form-control">
					<option value="1" <?php echo $materiOn;?> >ON</option>
					<option value="2" <?php echo $materiOff;?> >OFF</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>8</td>
			<td><strong>Fitur Rekap Nilai</strong></td>
			<td>
				<select name="fitur_rekap" class="form-control">
					<option value="1" <?php echo $rekapOn;?> >ON</option>
					<option value="2" <?php echo $rekapOff;?> >OFF</option>
				</select>
			</td>
		</tr>

		<tr>
			<td>9</td>
			<td><strong>Semester</strong></td>
			<td>
				<select name="semester" class="form-control">
					<option value="1" <?php echo $semesterOn;?> >1</option>
					<option value="2" <?php echo $semesterOff;?> >2</option>
				</select>
				
			</td>
		</tr>

		<tr>
			<td>10</td>
			<td><strong>Tahun Ajaran</strong></td>
			<!-- <td> -->
				<?php //(!empty($tahun_ajaran[0]->value)){ ?>
					<!-- <input type="number" class="form-control" name="tahun_ajaran" value="<?php //echo $tahun_ajaran[0]->value;?>"> -->
				<?php //}else{ ?> 
					<!-- <input type="number" class="form-control" name="tahun_ajaran"> -->
				<?php //} ?>
				
			</td>
			<td>
				<?php if (!empty($tahun_ajaran[0]->value)){ ?>
				<select name="tahun_ajaran" class="form-control">
					<option value="2016" <?php echo ($tahun_ajaran[0]->value == 2016 ?  'selected' : '');?> >2015-2016</option>
					<option value="2017" <?php echo ($tahun_ajaran[0]->value == 2017 ?  'selected' : '');?> >2016-2017</option>
					<option value="2018" <?php echo ($tahun_ajaran[0]->value == 2018 ?  'selected' : '');?> >2017-2018</option>
					<option value="2019" <?php echo ($tahun_ajaran[0]->value == 2019 ?  'selected' : '');?> >2018-2019</option>
					<option value="2020" <?php echo ($tahun_ajaran[0]->value == 2020 ?  'selected' : '');?> >2019-2020</option>
				</select>
				<?php }else{ ?> 
				<select name="tahun_ajaran" class="form-control">
					<option value="2016" 'selected'>2015-2016</option>
					<option value="2017" >2016-2017</option>
					<option value="2018" >2017-2018</option>
					<option value="2019" >2018-2019</option>
					<option value="2020" >2019-2020</option>
				</select>
				<?php } ?>
			</td>
		</tr>
		<tr rowspan="3" id="persen">
			<td>11</td>
			<td><strong>Prosentase Nilai</strong></td>
			<td>
				<div class="form-group">
				<?php if(!empty($nilai_harian[0]->value)){ ?>
					<div class="input-group">
						<div class="input-group-addon">Nilai Harian</div>
						<input type="number" class="form-control" name="nilai_harian" value="<?php echo $nilai_harian[0]->value;?>">
	      				<div class="input-group-addon">%</div>
	      			</div>
				<?php }else{ ?> 
					<div class="input-group">
						<div class="input-group-addon">Nilai Harian</div>
						<input type="number" class="form-control" name="nilai_harian">
	      				<div class="input-group-addon">%</div>
	      			</div>
				<?php } ?>
				<br>
				<?php if(!empty($nilai_uts[0]->value)){ ?>
					<div class="input-group">
						<div class="input-group-addon">UTS</div>
						<input type="number" class="form-control" name="nilai_uts" value="<?php echo $nilai_uts[0]->value;?>">
	      				<div class="input-group-addon">%</div>
	      			</div>
				<?php }else{ ?> 
					<div class="input-group">
						<div class="input-group-addon">UTS</div>
						<input type="number" class="form-control" name="nilai_uts">
	      				<div class="input-group-addon">%</div>
	      			</div>
				<?php } ?>
				<br>
				<?php if(!empty($nilai_uas[0]->value)){ ?>
					<div class="input-group">
						<div class="input-group-addon">UAS</div>
						<input type="number" class="form-control" name="nilai_uas" value="<?php echo $nilai_uas[0]->value;?>">
	      				<div class="input-group-addon">%</div>
	      			</div>
				<?php }else{ ?> 
					<div class="input-group">
						<div class="input-group-addon">UAS</div>
						<input type="number" class="form-control" name="nilai_uas">
	      				<div class="input-group-addon">%</div>
	      			</div>
				<?php } ?>
				</div>
			</td>
		</tr>
		
	</table>
	<br>
	<p class="text-right"><input type="submit" value="Simpan" class="btn btn-success"></p>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">
	$("#persen").hide();
	$('#kur').on('change', function() {
	  if( this.value == "2006"){
	  	$("#persen").show();
	  } else {
	  	$("#persen").hide();
	  }
	});
</script>