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
	<h1>NILAI RAPOR</h1>
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

	<div class="col-md-6">
		<?php
		function notempty($var) {
    return ($var==="0"||$var);
}
		?>
		<?php $url_sikap = Yii::app()->createUrl('/lesson/addMarkKetSik/'.$model->id);?>
		<form method="post" action="<?php echo $url_sikap;?>">
		
			<p class="text-right">
            <a href="<?php echo Yii::app()->createUrl('/lesson/DownloadAbsenPelajaran/'.$model->id);?>"><input type="button" value="DOWNLOAD FORM INPUT EXCEL" class="btn btn-primary"></a>
			<a href="<?php echo Yii::app()->createUrl('/lesson/EditNilaiRapor/'.$model->id);?>"><input type="button" value="EDIT" class="btn btn-success"></a></p>
			<input type="hidden" name="lesson_id" value="<?php echo $model->id;?>">
			<input type="hidden" name="semester" value="<?php echo $semester->value;?>">
			<input type="hidden" name="tahun_ajaran" value="<?php echo $tahun_ajaran->value;?>">
			<table class="table table-bordered table-hovered well table-responsive">
				<tbody>
					<tr>
						<th>No</th>
						<th>Nama Siswa</th>
						<th>S</th>
						<th>I</th>
						<th>A</th>
						<th>NUH 1</th>
						<th>NUH 2</th>
						<th>NUH 3</th>
						<th>NUH 4</th>
						<th>NUH 5</th>
						<th>NUH 6</th>
						<th>NUH 7</th>
						<th>NTU 1</th>
						<th>NTU 2</th>
						<th>NTU 3</th>
						<th>NTU 4</th>
						<th>NTU 5</th>
						<th>NTU 6</th>
						<th>NTU 7</th>
						<th>R.NH</th>
						<th>R.NT</th>
						<!-- <th>DES</th> -->
						<th>NH</th>
						<th>UTS P</th>
						<th>UTS K</th>
						<th>N.UAS</th>
						<th>N.PRAK</th>
						<th>N.PROJ</th>
						<th>N.PORTO</th>
						<th>PENG M</th>
						<th>KET M</th>
						<th class="bg-primary">PENG</th>
						<th class="bg-primary">KET</th>
					</tr>
					<?php $no = 1;?>
					
					<?php foreach ($siswa as $sw) { ?>
						<tr>
							<?php $rnh = array();?>
							<?php $rnt = array();?>
							<?php $rnh_ket = array();?>
							<?php $r_nh = 0;?>
							<?php $r_ket = 0;?>
							<?php $rnt = 0;?>
							<?php $nh = 0;?>
							<?php $nuas = 0;?>
							<?php $nutsp = 0;?>
							<?php $nutsk = 0;?>
							<?php $npeng_m = 0;?>
							<td><?php echo $no;?></td>
							<td><input type="hidden" name="student_id[]" value="<?php echo $sw->id;?>"><b><?php echo CHtml::encode($sw->display_name);?></b></td>
							<?php if (array_key_exists($sw->id,$nilai_arr)) { ?>
							<td>
								<?php 
									
									if (!empty($nilai_arr[$sw->id]['absensi']['Sakit'])) {
										echo $nilai_arr[$sw->id]['absensi']['Sakit'];;
									} else {
										echo "";
									}
									
								?>
							</td>
							<td>
								<?php 
									
									if (!empty($nilai_arr[$sw->id]['absensi']['Izin'])) {
										echo $nilai_arr[$sw->id]['absensi']['Izin'];;
									} else {
										echo "";
									}
									
								?>
							</td>
							<td>
								<?php 
									if (array_key_exists('absensi',$nilai_arr[$sw->id])) {
										if (!empty($nilai_arr[$sw->id]['absensi']['Alfa'])) {
											echo $nilai_arr[$sw->id]['absensi']['Alfa'];;
										} else {
											echo "";
										}
									} else {
										echo "";
									}
									
								?>
							</td>
							<td>
								<?php 
									
									if (array_key_exists('nilai-kd1',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-kd1'];
										$rnh[] = $nilai_arr[$sw->id]['nilai-kd1'];
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

									if (array_key_exists('nilai-kd2',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-kd2'];
										$rnh[] = $nilai_arr[$sw->id]['nilai-kd2'];
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

									if (array_key_exists('nilai-kd3',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-kd3'];
										$rnh[] = $nilai_arr[$sw->id]['nilai-kd3'];
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


									if (array_key_exists('nilai-kd4',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-kd4'];
										$rnh[] = $nilai_arr[$sw->id]['nilai-kd4'];
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

									if (array_key_exists('nilai-kd5',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-kd5'];
										$rnh[] = $nilai_arr[$sw->id]['nilai-kd5'];
									} else {
										echo "";
									}
									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd5'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 

									if (array_key_exists('nilai-kd6',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-kd6'];
										$rnh[] = $nilai_arr[$sw->id]['nilai-kd6'];
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

									if (array_key_exists('nilai-kd7',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-kd7'];
										$rnh[] = $nilai_arr[$sw->id]['nilai-kd7'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd7'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 
									
									if (array_key_exists('nilai-tu1',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-tu1'];
										$rnt_arr[] = $nilai_arr[$sw->id]['nilai-tu1'];
									} else {
										echo "";
									}	

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu1'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 

									if (array_key_exists('nilai-tu2',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-tu2'];
										$rnt_arr[] = $nilai_arr[$sw->id]['nilai-tu2'];
									} else {
										echo "";
									}
									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu2'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 

									if (array_key_exists('nilai-tu3',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-tu3'];
										$rnt_arr[] = $nilai_arr[$sw->id]['nilai-tu3'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu3'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 


									if (array_key_exists('nilai-tu4',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-tu4'];
										$rnt_arr[] = $nilai_arr[$sw->id]['nilai-tu4'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu4'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 

									if (array_key_exists('nilai-tu5',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-tu5'];
										$rnt_arr[] = $nilai_arr[$sw->id]['nilai-tu5'];
									} else {
										echo "";
									}
									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu5'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 

									if (array_key_exists('nilai-tu6',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-tu6'];
										$rnt_arr[] = $nilai_arr[$sw->id]['nilai-tu6'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu6'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<td>
								<?php 

									if (array_key_exists('nilai-tu7',$nilai_arr[$sw->id])) {
										echo $nilai_arr[$sw->id]['nilai-tu7'];
										$rnt_arr[] = $nilai_arr[$sw->id]['nilai-tu7'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'tu7'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							</td>
							<?php } else {?>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
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
						    <!--  <td >
								<?php 

									// if (!empty($nilai_arr[$sw->id]['nilai-tugas'])) {
									// 	echo $nilai_arr[$sw->id]['nilai-tugas'];
									// 	$rnt = $nilai_arr[$sw->id]['nilai-tugas'];
									// } else {
									// 	echo $rnt;
									// }

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'kd7'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td> -->
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

									if ($r_nh!=0 && $rnt!=0) {
										$nh = ((2*$r_nh)+$rnt)/3;
										echo round($nh);
										// $rnt = $nilai_arr[$sw->id]['nilai-tugas'];
									} else if ($r_nh==0) {
										$nh = $rnt;
										echo $nh;
									} else if ($rnt==0) {
										$nh = $r_nh;
										echo $nh;
									} else {
										echo $nh;
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
										$nutsp = $nilai_arr[$sw->id]['nilai-uts_p'];
										echo $nutsp; 	
									} else {
										echo $nutsp;
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
										$nutsk = $nilai_arr[$sw->id]['nilai-uts_k'];
										echo $nutsk; 	
									} else {
										echo $nutsk;
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
										$nuas = $nilai_arr[$sw->id]['nilai-uas'];
										echo $nuas; 	
									} else {
										echo $nuas;
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
										echo $nilai_arr[$sw->id]['nilai-kd1_ket'];
										$rnh_ket[] = $nilai_arr[$sw->id]['nilai-kd1_ket'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>
							  <td >
								<?php 

									if (!empty($nilai_arr[$sw->id]['nilai-kd2_ket'])) {
										echo $nilai_arr[$sw->id]['nilai-kd2_ket'];
										$rnh_ket[] = $nilai_arr[$sw->id]['nilai-kd2_ket'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>
							  <td >
								<?php 

									if (!empty($nilai_arr[$sw->id]['nilai-kd3_ket'])) {
										echo $nilai_arr[$sw->id]['nilai-kd3_ket'];
										$rnh_ket[] = $nilai_arr[$sw->id]['nilai-kd3_ket'];
									} else {
										echo "";
									}

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
							  <td >
								<?php 

									if (!empty($nilai_arr[$sw->id]['nilai-pengetahuan'])) {
										echo $nilai_arr[$sw->id]['nilai-pengetahuan'];
									} else {
										echo "";
									}

									// $cekNilai = FinalMark::model()->findByAttributes(array('user_id'=>$sw->id,'lesson_id'=>$model->id,'semester'=>$semester->value, 'tahun_ajaran'=>$tahun_ajaran->value, 'tipe'=>'pengetahuan'));
									// if(!empty($cekNilai)){
									// 	echo $cekNilai->nilai;
									// }
									
								?>
							 </td>
							  <td >
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