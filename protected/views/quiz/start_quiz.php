<?php
/* @var $this QuizController */
/* @var $model Quiz */

$this->breadcrumbs=array(
	'Ulangan'=>array('index'),
	$model->title,
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/flipclock.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/flipclock.css');

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/sisyphus.min.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/jquery.uilock.min.js',CClientScript::POS_HEAD);

$doc_types = array('doc','docx','pdf','xls','xlsx','ppt','pptx');
$vid_types = array('swf','mp4','MP4','avi','mkv','flv');
$img_types = array('jpg','png','gif');
$audio_types = array('aac','mp3','ogg');

/*Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/tinymce/tinymce.min.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image',CClientScript::POS_HEAD);
*/
/*$this->menu=array(
	array('label'=>'List Quiz', 'url'=>array('index')),
	array('label'=>'Create Quiz', 'url'=>array('create')),
	array('label'=>'Update Quiz', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Quiz', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Quiz', 'url'=>array('admin')),
);*/
//$total = count($total_question);
$cekQuiz=StudentQuiz::model()->findByAttributes(array('student_id'=>Yii::app()->user->id,'quiz_id'=>$model->id));
$total_pertanyaan = count($pertanyaan);

$passcode = NULL;
if(!empty($model->passcode)){
	$passcode = $model->passcode;
}

?>
<style type="text/css">
	#overlay {
	    background-color: rgba(0, 0, 0, 0.8);
	    z-index: 999;
	    position: absolute;
	    left: 0;
	    top: 0;
	    width: 100%;
	    height: 250%;
	    display: none;
	}
	#modalCentered {
	 	margin: auto;
	 	position: absolute;
	  	top: 0; left: 0; bottom: 0; right: 0;
	}

	.btn span.glyphicon {    			
		opacity: 0;				
	}
	.btn.active span.glyphicon {				
		opacity: 1;				
	}
</style>
<div id="overlay">
	<div id="modalCentered" class="modal" tabindex="-1" role="dialog" aria-labelledby="..." style="position:relative">
  		 <div class="modal-dialog modal-xs">
		    <div class="modal-content">
		    	<div class="panel panel-danger">
		    		<div class="panel-heading"></div>
		    		<div class="panel-body">
		    			<label>Masukkan Kode Untuk Membuka</label>	
		      			<input type="password" class="form-control" id="kode">
		    		</div>
		    	</div>
		    </div>
		  </div>
	</div>
</div>
<div id="notif-flash">
</div>
<div class="container" id="myWizard">	
<?php if(Yii::app()->user->YiiStudent && !empty($cekQuiz)){ ?>
	<?php if($cekQuiz->attempt < $model->repeat_quiz){ ?>
		<h1 class="text-right">Kuis <?php echo $model->title; ?></h1>
		<h4 class="text-right">Waktu Pengerjaan : <span id="waktu"><?php echo $model->end_time;?></span> Menit</center></h4>
		<button id="check" value="cek" class="btn btn-primary">Cek Koneksi dan Cek Total Jawaban Terisi</button>
		<div id="wkt" class="clock align-right" style="margin:2em;"></div>
		<div class="message"></div>
		<hr>
		<div class="container-full padt60">
													
			<row>
				<div class="col-xs-12 text-center padt10 bg-krem1" >				
					<div class="progress">
					     <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="<?php echo $total_pertanyaan;?>" style="width: 20%;">
					       Soal 1 dari <?php echo $model->total_question;?> Soal
					     </div>
					</div>		
				</div>
			</row>
			
			<row>
				<div class="pre-scrollable col-xs-2 margint10 text-center bg-krem1 pad5" >
					<div class="bold">Soal</div>
						<ul class="nav nav-pills">
				        	<?php $urutan=1;?>
				        	<?php 
		        			// for ($i=1; $i <= $model->total_question; $i++) { 
				        	$i = 1;
				        	foreach ($questions as $key){

				        		?> 
				        		<?php if($i == 1){ ?>
				        			<li class="list-soal active"><a href="#step<?php echo $i;?>" id="coba<?php echo $i?>" data-local="<?php echo $key->id; ?>" class="btn btn-soal" data-toggle="tab" data-step="<?php echo $i;?>"><?php echo $i;?></a></li>
				        		<?php }else{ ?>
				        			<li class="list-soal"><a href="#step<?php echo $i;?>"  id="coba<?php echo $i?>" data-local="<?php echo $key->id; ?>" class="btn btn-soal" data-toggle="tab" data-step="<?php echo $i;?>"><?php echo $i;?></a></li> 
				        		<?php } ?>
				        	<?php $i++; } ?>
				        </ul>
				</div>
				
				<div class="col-xs-10 margint10" >
					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'questions',
						'action'=>Yii::app()->createUrl('quiz/finishQuiz'),
						// Please note: When you enable ajax validation, make sure the corresponding
						// controller action is handling ajax validation correctly.
						// There is a call to performAjaxValidation() commented in generated controller code.
						// See class documentation of CActiveForm for details on this.
						'enableAjaxValidation'=>false,
						 'htmlOptions'=>array(
                               'onsubmit'=>"return false;"/* Disable normal form submit */
                               
                             ),
					)); ?>
					<?php echo $form->hiddenField($sq, 'quiz_id', array('value'=>$model->id));?>
					<?php echo $form->hiddenField($sq, 'student_id', array('value'=>Yii::app()->user->id));?>
					<div class="tab-content">
					<?php $urut=1;?>
					<div>
						<input type="hidden" name="qid" value="<?php echo $model->id;?>">
						<input id="dude" name="dude" value="0" type="hidden">
						<input type="hidden" name="indikasi" value="0" id="indikator">
					</div>
					<?php //shuffle($pertanyaan);?>
					<?php foreach ($questions as $key ) { ?>
				<?php if(empty($key->id_lama)){ ?>
					<?php $path_image=Clases::model()->path_image($key->id);?>
				<?php }else{ ?>
					<?php $path_image=Clases::model()->path_image($key->id_lama);?>
				<?php } ?>
				<?php if($model->total_question > 1){ ?>
					<?php if($urut == 1){ ?>
				        <div class="tab-pane fade in active" id="step<?php echo $urut;?>">
				         
					        <div class="panel panel-default">
					          	<div class="panel-heading bold">Soal <?php echo $urut;?></div>
					           	<div class="panel-body">
							           	<div style="color:black">
								            <?php echo $key->text;?>
								            <br>
								            <?php if(!empty($key->file)){ ?>
								            	<?php $ext = pathinfo($key->file, PATHINFO_EXTENSION);?>
								            	<?php if(empty($key->id_lama)){ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/'.$path_image.$key->file;?>
												<?php }else{ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$key->file;?>
												<?php } ?>
												<?php if(in_array($ext, $vid_types)){ ?>
													<div class="img-responsive">
														<center>
															<?php
																$this->widget('ext.jwplayer.Jwplayer',array(
																    'width'=>250,
																    'height'=>180,
																    'file'=>$img_url, // the file of the player, if null we use demo file of jwplayer
																    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
																    'options'=>array(
																        'controlbar'=>'bottom'
																    )
																));
															?>
														</center>
													</div>
												<?php }elseif(in_array($ext, $img_types)){?>
													<img src="<?php echo $img_url;?>" class="img-responsive">
												<?php }elseif(in_array($ext, $audio_types)){ ?>
													<div class="img-responsive">
														<audio controls="controls">
														
																<source src="<?php echo $img_url;?>" type="audio/mpeg">
															
														</audio>
													</div>
												<?php } ?>
											<?php } ?>
							            </div>
							            <div>
								            <?php if($key->type == NULL){ ?>
									            <?php $pil = json_decode($key->choices,true);?>
									            <?php 
									            	if(!empty($key->choices_files)){
									            		$gambar = json_decode($key->choices_files,true);
									            	}else{
									            		$gambar = NULL;
									            	}
									            	
									            ?>	
												
								            	<?php 
								            	if($model->random_opt == 1){
								            	shuffle($pil);
								                }
								            	foreach ($pil as $k => $value) { ?>
								            		<label class="btn btn-info btn-opsi">
								            			<input type="radio" name="pil[<?php echo $key->id;?>]" value='<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');?>'> <span><?php echo $value;?></span>
									            		<?php if(!empty($gambar[$k])){ ?>
									            			<?php if(empty($key->id_lama)){?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php }else{ ?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php } ?>
															<img src="<?php echo $img_pil;?>" class="img-responsive">		
														<?php } ?>
													</label>
								            		
													<br>
												<?php } ?>
												
											<?php }elseif($key->type == 1){ ?>
												<input class="form-control  input-lg" name="pil[<?php echo $key->id;?>]">
											<?php }else{?> 
												<textarea class="form-control" name="pil[<?php echo $key->id;?>]"></textarea>
											<?php } ?>
										</div>
									</div> 
								 
					        </div>

				         	<a class="btn btn-default btn-lg next" href="#">Selanjutnya</a>
				        </div>
			        <?php }elseif($urut == $model->total_question){ ?>
			        	<div class="tab-pane fade" id="step<?php echo $urut;?>">
				         
					        <div class="panel panel-default">
					          	<div class="panel-heading bold">Soal <?php echo $urut;?></div>
					           	<div class="panel-body">
							           	<div style="color:black">
								            <?php echo $key->text;?>
								            <br>
								            <?php if(!empty($key->file)){ ?>
								            	<?php $ext = pathinfo($key->file, PATHINFO_EXTENSION);?>
												<?php if(empty($key->id_lama)){ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/'.$path_image.$key->file;?>
												<?php }else{ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$key->file;?>
												<?php } ?>
												<?php if(in_array($ext, $vid_types)){ ?>
													<div class="img-responsive">
														<center>
															<?php
																$this->widget('ext.jwplayer.Jwplayer',array(
																    'width'=>250,
																    'height'=>180,
																    'file'=>$img_url, // the file of the player, if null we use demo file of jwplayer
																    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
																    'options'=>array(
																        'controlbar'=>'bottom'
																    )
																));
															?>
														</center>
													</div>
												<?php }elseif(in_array($ext, $img_types)){?>
													<img src="<?php echo $img_url;?>" class="img-responsive">
												<?php }elseif(in_array($ext, $audio_types)){ ?>
													<div class="img-responsive">
														<audio controls="controls">
															
																<source src="<?php echo $img_url;?>" type="audio/mpeg">
																
														</audio>
													</div>
												<?php } ?>
											<?php } ?>
							            </div>
							            <div>
								            <?php if($key->type == NULL){ ?>
									            <?php $pil = json_decode($key->choices,true);?>
									            <?php 
									            	if(!empty($key->choices_files)){
									            		$gambar = json_decode($key->choices_files,true);
									            	}else{
									            		$gambar = NULL;
									            	}
									            	
									            ?>	
												
								            	<?php 
								            	if($model->random_opt == 1){
								            	shuffle($pil);
								                }
								            	foreach ($pil as $k => $value) { ?>
								            		<label class="btn btn-info btn-opsi">
								            			<input type="radio" name="pil[<?php echo $key->id;?>]" value='<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');?>'> <span><?php echo $value;?></span>
									            		<?php if(!empty($gambar[$k])){ ?>
															<?php if(empty($key->id_lama)){?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php }else{ ?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php } ?>
															<img src="<?php echo $img_pil;?>" class="img-responsive">		
														<?php } ?>
													</label>
								            		
													<br>
												<?php } ?>
												
											<?php }elseif($key->type == 1){ ?>
												<input class="form-control  input-lg" name="pil[<?php echo $key->id;?>]">
											<?php }else{?> 
												<textarea class="form-control" name="pil[<?php echo $key->id;?>]"></textarea>
											<?php } ?>
										</div>
									</div> 
								 
					        </div>

				         	<a class="btn btn-default btn-lg prev" href="#">Kembali</a> <?php echo CHtml::submitButton($sq->isNewRecord ? 'Selesai' : 'Selesai',array('onclick'=>'myconfirm()','id'=>'finish','class'=>'btn btn-success btn-lg')); ?>
				        </div>
			        <?php }else{ ?>
			        	<div class="tab-pane fade" id="step<?php echo $urut;?>">
				         
					        <div class="panel panel-default">
					          	<div class="panel-heading bold">Soal <?php echo $urut;?></div>
					           	<div class="panel-body">
							           	<div style="color:black">
								            <?php echo $key->text;?>
								            <br>
								            <?php if(!empty($key->file)){ ?>
								            	<?php $ext = pathinfo($key->file, PATHINFO_EXTENSION);?>
												<?php if(empty($key->id_lama)){ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/'.$path_image.$key->file;?>
												<?php }else{ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$key->file;?>
												<?php } ?>
												<?php if(in_array($ext, $vid_types)){ ?>
													<div class="img-responsive">
														<center>
															<?php
																$this->widget('ext.jwplayer.Jwplayer',array(
																    'width'=>250,
																    'height'=>180,
																    'file'=>$img_url, // the file of the player, if null we use demo file of jwplayer
																    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
																    'options'=>array(
																        'controlbar'=>'bottom'
																    )
																));
															?>
														</center>
													</div>
												<?php }elseif(in_array($ext, $img_types)){?>
													<img src="<?php echo $img_url;?>" class="img-responsive">
												<?php }elseif(in_array($ext, $audio_types)){ ?>
													<div class="img-responsive">
														<audio controls="controls">
															
																<source src="<?php echo $img_url;?>" type="audio/mpeg">
															
														</audio>
													</div>
												<?php } ?>
											<?php } ?>
							            </div>
							            <div>
								            <?php if($key->type == NULL){ ?>
									            <?php $pil = json_decode($key->choices,true);?>
									            <?php 
									            	if(!empty($key->choices_files)){
									            		$gambar = json_decode($key->choices_files,true);
									            	}else{
									            		$gambar = NULL;
									            	}
									            	
									            ?>	
												
								            	<?php 
								            	if($model->random_opt == 1){
								            	shuffle($pil);
								                }
								            	foreach ($pil as $k => $value) { ?>
								            		<label class="btn btn-info btn-opsi">
								            			<input type="radio" name="pil[<?php echo $key->id;?>]" value='<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');?>'> <span><?php echo $value;?></span>
									            		<?php if(!empty($gambar[$k])){ ?>
															<?php if(empty($key->id_lama)){?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php }else{ ?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php } ?>
															<img src="<?php echo $img_pil;?>" class="img-responsive">		
														<?php } ?>
													</label>
								            		
													<br>
												<?php } ?>
												
											<?php }elseif($key->type == 1){ ?>
												<input class="form-control  input-lg" name="pil[<?php echo $key->id;?>]">
											<?php }else{?> 
												<textarea class="form-control" name="pil[<?php echo $key->id;?>]"></textarea>
											<?php } ?>
										</div>
									</div> 
								 
					        </div>

				         	<a class="btn btn-default btn-lg prev" href="#">Kembali</a> <a class="btn btn-default btn-lg next" href="#">Selanjutnya</a>
				        </div> 
			        <?php } ?>
			    <?php } else{ ?>
			    	<div class="tab-pane fade in active" id="step<?php echo $urut;?>">
				         
					        <div class="panel panel-default">
					          	<div class="panel-heading bold">Soal <?php echo $urut;?></div>
					           	<div class="panel-body">
							           	<div style="color:black">
								            <?php echo $key->text;?>
								            <br>
								            <?php if(!empty($key->file)){ ?>
								            	<?php $ext = pathinfo($key->file, PATHINFO_EXTENSION);?>
												<?php if(empty($key->id_lama)){ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/'.$path_image.$key->file;?>
												<?php }else{ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$key->file;?>
												<?php } ?>
												<?php if(in_array($ext, $vid_types)){ ?>
													<div class="img-responsive">
														<center>
															<?php
																$this->widget('ext.jwplayer.Jwplayer',array(
																    'width'=>250,
																    'height'=>180,
																    'file'=>$img_url, // the file of the player, if null we use demo file of jwplayer
																    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
																    'options'=>array(
																        'controlbar'=>'bottom'
																    )
																));
															?>
														</center>
													</div>
												<?php }elseif(in_array($ext, $img_types)){?>
													<img src="<?php echo $img_url;?>" class="img-responsive">
												<?php }else{ ?>
													<div class="img-responsive">
														<audio controls="controls">
															<center>
																<source src="<?php echo $img_url;?>" type="audio/mpeg">
															</center>	
														</audio>
													</div>
												<?php } ?>
											<?php } ?>
							            </div>
							            <div>
								            <?php if($key->type == NULL){ ?>
									            <?php $pil = json_decode($key->choices,true);?>
									            <?php 
									            	if(!empty($key->choices_files)){
									            		$gambar = json_decode($key->choices_files,true);
									            	}else{
									            		$gambar = NULL;
									            	}
									            	
									            ?>	
												
								            	<?php 
								            	if($model->random_opt == 1){
								            	shuffle($pil);
								                }
								            	foreach ($pil as $k => $value) { ?>
								            		<label class="btn btn-info btn-opsi">
								            			<input type="radio" name="pil[<?php echo $key->id;?>]" value='<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');?>'> <span><?php echo $value;?></span>
									            		<?php if(!empty($gambar[$k])){ ?>
															<?php if(empty($key->id_lama)){?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php }else{ ?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php } ?>
															<img src="<?php echo $img_pil;?>" class="img-responsive">		
														<?php } ?>
													</label>
								            		
													<br>
												<?php } ?>
												
											<?php }elseif($key->type == 1){ ?>
												<input class="form-control  input-lg" name="pil[<?php echo $key->id;?>]">
											<?php }else{?> 
												<textarea class="form-control" name="pil[<?php echo $key->id;?>]"></textarea>
											<?php } ?>
										</div>
									</div> 
								 
					        </div>

				         	<?php echo CHtml::submitButton($sq->isNewRecord ? 'Selesai' : 'Selesai',array('onclick'=>'myconfirm()','id'=>'finish','class'=>'btn btn-success btn-lg')); ?>
				        </div>
			    <?php } ?>
			  <?php $urut++;?>    
			  
			
			<?php } ?>
					</div>
					
					<?php $this->endWidget(); ?>		
					
				<div>
			</row>
			
		<div>
	<?php }else{ ?> 
		<p>Anda Sudah Mengerjakan Kuis Ini</p>
	<?php } ?>
	
<?php }else{ ?>

<h1 class="text-right">Kuis <?php echo $model->title; ?></h1>
<h4 class="text-right">Waktu Pengerjaan : <span id="waktu"><?php echo $model->end_time;?></span> Menit</center></h4>
<button id="check" value="cek" class="btn btn-primary">Cek Koneksi dan Cek Total Jawaban Terisi</button>
<div id="wkt" class="clock align-right" style="margin:2em;"></div>
<div class="message"></div>
<hr>
<div class="container-full padt60">
											
	<row>
		<div class="col-xs-12 text-center padt10 bg-krem1" >				
			<div class="progress">
			     <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="<?php echo $total_pertanyaan;?>" style="width: 20%;">
			       Soal 1 dari <?php echo $model->total_question;?> Soal
			     </div>
			</div>		
		</div>
	</row>
	
	<row>
		<div class="pre-scrollable col-xs-2 margint10 text-center bg-krem1 pad5" >
			<div class="bold">Soal</div>
				<ul class="nav nav-pills">
		        	<?php $urutan=1;?>
		        	<?php 
		        	// for ($i=1; $i <= $model->total_question; $i++) { 
		        	$i = 1;
		        	foreach ($questions as $key){

		        		?> 
		        		<?php if($i == 1){ ?>
		        			<li class="list-soal active"><a href="#step<?php echo $i;?>" id="coba<?php echo $i?>" data-local="<?php echo $key->id; ?>" class="btn btn-soal" data-toggle="tab" data-step="<?php echo $i;?>"><?php echo $i;?></a></li>
		        		<?php }else{ ?>
		        			<li class="list-soal"><a href="#step<?php echo $i;?>"  id="coba<?php echo $i?>" data-local="<?php echo $key->id; ?>" class="btn btn-soal" data-toggle="tab" data-step="<?php echo $i;?>"><?php echo $i;?></a></li> 
		        		<?php } ?>
		        	<?php $i++; } ?>
		        </ul>
		</div>
		
		<div class="col-xs-10 margint10" >
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'questions',
				'action'=>Yii::app()->createUrl('quiz/submitQuiz'),
				// Please note: When you enable ajax validation, make sure the corresponding
				// controller action is handling ajax validation correctly.
				// There is a call to performAjaxValidation() commented in generated controller code.
				// See class documentation of CActiveForm for details on this.
				'enableAjaxValidation'=>false,
				 'htmlOptions'=>array(
                               'onsubmit'=>"return false;"/* Disable normal form submit */
                             ),
			)); ?>
			<?php echo $form->hiddenField($sq, 'quiz_id', array('value'=>$model->id));?>
			<?php echo $form->hiddenField($sq, 'student_id', array('value'=>Yii::app()->user->id));?>
			<div class="tab-content">
			<?php $urut=1;?>
			<div>
				<input type="hidden" name="qid" value="<?php echo $model->id;?>">
				<input id="dude" name="dude" value="0" type="hidden">
				<input type="hidden" name="indikasi" value="0" id="indikator">
			</div>
			<?php //shuffle($pertanyaan);?>
			<?php 
			// echo "<pre>";
			// print_r($questions);
			// echo "</pre>";
			foreach ($questions as $key ) { ?>
				<?php if(empty($key->id_lama)){ ?>
					<?php $path_image=Clases::model()->path_image($key->id);?>
				<?php }else{ ?>
					<?php $path_image=Clases::model()->path_image($key->id_lama);?>
				<?php } ?>
				
				<?php if($model->total_question > 1){ ?>
					<?php if($urut == 1){ ?>
				        <div class="tab-pane fade in active" id="step<?php echo $urut;?>">
				         
					        <div class="panel panel-default">
					          	<div class="panel-heading bold">Soal <?php echo $urut;?></div>
					           	<div class="panel-body">
							           	<div style="color:black">
								            <?php echo $key->text;?>
								            <br>
								            <?php if(!empty($key->file)){ ?>
								            	<?php $ext = pathinfo($key->file, PATHINFO_EXTENSION);?>
								            	<?php if(empty($key->id_lama)){ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/'.$path_image.$key->file;?>
												<?php }else{ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$key->file;?>
												<?php } ?>
												<?php if(in_array($ext, $vid_types)){ ?>
													<div class="img-responsive">
														<center>
															<?php
																$this->widget('ext.jwplayer.Jwplayer',array(
																    'width'=>250,
																    'height'=>180,
																    'file'=>$img_url, // the file of the player, if null we use demo file of jwplayer
																    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
																    'options'=>array(
																        'controlbar'=>'bottom'
																    )
																));
															?>
														</center>
													</div>
												<?php }elseif(in_array($ext, $img_types)){?>
													<img src="<?php echo $img_url;?>" class="img-responsive">
												<?php }elseif(in_array($ext, $audio_types)){ ?>
													<div class="img-responsive">
														<audio controls="controls">
																<source src="<?php echo $img_url;?>" type="audio/mpeg">
															
														</audio>
													</div>
												<?php } ?>
											<?php } ?>
							            </div>
							            <div>
								            <?php if($key->type == NULL){ ?>
									            <?php $pil = json_decode($key->choices,true);?>
									            <?php 
									            	if(!empty($key->choices_files)){
									            		$gambar = json_decode($key->choices_files,true);
									            	}else{
									            		$gambar = NULL;
									            	}
									            	
									            ?>	
												
								            	<?php 
								            	if($model->random_opt == 1){
								            	shuffle($pil);
								                }
								            	foreach ($pil as $k => $value) { ?>
								            		<label class="btn btn-info btn-opsi">
								            			<input type="radio" name="pil[<?php echo $key->id;?>]" value='<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');?>'> <span><?php echo $value;?></span>
									            		<?php if(!empty($gambar[$k])){ ?>
									            			<?php if(empty($key->id_lama)){?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php }else{ ?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php } ?>
															<img src="<?php echo $img_pil;?>" class="img-responsive">		
														<?php } ?>
													</label>
								            		
													<br>
												<?php } ?>
												
											<?php }elseif($key->type == 1){ ?>
												<input class="form-control  input-lg" name="pil[<?php echo $key->id;?>]">
											<?php }else{?> 
												<textarea class="form-control" name="pil[<?php echo $key->id;?>]"></textarea>
											<?php } ?>
										</div>
									</div> 
								 
					        </div>

				         	<a class="btn btn-default btn-lg next" href="#">Selanjutnya</a>
				        </div>
			        <?php }elseif($urut == $model->total_question){ ?>
			        	<div class="tab-pane fade" id="step<?php echo $urut;?>">
				         
					        <div class="panel panel-default">
					          	<div class="panel-heading bold">Soal <?php echo $urut;?></div>
					           	<div class="panel-body">
							           	<div style="color:black">
								            <?php echo $key->text;?>
								            <br>
								            <?php if(!empty($key->file)){ ?>
								            	<?php $ext = pathinfo($key->file, PATHINFO_EXTENSION);?>
												<?php if(empty($key->id_lama)){ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/'.$path_image.$key->file;?>
												<?php }else{ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$key->file;?>
												<?php } ?>
												<?php if(in_array($ext, $vid_types)){ ?>
													<div class="img-responsive">
														<center>
															<?php
																$this->widget('ext.jwplayer.Jwplayer',array(
																    'width'=>250,
																    'height'=>180,
																    'file'=>$img_url, // the file of the player, if null we use demo file of jwplayer
																    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
																    'options'=>array(
																        'controlbar'=>'bottom'
																    )
																));
															?>
														</center>
													</div>
												<?php }elseif(in_array($ext, $img_types)){?>
													<img src="<?php echo $img_url;?>" class="img-responsive">
												<?php }elseif(in_array($ext, $audio_types)){ ?>
													<div class="img-responsive">
														<audio controls="controls">
															
																<source src="<?php echo $img_url;?>" type="audio/mpeg">
															
														</audio>
													</div>
												<?php } ?>
											<?php } ?>
							            </div>
							            <div>
								            <?php if($key->type == NULL){ ?>
									            <?php $pil = json_decode($key->choices,true);?>
									            <?php 
									            	if(!empty($key->choices_files)){
									            		$gambar = json_decode($key->choices_files,true);
									            	}else{
									            		$gambar = NULL;
									            	}
									            	
									            ?>	
												
								            	<?php 
								            	if($model->random_opt == 1){
								            	shuffle($pil);
								                }
								            	foreach ($pil as $k => $value) { ?>
								            		<label class="btn btn-info btn-opsi">
								            			<input type="radio" name="pil[<?php echo $key->id;?>]" value='<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');?>'> <span><?php echo $value;?></span>
									            		<?php if(!empty($gambar[$k])){ ?>
															<?php if(empty($key->id_lama)){?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php }else{ ?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php } ?>
															<img src="<?php echo $img_pil;?>" class="img-responsive">		
														<?php } ?>
													</label>
								            		
													<br>
												<?php } ?>
												
											<?php }elseif($key->type == 1){ ?>
												<input class="form-control  input-lg" name="pil[<?php echo $key->id;?>]">
											<?php }else{?> 
												<textarea class="form-control" name="pil[<?php echo $key->id;?>]"></textarea>
											<?php } ?>
										</div>
									</div> 
								 
					        </div>

				         	<a class="btn btn-default btn-lg prev" href="#">Kembali</a> <?php echo CHtml::submitButton($sq->isNewRecord ? 'Selesai' : 'Selesai',array('onclick'=>'myconfirm()','id'=>'finish','class'=>'btn btn-success btn-lg')); ?>
				        </div>
			        <?php }else{ ?>
			        	<div class="tab-pane fade" id="step<?php echo $urut;?>">
				         
					        <div class="panel panel-default">
					          	<div class="panel-heading bold">Soal <?php echo $urut;?></div>
					           	<div class="panel-body">
							           	<div style="color:black">
								            <?php echo $key->text;?>
								            <br>
								            <?php if(!empty($key->file)){ ?>
								            	<?php $ext = pathinfo($key->file, PATHINFO_EXTENSION);?>
												<?php if(empty($key->id_lama)){ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/'.$path_image.$key->file;?>
												<?php }else{ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$key->file;?>
												<?php } ?>
												<?php if(in_array($ext, $vid_types)){ ?>
													<div class="img-responsive">
														<center>
															<?php
																$this->widget('ext.jwplayer.Jwplayer',array(
																    'width'=>250,
																    'height'=>180,
																    'file'=>$img_url, // the file of the player, if null we use demo file of jwplayer
																    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
																    'options'=>array(
																        'controlbar'=>'bottom'
																    )
																));
															?>
														</center>
													</div>
												<?php }elseif(in_array($ext, $img_types)){?>
													<img src="<?php echo $img_url;?>" class="img-responsive">
												<?php }elseif(in_array($ext, $audio_types)){ ?>
													<div class="img-responsive">
														<audio controls="controls">
															
																<source src="<?php echo $img_url;?>" type="audio/mpeg">
																
														</audio>
													</div>
												<?php } ?>
											<?php } ?>
							            </div>
							            <div>
								            <?php if($key->type == NULL){ ?>
									            <?php $pil = json_decode($key->choices,true);?>
									            <?php 
									            	if(!empty($key->choices_files)){
									            		$gambar = json_decode($key->choices_files,true);
									            	}else{
									            		$gambar = NULL;
									            	}
									            	
									            ?>	
												
								            	<?php 
								            	if($model->random_opt == 1){
								            	shuffle($pil);
								                }
								            	foreach ($pil as $k => $value) { ?>
								            		<label class="btn btn-info btn-opsi">
								            			<input type="radio" name="pil[<?php echo $key->id;?>]" value='<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');?>'> <span><?php echo $value;?></span>
									            		<?php if(!empty($gambar[$k])){ ?>
															<?php if(empty($key->id_lama)){?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php }else{ ?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php } ?>
															<img src="<?php echo $img_pil;?>" class="img-responsive">		
														<?php } ?>
													</label>
								            		
													<br>
												<?php } ?>
												
											<?php }elseif($key->type == 1){ ?>
												<input class="form-control  input-lg" name="pil[<?php echo $key->id;?>]">
											<?php }else{?> 
												<textarea class="form-control" name="pil[<?php echo $key->id;?>]"></textarea>
											<?php } ?>
										</div>
									</div> 
								 
					        </div>

				         	<a class="btn btn-default btn-lg prev" href="#">Kembali</a> <a class="btn btn-default btn-lg next" href="#">Selanjutnya</a>
				        </div> 
			        <?php } ?>
			    <?php } else{ ?>
			    	<div class="tab-pane fade in active" id="step<?php echo $urut;?>">
				         
					        <div class="panel panel-default">
					          	<div class="panel-heading bold">Soal <?php echo $urut;?></div>
					           	<div class="panel-body">
							           	<div style="color:black">
								            <?php echo $key->text;?>
								            <br>
								            <?php if(!empty($key->file)){ ?>
								            	<?php $ext = pathinfo($key->file, PATHINFO_EXTENSION);?>
												<?php if(empty($key->id_lama)){ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/'.$path_image.$key->file;?>
												<?php }else{ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$key->file;?>
												<?php } ?>
												<?php if(in_array($ext, $vid_types)){ ?>
													<div class="img-responsive">
														<center>
															<?php
																$this->widget('ext.jwplayer.Jwplayer',array(
																    'width'=>250,
																    'height'=>180,
																    'file'=>$img_url, // the file of the player, if null we use demo file of jwplayer
																    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
																    'options'=>array(
																        'controlbar'=>'bottom'
																    )
																));
															?>
														</center>
													</div>
												<?php }elseif(in_array($ext, $img_types)){?>
													<img src="<?php echo $img_url;?>" class="img-responsive">
												<?php }elseif(in_array($ext, $audio_types)){ ?>
													<div class="img-responsive">
														<audio controls="controls">
															
																<source src="<?php echo $img_url;?>" type="audio/mpeg">
																
														</audio>
													</div>
												<?php } ?>
											<?php } ?>
							            </div>
							            <div>
								            <?php if($key->type == NULL){ ?>
									            <?php $pil = json_decode($key->choices,true);?>
									            <?php 
									            	if(!empty($key->choices_files)){
									            		$gambar = json_decode($key->choices_files,true);
									            	}else{
									            		$gambar = NULL;
									            	}
									            	
									            ?>	
												
								            	<?php 
								            	if($model->random_opt == 1){
								            	shuffle($pil);
								                }
								            	foreach ($pil as $k => $value) { ?>
								            		<label class="btn btn-info btn-opsi">
								            			<input type="radio" name="pil[<?php echo $key->id;?>]" value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');?>"> <span><?php echo $value;?></span>
									            		<?php if(!empty($gambar[$k])){ ?>
															<?php if(empty($key->id_lama)){?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php }else{ ?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php } ?>
															<img src="<?php echo $img_pil;?>" class="img-responsive">		
														<?php } ?>
													</label>
								            		
													<br>
												<?php } ?>
												
											<?php }elseif($key->type == 1){ ?>
												<input class="form-control  input-lg" name="pil[<?php echo $key->id;?>]">
											<?php }else{?> 
												<textarea class="form-control" name="pil[<?php echo $key->id;?>]"></textarea>
											<?php } ?>
										</div>
									</div> 
								 
					        </div>

				         	<?php echo CHtml::submitButton($sq->isNewRecord ? 'Selesai' : 'Selesai',array('id'=>'finish','class'=>'btn btn-success btn-lg','confirm'=>'Yakin Selesai Mengerjakan Kuis ?')); ?>
				        </div>
			    <?php } ?>
			  <?php $urut++;?>    
			  
			
			<?php } ?>
			</div>
			
			<?php $this->endWidget(); ?>		
			
		<div>
	</row>
	
<div>

<?php } ?>
<?php $url_user = $this->createUrl('site/acak');?>
<?php $redirect= $this->createUrl('site/index');?>
<?php $cek_url = Yii::app()->baseUrl.'/images/pencil.png';?>

</div>
<script type="text/javascript">
	//localStorage.clear();

	console.log(localStorage);
	cek_url = "<?php echo $cek_url;?>";
	function checkNetConnection(){
		 jQuery.ajaxSetup({async:false});
		 re="";
		 r=Math.round(Math.random() * 10000);
		 $.get(cek_url,{subins:r},function(d){
		  re=true;
		 }).error(function(){
		  re=false;
		 });
		 return re;
	}

	$(document).ready(function() {
		var url_user = "<?php echo $url_user;?>";
		var id_user = "<?php echo Yii::app()->user->id;?>";
		var id_quiz = "<?php echo $model->id;?>";
		var red_url = "<?php echo $redirect;?>"
		var clock;
		var idquiz="<?php echo $model->id;?>";
		var wkt = $("#waktu").text()*60;
		var passcode = "<?php echo $passcode ?>";
		//localStorage.setItem('qid',idquiz);
		
		if(typeof(localStorage.questionsundefinedqid) !== "undefined"){
			if(idquiz !== localStorage.questionsundefinedqid){
				//console.log(red_url+"/"+localStorage.questionsundefinedqid);
				$("#notif-flash").append('<div class="alert alert-warning notice" role="alert">Anda Harus Menghapus Data Ulangan Yang Lama Dahulu</div>');
				window.setTimeout(function(){
					window.location.replace(red_url);
				}, 1000);
				//console.log(localStorage.questionsundefinedqid);
			}
			//console.log(localStorage.questionsundefinedqid);
		}

		if(typeof(localStorage.questionsundefineddude) !== "undefined") {
		    var wktInv = parseInt(localStorage.questionsundefineddude);
		    if (wktInv!=0){
		    	wkt = wktInv+2;
		    }
		   // console.log(localStorage.questionsundefineddude);
		}
		
	    
		//clock.setTime($("#dude").val()));
		clock = $('.clock').FlipClock(wkt, {
	        clockFace: 'HourCounter',
	        countdown: true,
	        autoStart: false,
	        callbacks: {
	         	stop: function() {
	         		//$('.message').html('Waktu Habis!');
	         		//$("#questions").submit();
	         	}
	        }
	    });
		clock.start(function() {
			//console.log(clock.time.time);
			$("#dude").val(clock.time.time);
		});
		// console.log($("#dude").val());
	    $(document).on("contextmenu",function(e){
	        if(e.target.nodeName != "INPUT" && e.target.nodeName != "TEXTAREA")
	             e.preventDefault();
	     });

	    
		$(document).keydown(function (e) {
            return (e.which || e.keyCode) != 116;
        });

        $('.next').click(function(){

		    var nextId = $(this).parents('.tab-pane').next().attr("id");
		    $('[href=#'+nextId+']').tab('show');
		    return false;
		    
		  })

        $('.prev').click(function(){

		    var nextId = $(this).parents('.tab-pane').prev().attr("id");
		    $('[href=#'+nextId+']').tab('show');
		    return false;
		    
		  })

	 	$('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
		    
		    //update progress
		    var step = $(e.target).data('step');
		    var percent = (parseInt(step) / "<?php echo $total_pertanyaan;?>") * 100;
		    
		    $('.progress-bar').css({width: percent + '%'});
		    $('.progress-bar').text("Soal " + step + " dari <?php echo $total_pertanyaan;?> Soal");
		    
		    //e.relatedTarget // previous tab
		    
		})

		$('.first').click(function(){

	    	$('#myWizard a:first').tab('show')

		})

		$( function() {
			$("#questions").sisyphus({
				//autoRelease: true,
				timeout: 1
			});
			//$("#wkt").sisyphus();
			// or you can persist all forms data at one command
			// $( "form" ).sisyphus();
		} );
		
		/*$("#questions").submit(function( event ) {
			  localStorage.clear();
		});*/

		var isActive = true;
		var indikasi = 0;

		window.onfocus = function () { 
		  isActive = true; 
		}; 

		window.onblur = function () { 
		  isActive = false;
		  indikasi++;
		  console.log(indikasi);
		  $("#indikator").val(indikasi); 
		}; 
		
		// test
		var kode;
		if(passcode == null){
			pass = "abc123";
		}
		setInterval(function () { 
		  console.log(isActive ? 'Active' : 'Inactive'); 
			if(isActive == false){							
				localStorage.setItem('status',"1");
				$("#overlay").show();
				$("#modalCentered").show();
				//kode = prompt("Masukan Kode Untuk Membuka", "");
				$("#kode").change(function(){
					kode = $("#kode").val();
					if(kode == passcode){
						$("#overlay").hide();
						$("#modalCentered").hide();
						$('#kode').removeAttr('value');
						localStorage.setItem('status',"0");
					}else{
						location.reload();
					}	
				});
				
			}
		}, 1000);

		var isActive = true;
		var indikasi = 0;
		window.onfocus = function () { 
		  isActive = true; 
		}; 

		window.onblur = function () { 
		  isActive = false;
		  indikasi++;
		  console.log(indikasi);
		  $("#indikator").val(indikasi); 
		}; 
		
		if(localStorage.status == 1){
			$("#overlay").show();
			$("#modalCentered").show();
			//kode = prompt("Masukan Kode Untuk Membuka", "");
			
			$("#kode").change(function(){
					kode = $("#kode").val();
					// alert(kode);
					// alert(passcode);
					if(kode == passcode){
						$("#overlay").hide();
						$("#modalCentered").hide();
						$('#kode').removeAttr('value');
						localStorage.setItem('status',"0");
					}else{
						location.reload();
					}	
				});
				
		}

	  	$("#check").click(function(){
		  if(checkNetConnection()==true){
		   hitung = $('input[type="radio"]:checked', '#questions').length;
		   total = $('.tab-pane').length;
		   alert("Tersambung Ke Wifi "+hitung+" Soal Telah Dijawab dari "+ total );
		   //alert("Tersambung Ke Wifi");
		  }else{
		   hitung = $('input[type="radio"]:checked', '#questions').length;
		   total = $('.tab-pane').length;
		   alert("Tidak Tersambung Ke Wifi "+hitung+" Soal Telah Dijawab dari "+ total );
		   //alert("Tidak Tersambung Ke Wifi");
		   //$('#finish').addClass('disabled');
		  }
	 	});
	  	var hitung = 0;
	  	var total = 0;
	  	for ( var i = 0, len = localStorage.length; i < len; ++i ) {
		  //console.log(localStorage.getItem( localStorage.key( i ) ) );
		  //console.log(localStorage.key( i ));
		  //console.log( i );
		  //
		  if(localStorage.key(i).match(/questionsundefinedpil.*/)){
		  		console.log(localStorage.key( i ));
		 
		  		var nosoal=localStorage.key( i ).substring(localStorage.key( i ).lastIndexOf("[")+1,localStorage.key( i ).lastIndexOf("]"));
				//console.log(nosoal);
				//$("#coba"+nosoal+"").css("background-color","green");
				$("[data-local="+nosoal+"]").css("background-color","green");

		  }
		}
		//var content_array = JSON.parse(localStorage);
		//console.log(content_array);
		//console.log(localStorage);
	  	$('input:radio').change(
		    function(){
		        if (this.checked) {
		            var tabid = $(this).closest(".tab-pane").attr("id");
		            idtab = tabid.replace("step","coba");
		        	//console.log($("#"+idtab+"").attr('class'));
		        	$("#"+idtab+"").css("background-color","green");
		        	//$(this).css("background-color","green");
		        	//console.log($("#"+idtab+"").text());
		        }
		});
		
	  
	  	//var text = {}

		  /*console.log(localStorage);
		  console.log(idquiz);*/		 

		});

        function myconfirm(){
            hitung = $('input[type="radio"]:checked', '#questions').length;
		    total = $('.tab-pane').length;
		  	var r = confirm("Anda sudah mengerjakan "+hitung+" soal dari "+total+" soal, yakin akan mengumpulkan ?");
		  	if (r == true) {
		  		$( "#questions" ).removeAttr( "onSubmit" );
		  		$( "#questions").submit();
		  	} 
		}

		
</script>