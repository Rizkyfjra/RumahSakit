<?php
/* @var $this QuestionsController */
/* @var $model Questions */
$q=NULL;
if(isset($_GET['quiz_id'])){
	$q=$_GET['quiz_id'];
	$detail = Quiz::model()->findByPk($q);
	$this->breadcrumbs=array(
		'Pertanyaan'=>array('index'),
		$detail->title=>array('quiz/view','id'=>$detail->id),
		$model->title,
	);
}else{
	$this->breadcrumbs=array(
		'Pertanyaan'=>array('index'),
		$model->title,
	);
}

/*$this->menu=array(
	array('label'=>'List Questions', 'url'=>array('index')),
	array('label'=>'Create Questions', 'url'=>array('create')),
	array('label'=>'Update Questions', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Questions', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Questions', 'url'=>array('admin')),
);*/
?>

<h1>View Questions <?php echo $model->title; ?></h1>
<?php if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin){ ?>
<p>
	<?php echo CHtml::link('Edit Pertanyaan', array('update','id'=>$model->id,'quiz_id'=>$q),array('class'=>'btn btn-primary'))?>
	<?php echo CHtml::link('Tambah Pertanyaan', array('create'),array('class'=>'btn btn-success'))?>
	<?php echo CHtml::link('Lihat Daftar Pertanyaan', array('index'),array('class'=>'btn btn-danger'))?>
</p>
<?php } ?>
<div class="row">
	<div class="col-md-8">
		<table class="table table-hover table-bordered table-responsive">
			<tr class="danger">
			<th>ID</th>
			<th>Kompetensi Dasar</th>
			<th>Pertanyaan</th>
			<th>Pilihan Jawaban</th>
			<th>Kunci Jawaban</th>
			<th>File</th>
			</tr>
			<tr class="active">
				<?php if(empty($model->id_lama)){ ?>
					<?php $path_image=Clases::model()->path_image($model->id);?>
				<?php }else{ ?>
					<?php $path_image=Clases::model()->path_image($model->id_lama);?>
				<?php } ?>
				<td><?php echo $model->id;?></td>
				<td><?php echo $model->title;?></td>
				<td><?php echo $model->text;?></td>
				<td>
					<?php if($model->type == NULL){ ?>
						<?php $no='A';?>
						<?php if(!empty($model->choices)){ ?>
							<?php $pilihan=json_decode($model->choices,true);?>
							<?php $gambar=json_decode($model->choices_files,true);?>
							<?php foreach ($pilihan as $key => $value) { ?>
								<?php if(!empty($gambar[$key])){ ?>
									<?php
										if(empty($model->id_lama)){ 
											echo $no.". ".$value." <img src='".Yii::app()->baseUrl.'/images/question/'.$path_image.$key.'/'.$gambar[$key]."' class='img-responsive' width=100 height=100>"."<br>";
										}else{
											echo $no.". ".$value." <img src='".Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$key.'/'.$gambar[$key]."' class='img-responsive' width=100 height=100>"."<br>";
										}
									?>
								<?php }else{ ?> 
									<?php echo $no.". ".$value."<br>";?>
								<?php } ?>
								<?php $no++;?>
							<?php } ?>
						<?php } ?>
					<?php } ?>
				</td>
				<td><?php echo $model->key_answer;?></td>
				<td>
					<?php if(!empty($model->file)){ ?>
						<?php if(empty($model->id_lama)){ ?>
							<?php $img_url = Yii::app()->baseUrl.'/images/question/'.$path_image.'/'.$model->file;?>
						<?php }else{ ?>
							<?php $img_url = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.'/'.$model->file;?>
						<?php } ?>
						<?php $ext = pathinfo($model->file, PATHINFO_EXTENSION);?>
						<?php
							$doc_types = array('doc','docx','pdf','xls','xlsx','ppt','pptx');
							$vid_types = array('swf','mp4','MP4','avi','mkv','flv');
							$img_types = array('jpg','png','gif');
							$audio_types = array('mp3','ogg'); 
						?>
						<?php if(in_array($ext, $audio_types)){ ?>
							<audio controls>
							  <!-- <source src="horse.ogg" type="audio/ogg"> -->
							  <source src="<?php echo $img_url;?>" type="audio/mpeg">
							
							</audio>
						<?php } elseif(in_array($ext, $img_types)) {?>
							<img src="<?php echo $img_url;?>" class="img-responsive">
						<?php } elseif(in_array($ext, $vid_types)) {?>
							<div class="img-responsive">
								<center>
									<?php
										$this->widget('ext.jwplayer.Jwplayer',array(
										    'width'=>250,
										    'height'=>250,
										    'file'=>$img_url, // the file of the player, if null we use demo file of jwplayer
										    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
										    'options'=>array(
										        'controlbar'=>'bottom'
										    )
										));
									?>
								</center>
							</div>
						<?php } ?>
					<?php } ?>
				</td>
			</tr>
		</table>
	    </br>
	    <div>Preview Siswa</div>
		<div class="tab-pane fade in active" id="step<?php echo $model->id;?>">
				         
					        <div class="panel panel-default">
					          	<div class="panel-heading bold">Soal <?php echo $model->id;?></div>
					           	<div class="panel-body">
							           	<div style="color:black">
								            <?php echo $model->text;?>
								            <br>
								            <?php if(!empty($model->file)){ ?>
								            	<?php $ext = pathinfo($model->file, PATHINFO_EXTENSION);?>
												<?php if(empty($model->id_lama)){ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/'.$path_image.$model->file;?>
												<?php }else{ ?>
													<?php $img_url = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$model->file;?>
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
								            <?php if($model->type == NULL){ ?>
									            <?php $pil = json_decode($model->choices,true);?>
									            <?php 
									            	if(!empty($model->choices_files)){
									            		$gambar = json_decode($model->choices_files,true);
									            	}else{
									            		$gambar = NULL;
									            	}
									            	
									            ?>	
												
								            	<?php foreach ($pil as $k => $value) { ?>
								            		<label class="btn btn-info">
								            			<input type="radio" name="pil[<?php echo $model->id;?>]" value="<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');?>"> <span><?php echo $value;?></span>
									            		<?php if(!empty($gambar[$k])){ ?>
															<?php if(empty($model->id_lama)){?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php }else{ ?>
																<?php $img_pil = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$k.'/'.$gambar[$k];?>
															<?php } ?>
															<img src="<?php echo $img_pil;?>" class="img-responsive">		
														<?php } ?>
													</label>
								            		
													<br>
												<?php } ?>
												
											<?php }elseif($model->type == 1){ ?>
												<input class="form-control  input-lg" name="pil[<?php echo $model->id;?>]">
											<?php }else{?> 
												<textarea class="form-control" name="pil[<?php echo $model->id;?>]"></textarea>
											<?php } ?>
										</div>
									</div> 
								 
					        </div>

				        </div>


		<?php if(!empty($q)){ ?>
		<br>
		<p class="text-right"><?php echo CHtml::link('Kembali Ke Kuis', array('/quiz/view','id'=>$q),array('class'=>'btn btn-danger'))?></p>
		<?php } ?>
	</div>
</div>
