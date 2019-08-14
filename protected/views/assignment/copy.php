<?php
/* @var $this ChaptersController */
/* @var $model Chapters */
/* @var $form CActiveForm */

$this->breadcrumbs=array(
	'Tugas'=>array('index'),
	$tugas->title=>array('view','id'=>$tugas->id),
	'Salin'
);

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/wysihtml5-0.3.0.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap3-wysihtml5.js',CClientScript::POS_HEAD);

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap-wysihtml5.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap3-wysiwyg5-color.css');

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/wizard.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/wizard.css');

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/tinymce/tinymce.min.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image',CClientScript::POS_HEAD);

if (!empty($model->lesson_id)) {
	$lesson = $model->mapel;
	$model->lesson_id = $lesson->name.' (ID:'.$model->lesson_id.')';
	
}
$mapel=array();
if(!empty($lessons)){
	//$mp=$lessons->getData();
	foreach ($lessons as $value) {
		//array_push($mapel['key'],$value->id);
		$mapel[$value->id]=$value->name." (".$value->class->name.")";
	}
	//print_r($mapel);
}
$rekap=array(
	NULL=>'Ya',
	1=>'Tidak');
if(!empty($model->add_to_summary)){
		$slc = $model->add_to_summary;
	}else{
		$slc = NULL;
	}
		
$type=array(1=>'Video',2=>'Gambar',3=>'Dokumen');
$smt=array(1=>'1',2=>'2');

$lid=NULL;
if(isset($_GET['lks_id'])){
	$lid=$_GET['lks_id'];
}
?>

<!-- <div class="form col-md-6"> -->

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'chapters-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),	
)); ?>

	<!-- <p class="note">Fields with <span class="required">*</span> are required.</p> -->

	<?php
		if(!empty($model->lesson_id)){
			$selected = $model->lesson_id;
		}else{
			$selected = 1;
		}

		// if(!empty($model->chapter_type)){
		// 	$slc = $model->chapter_type;
		// } else {
		// 	$slc = NULL;
		// }

		if(!empty($model->semester)){
			$sld = $model->semester;
		}else{
			$sld = 1;
		}
		
	?>

	<?php echo $form->errorSummary($model); ?>
		
        <div class="col-xs-12">
            <div class="col-md-12">
               	<div class="form-group">
					<?php echo $form->labelEx($model,'title'); ?>
					<?php echo $form->textField($model,'title',array('class'=>'form-control','required'=>'required')); ?>
					<?php echo $form->error($model,'title'); ?>
				</div>
                <div class="form-group">
					<?php echo $form->labelEx($model,'lesson_id'); ?>
					<?php echo $form->dropdownList($model,'lesson_id',$mapel,array('class'=>'form-control', 'options'=>array($selected=>array('selected'=>true))))?>
					<?php echo $form->error($model,'lesson_id'); ?>
				</div>
				<div class="form-group">
    					<?php echo $form->labelEx($model,'due_date'); ?>
    					<?php
    					$this->widget('ext.YiiDateTimePicker.jqueryDateTime', array(
    				        'model' => $model,
    				        'attribute' => 'due_date',
    				        'options' => array(), //DateTimePicker options
    				        'htmlOptions' => array(
    				        	'class'=>'form-control'),
    				    ));
    				    ?>
    					<?php echo $form->error($model,'due_date'); ?>
    			</div>
    			<div class="form-group">
    					<?php echo $form->labelEx($model,'add_to_summary'); ?>
    					<?php echo $form->dropDownList($model,'add_to_summary',$rekap,array('class'=>'form-control', 'options'=>array($slc=>array('selected'=>true)))); ?>
    					<?php echo $form->error($model,'add_to_summary'); ?>
    			</div>
                <button class="btn btn-success btn-lg pull-right" type="submit">Selesai!</button>
            </div>
        </div>
   
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
    $('#pilihan').change(function(){
  		$('#chapterCollapse').collapse('show');
	});

	tinymce.init({
	    selector: "textarea",
	    wirisimagebgcolor: '#FFFFFF',
	    wirisimagesymbolcolor: '#000000',
	    wiristransparency: 'true',
	    wirisimagefontsize: '16',
	    wirisimagenumbercolor: '#000000',
	    wirisimageidentcolor: '#000000',
	    wirisformulaeditorlang: 'en',
	    plugins: [
	        "advlist autolink lists link image charmap print preview anchor",
	        "searchreplace visualblocks code fullscreen",
	        "insertdatetime media table contextmenu paste tiny_mce_wiris jbimages"
	    ],
	    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | tiny_mce_wiris_formulaEditor"
		
	});
</script>

<?php $this->endWidget(); ?>

<!-- </div> --><!-- form -->