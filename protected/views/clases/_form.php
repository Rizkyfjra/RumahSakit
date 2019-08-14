<?php
/* @var $this ClasesController */
/* @var $model Clases */
/* @var $form CActiveForm */
$wali=array();
//$walis = User::model()->findAll(array('condition'=>'role_id = 4 or role_id = 1'));
$walis = Clases::model()->findAll();
if(!empty($walis)){
	foreach ($walis as $value) {
		//array_push($mapel['key'],$value->id);
		$wali[$value->id]=$value->name;
	}
	//print_r($mapel);
	//echo "ada";
}
	if(!empty($model->teacher_id)){
		$model->teacher_id = $model->teacher->display_name.' | '.$model->teacher->username.' (ID:'.$model->teacher->id.')';
	}
?>
<div class="col-md-5">
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'clases-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
    'htmlOptions' => array('enctype' => 'multipart/form-data'),
    ));

 ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<?php
		if(!empty($model->class_id)){
			$selected = $model->class_id;
		}else{
			$selected = 1;
		}
	?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<?php //if($model->isNewRecord){ ?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'class_id'); ?>
		<?php echo $form->dropdownList($model,'class_id',$wali,array('class'=>'form-control','id'=>'pilihan', 'options'=>array($selected=>array('selected'=>true))))?>
	</div>
	<div class="form-group">
		<?php
			echo "Penanggung Jawab";
			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'model'=>$model,
	    		'attribute'=>'teacher_id',
				// additional javascript options for the autocomplete plugin
				'options'=>array(
				'minLength'=>'1',
				),
				'source'=>$this->createUrl("user/suggestWali"),
				'htmlOptions'=>array(
				//'style'=>'height:20px;',
				'class'=>'form-control'
				//'required'=>$model->verification_status>=1,
				),
			));
		?>
		<?php //echo $form->error($model,'teacher_id'); ?>
	</div>
	<?php //} ?>
	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
</div>