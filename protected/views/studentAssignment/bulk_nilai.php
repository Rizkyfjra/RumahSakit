<form enctype="multipart/form-data" id="student-assignment-form" action="<?php echo Yii::app()->createUrl("StudentAssignment/bulkNilai"); ?>" method="post">
	
	<input value="<?php echo $string_id; ?>" name="string_id" id="StudentAssignment_student_id" type="hidden">
	<div class="form-group">
		<label for="exampleInputEmail1">Nilai</label>
		<input class="form-control" name="score" id="StudentAssignment_score" type="text">
	</div>
	<div class="form-group">
	<input class="btn btn-success" type="submit" name="submit3" value="Submit">	</div>
</form>