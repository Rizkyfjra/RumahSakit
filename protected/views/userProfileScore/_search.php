<?php
/* @var $this UserProfileScoreController */
/* @var $model UserProfileScore */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_01_pai'); ?>
		<?php echo $form->textField($model,'smt_01_pai'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_01_pkn'); ?>
		<?php echo $form->textField($model,'smt_01_pkn'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_01_bindo'); ?>
		<?php echo $form->textField($model,'smt_01_bindo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_01_bingg'); ?>
		<?php echo $form->textField($model,'smt_01_bingg'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_01_mat'); ?>
		<?php echo $form->textField($model,'smt_01_mat'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_01_ipa'); ?>
		<?php echo $form->textField($model,'smt_01_ipa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_01_ips'); ?>
		<?php echo $form->textField($model,'smt_01_ips'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_01_seni'); ?>
		<?php echo $form->textField($model,'smt_01_seni'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_01_or'); ?>
		<?php echo $form->textField($model,'smt_01_or'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_01_tik'); ?>
		<?php echo $form->textField($model,'smt_01_tik'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_02_pai'); ?>
		<?php echo $form->textField($model,'smt_02_pai'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_02_pkn'); ?>
		<?php echo $form->textField($model,'smt_02_pkn'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_02_bindo'); ?>
		<?php echo $form->textField($model,'smt_02_bindo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_02_bingg'); ?>
		<?php echo $form->textField($model,'smt_02_bingg'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_02_mat'); ?>
		<?php echo $form->textField($model,'smt_02_mat'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_02_ipa'); ?>
		<?php echo $form->textField($model,'smt_02_ipa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_02_ips'); ?>
		<?php echo $form->textField($model,'smt_02_ips'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_02_seni'); ?>
		<?php echo $form->textField($model,'smt_02_seni'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_02_or'); ?>
		<?php echo $form->textField($model,'smt_02_or'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_02_tik'); ?>
		<?php echo $form->textField($model,'smt_02_tik'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_03_pai'); ?>
		<?php echo $form->textField($model,'smt_03_pai'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_03_pkn'); ?>
		<?php echo $form->textField($model,'smt_03_pkn'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_03_bindo'); ?>
		<?php echo $form->textField($model,'smt_03_bindo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_03_bingg'); ?>
		<?php echo $form->textField($model,'smt_03_bingg'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_03_mat'); ?>
		<?php echo $form->textField($model,'smt_03_mat'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_03_ipa'); ?>
		<?php echo $form->textField($model,'smt_03_ipa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_03_ips'); ?>
		<?php echo $form->textField($model,'smt_03_ips'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_03_seni'); ?>
		<?php echo $form->textField($model,'smt_03_seni'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_03_or'); ?>
		<?php echo $form->textField($model,'smt_03_or'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_03_tik'); ?>
		<?php echo $form->textField($model,'smt_03_tik'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_04_pai'); ?>
		<?php echo $form->textField($model,'smt_04_pai'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_04_pkn'); ?>
		<?php echo $form->textField($model,'smt_04_pkn'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_04_bindo'); ?>
		<?php echo $form->textField($model,'smt_04_bindo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_04_bingg'); ?>
		<?php echo $form->textField($model,'smt_04_bingg'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_04_mat'); ?>
		<?php echo $form->textField($model,'smt_04_mat'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_04_ipa'); ?>
		<?php echo $form->textField($model,'smt_04_ipa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_04_ips'); ?>
		<?php echo $form->textField($model,'smt_04_ips'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_04_seni'); ?>
		<?php echo $form->textField($model,'smt_04_seni'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_04_or'); ?>
		<?php echo $form->textField($model,'smt_04_or'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_04_tik'); ?>
		<?php echo $form->textField($model,'smt_04_tik'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_05_pai'); ?>
		<?php echo $form->textField($model,'smt_05_pai'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_05_pkn'); ?>
		<?php echo $form->textField($model,'smt_05_pkn'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_05_binddo'); ?>
		<?php echo $form->textField($model,'smt_05_binddo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_05_bingg'); ?>
		<?php echo $form->textField($model,'smt_05_bingg'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_05_mat'); ?>
		<?php echo $form->textField($model,'smt_05_mat'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_05_ipa'); ?>
		<?php echo $form->textField($model,'smt_05_ipa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_05_ips'); ?>
		<?php echo $form->textField($model,'smt_05_ips'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_05_seni'); ?>
		<?php echo $form->textField($model,'smt_05_seni'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_05_or'); ?>
		<?php echo $form->textField($model,'smt_05_or'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_05_tik'); ?>
		<?php echo $form->textField($model,'smt_05_tik'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_06_pai'); ?>
		<?php echo $form->textField($model,'smt_06_pai'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_06_pkn'); ?>
		<?php echo $form->textField($model,'smt_06_pkn'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_06_bindo'); ?>
		<?php echo $form->textField($model,'smt_06_bindo'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_06_bingg'); ?>
		<?php echo $form->textField($model,'smt_06_bingg'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_06_mat'); ?>
		<?php echo $form->textField($model,'smt_06_mat'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_06_ipa'); ?>
		<?php echo $form->textField($model,'smt_06_ipa'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_06_ips'); ?>
		<?php echo $form->textField($model,'smt_06_ips'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_06_seni'); ?>
		<?php echo $form->textField($model,'smt_06_seni'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_06_or'); ?>
		<?php echo $form->textField($model,'smt_06_or'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'smt_06_tik'); ?>
		<?php echo $form->textField($model,'smt_06_tik'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->