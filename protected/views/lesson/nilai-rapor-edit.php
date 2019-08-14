<style type="text/css">
input[type=number]::-webkit-inner-spin-button, 
input[type=number]::-webkit-outer-spin-button { 
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    margin: 0; 
}
</style>
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
	<?php echo CHtml::link('<div>Nilai Rapor</div>',array('#'), array('class'=>'btn btn-success')); ?>
  </div>
</div>
<br/>
	<h1>EDIT NILAI RAPOR</h1>
	<h1><?php echo $model->name; ?> <?php echo $namakelasnya; ?> </h1>

	<?php  
	$semester = Option::model()->find(array('condition'=>'key_config LIKE "%semester%"'));
	$tahun_ajaran = Option::model()->find(array('condition'=>'key_config LIKE "%tahun_ajaran%"'));
	$kurikulum = Option::model()->find(array('condition'=>'key_config LIKE "%kurikulum%"'));
	// $cekDesc1 = LessonKd::model()->findByAttributes(array('title'=>'KD1','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	// if (!empty($cekDesc1)) {
	// 	$cekDesc1 = $cekDesc1->description;
	// } else {
	// 	$cekDesc1 = "";
	// }
	// $cekDesc2 = LessonKd::model()->findByAttributes(array('title'=>'KD2','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	// if (!empty($cekDesc2)) {
	// 	$cekDesc2 = $cekDesc2->description;
	// } else {
	// 	$cekDesc2 = "";
	// }
	// $cekDesc3 = LessonKd::model()->findByAttributes(array('title'=>'KD3','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	// if (!empty($cekDesc3)) {
	// 	$cekDesc3 = $cekDesc3->description;
	// } else {
	// 	$cekDesc3 = "";
	// }
	// $cekDesc4 = LessonKd::model()->findByAttributes(array('title'=>'KD4','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	// if (!empty($cekDesc4)) {
	// 	$cekDesc4 = $cekDesc4->description;
	// } else {
	// 	$cekDesc4 = "";
	// }
	// $cekDesc5 = LessonKd::model()->findByAttributes(array('title'=>'KD5','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	// if (!empty($cekDesc5)) {
	// 	$cekDesc5 = $cekDesc5->description;
	// } else {
	// 	$cekDesc5 = "";
	// }
	// $cekDesc6 = LessonKd::model()->findByAttributes(array('title'=>'KD6','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	// if (!empty($cekDesc6)) {
	// 	$cekDesc6 = $cekDesc6->description;
	// } else {
	// 	$cekDesc6 = "";
	// }
	// $cekDesc7 = LessonKd::model()->findByAttributes(array('title'=>'KD7','lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value));
	// if (!empty($cekDesc7)) {
	// 	$cekDesc7 = $cekDesc7->description;
	// } else {
	// 	$cekDesc7 = "";
	// }
	 if(!empty($semester) and !empty($tahun_ajaran) and !empty($kurikulum) and $kurikulum->value == '2013'){

	// 	echo "<p>Semester : $semester->value , Tahun Ajaran : $tahun_ajaran->value</p";
	// }

	$optSemester = "";
	$optTahunAjaran = "";
	if(Yii::app()->session['semester']){
					$optSemester = Yii::app()->session['semester'];
				} else {
					$optSemester=Option::model()->findByAttributes(array('key_config'=>'semester'))->value;
				}
					if(Yii::app()->session['tahun_ajaran']){
					$optTahunAjaran = Yii::app()->session['tahun_ajaran'];
				} else {
					$optTahunAjaran=Option::model()->findByAttributes(array('key_config'=>'tahun_ajaran'))->value;
				}

    echo "<p>Semester : $optSemester , Tahun Ajaran : $optTahunAjaran</p";

	?>
	<p><?php 
	// echo "<pre>";
	// print_r($nilai_arr4); 
	// echo "</pre>";
	// echo "<pre>";
	// print_r($nilai_arr); 
	// echo "</pre>";
	?></p>
	<div class="col-md-6">
		<?php $url_nilai = Yii::app()->createUrl('/lesson/addNilaiRapor/'.$model->id);?>
		<form method="post" action="<?php echo $url_nilai;?>">
			<p class="text-right">
             <button type="button" title="Tambah Siswa Dari Excel" class="btn btn-primary btn-responsive" data-toggle="modal" data-target="#copyExcel">
             COPAS EXCEL
            </button></span>
        	
			<input type="submit" value="SIMPAN" class="btn btn-success"></p>
			<input type="hidden" name="lesson_id" value="<?php echo $model->id;?>">
			<input type="hidden" name="semester" value="<?php echo $semester->value;?>">
			<input type="hidden" name="tahun_ajaran" value="<?php echo $tahun_ajaran->value;?>">
			<table class="table table-bordered table-hovered well table-responsive">
				<tbody>
					<tr>
						<th>No</th>
						<th>Nama Siswa</th>
						<th>N_UH1</th>
						<th>N_UH2</th>
						<th>N_UH3</th>
						<th>N_UH4</th>
						<th>N_UH5</th>
						<th>N_UH6</th>
						<th>N_UH7</th>
						<th>N_TU1</th>
						<th>N_TU2</th>
						<th>N_TU3</th>
						<th>N_TU4</th>
						<th>N_TU5</th>
						<th>N_TU6</th>
						<th>N_TU7</th>
						<th>R_NH</th>
						<th>R_NTU</th>
						<!-- <th>DES</th> -->
						<th>NH</th>
						<th>UTS_P</th>
						<th>UTS_K</th>
						<th>N_UAS</th>
						<th>N.PRAK</th>
						<th>N.PROJ</th>
						<th>N.PORT</th>
						<th>PENG M</th>
						<th>KET M</th>
						<?php if ($optSemester=="2") { ?>
							<th>PENG Sem1</th>
							<th>KET Sem1</th>
						<?php } ?>
						<th>PENGE</th>
						<th>KETRA</th>
					</tr>
					<?php $no = 1;?>
					
					<?php foreach ($siswa as $sw) { ?>
						<tr>
							<?php $rnh = array();?>
							<?php $rnt_arr = array();?>
							<?php $rnh_ket = array();?>
							<?php $r_nh = 0;?>
							<?php $r_ket = 0;?>
							<?php $rnt = 0;?>
							<?php $nh = 0;?>
							<?php $nuas = 0;?>
							<?php $npeng_m = 0;?>
							<td><?php echo $no;?></td>
							<td><input type="hidden" name="student_id[]" value="<?php echo $sw->id;?>"><b><?php echo CHtml::encode($sw->display_name);?></b></td>
							<?php if (array_key_exists($sw->id,$nilai_arr)) { ?>
							<td>
								
								<?php 

							    	if (array_key_exists('nilai-kd1',$nilai_arr[$sw->id])) {
							    		$rnh[] = $nilai_arr[$sw->id]['nilai-kd1']; ?>
											<input type="number" name="score_kd1[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-kd1']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr[$sw->id]['nilai-0'])&& count($nilai_arr[$sw->id]['nilai-0'])>=1) { 
												$rnh[] = array_values($nilai_arr[$sw->id]['nilai-0'])[0];
												?>
												<input type="number" name="score_kd1[]" class="form-control" value="<?php echo array_values($nilai_arr[$sw->id]['nilai-0'])[0]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_kd1[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								
								<?php 

							    	if (array_key_exists('nilai-kd2',$nilai_arr[$sw->id])) {
							    		$rnh[] = $nilai_arr[$sw->id]['nilai-kd2']; ?>
											<input type="number" name="score_kd2[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-kd2']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr[$sw->id]['nilai-0'])&& count($nilai_arr[$sw->id]['nilai-0'])>=2) { 
												$rnh[] = array_values($nilai_arr[$sw->id]['nilai-0'])[1];
												?>
												<input type="number" name="score_kd2[]" class="form-control" value="<?php echo array_values($nilai_arr[$sw->id]['nilai-0'])[1]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_kd2[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								
								<?php 

							    	if (array_key_exists('nilai-kd3',$nilai_arr[$sw->id])) {
							    		$rnh[] = $nilai_arr[$sw->id]['nilai-kd3']; ?>
											<input type="number" name="score_kd3[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-kd3']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr[$sw->id]['nilai-0'])&& count($nilai_arr[$sw->id]['nilai-0'])>=3) { 
												$rnh[] = array_values($nilai_arr[$sw->id]['nilai-0'])[2];
												?>
												<input type="number" name="score_kd3[]" class="form-control" value="<?php echo array_values($nilai_arr[$sw->id]['nilai-0'])[2]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_kd3[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							
							<td>
								
								<?php 

							    if (array_key_exists('nilai-kd4',$nilai_arr[$sw->id])) {
							    		$rnh[] = $nilai_arr[$sw->id]['nilai-kd4']; ?>
											<input type="number" name="score_kd4[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-kd4']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr[$sw->id]['nilai-0'])&& count($nilai_arr[$sw->id]['nilai-0'])>=4) { 
												$rnh[] = array_values($nilai_arr[$sw->id]['nilai-0'])[3];
												?>
												<input type="number" name="score_kd4[]" class="form-control" value="<?php echo array_values($nilai_arr[$sw->id]['nilai-0'])[3]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_kd4[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							
							<td>
								
								<?php 

							    if (array_key_exists('nilai-kd5',$nilai_arr[$sw->id])) {
							    	$rnh[] = $nilai_arr[$sw->id]['nilai-kd5']; ?>
											<input type="number" name="score_kd5[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-kd5']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr[$sw->id]['nilai-0'])&& count($nilai_arr[$sw->id]['nilai-0'])>=5) { 
													$rnh[] = array_values($nilai_arr[$sw->id]['nilai-0'])[4];
												?>
												<input type="number" name="score_kd5[]" class="form-control" value="<?php echo array_values($nilai_arr[$sw->id]['nilai-0'])[4]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_kd5[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>

							<td>
								
								<?php 

							    	if (array_key_exists('nilai-kd6',$nilai_arr[$sw->id])) {
							    		$rnh[] = $nilai_arr[$sw->id]['nilai-kd6']; ?>
											<input type="number" name="score_kd6[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-kd6']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr[$sw->id]['nilai-0'])&& count($nilai_arr[$sw->id]['nilai-0'])>=6) { 
												$rnh[] = array_values($nilai_arr[$sw->id]['nilai-0'])[5];
												?>
												<input type="number" name="score_kd6[]" class="form-control" value="<?php echo array_values($nilai_arr[$sw->id]['nilai-0'])[5]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_kd6[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>

							<td>
								
								<?php 

							    	if (array_key_exists('nilai-kd7',$nilai_arr[$sw->id])) {
							    		$rnh[] = $nilai_arr[$sw->id]['nilai-kd7']; ?>
											<input type="number" name="score_kd7[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-kd7']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr[$sw->id]['nilai-0'])&& count($nilai_arr[$sw->id]['nilai-0'])>=7) { 
												$rnh[] = array_values($nilai_arr[$sw->id]['nilai-0'])[6];
												?>
												<input type="number" name="score_kd7[]" class="form-control" value="<?php echo array_values($nilai_arr[$sw->id]['nilai-0'])[6]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_kd7[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<?php } else {?>
							<td><input type="number" name="score_kd1[]" class="form-control"></td>
							<td><input type="number" name="score_kd2[]" class="form-control"></td>
							<td><input type="number" name="score_kd3[]" class="form-control"></td>
							<td><input type="number" name="score_kd4[]" class="form-control"></td>
							<td><input type="number" name="score_kd5[]" class="form-control"></td>
							<td><input type="number" name="score_kd6[]" class="form-control"></td>
							<td><input type="number" name="score_kd7[]" class="form-control"></td>

							<?php }?>

							 <?php if (array_key_exists($sw->id,$nilai_arr4)) { ?>
							<td>
								
								<?php 

							    	if (array_key_exists('nilai-tu1',$nilai_arr4[$sw->id])) {
							    		$rnt_arr[] = $nilai_arr4[$sw->id]['nilai-tu1']; ?>
											<input type="number" name="score_tu1[]" class="form-control" value="<?php echo $nilai_arr4[$sw->id]['nilai-tu1']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr4[$sw->id])&& count($nilai_arr4[$sw->id])>=1) { 
												$rnt_arr[] = array_values($nilai_arr4[$sw->id])[0];
												?>
												<input type="number" name="score_tu1[]" class="form-control" value="<?php echo array_values($nilai_arr4[$sw->id])[0]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_tu1[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								
								<?php 

							    	if (array_key_exists('nilai-tu2',$nilai_arr4[$sw->id])) {
							    		$rnt_arr[] = $nilai_arr4[$sw->id]['nilai-tu2']; ?>
											<input type="number" name="score_tu2[]" class="form-control" value="<?php echo $nilai_arr4[$sw->id]['nilai-tu2']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr4[$sw->id])&& count($nilai_arr4[$sw->id])>=2) { 
												$rnt_arr[] = array_values($nilai_arr4[$sw->id])[1];
												?>
												<input type="number" name="score_tu2[]" class="form-control" value="<?php echo array_values($nilai_arr4[$sw->id])[1]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_tu2[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								
								<?php 

							    	if (array_key_exists('nilai-tu3',$nilai_arr4[$sw->id])) {
							    		$rnt_arr[] = $nilai_arr4[$sw->id]['nilai-tu3']; ?>
											<input type="number" name="score_tu3[]" class="form-control" value="<?php echo $nilai_arr4[$sw->id]['nilai-tu3']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr4[$sw->id])&& count($nilai_arr4[$sw->id])>=3) { 
												$rnt_arr[] = array_values($nilai_arr4[$sw->id])[2];
												?>
												<input type="number" name="score_tu3[]" class="form-control" value="<?php echo array_values($nilai_arr4[$sw->id])[2]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_tu3[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							
							<td>
								
								<?php 

							    if (array_key_exists('nilai-tu4',$nilai_arr4[$sw->id])) {
							    		$rnt_arr[] = $nilai_arr4[$sw->id]['nilai-tu4']; ?>
											<input type="number" name="score_tu4[]" class="form-control" value="<?php echo $nilai_arr4[$sw->id]['nilai-tu4']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr4[$sw->id])&& count($nilai_arr4[$sw->id])>=4) { 
												$rnt_arr[] = array_values($nilai_arr4[$sw->id])[3];
												?>
												<input type="number" name="score_tu4[]" class="form-control" value="<?php echo array_values($nilai_arr4[$sw->id])[3]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_tu4[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							
							<td>
								
								<?php 

							    if (array_key_exists('nilai-tu5',$nilai_arr4[$sw->id])) {
							    	$rnt_arr[] = $nilai_arr4[$sw->id]['nilai-tu5']; ?>
											<input type="number" name="score_tu5[]" class="form-control" value="<?php echo $nilai_arr4[$sw->id]['nilai-tu5']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr4[$sw->id])&& count($nilai_arr4[$sw->id])>=5) { 
													$rnt_arr[] = array_values($nilai_arr4[$sw->id])[4];
												?>
												<input type="number" name="score_tu5[]" class="form-control" value="<?php echo array_values($nilai_arr4[$sw->id])[4]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_tu5[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>

							<td>
								
								<?php 

							    	if (array_key_exists('nilai-tu6',$nilai_arr4[$sw->id])) {
							    		$rnt_arr[] = $nilai_arr4[$sw->id]['nilai-tu6']; ?>
											<input type="number" name="score_tu6[]" class="form-control" value="<?php echo $nilai_arr4[$sw->id]['nilai-tu6']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr4[$sw->id])&& count($nilai_arr4[$sw->id])>=6) { 
												$rnt_arr[] = array_values($nilai_arr4[$sw->id])[5];
												?>
												<input type="number" name="score_tu6[]" class="form-control" value="<?php echo array_values($nilai_arr4[$sw->id])[5]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_tu6[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>

							<td>
								
								<?php 

							    	if (array_key_exists('nilai-tu7',$nilai_arr4[$sw->id])) {
							    		$rnt_arr[] = $nilai_arr4[$sw->id]['nilai-tu7']; ?>
											<input type="number" name="score_tu7[]" class="form-control" value="<?php echo $nilai_arr4[$sw->id]['nilai-tu7']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr4[$sw->id])&& count($nilai_arr4[$sw->id])>=7) { 
												$rnt_arr[] = array_values($nilai_arr4[$sw->id])[6];
												?>
												<input type="number" name="score_tu7[]" class="form-control" value="<?php echo array_values($nilai_arr4[$sw->id])[6]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_tu7[]" class="form-control">
											<?php }
									}
									

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<?php } else {?>
							<td><input type="number" name="score_tu1[]" class="form-control"></td>
							<td><input type="number" name="score_tu2[]" class="form-control"></td>
							<td><input type="number" name="score_tu3[]" class="form-control"></td>
							<td><input type="number" name="score_tu4[]" class="form-control"></td>
							<td><input type="number" name="score_tu5[]" class="form-control"></td>
							<td><input type="number" name="score_tu6[]" class="form-control"></td>
							<td><input type="number" name="score_tu7[]" class="form-control"></td>

							<?php }?>

							 <td >
								<?php 

									if (!empty($rnh)) {
										$r_nh = round(array_sum($rnh) / count($rnh));
										echo $r_nh;
									} else {
										echo $r_nh;
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd7'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
						     </td>

						      <td >
								<?php 

									if (!empty($rnt_arr)) {
										$rnt = round(array_sum($rnt_arr) / count($rnt_arr));
										echo $rnt;
									} else {
										echo $rnt;
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd7'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
						     </td>

						    
							  <td >
							    <?php 

									if ($r_nh!=0 & $rnt!=0) {
										$nh = ((2*$r_nh)+$rnt)/3;
										echo round($nh); 
								?>
										<input type="hidden" name="score_tugas[]" class="form-control" value="<?php echo $nh; ?>"> 
								<?php // $rnt = $nilai_arr[$sw->id]['nilai-tugas'];
									} else {
										echo $nh; 
								?>
										<input type="hidden" name="score_tugas[]" class="form-control" value="<?php echo $nh; ?>"> 
								<?php
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd7'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
						     </td>
							  <td >
								<?php 

								

									if (!empty($nilai_arr[$sw->id]['nilai-uts_p'])) {
							    		$nuts_p = $nilai_arr[$sw->id]['nilai-uts_p']; ?>
											<input type="number" name="score_uts_p[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-uts_p']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr[$sw->id]['nilai-1'])) {
												$nuas = array_values($nilai_arr[$sw->id]['nilai-1'])[0];
												?>
												<input type="number" name="score_uts_p[]" class="form-control" value="<?php echo array_values($nilai_arr[$sw->id]['nilai-1'])[0]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_uts_p[]" class="form-control">
											<?php }
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>
							 <td >
								<?php 

								

									if (!empty($nilai_arr[$sw->id]['nilai-uts_k'])) {
							    		$nuts_k = $nilai_arr[$sw->id]['nilai-uts_k']; ?>
											<input type="number" name="score_uts_k[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-uts_k']; ?>"> 
									<?php } else {  ?>
												<input type="number" name="score_uts_k[]" class="form-control">
									<?php
										}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>
							  <td >
								<?php 

								

									if (!empty($nilai_arr[$sw->id]['nilai-uas'])) {
							    		$nuas = $nilai_arr[$sw->id]['nilai-uas']; ?>
											<input type="number" name="score_uas[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-uas']; ?>"> 
									<?php } else {
										
											if (!empty($nilai_arr[$sw->id]['nilai-2'])) {
												$nuas = array_values($nilai_arr[$sw->id]['nilai-2'])[0];
												?>
												<input type="number" name="score_uas[]" class="form-control" value="<?php echo array_values($nilai_arr[$sw->id]['nilai-2'])[0]; ?>"> 
											<?php } else { ?>
												<input type="number" name="score_uas[]" class="form-control">
											<?php }
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>
							  <td >
								<?php 

									if (!empty($nilai_arr[$sw->id]['nilai-kd1_ket'])) {
							    		$rnh_ket[] = $nilai_arr[$sw->id]['nilai-kd1_ket'];
							    		?>
											<input type="number" name="score_kd1_ket[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-kd1_ket']; ?>"> 
									<?php } else { ?>
										
											
												<input type="number" name="score_kd1_ket[]" class="form-control">
											
									<?php }

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>
							  <td >
								<?php 
									if (!empty($nilai_arr[$sw->id]['nilai-kd2_ket'])) {
							    		$rnh_ket[] = $nilai_arr[$sw->id]['nilai-kd2_ket'];
							    		?>
											<input type="number" name="score_kd2_ket[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-kd2_ket']; ?>"> 
									<?php } else { ?>
										
											
												<input type="number" name="score_kd2_ket[]" class="form-control">
											
									<?php }

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>
							   <td >
								<?php 
									if (!empty($nilai_arr[$sw->id]['nilai-kd3_ket'])) {
							    		$rnh_ket[] = $nilai_arr[$sw->id]['nilai-kd3_ket'];
							    		?>
											<input type="number" name="score_kd3_ket[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-kd3_ket']; ?>"> 
									<?php } else { ?>
										
											
												<input type="number" name="score_kd3_ket[]" class="form-control">
											
									<?php }

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>
							  <td >
								 <?php 

									if ($nh!=0 & $nuas!=0) {
										$npeng_m = ((2*round($nh))+$nuas)/3;
										echo round($npeng_m);
										// $rnt = $nilai_arr[$sw->id]['nilai-tugas'];
									} else {
										echo $npeng_m;
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd7'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>
							  <td >
								<?php 

									if (!empty($rnh_ket)) {
										$r_ket = round(array_sum($rnh_ket) / count($rnh_ket));
										echo $r_ket;
									} else {
										echo $r_ket;
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd7'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>
							 <?php if ($optSemester=="2") { ?>
							 <td>
								 <?php if (!empty($nilai_arr3[$sw->id]['nilai-pengetahuan'])) { ?>
											<?php echo $nilai_arr3[$sw->id]['nilai-pengetahuan']; ?> 
								 <?php } else { ?>		
										-
								 <?php } ?>
							 </td>
							  <td>
								 <?php if (!empty($nilai_arr3[$sw->id]['nilai-keterampilan'])) { ?>
											<?php echo $nilai_arr3[$sw->id]['nilai-keterampilan']; ?> 
								 <?php } else { ?>		
										-
								 <?php } ?>
							 </td>
							 <?php }?>
							  <td >
								<?php 




									if (!empty($nilai_arr[$sw->id]['nilai-pengetahuan'])) {
							    		$rnh_ket[] = $nilai_arr[$sw->id]['nilai-pengetahuan'];
							    		?>
											<input type="number" name="score_pen[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-pengetahuan']; ?>"> 
									<?php } else { ?>
										
											
												<input type="number" name="score_pen[]" class="form-control">
											
									<?php }

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>
							  <td >
								<?php 

								


									if (!empty($nilai_arr[$sw->id]['nilai-keterampilan'])) {
							    		$rnh_ket[] = $nilai_arr[$sw->id]['nilai-keterampilan'];
							    		?>
											<input type="number" name="score_ket[]" class="form-control" value="<?php echo $nilai_arr[$sw->id]['nilai-keterampilan']; ?>"> 
									<?php } else { ?>
										
											
												<input type="number" name="score_ket[]" class="form-control">
											
									<?php }

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>

						
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
                <h4 class="modal-title" id="myNewCopy"><b>Nilai Siswa</b></h4>
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

			    var tugas = 0;
			    $("input[type=number][name='score_tugas[]']").each(function(){
					
					if (tugas < obj.data.length){
						$(this).val(obj.data[tugas].tugas);
					} else {
						//$(this).val(obj.data[i_kd7].kd7);
					}
						
					//});
			    tugas ++;
			    });

			    var uts_p = 0;
			    $("input[type=number][name='score_uts_p[]']").each(function(){
					
					if (uts_p < obj.data.length){
						$(this).val(obj.data[uts_p].uts_p);
					} else {
						//$(this).val(obj.data[i_kd7].kd7);
					}
						
					//});
			    uts_p ++;
			    });

			    var uts_k = 0;
			    $("input[type=number][name='score_uts_k[]']").each(function(){
					
					if (uts_k < obj.data.length){
						$(this).val(obj.data[uts_k].uts_k);
					} else {
						//$(this).val(obj.data[i_kd7].kd7);
					}
						
					//});
			    uts_k ++;
			    });

			     var n_uas = 0;
			    $("input[type=number][name='score_uas[]']").each(function(){
					
					if (n_uas < obj.data.length){
						$(this).val(obj.data[n_uas].n_uas);
					} else {
						//$(this).val(obj.data[i_kd7].kd7);
					}
						
					//});
			    n_uas ++;
			    });


			     var n_prak = 0;
			    $("input[type=number][name='score_kd1_ket[]']").each(function(){
					
					if (n_prak < obj.data.length){
						$(this).val(obj.data[n_prak].n_prak);
					} else {
						//$(this).val(obj.data[i_kd7].kd7);
					}
						
					//});
			    n_prak ++;
			    });


			     var n_proj = 0;
			    $("input[type=number][name='score_kd2_ket[]']").each(function(){
					
					if (n_proj < obj.data.length){
						$(this).val(obj.data[n_proj].n_proj);
					} else {
						//$(this).val(obj.data[i_kd7].kd7);
					}
						
					//});
			    n_proj ++;
			    });


			      var n_port = 0;
			    $("input[type=number][name='score_kd3_ket[]']").each(function(){
					
					if (n_port < obj.data.length){
						$(this).val(obj.data[n_port].n_port);
					} else {
						//$(this).val(obj.data[i_kd7].kd7);
					}
						
					//});
			    n_port ++;
			    });

			     var peng = 0;
			    $("input[type=number][name='score_pen[]']").each(function(){
					
					if (peng < obj.data.length){
						$(this).val(obj.data[peng].peng);
					} else {
						//$(this).val(obj.data[i_kd7].kd7);
					}
						
					//});
			    peng ++;
			    });

			    var ket = 0;
			    $("input[type=number][name='score_ket[]']").each(function(){
					
					if (ket < obj.data.length){
						$(this).val(obj.data[ket].ket);
					} else {
						//$(this).val(obj.data[i_kd7].kd7);
					}
						
					//});
			    ket ++;
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