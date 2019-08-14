<?php
	if($model->lesson->moving_class == 1){
		$kelasnya = $model->lesson->name;
		$idkelasnya = $model->lesson->id;
		$path_nya = 'lesson/'.$idkelasnya;
	}else{
		$kelasnya = $model->lesson->name;
		$idkelasnya = $model->lesson->id;
		$path_nya = 'lesson/'.$idkelasnya;
	}
?>
<div class="col-md-12">
  <div id="bc1" class="btn-group btn-breadcrumb">
	<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>Ujian</div>',array('/quiz/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>List</div>',array('/quiz/list'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>'.CHtml::encode($kelasnya).'</div>',array($path_nya,'type'=>'ulangan'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>'.CHtml::encode($model->title).'</div>',array('#'), array('class'=>'btn btn-success')); ?>
  </div>
</div>
