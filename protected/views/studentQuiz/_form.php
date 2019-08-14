<?php
/* @var $this StudentQuizController */
/* @var $model StudentQuiz */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'student-quiz-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'quiz_id'); ?>
		<?php echo $form->textField($model,'quiz_id'); ?>
		<?php echo $form->error($model,'quiz_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'student_id'); ?>
		<?php echo $form->textField($model,'student_id'); ?>
		<?php echo $form->error($model,'student_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_at'); ?>
		<?php echo $form->textField($model,'created_at'); ?>
		<?php echo $form->error($model,'created_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated_at'); ?>
		<?php echo $form->textField($model,'updated_at'); ?>
		<?php echo $form->error($model,'updated_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'score'); ?>
		<?php echo $form->textField($model,'score'); ?>
		<?php echo $form->error($model,'score'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'right_answer'); ?>
		<?php echo $form->textField($model,'right_answer'); ?>
		<?php echo $form->error($model,'right_answer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wrong_answer'); ?>
		<?php echo $form->textField($model,'wrong_answer'); ?>
		<?php echo $form->error($model,'wrong_answer'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'unanswered'); ?>
		<?php echo $form->textField($model,'unanswered'); ?>
		<?php echo $form->error($model,'unanswered'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'student_answer'); ?>
		<?php echo $form->textArea($model,'student_answer',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'student_answer'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->