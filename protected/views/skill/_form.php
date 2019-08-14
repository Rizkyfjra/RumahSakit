<?php
/* @var $this SkillController */
/* @var $model Skill */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/wysihtml5-0.3.0.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap3-wysihtml5.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/wizard.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/tinymce/tinymce.min.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image',CClientScript::POS_HEAD);
/*Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/jquery-upload/js/jquery.fileupload.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/jquery-upload/js/jquery.fileupload-image.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/jquery-upload/js/jquery.fileupload-process.js',CClientScript::POS_HEAD);*/

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap-wysihtml5.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/wizard.css');
$rekap=array(
	NULL=>'Ya',
	1=>'Tidak');
$smt=array(1=>'1',2=>'2');

$lid=NULL;
if(isset($_GET['lks_id'])){
    $lid=$_GET['lks_id'];
}

$tipe = NULL;
if(isset($_GET['type'])){
    $tipe = $_GET['type'];
}

$lsn=NULL;
if(isset($_GET['lesson_id'])){
    $lsn = $_GET['lesson_id'];
}

?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'skill-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<!-- <p class="note">Fields with <span class="required">*</span> are required.</p> -->

	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('class'=>'form-control')); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan',array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script>
   /* $('.textarea').wysihtml5({
    "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
    "emphasis": true, //Italics, bold, etc. Default true
    "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
    "html": true, //Button which allows you to edit the generated HTML. Default false
    "link": true, //Button to insert a link. Default true
    "image": false, //Button to insert an image. Default true,
    "color": false, //Button to change color of font
    "size": 'sm' //Button size like sm, xs etc.
});*/
	tinymce.init({
	    selector: "textarea",
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
	        "insertdatetime media table contextmenu paste jbimages"
	    ],
	    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages"
		
	});
	
</script>