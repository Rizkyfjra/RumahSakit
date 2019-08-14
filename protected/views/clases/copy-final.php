<?php
/* @var $this LotController */
/* @var $model Lot */
$this->breadcrumbs=array(
	'Option'=>array('option/index'),
	'Daftarkan Kelas',
);

?>
<div class="container well col-md-6">
	<h2>Daftarkan Kelas</h2>
	<form method="post" action="" onsubmit="return confirm('Yakin selesai menambahkan daftar kelas ?');">
	<div class="form">
		<div class="form-group">
			<textarea class="form-control" name="kelas" cols="10" rows="10">XI IPA 1 
XI IPA 2 (contoh)
			</textarea>
		</div>
		<div class="form-group">
			<p class="text-right"><input type="submit" value="Simpan" class="btn btn-success"></p>
		</div>
	</div><!-- form -->
	</form>
</div>
