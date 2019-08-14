<?php
/* @var $this ChaptersController */
/* @var $model Chapters */

$this->breadcrumbs=array(
	// 'Materi'=>array('index'),
	$model->mapel->name=>array('lesson/'.$model->mapel->id.'?type=materi'),
	$model->title,
);
$tipe = NULL;

$doc_types = array('doc','docx','pdf','xls','xlsx','ppt','pptx');
$vid_types = array('swf','mp4','MP4','avi','mkv','flv');
$img_types = array('jpg', 'png', 'gif');

/*echo "<pre>";
print_r($files);
echo "</pre>";*/
/*$this->menu=array(
	array('label'=>'List Chapters', 'url'=>array('index')),
	array('label'=>'Create Chapters', 'url'=>array('create')),
	array('label'=>'Update Chapters', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Chapters', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Chapters', 'url'=>array('admin')),
);*/
?>
<div class="container">
	<h2><?php echo $model->title;?></h2>
	<p><?php 
		if(!Yii::app()->user->YiiStudent){
			echo CHtml::link('Salin', array('copy','id'=>$model->id), array('class'=>'btn btn-success'));
		} 
	?></p>
	<div class="row">
		<div class="col-md-8">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title"><?php echo $model->title; ?>
						<?php $cekFile = ChapterFiles::model()->findByAttributes(array('id_chapter'=>$model->id));?>
						<span class="pull-right">
						<?php if(!empty($cekFile->file) && $cekFile->type != 1){ ?>
							<?php echo CHtml::link('<i class="fa fa-download"></i> Download', array('chapterFiles/download','id'=>$cekFile->id),array('title'=>'Download'));?>
						<?php } ?>
						<?php if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin) { ?>
							<?php echo CHtml::link('<i class="fa fa-pencil-square-o"></i> Edit', array('chapters/update','id'=>$model->id,'lesson_id'=>$model->mapel->id));?>
						<?php }else{ ?>
							
						<?php } ?>
						</span>
					</h3>
				</div>
				<div class="panel-body">
					<?php if($model->chapter_type == 1){ ?>
						<?php $cekVideo = ChapterFiles::model()->findByAttributes(array('id_chapter'=>$model->id));?>
						<?php if(!empty($cekVideo)){ ?>
							<?php if($cekVideo->type == 'swf'){ ?>
								<div class="img-responsive">
									<center>
										<object width="600" height="400" data="<?php echo Yii::app()->baseUrl.'/images/chapters/'.$model->id.'/'.$cekVideo->file;?>"></object>
									</center>
								</div>
							<?php }else{ ?>
								<div class="img-responsive">
									<center>
										<?php
											$this->widget('ext.jwplayer.Jwplayer',array(
											    'width'=>500,
											    'height'=>360,
											    'file'=>Yii::app()->baseUrl.'/images/chapters/'.$model->mapel->id.'/'.$cekVideo->file, // the file of the player, if null we use demo file of jwplayer
											    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
											    'options'=>array(
											        'controlbar'=>'bottom'
											    )
											));
										?>
									</center>
								</div>
							<?php } ?>
						<?php }else{ ?>
							<p>Video Tidak Tersedia</p> 
						<?php } ?>
					<?php }elseif($model->chapter_type == 2){ ?>
						<?php $cekGambar = ChapterFiles::model()->findByAttributes(array('id_chapter'=>$model->id));?>
						<?php if(!empty($cekGambar->file)){ ?>
						<?php $url = Yii::app()->baseUrl."/images/chapters/".$model->mapel->id."/".$cekGambar->file;?>
						<?php } ?>
						<?php if(!empty($cekGambar->file)){ ?>
							<center>
								<a href="<?php echo $url;?>" target="_blank"><img src="<?php echo $url;?>" class="img-responsive"></a>
							</center> 
						<?php } ?>

					<?php }elseif($model->chapter_type == 3){ ?>
						<?php $cekDokumen = ChapterFiles::model()->findByAttributes(array('id_chapter'=>$model->id));?>
						<?php if(!empty($cekDokumen)){ ?>
							<?php if($cekDokumen->type == "pdf"){ ?>
								<div style="height:600px;">
								<?php

								Yii::app()->clientScript->registerCoreScript('jquery');

								$this->widget('ext.pdfJs.QPdfJs',array(
									'url'=>Yii::app()->baseUrl."/images/chapters/upload/".$cekDokumen->file,
									))
								?>
								</div>
							<?php } elseif($cekDokumen->type == 'doc' || $cekDokumen->type == 'docx'){ ?>
								<?php 
									$url = Yii::app()->baseUrl."/images/ms-word.jpg";
									$url_img = Yii::app()->baseUrl."/images/chapters/".$model->mapel->id."/".$cekDokumen->file;
									?>
								<center>
									<a href="<?php echo $url_img;?>"><img src="<?php echo $url;?>" class="img-responsive"></a>
								</center> 
								<p class="text-center"><a href="<?php echo $url_img;?>"><?php echo $cekDokumen->file;?></a></p>
							<?php } elseif($cekDokumen->type == 'xls' || $cekDokumen->type == 'xlsx'){ ?>
								<?php 
									$url = Yii::app()->baseUrl."/images/ms-excel.jpg";
									$url_img = Yii::app()->baseUrl."/images/chapters/".$model->mapel->id."/".$cekDokumen->file;
									?>
								<center>
									<a href="<?php echo $url_img;?>"><img src="<?php echo $url;?>" class="img-responsive"></a>
								</center> 
								<p class="text-center"><a href="<?php echo $url_img;?>"><?php echo $cekDokumen->file;?></a></p>
							<?php } elseif($cekDokumen->type == 'ppt' || $cekDokumen->type == 'pptx'){ ?>
								<?php 
									$url = Yii::app()->baseUrl."/images/ms-ppt.png";
									$url_img = Yii::app()->baseUrl."/images/chapters/".$model->mapel->id."/".$cekDokumen->file;
									?>
								<center>
									<a href="<?php echo $url_img;?>"><img src="<?php echo $url;?>" class="img-responsive"></a>
								</center> 
								<p class="text-center"><a href="<?php echo $url_img;?>"><?php echo $cekDokumen->file;?></a></p>
							<?php }//else{ ?>
								<?php //$url = Yii::app()->baseUrl."/images/chapters/".$model->mapel->id."/".$cekDokumen->file;?>
								<!-- <center>
									<a href=""><img src="<?php //echo $url;?>" class="img-responsive"></a>
								</center> 
								<p>File : <?php //echo $cekDokumen->file;?></p>  -->
							<?php //} ?>
						<?php } ?>	
					<?php } ?>
					<!-- <p><?php //echo $model->description;?></p> -->
					<p><?php echo $model->content;?></p>
				</div>
			</div>
		</div>
	</div>
</div>

<?php /*$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'id_lesson',
		'title',
		'description',
		'created_at',
		'updated_at',
		'created_by',
		'updated_by',
	),
)); */?>
