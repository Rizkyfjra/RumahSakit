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
		<p class="text-right"><a href="#" id="tambah" class="btn btn-primary" title="Tambah Kelas"><i class="fa fa-plus"></i></a></p>
		<p class="text-right"><b>Tingkat</b></p>
		<div class="form-group" id="initial">
			<label>Nama Kelas</label>
			<div class="input-group">
				<input type="text" class="form-control" name="kelas[]">
				<div class="input-group-addon">
					<select name="grade[]">
						<option value="1">1</option>
						<option value="2">2</option>
						<option value="3">3</option>
						<option value="4">4</option>
						<option value="5">5</option>
						<option value="6">6</option>
						<option value="7">7</option>
						<option value="8">8</option>
						<option value="9">9</option>
						<option value="10">10</option>
						<option value="11">11</option>
						<option value="12">12</option>
					</select>
				</div>
			</div>
		</div>
		<div class="form-group">
			<p class="text-right"><input type="submit" value="Simpan" class="btn btn-success"></p>
		</div>
	</div><!-- form -->
	</form>
</div>
<script type="text/javascript">
$("#tambah").click(function(){
	//no=no+1;
	//console.log("Berhasil");
	//$('<div class="form-group"><input type="text" name="pil[]" class="form-control"></div>').appendTo("#last");
	$('#initial').append('<br><div class="form-group" id="initial"><div class="input-group"><input type="text" class="form-control" name="kelas[]"><div class="input-group-addon"><select name="grade[]"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select></div></div></div>');
	/*var id = $(this).attr('id'); 
	//for (var i = 67; i <= 90; i++) {
	    //$(select).append('<option>' + string.fromCharCode(i) + '</option>');
	//}
	alert(fromCharCode(67));
	iter++;*/
	/*$("#aid").val(id);
	$("<td><label></label>Nilai "+$('#'+id+'').text()+"<input type='text' class='form-control' size=3 name='nilai[]'><br><input type='submit' class='form-control btn btn-danger' value='Nilai'/></td>").insertAfter(".kolom");*/
	//alert(id);
	//console.log(no);
});
</script>