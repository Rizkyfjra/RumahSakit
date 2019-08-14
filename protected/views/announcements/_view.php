<?php
/* @var $this AnnouncementsController */
/* @var $data Announcements */
?>
<li><?php echo CHtml::link(CHtml::encode($data->title),array('Announcements/view','id'=>$data->id)); ?> <!-- <span>(<?php //echo CHtml::encode(date('d M Y',strtotime($data->created_at))); ?>)</span> --></li>
<!-- <tr>
	<td><?php //echo CHtml::link(CHtml::encode($data->id),array('Announcements/view','id'=>$data->id)); ?></td>
	<td></td>
	<td><?php //echo CHtml::encode($data->content); ?></td>
	<td></td>
	<?php //if(Yii::app()->user->YiiAdmin){ ?>
	<td><?php //echo CHtml::link('<i class="glyphicon glyphicon-edit" style="color:green"></i>', array('Announcements/update','id'=>$data->id));?></td>
	<?php //} ?>
</tr>	 -->