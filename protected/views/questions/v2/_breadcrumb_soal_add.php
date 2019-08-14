<?php
	if(isset($_GET['quiz_id'])){
		$modelQuiz = Quiz::model()->findByPk($_GET['quiz_id']);

		if($modelQuiz->lesson->moving_class == 1){
			$kelasnya = $modelQuiz->lesson->name;
			$idkelasnya = $modelQuiz->lesson->id;
			$path_nya = 'lesson/'.$idkelasnya;
		}else{
			$kelasnya = $modelQuiz->lesson->name;
			$idkelasnya = $modelQuiz->lesson->id;
			$path_nya = 'lesson/'.$idkelasnya;
		}
	}
?>
<div class="col-md-12">
  <div id="bc1" class="btn-group btn-breadcrumb">
  	<?php
		if(isset($_GET['quiz_id'])){
  	?>
	<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>Ujian</div>',array('/quiz/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>'.CHtml::encode($kelasnya).'</div>',array($path_nya,'type'=>'ulangan'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>'.CHtml::encode($modelQuiz->title).'</div>',array('/quiz/view', 'id'=>$modelQuiz->id), array('class'=>'btn btn-default')); ?>
	<?php
			if(!$model->isNewRecord){
				echo CHtml::link('<div>Sunting Soal</div>',array('#'), array('class'=>'btn btn-success'));
			}else{
				echo CHtml::link('<div>Tambah Soal</div>',array('#'), array('class'=>'btn btn-success'));
			}
	?>
  	<?php
  		}else{
  	?>
	<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>Bank Soal</div>',array('/questions/index'), array('class'=>'btn btn-default')); ?>
	<?php
			if(!$model->isNewRecord){
				echo CHtml::link('<div>Sunting Soal</div>',array('#'), array('class'=>'btn btn-success'));
			}else{
				echo CHtml::link('<div>Tambah Soal</div>',array('#'), array('class'=>'btn btn-success'));
			}
	?>
  	<?php
  		}
  	?>
  </div>
</div>
