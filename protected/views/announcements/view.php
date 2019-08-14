<?php
/* @var $this AnnouncementsController */
/* @var $model Announcements */

$this->breadcrumbs=array(
	'Pengumuman'=>array('index'),
	$model->title,
);

/*$this->menu=array(
	array('label'=>'List Announcements', 'url'=>array('index')),
	array('label'=>'Create Announcements', 'url'=>array('create')),
	array('label'=>'Update Announcements', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Announcements', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Announcements', 'url'=>array('admin')),
);*/
?>


<div class="col-md-12">
	<div class="panel panel-info">
	  <div class="panel-heading ">
	    <h3 class="panel-title">
	    	<STRONG><?php echo ucfirst($model->title); ?></STRONG></h3>
	    	<?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiKepsek){ ?>
	    		<p class="text-right"><?php echo CHtml::link("<i class='glyphicon glyphicon-edit'></i>",array('update','id'=>$model->id),array('class'=>'btn btn-success btn-xs'));?> <span><?php echo CHtml::link("<i class='glyphicon glyphicon-remove'></i>",array('hapus','id'=>$model->id),array('class'=>'btn btn-danger btn-xs'));?></span></p>
	  		<?php } ?>
	  </div>
	  <div class="panel-body">  
		<div class="col-md-8"><?php echo $model->content;?></div>
	  </div>
	  <div class="panel-footer">
	  	<p class="text-right">Dibuat Oleh <STRONG><?php echo CHtml::link(ucfirst($model->author->display_name), array('/user/view','id'=>$model->author->id)) ;?></STRONG> <i class='glyphicon glyphicon-calendar'></i> <STRONG><?php echo date('d M Y',strtotime($model->created_at));?></STRONG></p>
	  </div>
	</div>
</div>
