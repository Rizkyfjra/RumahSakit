<?php
/* @var $this LessonController */
/* @var $data Lesson */
?>
	<tr>
		<td><?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?></td>
		<td><?php echo CHtml::link(CHtml::encode($data->users->display_name), array('user/view', 'id'=>$data->user_id)); ?></td>
		<td><?php echo CHtml::encode($data->class->name); ?></td>
	</tr>
