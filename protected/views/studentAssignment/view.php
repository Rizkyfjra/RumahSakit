<?php
/* @var $this StudentAssignmentController */
/* @var $model StudentAssignment */

$this->breadcrumbs=array(
	'Tugas Siswa'=>array('index'),
	$model->student->display_name,
);

/*$this->menu=array(
	array('label'=>'List StudentAssignment', 'url'=>array('index')),
	array('label'=>'Create StudentAssignment', 'url'=>array('create')),
	array('label'=>'Update StudentAssignment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StudentAssignment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StudentAssignment', 'url'=>array('admin')),
);*/
//phpinfo();
$video=array('mp4', 'MP4', 'avi', 'flv', 'mkv');
?>

<div class="container">
	<div class="">
	<div class="panel panel-primary">
	  <div class="panel-heading">
	    <h3 class="panel-title">Nama Siswa : <b><?php echo ucfirst($model->student->display_name); ?></b></h3>
	  </div>
	  <div class="panel-body">
	  	<table class="table table-hover table-responsive">
	  		<th>ID</th>
	  		<th>Nama Tugas</th>
	  		<th>Batas Pengumpulan</th>
	  		<th>Dikumpulkan Tanggal</th>
	  		<th>Nilai Tugas</th>
	  		<th>Tepat Waktu</th>
	  		<th></th>
	  		<tr>
				<td><?php echo ucfirst($model->id); ?></td>
	  			<td><?php echo ucfirst($model->teacher_assign->title); ?></td>
	  			<td><?php echo date('d M Y G:i:s',strtotime($model->teacher_assign->due_date)); ?></td>
	  			<td><?php echo date('d M Y G:i:s',strtotime($model->created_at)); ?></td>
	  			<td><?php if($model->score != NULL){ ?>
				    	<?php echo $model->score; ?>
				    	<?php } else { ?>
				    	<?php echo "Belum Diberi Nilai";?>
				    	<?php } ?></td>
				<td>
				<?php
					
					if(!empty($model->teacher_assign->due_date > $model->created_at)){
						echo "Ya";	
					} else {
						echo "Tidak";
					}
				?>
				</td>
				<?php if(!Yii::app()->user->YiiStudent){ ?>    	
	  				<td><?php echo CHtml::link('Nilai', array('update','id'=>$model->id,'type'=>1));?></td>
	  			<?php }else{?>
	  				<td></td>
	  			<?php } ?>
	  		</tr>
	  	</table>
	  </div>
	</div>
	</div>
	<div class="col-md-8">
		<div class="panel panel-success">
			<div class="panel-heading"><h3 class="panel-title">Jawaban</h3></div>
		</div>
		<div class="panel-body">
			<?php echo $model->content; ?>
			<?php if(Yii::app()->user->YiiTeacher && !empty($model->content) or (Yii::app()->user->YiiAdmin && !empty($model->content))){ ?>
			<p class="text-right"><?php echo CHtml::link('Koreksi',array('update','id'=>$model->id,'type'=>2),array('class'=>'btn btn-primary'));?></p>
			<?php } ?>
			<?php if(!empty($model->file)){ ?>
			<?php $ext = pathinfo($model->file, PATHINFO_EXTENSION);?>
				<?php if($ext == 'jpg' || $ext == 'png' || $ext == 'png'){ ?>
				<img src="<?php echo Yii::app()->baseUrl;?>/images/students/<?php echo $model->student_id;?>/<?php echo $model->file;?>" class="thumbnails" height="250px" width="400px">
				<?php }elseif(in_array($ext, $video)){ ?> 
					<div class="img-responsive">
						<center>
							<?php
								$this->widget('ext.jwplayer.Jwplayer',array(
								    'width'=>500,
								    'height'=>360,
								    'file'=>Yii::app()->baseUrl.'/images/students/'.$model->student_id.'/'.$model->file, // the file of the player, if null we use demo file of jwplayer
								    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
								    'options'=>array(
								        'controlbar'=>'bottom'
								    )
								));
							?>
						</center>
					</div>
				<?php }elseif($ext == 'pdf'){ ?> 

					<div style="height:600px;">
					<?php

					Yii::app()->clientScript->registerCoreScript('jquery');

					$this->widget('ext.pdfJs.QPdfJs',array(
						'url'=>Yii::app()->baseUrl."/images/students/".$model->student_id."/".$model->file,
						))
					?>
					</div>
				<?php } ?>
			<?php } ?>	
		</div>
		<div class="pane-footer">File : <?php echo CHtml::link($model->file, array('studentAssignment/download','id'=>$model->id,'id_user'=>$model->student_id)); ?></div>
	</div>
</div>
<?php if(Yii::app()->user->YiiTeacher){ ?>
	<script type="text/javascript">
		localStorage.clear();
	</script>
<?php } ?>