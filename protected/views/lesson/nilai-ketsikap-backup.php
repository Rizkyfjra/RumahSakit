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
<div class="container">
	<h1>NILAI PENGETAHUAN</h1>
	<h1><?php echo $model->name; ?> <?php echo $namakelasnya; ?> </h1>

	<?php  
	$semester = Option::model()->find(array('condition'=>'key_config LIKE "%semester%"'));
	$tahun_ajaran = Option::model()->find(array('condition'=>'key_config LIKE "%tahun_ajaran%"'));
	$kurikulum = Option::model()->find(array('condition'=>'key_config LIKE "%kurikulum%"'));
	$cekDesc1 = LessonKd::model()->findByAttributes(array('title'=>'KD1','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	if (!empty($cekDesc1)) {
		$cekDesc1 = $cekDesc1->description;
	} else {
		$cekDesc1 = "";
	}
	$cekDesc2 = LessonKd::model()->findByAttributes(array('title'=>'KD2','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	if (!empty($cekDesc2)) {
		$cekDesc2 = $cekDesc2->description;
	} else {
		$cekDesc2 = "";
	}
	$cekDesc3 = LessonKd::model()->findByAttributes(array('title'=>'KD3','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	if (!empty($cekDesc3)) {
		$cekDesc3 = $cekDesc3->description;
	} else {
		$cekDesc3 = "";
	}
	$cekDesc4 = LessonKd::model()->findByAttributes(array('title'=>'KD4','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	if (!empty($cekDesc4)) {
		$cekDesc4 = $cekDesc4->description;
	} else {
		$cekDesc4 = "";
	}
	$cekDesc5 = LessonKd::model()->findByAttributes(array('title'=>'KD5','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	if (!empty($cekDesc5)) {
		$cekDesc5 = $cekDesc5->description;
	} else {
		$cekDesc5 = "";
	}
	$cekDesc6 = LessonKd::model()->findByAttributes(array('title'=>'KD6','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	if (!empty($cekDesc6)) {
		$cekDesc6 = $cekDesc6->description;
	} else {
		$cekDesc6 = "";
	}
	$cekDesc7 = LessonKd::model()->findByAttributes(array('title'=>'KD7','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	if (!empty($cekDesc7)) {
		$cekDesc7 = $cekDesc7->description;
	} else {
		$cekDesc7 = "";
	}
	if(!empty($semester) and !empty($tahun_ajaran) and !empty($kurikulum) and $kurikulum->value == '2013'){

		echo "<p>Semester : $semester->value , Tahun Ajaran : $tahun_ajaran->value</p";


	?>

	<div class="col-md-6">
		<?php $url_sikap = Yii::app()->createUrl('/lesson/addMarkKetSik/'.$model->id);?>
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
						<th>KD 1</th>
						<th>KD 2</th>
						<th>KD 3</th>
						<th>KD 4</th>
						<th>KD 5</th>
						<th>KD 6</th>
						<th>KD 7</th>
						<th>Nilai Pen</th>
						
						<!-- <th>DES</th> -->
						<th>Input Nilai KD 1</th>
						<th>Input Nilai KD 2</th>
						<th>Input Nilai KD 3</th>
						<th>Input Nilai KD 4</th>
						<th>Input Nilai KD 5</th>
						<th>Input Nilai KD 6</th>
						<th>Input Nilai KD 7</th>
						<th>Input Nilai Pen</th>
					</tr>
					<?php $no = 1;?>
					<?php foreach ($siswa as $sw) { ?>
						<tr>
							<td><?php echo $no;?></td>
							<td><input type="hidden" name="student_id[]" value="<?php echo $sw->id;?>"><b><?php echo CHtml::encode($sw->display_name);?></b></td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd1'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd2'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd3'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd4'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd5'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd6'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td>
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd7'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							<td class="danger">
								<?php 
									$cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									if(!empty($cekNilai)){
										echo $cekNilai->nilai;
									}
									
								?>
							</td>
							
							<!-- <td class="danger"> -->
								<?php 
									// $nilaiKd = FinalMark::model()->findAll(array("condition"=>"user_id = $sw->id and lesson_id = $model->id and semester = $semester->value and tahun_ajaran = $tahun_ajaran->value and tipe in ('kd1','kd2','kd3','kd4','kd5','kd6','kd7')"));
 								// 	// echo "<pre>";
 								// 	// print_r($nilaiKd);
 								// 	// echo "</pre>";
 								// 	$sb = "Sangat baik dalam memahami";
 								// 	$b = "Baik dalam memahami";
 								// 	$pd = "Perlu ditingkatkan dalam penguasaan";
 								// 	foreach ($nilaiKd as $key => $value) {
 								// 		//echo $value->tipe."</br>";
 								// 		//echo $value->nilai."</br>";

 								// 		if ($value->nilai>= 86 && $value->nilai<=100) {
 											
 								// 				switch ($value->tipe) {
 								// 					case 'kd1':
 								// 						$sb .= $cekDesc1;
 								// 						break;
 								// 					case 'kd2':
 								// 						$sb .= $cekDesc2;
 								// 						break;
 								// 					case 'kd3':
 								// 						$sb .= $cekDesc3;
 								// 						break;
 								// 					case 'kd4':
 								// 						$sb .= $cekDesc4;
 								// 						break;
 								// 					case 'kd5':
 								// 						$sb .= $cekDesc5;
 								// 						break;
 								// 					case 'kd6':
 								// 						$sb .= $cekDesc6;
 								// 						break;
 								// 					case 'kd7':
 								// 						$sb .= $cekDesc7;
 								// 						break;
 								// 				}
 								// 		}

 								// 		else if ($value->nilai>= 70 && $value->nilai<=86) {
 											
 								// 				switch ($value->tipe) {
 								// 					case 'kd1':
 								// 						$b .= $cekDesc1;
 								// 						break;
 								// 					case 'kd2':
 								// 						$b .= $cekDesc2;
 								// 						break;
 								// 					case 'kd3':
 								// 						$b .= $cekDesc3;
 								// 						break;
 								// 					case 'kd4':
 								// 						$b .= $cekDesc4;
 								// 						break;
 								// 					case 'kd5':
 								// 						$b .= $cekDesc5;
 								// 						break;
 								// 					case 'kd6':
 								// 						$b .= $cekDesc6;
 								// 						break;
 								// 					case 'kd7':
 								// 						$b .= $cekDesc7;
 								// 						break;
 								// 				}
 								// 		}

 								// 		else {
 											
 								// 				switch ($value->tipe) {
 								// 					case 'kd1':
 								// 						$pd .= $cekDesc1;
 								// 						break;
 								// 					case 'kd2':
 								// 						$pd .= $cekDesc2;
 								// 						break;
 								// 					case 'kd3':
 								// 						$pd .= $cekDesc3;
 								// 						break;
 								// 					case 'kd4':
 								// 						$pd .= $cekDesc4;
 								// 						break;
 								// 					case 'kd5':
 								// 						$pd .= $cekDesc5;
 								// 						break;
 								// 					case 'kd6':
 								// 						$pd .= $cekDesc6;
 								// 						break;
 								// 					case 'kd7':
 								// 						$pd .= $cekDesc7;
 								// 						break;
 								// 				}
 								// 		}
 								// 	}

 								// 	echo $sb . $b . $pd;
								?>
							<!-- </td> -->
							
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
							<td class="danger">
								<input type="number" name="score_pen[]" class="form-control">
							</td>
							<?php
								$cekdesc_peng = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'desc_pengetahuan'));
								if(!empty($cekdesc_peng)){
										echo "<td>";
										echo $cekdesc_peng->nilai_desc;
										echo "</td>";
								}
  
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
			    $("input[type=number][name='score_pen[]']").each(function(){
					
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