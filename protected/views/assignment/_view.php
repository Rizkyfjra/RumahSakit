<?php
/* @var $this AssignmentController */
/* @var $data Assignment */
	$status = null;
	if (Yii::app()->user->YiiStudent) {
		$current_user = Yii::app()->user->id;
		$assignment_id = $data->id;
		$modelCek=StudentAssignment::model()->findAll(array("condition"=>"assignment_id = $assignment_id and student_id = $current_user"));
		if (!empty($modelCek)){
			$status = "<span class='glyphicon glyphicon-ok'></span>";
		} else {
			$status = "<span class='glyphicon glyphicon-remove'></span>";
		}
	}
	
?>

<?php
	$lesson = $data->lesson;
	$class = $lesson->class;
	
	echo "<tr>";
	echo "<td>".CHtml::link(CHtml::encode($data->id), array('assignment/view', 'id'=>$data->id))."</td>";
	echo "<td>".CHtml::link(CHtml::encode($data->title), array('assignment/view', 'id'=>$data->id))."</td>";
	echo "<td>".$lesson->name."</td>";
	echo "<td>".$class->name."</td>";
	echo "<td>".CHtml::encode($data->due_date)."</td>";
	if($status != null){
	echo "<td>$status</td>";
	}
	echo "</tr>";


?>