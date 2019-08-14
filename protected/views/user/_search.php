<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="control-group">
		<div class="controls">
			<?php echo $form->label($model,'id'); ?>
			<?php echo $form->textField($model,'id'); ?>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<?php echo $form->label($model,'email'); ?>
			<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255)); ?>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<?php echo $form->label($model,'username'); ?>
			<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>255)); ?>
		</div>
	</div>

	<div class="control-group">
		<div class="controls">
			<?php echo $form->label($model,'role_id'); ?>
			<?php echo $form->textField($model,'role_id'); ?>
		</div>
	</div>

	<div class="buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>


<?php $this->endWidget(); ?>

</div><!-- search-form -->