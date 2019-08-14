<?php
/* @var $this LotController */
/* @var $model Lot */
$this->breadcrumbs=array(
	'Soal'=>array('index'),
	'Bulk',
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
	<h2>Import Soal</h2>
	<p>Untuk Format File Yang Akan Di Import Silahkan Download File Di Dibawah Ini</p>
	<p>Kemudian Save As Dalam Bentuk "CSV (Comma Delimited)".</p>
	<p>Dengan List Separator Menggunakan Tanda PAGAR "#".</p>
	<p><?php echo CHtml::link('contoh-format-import-soal.csv', array('downloadFile'));?></p>
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
	  <?php echo $form->labelEx($model,'Pilih CSV :'); ?>
	        <?php 
	            $this->widget('CMultiFileUpload', array(
	                'model'=>$model,
	                'name' => 'csvfile',
	                'max'=>1,
	                'accept' => 'csv',
	                'duplicate' => 'Duplicate file!', 
	                'denied' => 'Invalid file type',              
	            ));
	        ?>
	  <?php echo $form->error($model,'csvfile'); ?>
	 </div>
	 <div class="row">
	 </div>
	 <div class="form-group">
	  <?php echo CHtml::submitButton('Import',array("id"=>"Import",'name'=>'Import','class'=>'btn btn-success')); ?>
	 </div>
	<?php $this->endWidget(); ?>
	</div><!-- form -->

</div>
<script type="text/javascript">
	var config = {
	      '.chosen-select'           : {},
	      '.chosen-select-deselect'  : {allow_single_deselect:true},
	      '.chosen-select-no-single' : {disable_search_threshold:10},
	      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
	      '.chosen-select-width'     : {width:"95%"}
	    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
</script>