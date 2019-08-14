<div class="col-md-12">
  <div id="bc1" class="btn-group btn-breadcrumb">
	<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>Tugas</div>',array('/assignment/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>List</div>',array('/assignment/list'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>Salin Tugas</div>',array('#'), array('class'=>'btn btn-success')); ?>
  </div>
</div>
