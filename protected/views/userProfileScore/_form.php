<?php
/* @var $this UserProfileScoreController */
/* @var $model UserProfileScore */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-profile-score-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Kolom dengan tanda <span class="required">*</span> wajib di isi.</p>

	<?php echo $form->errorSummary($model); ?>

	<br/><br/>
	<div class="form-group">
		<div class="alert alert-info">
			<p><strong>DATA SEJARAH NILAI SISWA</strong></p>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<label></label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<label>AGAMA</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<label>PKN</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<label>B.INDO</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<label>B.INGG</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<label>MAT</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<label>IPA</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<label>IPS</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<label>SENI</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<label>OR</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<label>TIK</label>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<label>Semester 1</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_01_pai',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_01_pkn',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_01_bindo',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_01_bingg',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_01_mat',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_01_ipa',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_01_ips',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_01_seni',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_01_or',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_01_tik',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<label>Semester 2</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_02_pai',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_02_pkn',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_02_bindo',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_02_bingg',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_02_mat',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_02_ipa',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_02_ips',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_02_seni',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_02_or',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_02_tik',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<label>Semester 3</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_03_pai',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_03_pkn',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_03_bindo',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_03_bingg',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_03_mat',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_03_ipa',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_03_ips',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_03_seni',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_03_or',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_03_tik',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<label>Semester 4</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_04_pai',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_04_pkn',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_04_bindo',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_04_bingg',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_04_mat',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_04_ipa',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_04_ips',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_04_seni',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_04_or',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_04_tik',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<label>Semester 5</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_05_pai',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_05_pkn',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_05_bindo',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_05_bingg',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_05_mat',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_05_ipa',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_05_ips',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_05_seni',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_05_or',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_05_tik',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-2">
			<div class="form-group">
				<label>Semester 6</label>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_06_pai',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_06_pkn',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_06_bindo',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_06_bingg',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_06_mat',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_06_ipa',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_06_ips',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_06_seni',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_06_or',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
		<div class="col-sm-1">
			<div class="form-group">
				<?php echo $form->textField($model,'smt_06_tik',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
			</div>
		</div>
	</div>

	<br/>
	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Perbaharui',array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->