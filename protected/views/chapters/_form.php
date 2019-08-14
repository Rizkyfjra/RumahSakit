<?php
/* @var $this ChaptersController */
/* @var $model Chapters */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/wysihtml5-0.3.0.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/bootstrap3-wysihtml5.js',CClientScript::POS_HEAD);

//Blueimp CSS Dependencies 
/*Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/blueimp/css/style.css'); 
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/blueimp/css/jquery.fileupload.css'); 
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/blueimp/css/jquery.fileupload-ui.css');   
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/blueimp/css/jquery.fileupload-noscript.css'); 
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/blueimp/css/jquery.fileupload-ui-noscript.css');*/  

Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap-wysihtml5.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/bootstrap3-wysiwyg5-color.css');

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/wizard.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/wizard.css');

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/tinymce/tinymce.min.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image',CClientScript::POS_HEAD);


// Blueimp Jquery Dependencies
/*Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/blueimp/js/vendor/jquery.ui.widget.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/JavaScript-Load-Image/js/load-image.all.min.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/JavaScript-Templates/js/tmpl.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/blueimp/js/vendor/jquery.ui.widget.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/blueimp/js/jquery.iframe-transport.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/blueimp/js/jquery.fileupload.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/blueimp/js/jquery.fileupload-process.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/blueimp/js/jquery.fileupload-image.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/blueimp/js/jquery.fileupload-audio.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/blueimp/js/jquery.fileupload-video.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/blueimp/js/jquery.fileupload-validate.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/blueimp/js/jquery.fileupload-ui.js',CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/blueimp/js/main.js',CClientScript::POS_END);*/

if (!empty($model->id_lesson)) {
	$lesson = $model->mapel;
	$model->id_lesson = $lesson->name.' (ID:'.$model->id_lesson.')';
	
}
$mapel=array();
if(!empty($lessons)){
	//$mp=$lessons->getData();
	foreach ($lessons as $value) {
		//array_push($mapel['key'],$value->id);
		if($value->moving_class == 1){
			$mapel[$value->id]=$value->name." (".$value->grade->name.")";
		}else{
			$mapel[$value->id]=$value->name." (".$value->class->name.")";
		}
		
	}
	//print_r($mapel);
}
$type=array(1=>'Video',2=>'Gambar',3=>'Dokumen');
$smt=array(1=>'1',2=>'2');

$lid=NULL;
if(isset($_GET['lks_id'])){
	$lid=$_GET['lks_id'];
}

$lesson_id = null;
if(isset($_GET['lesson_id'])){
	$lesson_id = $_GET['lesson_id'];
}
?>
<div class="stepwizard">
    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
            <p>Judul & Tipe Materi</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
            <p>Pilih Kelas</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
            <p>Isi Materi & Lampiran</p>
        </div>
    </div>
</div>

<!-- <div class="form col-md-6"> -->

<?php $form=$this->beginWidget('CActiveForm', array(
	//'id'=>'chapters-form',
	'id'=>'fileupload',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),	
)); ?>

	<!-- <p class="note">Fields with <span class="required">*</span> are required.</p> -->

	<?php
		if(!empty($model->id_lesson)){
			$selected = $model->id_lesson;
		}else{
			$selected = 1;
		}

		if(!empty($model->chapter_type)){
			$slc = $model->chapter_type;
		} else {
			$slc = NULL;
		}

		if(!empty($model->semester)){
			$sld = $model->semester;
		}else{
			$sld = 1;
		}
		
	?>

	<?php echo $form->errorSummary($model); ?>
	<?php echo $form->errorSummary($model2); ?>
	<input type="hidden" name="lks_id" value="<?php echo $lid?>">
	<div class="row setup-content" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Step 1</h3>
               	<div class="form-group">
					<?php echo $form->labelEx($model,'title'); ?>
					<?php echo $form->textField($model,'title',array('class'=>'form-control','required'=>'required')); ?>
					<?php echo $form->error($model,'title'); ?>
				</div>
                <div class="form-group">
					<?php echo $form->labelEx($model,'chapter_type'); ?>
					<?php echo $form->dropdownList($model,'chapter_type',$type,array('class'=>'form-control','required'=>'required','id'=>'pilihan','empty'=>'Pilih Tipe Materi','options'=>array($slc=>array('selected'=>true)))); ?>
					<?php echo $form->error($model,'chapter_type'); ?>
				</div>
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Berikutnya</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-2">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Step 2</h3>
                <?php if(empty($lesson_id)){ ?>
                <div class="form-group">
					<?php echo $form->labelEx($model,'id_lesson'); ?>
					<?php echo $form->dropdownList($model,'id_lesson',$mapel,array('class'=>'form-control', 'options'=>array($selected=>array('selected'=>true))))?>
					<?php echo $form->error($model,'id_lesson'); ?>
				</div>
				<?php } ?>
               <div class="form-group">
					<?php echo $form->labelEx($model,'semester'); ?>
					<?php echo $form->dropdownList($model,'semester',$smt,array('class'=>'form-control','options'=>array($sld=>array('selected'=>true)))); ?>
					<?php echo $form->error($model,'semester'); ?>
				</div>
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Berikutnya</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-3">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Step 3</h3>
                <div class="form-group">
					<?php echo $form->labelEx($model2,'file'); ?>
					<?php echo $form->fileField($model2,'file',array('class'=>'form-control')); ?>
					<?php echo $form->error($model2,'file'); ?>
					<p class="help-block">Maksimal Ukuran file 2 Mb</p>
					<!-- <div class="row fileupload-buttonbar">
			            <div class="col-lg-7">
			                
			                <span class="btn btn-success fileinput-button">
			                    <i class="glyphicon glyphicon-plus"></i>
			                    <span>Tambah...</span>
			                    <input type="file" name="files[]" multiple>
			                </span>
			                
			                <button type="reset" class="btn btn-warning cancel">
			                    <i class="glyphicon glyphicon-ban-circle"></i>
			                    <span>Batal</span>
			                </button>
			    
			            </div>
			            
			            <div class="col-lg-5 fileupload-progress fade">
			               
			                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
			                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
			                </div>
			               
			                <div class="progress-extended">&nbsp;</div>
			            </div>
			        </div> -->
			        
			        <table role="presentation" class="table table-striped well"><tbody class="files"></tbody></table>
				</div>
				<div class="form-group">
					<?php echo $form->labelEx($model,'content'); ?>
					<?php echo $form->textArea($model,'content',array('class'=>'textarea form-control','rows'=>4, 'cols'=>30)); ?>
					<?php echo $form->error($model,'content'); ?>
				</div>
                <button class="btn btn-success btn-lg pull-right" type="submit">Selesai!</button>
            </div>
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
	    invalid_elements :'script',
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
	    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | tiny_mce_wiris_formulaEditor | fontsizeselect"
		
	});
</script>
<script id="template-upload" type="text/x-tmpl">
// {% for (var i=0, file; file=o.files[i]; i++) { %}
//     <tr class="template-upload fade">
//         <td>
//             <span class="preview"></span>
//         </td>
//         <td>
//             <p class="name">{%=file.name%}</p>
//             <strong class="error text-danger"></strong>
//         </td>
//         <td>
//             <p class="size">Processing...</p>
//             <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
//         </td>
//         <td>
//             {% if (!i) { %}
//                 <button class="btn btn-warning cancel">
//                     <i class="glyphicon glyphicon-ban-circle"></i>
//                     <span>Cancel</span>
//                 </button>
//             {% } %}
//         </td>
//     </tr>
// {% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
// {% for (var i=0, file; file=o.files[i]; i++) { %}
//     <tr class="template-download fade">
//         <td>
//             <span class="preview">
//                 {% if (file.thumbnailUrl) { %}
//                     <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}"></a>
//                 {% } %}
//             </span>
//         </td>
//         <td>
//             <p class="name">
//                 {% if (file.url) { %}
//                     <a href="{%=file.url%}" title="{%=file.name%}" download="{%=file.name%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
//                 {% } else { %}
//                     <span>{%=file.name%}</span>
//                 {% } %}
//             </p>
//             {% if (file.error) { %}
//                 <div><span class="label label-danger">Error</span> {%=file.error%}</div>
//             {% } %}
//         </td>
//         <td>
//             <span class="size">{%=o.formatFileSize(file.size)%}</span>
//         </td>
//         <td>
//             {% if (file.deleteUrl) { %}
//                 <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>
//                     <i class="glyphicon glyphicon-trash"></i>
//                     <span>Delete</span>
//                 </button>
//                 <input type="checkbox" name="delete" value="1" class="toggle">
//             {% } else { %}
//                 <button class="btn btn-warning cancel">
//                     <i class="glyphicon glyphicon-ban-circle"></i>
//                     <span>Cancel</span>
//                 </button>
//             {% } %}
//         </td>
//     </tr>
// {% } %}
</script>
<?php $this->endWidget(); ?>

<!-- </div> --><!-- form -->