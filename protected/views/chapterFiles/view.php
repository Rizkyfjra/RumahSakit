<?php
/* @var $this ChapterFilesController */
/* @var $model ChapterFiles */
$chapter = $model->idChapter;
$lesson = $chapter->mapel;

$this->breadcrumbs=array(
	'Dokumen'=>array('index'),
	$lesson->name=>array('lesson/view','id'=>$lesson->id),
	$chapter->title=>array('chapters/view','id'=>$chapter->id),
	$model->file
);
/*$this->menu=array(
	array('label'=>'List ChapterFiles', 'url'=>array('index')),
	array('label'=>'Create ChapterFiles', 'url'=>array('create')),
	array('label'=>'Update ChapterFiles', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete ChapterFiles', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage ChapterFiles', 'url'=>array('admin')),
);*/
?>
<?php if($tipe == 'dokumen'){ ?>
	<?php if($model->type == "pdf"){ ?>
		<div style="height:600px;">
		<?php

		Yii::app()->clientScript->registerCoreScript('jquery');

		$this->widget('ext.pdfJs.QPdfJs',array(
			'url'=>Yii::app()->baseUrl."/images/chapters/".$lesson->id."/".$model->file,
			))
		?>
	</div>
	<?php } ?>
	
<?php } elseif($tipe == 'video') { ?>
<!-- <div align="center" class="embed-responsive embed-responsive-16by9">
    <video autoplay class="embed-responsive-item">
        <source src="<?php //echo Yii::app()->baseUrl.'/images/chapters/'.$lesson->id.'/'.$model->file?>" type="video/mp4">
    </video>
</div> -->
<?php
	/*$this->widget ( 'ext.mediaElement.MediaElementPortlet',
			    array ( 
			    'url' => Yii::app()->baseUrl.'/images/chapters/'.$lesson->id.'/'.$model->file,
			    'mimeType' =>'video/mp4',
			    'autoplay'=>false,
			// or you can set the model and attributes
			    //'model' => $model,
			    //'attribute' => 'url'
			// its required and so you have to set correctly
			    // 'mimeType' =>'audio/mp3',
			 
		    ));  */
?>
	<?php if($model->type == 'swf'){ ?>

			<div class="img-responsive"><center>
				<object width="600" height="400" data="<?php echo Yii::app()->baseUrl.'/images/chapters/'.$lesson->id.'/'.$model->file;?>"></object>
			</center>
	<?php }else{ ?>
		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad">
			<div class="panel panel-success">
				<div class="panel-heading">
					<h3 class="panel-title">Dokumen <?php echo $model->file;?></h3>
				</div>
				<div class="panel-body">

				<div class="img-responsive"><center>
				<?php
					$this->widget('ext.jwplayer.Jwplayer',array(
					    'width'=>500,
					    'height'=>360,
					    'file'=>Yii::app()->baseUrl.'/images/chapters/'.$lesson->id.'/'.$model->file, // the file of the player, if null we use demo file of jwplayer
					    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
					    'options'=>array(
					        'controlbar'=>'bottom'
					    )
					));
					/*$this->widget ( 'ext.mediaElement.MediaElementPortlet',
					    array ( 
					    'url' => Yii::app()->baseUrl.'/images/chapters/'.$lesson->id.'/'.$model->file,
					    'mimeType' =>'video/mp4',
					    'autoplay'=>false,
					// or you can set the model and attributes
					    //'model' => $model,
					    //'attribute' => 'url'
					// its required and so you have to set correctly
					    // 'mimeType' =>'audio/mp3',
					 
				    )); */ 
				?>
				</center>
				</div>
		</div>
			</div>
		</div>
	<?php } ?>
<?php } else { ?>
<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad">
	<div class="panel panel-success">
		<div class="panel-heading">
			<h3 class="panel-title">Dokumen <?php echo $model->file;?></h3>
		</div>
		<div class="panel-body">
	<?php $url = Yii::app()->baseUrl."/images/chapters/".$lesson->id."/".$model->file;?>
	<center><img src="<?php echo $url;?>" class="thumbnails"></center>
	</div>
	</div>
</div>
<?php } ?>
		