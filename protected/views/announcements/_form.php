<?php
/* @var $this AnnouncementsController */
/* @var $model Announcements */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/wysihtml5-0.3.0.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap3-wysihtml5.js',CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap-wysihtml5.css');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/tinymce/tinymce.min.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image',CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/wizard.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/wizard.css');

$pilihan = array(
			1=>'Umum',
			2=>'Dokter',
			3=>'Pasien');
	if(!empty($model->type)){
		$selected = $model->type;
	}else{
		$selected = 1;
	}
?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'announcements-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<label for="exampleInputEmail1">Judul</label>
		<?php echo $form->textField($model,'title',array('class'=>'form-control','placeholder'=>'Judul')); ?>
		<?php echo $form->error($model,'title'); ?>
	</div>

	<div class="form-group">
		<label for="exampleInputEmail1">Tipe Pengumuman</label>
		<?php echo $form->dropDownList($model,'type',$pilihan,array('class'=>'form-control','options'=>array($selected=>array('selected'=>true)))); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="form-group">
		<label for="exampleInputEmail1">Isi Pengumuman</label>
		<?php echo $form->textArea($model,'content',array('id'=>'textword1','rows'=>6, 'cols'=>50,'class'=>'textarea form-control','placeholder'=>'Isi teks ...')); ?>
		<?php echo $form->error($model,'content'); ?>
	</div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Tambah' : 'Simpan',array('class'=>'btn btn-success')); ?>
	</div>

<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/ckeditor/ckeditor.js"></script>
<script>

CKEDITOR.replace( 'textword1' );
//     $('.textarea').wysihtml5({
//     "font-styles": true, //Font styling, e.g. h1, h2, etc. Default true
//     "emphasis": true, //Italics, bold, etc. Default true
//     "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
//     "html": true, //Button which allows you to edit the generated HTML. Default false
//     "link": true, //Button to insert a link. Default true
//     "image": true, //Button to insert an image. Default true,
//     "color": true, //Button to change color of font
//     "size": 'sm' //Button size like sm, xs etc.
// });

  //           tinymce.init({
		//     selector: ".textarea",
		//     invalid_elements :'script',
		//     // wirisimagebgcolor: '#FFFFFF',
		//     // wirisimagesymbolcolor: '#000000',
		//     // wiristransparency: 'true',
		//     // wirisimagefontsize: '16',
		//     // wirisimagenumbercolor: '#000000',
		//     // wirisimageidentcolor: '#000000',
		//     // wirisformulaeditorlang: 'en',
		//     plugins: [
		//         "advlist autolink lists link image charmap print preview anchor",
		//         "searchreplace visualblocks code fullscreen",
		//         "insertdatetime media table contextmenu paste jbimages"
		//     ],
		//     toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | fontsizeselect",
		// 	relative_urls: false
		// });
</script>
<?php $this->endWidget(); ?>

