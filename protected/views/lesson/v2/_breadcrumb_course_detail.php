<div class="col-md-12">
  <div id="bc1" class="btn-group btn-breadcrumb">
    <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
    <?php echo CHtml::link('<div>Pelajaran</div>',array('/lesson/index'), array('class'=>'btn btn-default')); ?>
    <?php echo CHtml::link('<div>'.CHtml::encode($model->name).'</div>',array('#'), array('class'=>'btn btn-success')); ?>
  </div>
  <!-- <div class="pull-right">
    <a href="<?php echo Yii::app()->createUrl('/lesson/view/'.$model->id) ?>" class="btn btn-danger">
      <span class="hidden-xs"><i class="fa fa-chevron-left"></i> Kembali ke Pelajaran "<?php echo $model->name ?>"</span>
      <span class="visible-xs"><i class="fa fa-times"></i></span>
    </a>
  </div> -->
</div>
