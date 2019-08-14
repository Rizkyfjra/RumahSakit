<?php
/* @var $this StudentAssignmentController */
/* @var $data StudentAssignment */
?>

<div class="row">
	<div class="panel panel-success">
	  <div class="panel-heading">
	    <h3 class="panel-title"><?php echo $data->title?> <span class="pull-right">Batas Pengumpulan : <STRONG><?php echo date('d M Y',strtotime($data->due_date));?></STRONG></span></h3>
	  </div>
	  <div class="panel-body">
	    <?php echo $data->content?>
	  </div>
	   <div class="panel-footer"><p class="text-right"><?php echo CHtml::link("<i class='glyphicon glyphicon-edit'></i>",array('update','id'=>$data->id),array('class'=>'btn btn-success btn-xs'));?></p></div>
	</div>
	<!-- <b><?php //echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php //echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php //echo CHtml::encode($data->getAttributeLabel('assignment_id')); ?>:</b>
	<?php //echo CHtml::encode($data->assignment_id); ?>
	<br />

	<b><?php //echo CHtml::encode($data->getAttributeLabel('file')); ?>:</b>
	<?php //echo CHtml::encode($data->file); ?>
	<br />

	<b><?php //echo CHtml::encode($data->getAttributeLabel('student_id')); ?>:</b>
	<?php //echo CHtml::encode($data->student_id); ?>
	<br />

	<b><?php //echo CHtml::encode($data->getAttributeLabel('score')); ?>:</b>
	<?php //echo CHtml::encode($data->score); ?>
	<br />

	<b><?php //echo CHtml::encode($data->getAttributeLabel('uploaded_at')); ?>:</b>
	<?php //echo CHtml::encode($data->uploaded_at); ?>
	<br /> -->


</div>