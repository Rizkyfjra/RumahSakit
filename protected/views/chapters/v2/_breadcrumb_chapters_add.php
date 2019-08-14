<div class="col-md-12">
  <div id="bc1" class="btn-group btn-breadcrumb">
	<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
	<?php echo CHtml::link('<div>Materi</div>',array('/chapters/index'), array('class'=>'btn btn-default')); ?>
    <?php if(!$model->isNewRecord){ ?>
	<?php echo CHtml::link('<div>Sunting Materi</div>',array('#'), array('class'=>'btn btn-success')); ?>
	<?php }else{ ?>
	<?php echo CHtml::link('<div>Tambah Materi</div>',array('#'), array('class'=>'btn btn-success')); ?>	
	<?php } ?>	
  </div>
</div>
