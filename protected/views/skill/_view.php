<?php
/* @var $this SkillController */
/* @var $data Skill */
?>
<tr>
	<td><?php echo CHtml::link(CHtml::encode($data->name), array('view', 'id'=>$data->id)); ?></td>
	<td><?php echo CHtml::link(CHtml::encode($data->lesson->name), array('lesson/view', 'id'=>$data->lesson->id)); ?></td>
	<td>
		<?php 
			if($data->lesson->moving_class == 1){
				echo $data->lesson->grade->name;
			}else{
				echo $data->lesson->class->name;
			}	
			 
		?>
	</td>
</tr>