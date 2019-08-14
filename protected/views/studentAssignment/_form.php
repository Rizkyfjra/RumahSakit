<?php
/* @var $this StudentAssignmentController */
/* @var $model StudentAssignment */
/* @var $form CActiveForm */


?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'studentAssignment',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>
	<!-- <p class="note">Fields with <span class="required">*</span> are required.</p> -->

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->hiddenField($model,'student_id',array('value'=>Yii::app()->user->id));?>
	<?php if(!empty($tugas_id)) { ?>
	<?php echo $form->hiddenField($model,'assignment_id',array('value'=>$tugas_id));?>
	<?php } else { ?>
		<?php echo $form->hiddenField($model,'assignment_id');?>
	<?php } ?>
	<?php if(!empty($id_sa)){ ?>
		<?php echo $form->hiddenField($model,'id',array('value'=>$id_sa));?>
	<?php } ?>
	<?php if($type == NULL || $type == 2){ ?>
	<div class="form-group">
		<label for="exampleInputEmail1">Jawaban</label>
		<?php echo $form->textArea($model,'content',array('class'=>'textarea form-control','rows'=>4, 'cols'=>50))?>
		<?php echo $form->error($model,'content')?>
	</div>
	<?php } ?>
	<?php if(Yii::app()->user->YiiStudent){ ?>
	<div class="form-group">
		<label for="exampleInputEmail1">File Yang Akan Diupload</label>
		<?php echo $form->fileField($model,'file'); ?>
		<?php echo $form->error($model,'file'); ?>
		<p class="help-block">Upload file tugas disini. (Max 5 Mb)</p>
	</div>
	<?php } else { ?>
		<?php if($type == 1){ ?>
		<div class="form-group">
			<label for="exampleInputEmail1">Nilai</label>
			<?php echo $form->textField($model,'score',array('class'=>'form-control')); ?>
			<?php echo $form->error($model,'score'); ?>
			<p class="help-block">Skala Nilai 1-100.</p>
		</div>
		<?php } ?>
	<?php } ?>
	<?php if(Yii::app()->user->YiiStudent){ ?>
	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Kumpulkan' : 'Kumpulkan',array('class'=>'btn btn-success','name'=>'upload', 'confirm'=>'Yakin Mengumpulkan Tugas ?')); ?>
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan',array('class'=>'btn btn-primary','name'=>'save')); ?>
	</div>
	<?php }else{?> 
		<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan',array('class'=>'btn btn-success','name'=>'upload')); ?>
	</div>
	<?php } ?>
<script>
    /*$('.textarea').wysihtml5({
	    "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
	    "emphasis": true, //Italics, bold, etc. Default true
	    "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
	    "html": true, //Button which allows you to edit the generated HTML. Default false
	    "link": true, //Button to insert a link. Default true
	    "image": true, //Button to insert an image. Default true,
	    "color": true, //Button to change color of font
	    "size": 'sm' //Button size like sm, xs etc.
	});*/
	tinymce.init({
	    selector: "textarea",
	    invalid_elements :'script',
	    // wirisimagebgcolor: '#FFFFFF',
	    // wirisimagesymbolcolor: '#000000',
	    // wiristransparency: 'true',
	    // wirisimagefontsize: '16',
	    // wirisimagenumbercolor: '#000000',
	    // wirisimageidentcolor: '#000000',
	    // wirisformulaeditorlang: 'en',
	    plugins: [
	        "advlist autolink lists link image charmap print preview anchor",
	        "searchreplace visualblocks code fullscreen",
	        "insertdatetime media table contextmenu paste"
	    ],
	    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image | fontsizeselect"
		
	});

	/*$( function() {
		$( "#studentAssignment" ).sisyphus({
			timeout: 1
		});
	} );

	$("#studentAssignment").submit(function( event ) {
		  localStorage.clear();
	});*/

</script>

<?php $this->endWidget(); ?>

</div><!-- form -->