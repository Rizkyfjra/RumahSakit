<?php
/* @var $this UsersController */
/* @var $model Users */
/* @var $form CActiveForm */
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'change-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'htmlOptions' => array('class'=>'form-horizontal'),
	'enableAjaxValidation'=>false,
)); ?>

	<!-- <p class="note">Fields with <span class="required">*</span> are required.</p> -->

	<?php //echo $form->errorSummary($model); ?>

	<div class="form-group">
		<label for="input-password-0" class="col-sm-3 control-label">Password Lama</label>
		<div class="col-sm-9">
			<?php echo $form->passwordField($model,'old_password',array('class'=>'form-control', 'placeholder'=>'Password Lama')); ?>
		</div>
		<?php echo $form->error($model,'old_password');?>
	</div>

	<div class="form-group">
		<label for="input-password-1" class="col-sm-3 control-label">Password Baru</label>
		<div class="col-sm-9">
			<?php echo $form->passwordField($model,'new_password',array('class'=>'form-control', 'placeholder'=>'Password Baru')); ?>
		</div>
		<?php echo $form->error($model,'new_password');?>
	</div>

	<div class="form-group">
		<label for="input-password-2" class="col-sm-3 control-label">Ulang Password</label>
		<div class="col-sm-9">
			<?php echo $form->passwordField($model,'new_password2',array('class'=>'form-control', 'placeholder'=>'Ulang Password')); ?>
		</div>
		<?php echo $form->error($model,'new_password2');?>
	</div>
	<hr>
	<div class="form-group">
		<div class="col-sm-offset-3 col-sm-6">
			<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan',array('class'=>'btn btn-primary')); ?>
		</div>
	</div>

<?php $this->endWidget(); ?>
