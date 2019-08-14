<?php
/* @var $this QuestionsController */
/* @var $model Questions */
/* @var $form CActiveForm */
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/tinymce/tinymce.min.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/tinymce/plugins/tiny_mce_wiris/integration/WIRISplugins.js?viewer=image',CClientScript::POS_HEAD);

Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/wizard.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/sisyphus.min.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/wizard.css');
$tipe=array(
	NULL=>'Pilihan Ganda');
$qid=NULL;
if(isset($_GET['quiz_id'])){
	$qid=$_GET['quiz_id'];
}
if(Yii::app()->user->YiiTeacher){
	$mapel = Lesson::model()->findAll(array('condition'=>'user_id = '.Yii::app()->user->id));
}else{
	$mapel = Lesson::model()->findAll();
}

$lesson = array();
foreach ($mapel as $value) {
	if($value->moving_class == 1){
		$lesson[$value->id]=$value->name." (".$value->grade->name.")";
	}else{
		$lesson[$value->id]=$value->name." (".$value->class->name.")";
	}
}
// $remove_pict = explode(",", "0");
// $tmp = json_decode($model->choices_files,true);
// echo "<pre>";
// print_r($remove_pict);
// echo "</pre>";

// foreach ($remove_pict as $value) {
// 	unset($tmp[$value]);
// }

// echo "<pre>";
// print_r($tmp);
// echo "</pre>";
?>

<div class="stepwizard">
    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
            <p>Judul & Tipe Pertanyaan</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
            <p>Isi Pertanyaan</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
            <p>Pilihan & Kunci Jawaban</p>
        </div>

    </div>
</div>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'questions-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Isian dengan <span class="required">*</span> wajib diisi.</p>

	<?php echo $form->errorSummary($model); ?>
	<?php
		if(!empty($model->tipe)){
			$selected=$model->tipe;
		}else{
			$selected=NULL;
		}

		if(!empty($model->lesson_id)){
			$lsn=$model->lesson_id;
		}else{
			$lsn=NULL;
		}
	?>
	<!-- <div class="row">
		<?php //echo $form->labelEx($model,'quiz_id'); ?>
		<?php //echo $form->textField($model,'quiz_id'); ?>
		<?php //echo $form->error($model,'quiz_id'); ?>
	</div> -->
	
	<div class="row setup-content" id="step-1">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Langkah 1</h3>
                <div class="form-group">
					<?php echo $form->labelEx($model,'title'); ?>
					<?php echo $form->textField($model,'title',array('class'=>'form-control','required'=>'required')); ?>
					<?php echo $form->error($model,'title'); ?>
				</div>
				<div class="form-group">
					<?php echo $form->labelEx($model,'type'); ?>
					<?php echo $form->dropdownList($model,'type',$tipe,array('class'=>'form-control','id'=>'pilihan', 'options'=>array($selected=>array('selected'=>true))))?>
					<?php echo $form->error($model,'type'); ?>
				</div>
				<div class="form-group">
					<?php //echo $form->labelEx($model,'lesson_id'); ?>
					<?php //echo $form->dropdownList($model,'lesson_id',$lesson,array('class'=>'form-control','id'=>'pilihan', 'options'=>array($lsn=>array('selected'=>true))))?>
					<?php //echo $form->error($model,'lesson_id'); ?>
				</div>
				<?php if($qid != NULL){?>
					<?php echo $form->hiddenField($model,'quiz_id',array('class'=>'form-control','value'=>$qid)); ?>
                <?php } ?>
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Berikutnya</button>
                <button class="btn btn-success btn-lg pull-right" type="submit">Selesai!</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-2">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Langkah 2</h3>
               	<div class="form-group">
					<?php echo $form->labelEx($model,'text'); ?>
					<?php echo $form->textArea($model,'text',array('rows'=>6, 'cols'=>10, 'class'=>'form-control', 'value'=>$model->text)); ?>
					<?php echo $form->error($model,'text'); ?>
				</div>
				<div class="form-group">
					<?php echo $form->labelEx($model,'file'); ?> 
					<?php echo $form->fileField($model,'file'); ?>	
					<?php echo $form->error($model,'file'); ?>
					<p class="help-block">Maksimal Ukuran file 2 Mb</p>

				</div>
				<button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Berikutnya</button>
            	<button class="btn btn-success btn-lg pull-right" type="submit">Selesai!</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-3">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Langkah 3</h3>
                <div id="pilgan">
	                <a href="#" id="addChoices" class="btn btn-primary pull-right">Tambah Pilihan</a><br>
					<div id="plh">
						<label>Pilihan</label>
						<p class="help-block">* Tidak Perlu Mencantumkan Pilihan (A,B,C,D dst)</p>
						<p class="help-block">* Masukkan Pilihan Jawabannya Saja</p>
						<p class="help-block">* Cek Pilihan Jawaban</p>

						<?php if(!empty($model->choices)){?>
							<?php if($model->type == NULL){ ?>
								<?php $path_image=Clases::model()->path_image($model->id);?>
								<?php $pilihan = json_decode($model->choices,true);?>
								<?php $gambar = json_decode($model->choices_files,true);?>
								<?php $urutan=1;?>
								<?php $no=1;?>
								<?php $count=count($pilihan);?>
								<p class="hidden" id="total"><?php echo $count;?></p>
								<input type="hidden" name="remove_pict" id="remove_pict" class="form-control" value="">
								<?php
									//$alphabet = range('A', 'Z');
									foreach ($pilihan as $key => $value) { 
								?>
									<div class="form-group" id="index<?php echo $key; ?>">
										
										<div>

											<?php //echo $form->labelEx($questions,'choices'); ?>
											<textarea rows="10"  name="pil[]" class="form-control"  >
													<?php echo $value;?>
											</textarea>
											


											
											<?php //echo $form->error($questions,'choices');?>
										</div>
										<div class="input-group">

											<?php //echo $form->labelEx($questions,'choices'); ?>
											
											<span style="font-size:24px;" class="input-group-addon">
												<?php if($value == $model->key_answer){ ?>
													<input type="radio" name="answer" value="<?php echo $no;?>" checked required> <span><b>Klik disini bila opsi ini adalah jawaban benar</b></span>
												<?php }else{ ?>
													<input type="radio" name="answer" value="<?php echo $no;?>" required> <span><b>Klik disini bila opsi ini adalah jawaban benar</b></span>
												<?php } ?>	
											</span>
											
											<!-- <input type="file" name="gambar[]" class="form-control imgfile"> --> 
											<span class="input-group-btn">
												<button type="button" class="btn btn-danger remove" data-index="index<?php echo $key; ?>" data-no="<?php echo $key; ?>">Remove</button>
											</span> 


											
											<?php //echo $form->error($questions,'choices');?>
										</div>
										<?php if(!empty($gambar[$key])){ 

												echo "<img src='".Yii::app()->baseUrl.'/images/question/'.$path_image.$key.'/'.$gambar[$key]."' class='img-responsive' width=100 height=100 style='margin-left:35%''>"."<br>";

											} ?>

									</div>
									
									<?php $no++;?>
									<?php $urutan++;?>
								<?php } ?>
							<?php } ?> 
								
						<?php }else{ ?>
							<p class="hidden" id="total">0</p>
							<div class="form-group">
								<!-- <div class="input-group">
								<?php //echo $form->labelEx($questions,'choices'); ?>
								<input type="text" name="pil[]" class="form-control"><span class="input-group-addon"><input type="radio" name="answer" value=1></span>
								<input type="file" name="gambar[]" class="form-control">
								<?php //echo $form->error($questions,'choices'); ?>
								</div> -->
 
										<div>	
											<textarea rows="10"  name="pil[]" class="form-control"  >
													
											</textarea>	
										</div>
										<div class="input-group">
											<span style="font-size:24px;" class="input-group-addon">
												<input type="radio" name="answer" value=1 required> <span><b>Klik disini bila opsi ini adalah jawaban benar</b></span>	
											</span>
										</div>
							</div>
							
							<div class="form-group">
								<!-- <div class="input-group">	
								<input type="text" name="pil[]"  class="form-control"><span class="input-group-addon"><input type="radio" name="answer" value=2></span>
								<input type="file" name="gambar[]" class="form-control">
								<?php //echo $form->error($questions,'choices'); ?>
								<?php //echo $form->error($questions,'choices'); ?>
								</div> -->
								        <div>	
											<textarea rows="10"  name="pil[]" class="form-control"  >
													
											</textarea>	
										</div>
										<div class="input-group">
											<span style="font-size:24px;" class="input-group-addon">
												<input type="radio" name="answer" value=2 required> <span><b>Klik disini bila opsi ini adalah jawaban benar</b></span>	
											</span>
										</div>
							</div> 
						<?php } ?>	
					</div>
				</div>	
				<div id="isian">
					<div class="form-group">
						<?php echo $form->labelEx($model,'key_answer'); ?>
						<?php echo $form->textArea($model,'key_answer',array('class'=>'form-control')); ?>
						<?php echo $form->error($model,'key_answer'); ?>
					</div>
				</div>

                
                <button class="btn btn-success btn-lg pull-right" type="submit">Selesai!</button>
                <a href="<?php echo Yii::app()->createUrl('/questions/'.$model->id) ?>"><button class="btn btn-info btn-lg pull-right" type="button">Batal!</button></a>
            </div>
        </div>
    </div>

<?php $this->endWidget(); ?>
<script type="text/javascript">
	$(document).ready(function(){
		//var	iter = 67;
		if($("#total").text() == 0){
			var no = 2;
		}else{
			var no = parseInt($("#total").text());
		}
		
		
		$("#addChoices").click(function(){
			no=no+1;
			//console.log("Berhasil");
			//$('<div class="form-group"><input type="text" name="pil[]" class="form-control"></div>').appendTo("#last");
			// $('#plh').append('<div class="form-group"><div class="input-group"><input type="text" name="pil[]" class="form-control"><span class="input-group-addon"><input type="radio" name="answer" value='+no+'></span><input type="file" name="gambar[]" class="form-control"></div></div>');
			$('#plh').append('<div class="form-group"><textarea rows="10"  name="pil[]" class="form-control"  ></textarea><div class="input-group"><span style="font-size:24px;" class="input-group-addon"><input type="radio" name="answer" value='+no+' required> <span><b>Klik disini bila opsi ini adalah jawaban benar</b></span></span></div></div>');
			initMCEall();	
			/*var id = $(this).attr('id'); 
			//for (var i = 67; i <= 90; i++) {
			    //$(select).append('<option>' + string.fromCharCode(i) + '</option>');
			//}
			alert(fromCharCode(67));
			iter++;*/
			/*$("#aid").val(id);
			$("<td><label></label>Nilai "+$('#'+id+'').text()+"<input type='text' class='form-control' size=3 name='nilai[]'><br><input type='submit' class='form-control btn btn-danger' value='Nilai'/></td>").insertAfter(".kolom");*/
			//alert(id);
			//console.log(no);
		});
		//console.log($("#pilihan").val());

		$("#pilihan").change(function () {
	        var pil = this.value;
	        //console.log(pil);

	        if(pil == 1){
	        	$("#pilgan").hide();
	        	$("#isian").show();
	        }else{
	        	$("#pilgan").show();
	        	$("#isian").hide();
	        }
	    });

	    if($("#pilihan").val() == 1){
	    	$("#pilgan").hide();
	        $("#isian").show();
	    }else{
	    	$("#pilgan").show();
	        $("#isian").hide();
	    }
		function initMCEall(){
		tinymce.init({
		    selector: "textarea",
		    // wirisimagebgcolor: '#FFFFFF',
		    // wirisimagesymbolcolor: '#000000',
		    // wiristransparency: 'true',
		    // wirisimagefontsize: '16',
		    // wirisimagenumbercolor: '#000000',
		    // wirisimageidentcolor: '#000000',
		     // wirisformulaeditorlang: 'en',
		         invalid_elements :'script',
			  //  invalid_styles: 'color font-size text-decoration font-weight',
			   // menubar: false,
			   // toolbar:false,
			   //  statusbar:false,
			   // forced_root_block : "",
			   // cleanup: true,
			   // remove_linebreaks: true,
			    //convert_newlines_to_brs: false,
			   // inline_styles : false,
			   valid_styles : { '*' : 'color,font-size,font-weight,font-style,text-decoration' },
			    entity_encoding: 'raw',
			    entities: '160,nbsp,38,amp,60,lt,62,gt',
		    plugins: [
		        "advlist autolink lists link image charmap print preview anchor",
		        "searchreplace visualblocks code fullscreen",
		        "insertdatetime media table contextmenu paste jbimages"
		    ],
		    paste_data_images: true,
		    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image jbimages | fontsizeselect",
			relative_urls: false
		});
		}
		initMCEall();	
	});
</script>


<script type="text/javascript">

//localStorage.clear();
var theUrl = $(location).attr('href').split("/");
var theCon = theUrl[theUrl.length - 2];
var theMeth = theUrl[theUrl.length - 1];

if (theCon=="questions" && theMeth == "create") {
	$("#Questions_title").change(function(){
		var theTitle = $("#Questions_title").val();
		if(typeof(Storage) !== "undefined") {
	    localStorage.setItem("titleQuestion", theTitle);
		} else {
		    // Do Nothing
		}
	});
	var localTitle = localStorage.getItem("titleQuestion");
	$("#Questions_title").val(localTitle);
} else{
	//Do Nothing
};






$( ".remove" ).click(function() {
	//alert($(this).attr('data-index'));
	//var dataArr = [];
	dataID = $(this).attr('data-index');
	// dataNO = $(this).attr('data-no');

	// if (dataNO == 0){
	// 	dataNO = 'kosong';
	// }

	// var remove_pict = $('#remove_pict').val();
	// // alert(remove_pict);
	// if (remove_pict){
	// 	dataArr.push(remove_pict);
	// }
	//dataArr.push(dataNO);

	$( "#"+dataID ).remove();
	//$( "#remove_pict" ).val(dataArr);
});
</script>