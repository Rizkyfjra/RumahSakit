<div class="col-md-12">
  <div id="bc1" class="btn-group btn-breadcrumb">
	<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>Pengguna</div>',array('/user/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>'.CHtml::encode($model->display_name).'</div>',array('/user/view', 'id'=>$model->id), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>Ganti Password</div>',array('#'), array('class'=>'btn btn-success')); ?>
  </div>
</div>
