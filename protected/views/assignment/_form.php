<?php
/* @var $this AssignmentController */
/* @var $model Assignment */
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
//Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/jquery-upload/css/jquery.fileupload.css');

//Blueimp CSS Dependencies 
/*Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/blueimp/css/style.css'); 
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/blueimp/css/jquery.fileupload.css'); 
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/blueimp/css/jquery.fileupload-ui.css');   
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/blueimp/css/jquery.fileupload-noscript.css'); 
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/blueimp/css/jquery.fileupload-ui-noscript.css');*/

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
	//echo "ada";
}

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


<div class="stepwizard">
    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
            <p>Judul & Batas Pengumpulan</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
            <p>Pilih Kelas & Opsi</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
            <p>Isi Tugas & Lampiran</p>
        </div>
    </div>
</div>

</div>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	//'id'=>'assignment-form',
    'id'=>'fileupload',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
	'enableAjaxValidation'=>false,
)); ?>

	<?php

	if(!empty($model->lesson_id)){
		$selected = $model->lesson_id;
	}else{
		$selected = 1;
	} 

	if(!empty($model->file)){
		$model->file = $model->file;
	}

	if(!empty($model->add_to_summary)){
		$slc = $model->add_to_summary;
	}else{
		$slc = NULL;
	}

    // if(!empty($model->semester)){
    //     $sld = $model->semester;
    // }else{
    //     $sld = 1;
    // }

	?>


	<?php echo $form->errorSummary($model); ?>

    <input type="hidden" name="lks_id" value="<?php echo $lid?>">
	<div class="row setup-content" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Langkah 1</h3>
               	<div class="form-group">
					<?php echo $form->labelEx($model,'title'); ?>
					<?php echo $form->textField($model,'title',array('class'=>'form-control','required'=>'required')); ?>
					<?php echo $form->error($model,'title'); ?>
				</div>
                <?php if(empty($tipe)){ ?>
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
                <?php } ?>
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Berikutnya</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-2">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Langkah 2</h3>
                <?php if(empty($lsn)){ ?>
                <div class="form-group">
					<?php echo $form->labelEx($model,'lesson_id'); ?>
					<?php echo $form->dropdownList($model,'lesson_id',$mapel,array('class'=>'form-control','id'=>'pilihan', 'options'=>array($selected=>array('selected'=>true))))?>
					<?php echo $form->error($model,'lesson_id'); ?>
				</div>
                <?php } ?>
                <?php if(empty($tipe)){ ?>
                    <div class="form-group">
    					<?php echo $form->labelEx($model,'add_to_summary'); ?>
    					<?php echo $form->dropDownList($model,'add_to_summary',$rekap,array('class'=>'form-control', 'options'=>array($slc=>array('selected'=>true)))); ?>
    					<?php echo $form->error($model,'add_to_summary'); ?>
    				</div>
                <?php } ?>
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Berikutnya</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-3">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Langkah 3</h3>
                
                <div class="form-group">
					<?php echo $form->labelEx($model,'content'); ?>
					<?php echo $form->textArea($model,'content',array('value'=>$model->content,'rows'=>6, 'cols'=>50, 'class'=>'textarea form-control')); ?>
					<?php echo $form->error($model,'content'); ?>
				</div>
                <?php if(empty($tipe)){ ?>
				<div class="form-group">
					<?php echo $form->labelEx($model,'file'); ?>
                     <input type="file" name="files[]" multiple> 
					
					<?php echo $form->error($model,'file'); ?>
                    
					<p class="help-block">Maksimal Ukuran file 2 Mb</p>
                    <!--
                        <input type="file" name="files[]" multiple> 
                        <div class="row fileupload-buttonbar">
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
                    </div>--> 
                    
                    <!-- <table role="presentation" class="table table-striped well"><tbody class="files"></tbody></table> -->
				</div>
                <?php } ?>
                <button class="btn btn-success btn-lg pull-right" type="submit" name="simpan">Simpan</button>
                <?php if(empty($tipe)){ ?>
                <span><button class="btn btn-primary btn-lg pull-right" type="submit" name="tampil">Tampilkan</button></span>
                <?php } ?>
            </div>
        </div>
    </div>

	<!-- 
	
	

	<div class="form-group">
		<?php echo $form->labelEx($model,'recipient'); ?>
		<?php
			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'model'=>$model,
	    		'attribute'=>'recipient',
				// additional javascript options for the autocomplete plugin
				'options'=>array(
				'minLength'=>'1',
				),
				'source'=>$this->createUrl("assignment/suggestSiswa"),
				'htmlOptions'=>array(
				//'style'=>'height:20px;',
				'class'=>'form-control'
				//'required'=>$model->verification_status>=1,
				),
			));
		?>
		<?php echo $form->error($model,'recipient'); ?>
		<p class="help-block">Isi Jika Tugas Untuk Individu</p>
	</div>

	

	

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Buat' : 'Simpan',array('class'=>'btn btn-success')); ?>
	</div>-->

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
	        "insertdatetime media table contextmenu paste jbimages"
	    ],
	    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | fontsizeselect"
		
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