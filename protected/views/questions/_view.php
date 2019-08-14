<?php
/* @var $this QuestionsController */
/* @var $data Questions */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('quiz_id')); ?>:</b>
	<?php echo CHtml::encode($data->quiz_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('question_no')); ?>:</b>
	<?php echo CHtml::encode($data->question_no); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('text')); ?>:</b>
	<?php echo CHtml::encode($data->text); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('choices')); ?>:</b>
	<?php echo CHtml::encode($data->choices); ?>
	<br />


</div>