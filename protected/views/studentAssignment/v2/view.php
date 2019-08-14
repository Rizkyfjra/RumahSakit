<?php
	$video=array('mp4', 'MP4', 'avi', 'flv', 'mkv');
?>
<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_student_assignment_detail', array(
      //   'model'=>$model
      // ));
    ?>
    <div class="col-md-12">
	  <div id="bc1" class="btn-group btn-breadcrumb">
	    <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
	    <?php echo CHtml::link('<div>Tugas Siswa</div>',array('/studentAssignment'), array('class'=>'btn btn-default')); ?>
	    <?php echo CHtml::link('<div>'.$model->student->display_name.'</div>',array('#'), array('class'=>'btn btn-success')); ?>
	  </div>
	</div>

    <div class="col-lg-12">
      <h3>Tugas
          <small>Tugas Siswa</small>
      </h3>
	  <div class="row">
		<div class="col-md-6">
	      <div class="col-card">
	      	<h5><?php echo ucfirst($model->student->display_name); ?></h5>
	      	<hr/>
	  		<table class="table table-hover table-responsive">
	  			<tr>
			  		<th>ID</th>
			  		<th>Nama Tugas</th>
			  		<th>Batas Pengumpulan</th>
			  		<th>Dikumpulkan Tanggal</th>
			  		<th>Nilai Tugas</th>
			  		<th>Tepat Waktu</th>
			  		<th></th>
			  	</tr>
	  			<tr>
					<td><?php echo ucfirst($model->id); ?></td>
		  			<td><?php echo ucfirst($model->teacher_assign->title); ?></td>
		  			<td><?php echo date('d M Y G:i:s',strtotime($model->teacher_assign->due_date)); ?></td>
		  			<td><?php echo date('d M Y G:i:s',strtotime($model->created_at)); ?></td>
		  			<td>
		  				<?php
		  					if($model->score != NULL){
					    		echo $model->score;
					    	} else {
					    		echo "Belum Diberi Nilai";
					    	}
					    ?>
					</td>
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
	  <br class="clearfix"/>
	  <div class="row">
	  	<div class="col-md-6">
	  	  <div class="col-card">
	      	<h5>Jawaban</h5>
	      	<hr/>
			<?php
				echo "<p>".$model->content."</p>";
				if(Yii::app()->user->YiiTeacher && !empty($model->content)){
			?>
			<p class="text-right"><?php echo CHtml::link('Koreksi',array('update','id'=>$model->id,'type'=>2),array('class'=>'btn btn-primary'));?></p>
			<?php
				}

				if(!empty($model->file)){
					$ext = pathinfo($model->file, PATHINFO_EXTENSION);
					if($ext == 'jpg' || $ext == 'png' || $ext == 'png'){ 
			?>
				<img src="<?php echo Yii::app()->baseUrl;?>/images/students/<?php echo $model->student_id;?>/<?php echo $model->file;?>" class="thumbnails" height="250px" width="400px">
			<?php
					}elseif(in_array($ext, $video)){ 
			?> 
					<div class="img-responsive">
						<center>
							<?php
								$this->widget('ext.jwplayer.Jwplayer',array(
								    'width'=>500,
								    'height'=>360,
								    'file'=>Yii::app()->baseUrl.'/images/students/'.$model->student_id.'/'.$model->file,
								    'image'=>NULL,
								    'options'=>array(
								        'controlbar'=>'bottom'
								    )
								));
							?>
						</center>
					</div>
			<?php
					}elseif($ext == 'pdf'){
			?> 
					<div style="height:600px;">
					<?php
						Yii::app()->clientScript->registerCoreScript('jquery');

						$this->widget('ext.pdfJs.QPdfJs',array(
							'url'=>Yii::app()->baseUrl."/images/students/".$model->student_id."/".$model->file,
						))
					?>
					</div>
			<?php
					}
				} 
			?>
			<hr/>
			<p>File : <?php echo CHtml::link($model->file, array('studentAssignment/download','id'=>$model->id,'id_user'=>$model->student_id)); ?></p>
		   </div>
		 </div>
	   </div>
	</div>
  </div>
</div>

<?php if(Yii::app()->user->YiiTeacher){ ?>
<script type="text/javascript">
	localStorage.clear();
</script>
<?php } ?>