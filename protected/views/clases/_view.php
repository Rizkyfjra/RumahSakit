<?php
/* @var $this ClasesController */
/* @var $data Clases */
?>
<tr>
	<!-- <td><?php //echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?></td> -->
	<td><?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?></td>
	<td>
		<?php 
			if(!empty($data->teacher_id)){
				echo CHtml::link(CHtml::encode($data->teacher->display_name), array('user/view', 'id'=>$data->teacher_id)); 
			}
		?>
	</td>
	<td><?php echo CHtml::encode($data->id);?></td>
	<td>
		<?php 
			echo CHtml::link('Lihat <i class="fa fa-hand-pointer-o"></i>', array('view', 'id'=>$data->id),array('class'=>'btn btn-info')); 
		?>
	</td>
</tr>
