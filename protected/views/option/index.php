<?php
/* @var $this OptionController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Option',
);

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


/*$this->menu=array(
	array('label'=>'Create Option', 'url'=>array('create')),
	array('label'=>'Manage Option', 'url'=>array('admin')),
);*/
?>

<h1>Option</h1>
<p class="text-right">
	<span><?php echo CHtml::link('<i class="fa fa-folder"></i> Daftar Kelas', array('clases/index'),array('class'=>'btn btn-success'));?><?php echo CHtml::link('<i class="fa fa-plus"></i> Tambah Kelas', array('clases/addExcel'),array('class'=>'btn btn-info'));?><?php echo CHtml::link('<i class="fa fa-refresh"></i> Restart', array('Admin/restart'),array('class'=>'btn btn-warning','confirm' => 'Yakin Restart Pinisi Exambox ?'));?></span><span><?php echo CHtml::link('<i class="fa fa-power-off"></i> Power Off', array('Admin/poweroff'),array('class'=>'btn btn-danger','confirm' => 'Yakin Mematikan Pinisi Exambox ?'));?></span><?php echo CHtml::link('Edit', array('atur'),array('class'=>'btn btn-primary'));?>
</p>
<table class="table table-bordered table-hover table-responsive well">
	<tr>
		<th>No</th>
		<th>Konfigurasi</th>
		<th>Keterangan</th>
	</tr>
	<?php if(!empty($dataProvider->getData())){ ?>
		<tr>
			<td>1</td>
			<td>Nama Sekolah</td>
			<td>
				<?php 
					if(!empty($nama_sekolah[0]->value)){ 
						echo $nama_sekolah[0]->value;
					}	
				?>
			</td>
		</tr>

		<tr>
			<td>2</td>
			<td>Kepala Sekolah</td>
			<td>
				<?php 
					if(!empty($kepala_sekolah[0]->value)){ 
						$kepsek = User::model()->findByPk($kepala_sekolah[0]->value);
						echo $kepsek->display_name;
					}	
				?>
			</td>
		</tr>

		<tr>
			<td>3</td>
			<td>Alamat Sekolah</td>
			<td>
				<?php 
					if(!empty($alamat_sekolah[0]->value)){ 
						echo $alamat_sekolah[0]->value;
					}	
				?>
			</td>
		</tr>

		<tr>
			<td>4</td>
			<td>Kurikulum</td>
			<td>
				<?php 
					if(!empty($kurikulum_sekolah[0]->value)){ 
						echo $kurikulum_sekolah[0]->value;
					}	
				?>
			</td>
		</tr>

		<tr>
			<td>5</td>
			<td>Fitur Ulangan</td>
			<td>
				<?php 
					if(!empty($ulangan[0]->value)){ 
						if($ulangan[0]->value == '1'){
							echo "ON";
						}else{
							echo "OFF";
						} 
					}	
				?>
			</td>
		</tr>

		<tr>
			<td>6</td>
			<td>Fitur Tugas</td>
			<td>
				<?php 
					if(!empty($tugas[0]->value)){ 
						if($tugas[0]->value == '1'){
							echo "ON";
						}else{
							echo "OFF";
						} 
					}	
				?>
			</td>
		</tr>

		<tr>
			<td>7</td>
			<td>Fitur Materi</td>
			<td>
				<?php 
					if(!empty($materi[0]->value)){ 
						if($materi[0]->value == '1'){
							echo "ON";
						}else{
							echo "OFF";
						} 
					}	
				?>
			</td>
		</tr>

		<tr>
			<td>8</td>
			<td>Fitur Rekap Nilai</td>
			<td>
				<?php 
					if(!empty($rekap[0]->value)){
						if($rekap[0]->value == '1'){
							echo "ON";
						}else{
							echo "OFF";
						} 
						
					}	
				?>
			</td>
		</tr>

		<tr>
			<td>9</td>
			<td>Semester</td>
			<td>
				<?php 
					if(!empty($semester[0]->value)){ 
						echo $semester[0]->value;
					}	
				?>
			</td>
		</tr>

		<tr>
			<td>10</td>
			<td>Tahun Ajaran</td>
			<td>
				<?php 
					if(!empty($tahun_ajaran[0]->value)){ 
						echo $tahun_ajaran[0]->value;
					}	
				?>
			</td>
		</tr>
		<?php if($kurikulum_sekolah[0]->value != 2013){ ?>
		<tr rowspan="3">
			<td>11</td>
			<td>Prosentase Nilai</td>
			<td>
				<p>	
					NILAI HARIAN :
					<span>
						<?php 
							if(!empty($nilai_harian[0]->value)){ 
								echo $nilai_harian[0]->value."%";
							}	
						?>
					</span>
				</p>
				<p>
					NILAI UTS :
					<span>
					<?php 
						if(!empty($nilai_uts[0]->value)){ 
							echo $nilai_uts[0]->value."%";
						}	
					?>
					</span>
				</p>
				<p>
					NILAI UAS :
					<span>
					<?php 
						if(!empty($nilai_uas[0]->value)){ 
							echo $nilai_uas[0]->value."%";
						}	
					?>
					</span>
				</p>
			</td>
		</tr>
		<?php } ?>

	<?php } ?>
</table>

<?php /*$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
));*/ ?>
