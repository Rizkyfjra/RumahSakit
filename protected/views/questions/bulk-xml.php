<?php
/* @var $this LotController */
/* @var $model Lot */
$this->breadcrumbs=array(
	'Soal'=>array('index'),
	'Bulk XML',
);
if(Yii::app()->user->YiiTeacher){
	$mapel = Lesson::model()->findAll(array('condition'=>'user_id = '.Yii::app()->user->id));
}else{
	$mapel = Lesson::model()->findAll();
}
$lesson = array();
foreach ($mapel as $value) {
	$lesson[$value->id]=$value->name." (".$value->class->name.")";
}

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/chosen.jquery.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/chosen.css');

?>
<div class="container well col-md-6">
	<h2>Import Soal Format XML</h2>
	<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
	 'id'=>'csv-form',
	 'enableAjaxValidation'=>false,
	    'htmlOptions'=>array('enctype' => 'multipart/form-data'),
	)); ?>
	 
	 <?php //echo $form->errorSummary($model); ?>
	 <?php echo $form->hiddenField($model,'activity_type',array('value'=>'test')); ?>
	 <div class="form-group">
	 	<label>Nama Soal</label>
	 	<input type="text" name="ulangan" required="required" class="form-control">
	 </div>
	 <div class="row">
	 </div>
	 <div class="row">
	 </div>
	 <div class="form-group">
	  <?php echo $form->labelEx($model,'Pilih File XML :'); ?>
	        <?php 
	            $this->widget('CMultiFileUpload', array(
	                'model'=>$model,
	                'name' => 'xmlfile',
	                'max'=>1,
	                'accept' => 'xml',
	                'duplicate' => 'Duplicate file!', 
	                'denied' => 'Invalid file type',              
	            ));
	        ?>
	  <?php //echo $form->error($model,'csvfile'); ?>
	 </div>
	 <div class="row">
	 </div>
	 <div class="form-group">
	  <?php echo CHtml::submitButton('Import',array("id"=>"Import",'name'=>'Import','class'=>'btn btn-success')); ?>
	 </div>
	<?php $this->endWidget(); ?>
	</div><!-- form -->

</div>