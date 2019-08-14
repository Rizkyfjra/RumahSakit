	<?php
/* @var $this QuizController */
/* @var $model Quiz */


if($model->lesson->moving_class == 1){
										$kelasnya = $model->lesson->name;
										$idkelasnya = $model->lesson->id;
										$path_nya = 'lesson/'.$idkelasnya;
									}else{
										$kelasnya = $model->lesson->name;
										$idkelasnya = $model->lesson->id;
										$path_nya = 'lesson/'.$idkelasnya;
									}


$this->breadcrumbs=array(
	$kelasnya=>array($path_nya."?type=ulangan"),
	$model->title,
);

// echo"<pre>";
// 	print_r($model);
// echo"</pre>";

/*$this->menu=array(
	array('label'=>'List Quiz', 'url'=>array('index')),
	array('label'=>'Create Quiz', 'url'=>array('create')),
	array('label'=>'Update Quiz', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Quiz', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Quiz', 'url'=>array('admin')),
);*/
/*echo "<pre>";
//print_r($questions);
//print_r($max);
//print_r($min);
print_r($student_quiz);
echo "</pre>";*/

$active = '';
$list='';
$mark='';
$bs='';
$inval=NULL;
if(isset($_GET['type'])){
	$active=$_GET['type'];
}

if(isset($_GET['teks'])){
	$active=2;
	$inval=$_GET['teks'];
}

if($active==1){
	$mark='active';
}elseif($active==2){
	$bs='active';
}else{
	$list='active';
}

?>
	<div class="container-full padt60">
			<row>			
				<div class="col-xs-4 pad5" >	
						<div>
							<img class="img-rounded" src="<?php echo Yii::app()->theme->baseUrl;?>/img/l1.png" style="width:80px;height:80px;background-color:#fff;padding:5px;">
						 </div>		
						  
				</div>
				<div class="col-xs-8 pad5" >						  
						 <div>
							<div><b><?php echo ucfirst($model->title); ?></b></div>
							<div>Kelas 
								<?php
									if($model->lesson->moving_class == 1){
										echo $model->lesson->grade->name;
									}else{
										echo $model->lesson->class->name;
									}
									
								?>
							</div>
							<div><button type="button" class="btn btn-primary bg-coklat" data-toggle="collapse" data-target="#demo"><span class="fa fa-expand"></span> Detail Ulangan</button></div>
						</div>					  
				</div>
			</row>
											
											
			<row>
					
				<div class="pad5 collapse" id="demo">
						<table class="table table-hover table-responsive">
							<tbody>
								<tr class="active">
									<th>Pelajaran</th>
									<td><?php echo $model->lesson->name;?></td>
								</tr>
								<tr class="active">
									<th>Waktu Pengerjaan</th>
									<td><?php echo $model->end_time;?> Menit</td>
								</tr>
								<tr class="active">
									<th>Total Pertanyaan</th>
									<td><?php echo $model->total_question;?></td>
								</tr>
								<tr class="active">
									<th>Acak Soal</th>
									<td>
										<?php
											if($model->random == 1){
												echo "Ya";
											}else{
												echo "Tidak";
											}
										?>
									</td>
								</tr>
								<tr class="active">
									<th>Percobaan Kuis</th>
									<td><?php echo $model->repeat_quiz;?> Kali</td>
								</tr>
								<tr class="active">
									<th>Status</th>
									<?php
										if($model->status == NULL){
											$status = 'Belum Ditampilkan';
										}elseif($model->status == 1){
											$status = 'Sudah Ditampilkan';
										}else{
											$status = 'Sudah Ditutup';
										}
									?>
									<td><?php echo $status;?></td>
								</tr>
								<?php if(!Yii::app()->user->YiiStudent){ ?>
									<tr class="active">
									<th>Kode Pembuka</th>
									<td><b><?php echo $model->passcode;?></b></td>
								</tr>
								<?php } ?>
							</tbody>
						</table>
						<?php if(!Yii::app()->user->YiiStudent){?>
							<?php if(!Yii::app()->user->YiiKepsek){ ?>
								<?php if(!Yii::app()->user->YiiWali){ ?>
									<?php echo CHtml::link('<span class="glyphicon glyphicon-edit marginb10"></span> Edit Ulangan', array('update','id'=>$model->id),array('class'=>'btn btn-primary'))?> 
									<?php echo CHtml::link('<i class="fa fa-files-o"></i> Salin Ulangan', array('copy','id'=>$model->id),array('class'=>'btn btn-default'))?> 
								<?php } ?>
							<?php } ?>
						<?php } ?>								
									
				</div>
			</div>
				
			</row>
			
			<row>
				<div class="col-xs-12 text-center padt10 bg-krem1" >
					<?php if(!Yii::app()->user->YiiStudent){ ?>
						<?php if(!Yii::app()->user->YiiKepsek){ ?>
						<?php if(!Yii::app()->user->YiiWali){ ?>
							<div class="pad5 inline" >
								<?php echo CHtml::link('<span style="font-size:1.1em;" class="fa fa-bank"></span>', array('/questions/create','quiz_id'=>$model->id), array('class'=>'btn btn-primary btn-lg bg-kuning bor text-center','style'=>'width:55px;height:55px;'))?>
								
								<div>Tambah<br/>Soal</div>
							</div>
						<?php } ?>	
						<!-- <div class="pad5 inline" >

							<a href="#about" class="btn btn-primary btn-lg bg-kuning bor text-center" style="width:55px;height:55px;"><span style="font-size:1.1em;" class="fa fa-bank"></span></a>
							<div>Bank<br/>Soal</div>
						</div> -->
						<div class="pad5 inline" >
							<?php echo CHtml::link('<span style="font-size:1.1em;" class="fa fa-eye"></span>', array('startQuiz','id'=>$model->id),array('class'=>'btn btn-primary btn-lg bg-kuning bor text-center','style'=>'width:55px;height:55px;'))?>
							
							<div>Preview<br/>Ulangan</div>
						</div>
						<div class="pad5 inline" >
							<?php if($model->status != 1){ ?>
								<?php echo CHtml::link('<span style="font-size:1.1em;" class="fa fa-upload"></span>', array('/quiz/displayQuiz', 'id'=>$model->id),array('class'=>'btn btn-primary btn-lg bg-kuning bor text-center','style'=>'width:55px;height:55px;'));?>
								<div>Tampilkan<br/>Ulangan</div>
							<?php }elseif($model->status != 2){ ?>
								<?php echo CHtml::link('<span style="font-size:1.1em;" class="fa fa-upload"></span>', array('/quiz/hideQuiz', 'id'=>$model->id),array('class'=>'btn btn-primary btn-lg bg-kuning bor text-center','style'=>'width:55px;height:55px;'));?>
								<div>Tutup<br/>Ulangan</div>
							<?php } ?>
							
						</div>
						
						<?php } ?>
					<?php } else {?>
						<?php
							$cekQuiz = StudentQuiz::model()->findByAttributes(array('quiz_id'=>$model->id,'student_id'=>Yii::app()->user->id)); 
						?>
						<?php if(!empty($cekQuiz)){ ?>
							<?php if($cekQuiz->attempt == $model->repeat_quiz){?>
								<div class="pad5 inline" >
									<?php echo CHtml::link('<span style="font-size:1.1em;" class="fa fa-check"></span>','#',array('class'=>'btn btn-primary btn-lg bg-kuning bor text-center','style'=>'width:55px;height:55px;'));?>
									<div>Sudah<br/>Mengerjakan</div>
									 <?php //echo $cekQuiz->attempt;?>.
								</div>
								
							<?php }else{?>
								<div class="pad5 inline" > 
									<?php if($model->status == 	1){ ?>
										<?php echo CHtml::link('<span style="font-size:1.1em;" class="fa fa-circle-o-notch"></span>', array('/quiz/startQuiz','id'=>$model->id,'sq'=>$cekQuiz->id),array('class'=>'btn btn-primary btn-lg bg-kuning bor text-center','style'=>'width:55px;height:55px;'));?>
									<div>Mulai<br/>
									Lagi
									</div>
									<?php } else{ ?>
										
									<?php } ?>
								</div>
							<?php } ?>
						<?php }else{ ?>
							<div class="pad5 inline" >
							<?php if($model->status == 1){ ?>
								<?php echo CHtml::link('<span style="font-size:1.1em;" class="fa fa-circle-o-notch"></span>', array('/quiz/startQuiz','id'=>$model->id),array('class'=>'btn btn-primary btn-lg bg-kuning bor text-center','style'=>'width:55px;height:55px;'));?>
								<div>Mulai<br/></div>
							<?php } ?>
							</div>				
						<?php } ?>
					<?php } ?>
						
				</div>
			</row>
			
			<row>
			<?php if(!Yii::app()->user->YiiStudent){ ?>
				<div class="col-xs-12 margint10" >
					<div role="tabpanel">

					  <!-- Nav tabs -->
					  <ul class="nav nav-tabs responsive" role="tablist">
					    <li role="presentation" class="<?php echo $list;?>"><a href="#daftar" aria-controls="home" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-book"></span>Soal</a></li>
					    <li role="presentation" class="<?php echo $bs;?>"><a href="#bank" aria-controls="profile" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-list-alt"></span>Bank Soal</a></li>
					    <li role="presentation" class="<?php echo $mark;?>"><a href="#nilai" aria-controls="profile" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-tasks"></span>Nilai</a></a></li>
					    <li role="presentation"><a href="#statistik" aria-controls="profile" role="tab" data-toggle="tab"><span class="glyphicon glyphicon-pencil"></span>Analisa</a></li>
					  </ul>

					  <!-- Tab panes -->
					  <div class="tab-content">
					    <div role="tabpanel" class="tab-pane <?php echo $list;?>" id="daftar">
					    	<?php if(!Yii::app()->user->YiiStudent){ ?>
								<?php if(Yii::app()->user->YiiKepsek){ ?>
									<h4><b>Daftar Siswa</b></h4>
									<table class="table table-bordered table-hover table-responsive">
										<tbody>
											<th>No</th>
											<th>Nama Siswa</th>
											<th>Status</th>
											<?php $siswa = User::model()->findAll(array('condition'=>'class_id = '.$model->lesson->class_id));?>
											<?php $urutan=1;?>
											<?php if(!empty($siswa)){ ?>
												<?php foreach ($siswa as $murid) { ?>
													<tr>
														<td><?php echo $urutan;?></td>
														<td><?php echo $murid->display_name;?></td>
														<td></td>
													</tr>
													<?php $urutan++;?>
												<?php } ?>
											<?php } ?>
										</tbody>
									</table>
								<?php }else{ ?>
								<h4><b>Daftar Pertanyaan Di Ulangan Ini</b></h4>
								<div class="table-responsive">
								<table class="table table-bordered table-hover">
									<tr class="danger">
									<th>No</th>
									<th>Judul Pertanyaan</th>
									<th>Pertanyaan</th>
									<th>Kunci Jawaban</th>
									<th>Tipe</th>
									<th></th>
									</tr>
									<?php $np=1;?>
									<?php if(!empty($model->question)){ ?>
									<?php $pertanyaan = explode(',', $model->question);?>
									<?php foreach ($pertanyaan as $key) { ?>
										<?php $detail = Questions::model()->findByPk($key);?>
										<tr class="info">
											<td><?php echo CHtml::link($np, array('/questions/view','id'=>$key,'quiz_id'=>$model->id));?></td>
											<td><?php echo CHtml::link($detail->title, array('/questions/view','id'=>$key,'quiz_id'=>$model->id));?></td>
											<td><?php echo CHtml::link($detail->text, array('/questions/view','id'=>$key,'quiz_id'=>$model->id));?></td>
											<td><?php echo $detail->key_answer;?></td>
											<td>
												<?php
													if($detail->type == 1){
														echo "Isian";
													}elseif ($detail->type == 2) {
														echo "Esai";
													}else{
														echo "Pilihan Ganda";
													} 
												?>
											</td>
											<td>
												<?php if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin){ ?>
												<?php echo CHtml::link('Hapus', array('deleteQuestion','quiz'=>$model->id,'question'=>$key), array('class'=>'btn btn-warning'));?>
												<?php } ?>
											</td>
										</tr>
										<?php $np++;?>
									<?php } ?>
									<?php } ?>
								</table>
								</div>
								<?php } ?>
							<?php } ?>
					    </div>
					    <div role="tabpanel" class="tab-pane <?php echo $bs;?>" id="bank">
					    	<h3>
					    		Judul
					    		<?php 
									$filter_url = Yii::app()->createUrl("/quiz/filterQuestion/".$model->id);
								?>
								
								<form action="<?php echo $filter_url;?>" method="get" role="search">
									<div class="form-group col-md-5">
										<input id="teks" type="text" name="teks" class="form-control" value="<?php echo $inval;?>">
									</div>
									<div class="form-group">
										<input type="submit" value="Cari" class="btn btn-primary">
									</div>

								</form>
					    	</h3>
					    	<h4><b>Pertanyaan 
					    		<?php if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin){ ?>
					    		<?php //if(!empty($tugas)){ 
									echo "<form method='POST' name='checkform' id='checkform' action='".Yii::app()->createUrl("questions/bulkSoal")."'>";
								?>
					    		<span class="pull-right"><input type="submit" value="Tambah" class="btn btn-success"></span>
								<?php } ?>
								</p></b>
							</h4>
							<p><?php //echo CHtml::link('Import Soal', array('/questions/bulkExcel','id'=>$model->id),array('class'=>'btn btn-info'))?></p>
							<br>	
									<div class="table-responsive">
									<table class="table table-hover table-bordered">
										<tr class="danger">
										<th>Judul</th>
										<th>Pertanyaan</th>
										<th>Pilihan Jawaban</th>
										<th>Kunci Jawaban</th>
										<?php if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin){ ?>
										<th class="text-center">
											<br>
												<input type="checkbox" id="selectAll">
										</th>
										<?php } ?>
										<!-- <th></th> -->
										</tr>
										<?php foreach ($questions as $value) { ?>
											<tr class="info">
												<td><?php echo CHtml::link($value->title, array('questions/view','id'=>$value->id));?></td>
												<td><?php echo $value->text;?></td>
												<?php
													if(!empty($value->choices)){ 
														$pil = json_decode($value->choices,true);
													}else{
														$pil = null;
													}
													$n = 'A';
												?>
												<td>
													<?php if($value->type==NULL){ ?>
														<?php if(!empty($pil)){ ?>
														<?php
															foreach ($pil as $val) {
															 	echo $n.". ".$val."<br>";
															 	$n++;
															 } 
														?>
														<?php } ?>
													<?php } ?>
												</td>
												<td><?php echo $value->key_answer;?></td>
												<?php if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin){ ?>
												<?php echo "<td><input type='checkbox' name='soal[]' value=$value->id class='soal'></td>"; ?>
												<?php } ?>
												<!-- <td><?php //echo CHtml::link("Tambah", array('quiz/addQuestion','id'=>$value->id,'idq'=>$model->id), array('class'=>'btn btn-success'))?></td> -->
											</tr>
										<?php } ?>
										<input type="hidden" value="<?php echo $model->id;?>" name="quiz">
									</table>
									</div>
									<div class="text-center">
									<?php
									  $this->widget('CLinkPager', array(
									                'pages'=>$pages,
									                'maxButtonCount'=>10,
									                ));
									?>
									</div>
									<?php echo "</form>";?>
					    </div>
					    <div role="tabpanel" class="tab-pane <?php echo $mark;?>" id="nilai">
					    	<?php if(!Yii::app()->user->YiiStudent){ ?>
								<div class="col-md-8">
									<h4><b>Daftar Siswa Yang Sudah Mengerjakan Kuis Ini</b><p></p></h4>
									<?php if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin){ ?>
									<p class="pull-right"><?php echo CHtml::link('Download Nilai', array('downloadNilai','id'=>$model->id), array('class'=>'btn btn-success'))?> <?php echo CHtml::link('Update Nilai Ulangan', array('updateNilai','id'=>$model->id), array('class'=>'btn btn-danger'))?></p>
									<?php  } ?>
									<div class="table-responsive">
									<table class="table table-hover table-bordered table-responsive">
										<tr class="danger">
										<th>No</th>
										<th>Nama Siswa</th>
										<th>Pelajaran</th>
										<th>Kelas</th>
										<!-- <th>Nilai Pilihan Ganda</th> -->
										<!-- <th>Nilai Esai</th> -->
										<th>Jawaban Benar</th>
										<th>Jawaban Salah</th>
										<th>Jawaban Kosong</th>
										<th>Nilai Total</th>
										<th>Download</th>
										<th>Aksi</th>
										</tr>
										<?php $no=1;?>
										<?php if(!empty($student_quiz)){ ?>
											<?php foreach ($student_quiz as $nk) { ?>
												<?php
													$benar=NULL;
											    	$salah=NULL;
											    	$kosong=NULL;
											    	$total_jawab=NULL;
											    	$raw=explode(',', $model->question);
											    	$soal_pg=0;
													foreach ($raw as $sl) {
														$cekSoal = Questions::model()->findByPk($sl);
														if(!empty($cekSoal)){
															if($cekSoal->type != 2){
																$soal_pg++;
															}
														}
													}
											    	$nilaiEsai=0;
											    	if(!empty($nk->student_answer)){
											    		$jawaban = json_decode($nk->student_answer,true);
											    		foreach ($jawaban as $k => $val) {
											    			$soal = Questions::model()->findByPk($k);
											    			if($soal->type != 2){
												    			if(strtolower($soal->key_answer) == strtolower($val)){
												    				$benar = $benar+1;
												    			}else{
												    				$salah = $salah+1;
												    			}
												    			//$soal_pg++;
												    		}	
											    		}
											    		//$nilaiPg=round(($benar/$soal_pg)*100*0.6);
											    		$nilaiPg=round(($benar/$soal_pg)*100);
											    	}
											    	
											    	$nilaiEsai = $nk->score - $nilaiPg;
												?>
												<tr class="info">
													<td><?php echo $no;?></td>
													<td><?php echo CHtml::link($nk->user->display_name, array('/studentQuiz/view','id'=>$nk->id));?></td>
													<td><?php echo $model->lesson->name?></td>
													<td><?php echo $nk->user->class->name;?></td>
													<!-- <td><?php //echo $nilaiPg;?></td> -->
													<!-- <td><?php //echo $nilaiEsai;?></td> -->
													<td><?php echo $nk->right_answer;?></td>
													<td><?php echo $nk->wrong_answer;?></td>
													<td><?php echo $nk->unanswered;?></td>
													<td><?php echo $nk->score;?></td>
													<td><?php echo CHtml::link("Download Jawaban", array('/studentQuiz/download','id'=>$nk->id));?></td>
													<td><?php echo CHtml::link("BATALKAN", array('/studentQuiz/delete','id'=>$nk->id), array('confirm' => 'Anda yakin akan membatalkan ujian siswa ini?'));?></td>
												</tr>
											<?php $no++; } ?>
										<?php  } ?>
									</table>
									</div>
								</div>
								<?php } ?>
					    </div>
					    <div role="tabpanel" class="tab-pane" id="statistik">
					    	<?php if(!Yii::app()->user->YiiStudent){ ?>
						    	<?php if(!empty($student_quiz)){ ?>
										<h4><b>Analisis Ulangan</b></h4>
										<table>
											<tr>
												<td>Nilai Tertinggi</td>
												<td>:</td>
												<td><strong><?php echo $max[0]->score;?></strong></td>
											</tr>
											<tr>
												<td>Nilai Terendah</td>
												<td>:</td>
												<td><strong><?php echo $min[0]->score;?></td>
											</tr>
											<tr>
												<td>Rata-Rata Nilai Kelas</td>
												<td>:</td>
												<td><strong><?php echo number_format($rata[0]->score,'2',',','.');?></strong></td>
											</tr>
										</table>
										
										<table class="table table-bordered table-hover table-responsive well">
											<tbody>
												<tr>
													<th class="info" rowspan="2">No</th>
													<th class="info" rowspan="2">Nama Siswa</th>
													<?php 
														if(!empty($model->question)){
															$pertanyaan = explode(',', $model->question);
															foreach ($pertanyaan as $qid) {
																$qval = Questions::model()->findByPk($qid);
																echo "<th class='info'>".$qval->title."</th>";
															}
													 	} 
													?>
													<th class="info" rowspan="2">Total Benar</th>
													<th class="info" rowspan="2">Total Salah</th>
													<th class="info" rowspan="2">Nilai</th>
												</tr>
												<tr>
													<?php
														if(!empty($model->question)){
															// $pertanyaan = explode(',', $model->question);
															// foreach ($pertanyaan as $qid) {
															// 	$qval = Questions::model()->findByPk($qid);
															// 	echo "<th></th>";
															// }
															echo "<th class='info' colspan=".$model->total_question."></th>";
													 	} 
													?>
												</tr>
												<?php if(!empty($student_quiz)){ ?>
													<?php
														$nomor = 1; 
														$persoalbenar = 0;
														$persoalsalah = 0;
														foreach ($student_quiz as $pelajar) {
													?>
														<tr>
															<td><?php echo $nomor;?></td>
															<td><?php echo CHtml::link($pelajar->user->display_name, array('/studentQuiz/view','id'=>$pelajar->id));?></td>

															<?php 
																$jawaban=json_decode($pelajar->student_answer,true);
																//print_r($jawaban);
																$right_answer = 0;
																$wrong_answer = 0;
																if(!empty($model->question)){
																	$pertanyaan = explode(',', $model->question);
																	foreach ($pertanyaan as $qid) {
																		$qval = Questions::model()->findByPk($qid);
																		if(!empty($jawaban[$qid]) && $jawaban[$qid] == $qval->key_answer){
																			//if($jawaban[$qid])
																			echo "<td><center><i class='fa fa-check btn btn-success'></i></center></td>";
																			//print_r($jawaban[$qid]);
																			$right_answer++;
																			$persoalbenar = $persoalbenar+1;
																		}else{
																			echo "<td><center><i class='fa fa-times btn btn-danger'></i></center></td>";
																			$wrong_answer++;
																			$persoalsalah = $persoalsalah+1;
																		}
																	}
															 	} 
															?>
															<td><b><?php echo $right_answer;?></b></td>
															<td><b><?php echo $wrong_answer;?></b></td>
															<td><b><?php echo $pelajar->score;?></b></td>
														</tr>		
													<?php
															$nomor++;	
														} 
													?>
													<tr>
														<th class="danger" colspan="2"><center>Benar/Salah Per Soal</center></th>
														<?php
															$pertanyaans = explode(',', $model->question);
															foreach ($pertanyaans as $qids) {
																$cekSl = Questions::model()->findByPk($qids);
																$benar_per_soal=0;
																$salah_per_soal=0;
																$jawab=NULL;
																foreach ($student_quiz as $jawabs) {
																 	$siswa_jawab = json_decode($jawabs->student_answer);	
																	if(!empty($siswa_jawab)){
																		foreach ($siswa_jawab as $jwbs => $isis) {
																			if($cekSl->id == $jwbs){
																				if($isis == $cekSl->key_answer){
																					$benar_per_soal=$benar_per_soal+1;
																				}else{
																					$salah_per_soal=$salah_per_soal+1;
																				}
																			}
																		}
																	}else{
																		$total_salah=$total_salah+1;
																	}
																}
																echo "<th><center><span class='label label-success'>".$benar_per_soal."</span> / <span class='label label-danger'>".$salah_per_soal."</span></center></th>";
															}
														?>
														<th class="danger" colspan="3"></th>		
													</tr>
												<?php } ?>
											</tbody>													
										</table>
									
								<?php } ?>
							<?php } ?>	
					    </div>
					  </div>

					</div>
					
				<div>
			<?php } ?>
			</row>				
		<div>
	
<?php $url_user = $this->createUrl('site/userStatus');?>

<script type="text/javascript">
	//localStorage.clear();
	console.log(localStorage);
	/*var url_user = "<?php echo $url_user;?>";
	var id_user = "<?php echo Yii::app()->user->id;?>";
	var id_quiz = "<?php echo $model->id;?>";
	$("#myWizard").on( "mouseenter", function() {
		$.post(url_user, {user_id:id_user, quiz_id:id_quiz,type:"aktif"})
		  .done(function( data ) {
		    console.log( "Data Loaded: " + data );
		  });
		    
	});*/
	 $('#selectAll').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.soal').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            $('.soal').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
    });
</script>