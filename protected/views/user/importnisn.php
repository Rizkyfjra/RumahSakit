<?php
/* @var $this LotController */
/* @var $model Lot */
$this->breadcrumbs=array(
	'Lesson'=>array('index'),
	'Bulk',
);

?>
<div class="container">
	<h2>Import NISN</h2>
	
	<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
	 'id'=>'csv-form',
	 'enableAjaxValidation'=>false,
	    'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>
	 
	 <?php //echo $form->errorSummary($model); ?>
	 <?php echo $form->hiddenField($model,'activity_type',array('value'=>'test')); ?>
	 <div class="form-group">
	  <?php echo $form->labelEx($model,'Pilih Excel :'); ?>
	        <?php 
	            $this->widget('CMultiFileUpload', array(
	                'model'=>$model,
	                'name' => 'csvfile',
	                'max'=>1,
	                'accept' => 'xlsx',
	                'duplicate' => 'Duplicate file!', 
	                'denied' => 'Invalid file type',              
	            ));
	        ?>
	  <?php echo $form->error($model,'csvfile'); ?>
	 </div>
	 
	 <div class="form-group">
	  <?php echo CHtml::submitButton('Import',array("id"=>"Import",'name'=>'Import','class'=>'btn btn-success')); ?>
	 </div>
	<?php $this->endWidget(); ?>
	</div><!-- form -->

</div>
