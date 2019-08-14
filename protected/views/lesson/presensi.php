<?php
	$kelasnya = $model->name;
	$idkelasnya = $model->id;
	$path_nya = 'lesson/'.$idkelasnya;

	$this->breadcrumbs=array(
		$kelasnya=>array($path_nya)
	);

	if($model->moving_class == 1){
		$namakelasnya = "";
	} else {
		$namakelasnya = $model->class->name;
	}
?>
<div class="container">
	<h1>PRESENSI PELAJARAN SISWA</h1>
	<h1><?php echo $model->name; ?> <?php echo $namakelasnya; ?> </h1>

	<?php  
		$semester = Option::model()->find(array('condition'=>'key_config LIKE "%semester%"'));
		$tahun_ajaran = Option::model()->find(array('condition'=>'key_config LIKE "%tahun_ajaran%"'));
		
		echo "<p>Semester : $semester->value , Tahun Ajaran : $tahun_ajaran->value</p";
	?>
	<div class="col-md-6">
		<?php $url_presensi = Yii::app()->createUrl('/lesson/addMarkPresensi/'.$model->id);?>
		<form method="post" action="<?php echo $url_presensi;?>">
			<p class="text-right"><input type="submit" name="save" value="Simpan" class="btn btn-success"></p>
			<table class="table table-bordered table-hovered well table-responsive">
				<tbody>
					<tr>
						<th>No</th>
						<th>Nama Siswa</th>
						<th width="10%">Hadir</th>
						<th width="10%">Sakit</th>
						<th width="10%">Izin</th>
						<th width="10%">Tanpa Keterangan</th>
						<th class="info" width="10%">Input Hadir</th>
						<th class="info" width="10%">Input Sakit</th>
						<th class="info" width="10%">Input Izin</th>
						<th class="info" width="10%">Input Tanpa Keterangan</th>
					</tr>
					<?php $no = 1;?>
					<?php 
						if(!empty($presensi)){
							foreach ($presensi as $keys => $value) {
					?>
						<tr>
							<td><?php echo $no;?></td>
							<td><?php echo $value['display_name'] ?></td>
							<td><?php echo '',($value['presensi_hadir'] == NULL) ? '0' : $value['presensi_hadir'] ?></td>
							<td><?php echo '',($value['presensi_sakit'] == NULL) ? '0' : $value['presensi_sakit'] ?></td>
							<td><?php echo '',($value['presensi_izin'] == NULL) ? '0' : $value['presensi_izin'] ?></td>
							<td><?php echo '',($value['presensi_alfa'] == NULL) ? '0' : $value['presensi_alfa'] ?></td>
							<td class="info"><input type="number" class="form-control" name="presensi-hadir-<?php echo $value['id'] ?>"></td>
							<td class="info"><input type="number" class="form-control" name="presensi-sakit-<?php echo $value['id'] ?>"></td>
							<td class="info"><input type="number" class="form-control" name="presensi-izin-<?php echo $value['id'] ?>"></td>
							<td class="info"><input type="number" class="form-control" name="presensi-alfa-<?php echo $value['id'] ?>"></td>
						</tr>
						<?php $no++; ?>
					<?php 
							}
						}
					?>
				</tbody>
			</table>
		</form>
	</div>
</div>