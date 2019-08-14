<?php
/* @var $this LotController */
/* @var $model Lot */
$this->breadcrumbs=array(
	'User'=>array('index'),
	'Bulk',
);

?>
<div class="container">
	<h2>Bulk User</h2>
	<p> <strong>Untuk Format File Yang Akan Di Import Silahkan Download File Di Dibawah Ini</strong></p>
	<p><?php echo CHtml::link('format-user.xlsx', array('downloadFile'));?></p>
	<p><b>Role User Di Kolom Role Dalam File Adalah Sebagai Berikut : </b></p>
	<div>
		<ul>
			<li><b>siswa</b></li>
			<li><b>guru</b></li>
			<li><b>walikelas</b></li>
			<li><b>kepalasekolah</b></li>
		</ul>
	</div>
	<p><b>ID Kelas Bisa Dilihat Di Daftar Kelas</b></p>
	<p>*ID Kelas Hanya Digunakan Untuk Role User Siswa Saja</p>
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
