<?php
/* @var $this StudentQuizController */
/* @var $data StudentQuiz */
?>

<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('quiz_id')); ?>:</b>
	<?php echo CHtml::encode($data->quiz_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('student_id')); ?>:</b>
	<?php echo CHtml::encode($data->student_id); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('created_at')); ?>:</b>
	<?php echo CHtml::encode($data->created_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('updated_at')); ?>:</b>
	<?php echo CHtml::encode($data->updated_at); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('score')); ?>:</b>
	<?php echo CHtml::encode($data->score); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('right_answer')); ?>:</b>
	<?php echo CHtml::encode($data->right_answer); ?>
	<br />

	<?php /*
	<b><?php echo CHtml::encode($data->getAttributeLabel('wrong_answer')); ?>:</b>
	<?php echo CHtml::encode($data->wrong_answer); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('unanswered')); ?>:</b>
	<?php echo CHtml::encode($data->unanswered); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('student_answer')); ?>:</b>
	<?php echo CHtml::encode($data->student_answer); ?>
	<br />

	*/ ?>

</div>