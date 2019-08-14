<?php
if($model->moving_class == 1){
										$kelasnya = $model->name;
										$idkelasnya = $model->id;
										$path_nya = 'lesson/'.$idkelasnya;
									}else{
										$kelasnya = $model->name;
										$idkelasnya = $model->id;
										$path_nya = 'lesson/'.$idkelasnya;
									}


$this->breadcrumbs=array(
	$kelasnya=>array($path_nya)
);
if($model->moving_class == 1){
	$namakelasnya = "";
} else {
	$namakelasnya = $model->class->name;
}
?>
<?php

		$kelasnya = $model->name;
		$idkelasnya = $model->id;
		$path_nya = 'lesson/'.$idkelasnya;
?>
<div class="container">
<div class="col-md-12">
  <div id="bc1" class="btn-group btn-breadcrumb">
	<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>Ujian</div>',array('/quiz/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>List</div>',array('/quiz/list'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>'.CHtml::encode($kelasnya).'</div>',array($path_nya,'type'=>'nilai'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>Nilai Keterampilan</div>',array('#'), array('class'=>'btn btn-success')); ?>
  </div>
</div>
	<h1>NILAI KETERAMPILAN</h1>
	<h1><?php echo $model->name; ?> <?php echo $namakelasnya; ?>  </h1>

	<?php  
	$semester = Option::model()->find(array('condition'=>'key_config LIKE "%semester%"'));
	$tahun_ajaran = Option::model()->find(array('condition'=>'key_config LIKE "%tahun_ajaran%"'));
	$kurikulum = Option::model()->find(array('condition'=>'key_config LIKE "%kurikulum%"'));

	if(!empty($semester) and !empty($tahun_ajaran) and !empty($kurikulum) and $kurikulum->value == '2013'){

		echo "<p>Semester : $semester->value , Tahun Ajaran : $tahun_ajaran->value</p";


	?>

	<div class="col-md-6">
		<?php $url_sikap = Yii::app()->createUrl('/lesson/addMarkKetSikDua/'.$model->id);?>
		<form method="post" action="<?php echo $url_sikap;?>">
			<p class="text-left">
             <button type="button" title="Tambah Siswa Dari Excel" class="btn btn-primary btn-responsive" data-toggle="modal" data-target="#copyExcel">
             COPAS EXCEL
            </button></span>
        	</p>
			<p class="text-right"><input type="submit" value="Simpan" class="btn btn-success"></p>
			<input type="hidden" name="lesson_id" value="<?php echo $model->id;?>">
			<input type="hidden" name="semester" value="<?php echo $semester->value;?>">
			<input type="hidden" name="tahun_ajaran" value="<?php echo $tahun_ajaran->value;?>">
			<table class="table table-bordered table-hovered well table-responsive">
				<tbody>
					<tr>
						<th>No</th>
						<th>Nama Siswa</th>
						<th>NUH 1</th>
						<th>NUH 2</th>
						<th>NUH 3</th>
						<th>NUH 4</th>
						<th>NUH 5</th>
						<th>NUH 6</th>
						<th>NUH 7</th>
						<th>Nilai Ket</th>
						<th>Input Nilai NUH 1</th>
						<th>Input Nilai NUH 2</th>
						<th>Input Nilai NUH 3</th>
						<th>Input Nilai NUH 4</th>
						<th>Input Nilai NUH 5</th>
						<th>Input Nilai NUH 6</th>
						<th>Input Nilai NUH 7</th>
						<th>Input Nilai Ket</th>
					</tr>
					<?php $no = 1;?>
					<?php foreach ($siswa as $sw) { ?>
						<tr>
							<td><?php echo $no;?></td>
							<td><input type="hidden" name="student_id[]" value="<?php echo $sw->id;?>"><b><?php echo CHtml::encode($sw->display_name);?></b></td>
							<td>
								<?php 
									
									if (!empty($nilai_arr[$sw->id]['nilai-kd1_ket'])) {
										echo $nilai_arr[$sw->id]['nilai-kd1_ket'];
									} else {
										echo "";
									}	

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 

									if (!empty($nilai_arr[$sw->id]['nilai-kd2_ket'])) {
										echo $nilai_arr[$sw->id]['nilai-kd2_ket'];
									} else {
										echo "";
									}
									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd2'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 

									if (!empty($nilai_arr[$sw->id]['nilai-kd3_ket'])) {
										echo $nilai_arr[$sw->id]['nilai-kd3_ket'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd3'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 


									if (!empty($nilai_arr[$sw->id]['nilai-kd4_ket'])) {
										echo $nilai_arr[$sw->id]['nilai-kd4_ket'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd4'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 

									if (!empty($nilai_arr[$sw->id]['nilai-kd5_ket'])) {
										echo $nilai_arr[$sw->id]['nilai-kd5_ket'];
									} else {
										echo "";
									}
									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd5_ket'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 

									if (!empty($nilai_arr[$sw->id]['nilai-kd6_ket'])) {
										echo $nilai_arr[$sw->id]['nilai-kd6_ket'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd6'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 

									if (!empty($nilai_arr[$sw->id]['nilai-kd7_ket'])) {
										echo $nilai_arr[$sw->id]['nilai-kd7_ket'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd7'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td class="danger">
								<?php 

									if (!empty($nilai_arr[$sw->id]['nilai-keterampilan'])) {
										echo $nilai_arr[$sw->id]['nilai-keterampilan'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							
							<td>
								<input type="number" name="score_kd1[]" class="form-control">
							</td>
							<td>
								<input type="number" name="score_kd2[]" class="form-control">
							</td>
							<td>
								<input type="number" name="score_kd3[]" class="form-control">
							</td>
							<td>
								<input type="number" name="score_kd4[]" class="form-control">
							</td>
							<td>
								<input type="number" name="score_kd5[]" class="form-control">
							</td>
							<td>
								<input type="number" name="score_kd6[]" class="form-control">
							</td>
							<td>
								<input type="number" name="score_kd7[]" class="form-control">
							</td>
							
							<td class="info">
								<input type="number" name="score_ket[]" class="form-control">
							</td>

							<?php
								if (!empty($nilai_arr[$sw->id]['desc-desc_keterampilan'])) {
										echo "<td>";	
										echo $nilai_arr[$sw->id]['desc-desc_keterampilan'];
										echo "</td>";
									} 

								// $cekdesc_ket = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'desc_keterampilan'));
								// if(!empty($cekdesc_ket)){
								// 		echo "<td>";
								// 		echo $cekdesc_ket->nilai_desc;
								// 		echo "</td>";
								// }
							?>
							
						</tr>
						<?php $no++; ?>
					<?php } ?>
				</tbody>
			</table>
		</form>
	</div>

	<?php } else { 

		echo "<p>Harap Masukan Config Semester dan Tahun Ajaran, Serta Kurikulum Harus 2013</p>";

	}

	?>
</div>
 <div class="modal fade" id="copyExcel" tabindex="-1" role="dialog" aria-labelledby="myNewCopy">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myNewCopy"><b>Data Siswa</b></h4>
              </div>
              <div class="modal-body idata">
                <?php $copy_url = Yii::app()->createUrl('/lesson/copykd/'.$model->id); ?>
                <!-- <form method="post" action="<?php echo $copy_url;?>" onsubmit="return confirm('Yakin menambahkan siswa ke kelas ini ?');"> -->
                <div class="form-group">
                    <textarea id="datamasuk" class="form-control" cols="5" rows="5" name="datasiswa"></textarea>
                </div>
                  <button class="btn btn-success" id="tambah">Tambah</button>
                <!-- </form> -->    
              </div>
              <div class="modal-footer">
                <span></span><button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
              </div>
              
            </div>
          </div>
        </div>
</div>
<script>
  if ($(window).width() > 960) {
    $("#wrapper").toggleClass("toggled");
  }
</script>
<script type="text/javascript">

// $("input[type=number][name='score_kd1[]']").each(function(){
// 	$(this).val('100');
// });
var url_post = "<?php echo $copy_url;?>";
  var ds;
  var obj;

  $("#tambah").click(function(){
    ds = $("#datamasuk").val();
    $.ajax({
        url: url_post,
        type: "POST",
        data: {datasiswa:ds},
        success: function(resp){
           //console.log(resp);
           obj = jQuery.parseJSON(resp);
           //console.log(obj.messages);
           if(obj.messages == "success"){
              $('#copyExcel').modal('hide');
              // $('#inputData').modal('show');
              // var i = 1;

              // if (1 < obj.data.length){
              // 	console.log(obj.data[1]);
              // 	console.log(obj.data.length);
              // }else {
              // 	console.log("tidak ada");
              // 	console.log(obj.data.length);
              // }
              
              	var i_kd1 = 0;
              	$("input[type=number][name='score_kd1[]']").each(function(){
					if (i_kd1 < obj.data.length){
						$(this).val(obj.data[i_kd1].kd1);
					} else {
						//$(this).val(obj.data[i_kd1].kd1);
					}
						
					//});
				i_kd1 ++;	
			    });

              	var i_kd2 = 0;
			    $("input[type=number][name='score_kd2[]']").each(function(){
					
					if (i_kd2 < obj.data.length){
						$(this).val(obj.data[i_kd2].kd2);
					} else {
						//$(this).val(obj.data[i_kd2].kd2);
					}
						
					//});
			    i_kd2 ++;	
			    });

			    var i_kd3 = 0;
			    $("input[type=number][name='score_kd3[]']").each(function(){
					
					if (i_kd3 < obj.data.length){
						$(this).val(obj.data[i_kd3].kd3);
					} else {
						//$(this).val(obj.data[i_kd3].kd3);
					}
						
					//});
			    i_kd3 ++;	
			    });

			    var i_kd4 = 0;
			    $("input[type=number][name='score_kd4[]']").each(function(){
					
					if (i_kd4 < obj.data.length){
						$(this).val(obj.data[i_kd4].kd4);
					} else {
						//$(this).val(obj.data[i_kd4].kd4);
					}
						
					//});
			    i_kd4 ++;	
			    });

			    var i_kd5 = 0;
			    $("input[type=number][name='score_kd5[]']").each(function(){
					
					if (i_kd5 < obj.data.length){
						$(this).val(obj.data[i_kd5].kd5);
					} else {
						//$(this).val(obj.data[i_kd5].kd5);
					}
						
					//});
			    i_kd5 ++;
			    });

			    var i_kd6 = 0;
			    $("input[type=number][name='score_kd6[]']").each(function(){
					
					if (i_kd6 < obj.data.length){
						$(this).val(obj.data[i_kd6].kd6);
					} else {
						//$(this).val(obj.data[i_kd6].kd6);
					}
						
					//});
			    i_kd6 ++;
			    });

			    var i_kd7 = 0;
			    $("input[type=number][name='score_kd7[]']").each(function(){
					
					if (i_kd7 < obj.data.length){
						$(this).val(obj.data[i_kd7].kd7);
					} else {
						//$(this).val(obj.data[i_kd7].kd7);
					}
						
					//});
			    i_kd7 ++;
			    });

			    var nilai = 0;
			    $("input[type=number][name='score_ket[]']").each(function(){
					
					if (nilai < obj.data.length){
						$(this).val(obj.data[nilai].nilai);
					} else {
						//$(this).val(obj.data[i_kd7].kd7);
					}
						
					//});
			    nilai ++;
			    });
			   
			    //i++;
                // console.log(value.nomor_induk);
                // $('#siswas').append('<tr class="tambahan"><td><input type="text" name="nis[]" value="'+value.nomor_induk+'" class="form-control"></td> <td><input type="text" name="nama[]" value="'+value.nama_lengkap+'" class="form-control"></td></tr>');
              
              
              
           }
           console.log(obj);
        }
      });
    //  console.log(ds);
  });
</script>