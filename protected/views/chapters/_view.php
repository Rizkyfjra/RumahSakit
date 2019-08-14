<?php
/* @var $this ChaptersController */
/* @var $data Chapters */
?>
	<tr>
		<td><?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?></td>
		<td><?php echo CHtml::link(CHtml::encode($data->mapel->name), array('lesson/view', 'id'=>$data->id_lesson)); ?></td>
		<td><?php echo CHtml::encode($data->title); ?></td>
		<td><?php echo CHtml::encode($data->created_at); ?></td>
		<td><?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?></td>
	</tr>