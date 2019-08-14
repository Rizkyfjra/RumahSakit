<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
	// $status_array = array(1 => 'Guru',2 => 'Murid',3=>'Kepala Sekolah',4=>'Wali Kelas');
	$status_array = array(1 => 'Guru',3=>'Kepala Sekolah',4=>'Wali Kelas');
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'email'); ?>
		<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'email'); ?>
	</div>
	<?php if(!Yii::app()->user->YiiStudent){ ?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
	<?php } ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'display_name'); ?>
		<?php echo $form->textField($model,'display_name',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'display_name'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php echo $form->fileField($model,'image',array('id'=>'img')); ?>
		<?php echo $form->error($model,'image'); ?>
		<?php if(($model->image))
              	{
              		$img_url = Yii::app()->baseUrl.'/images/user/'.$model->id.'/'.$model->image;
              	} else {
              		$img_url = Yii::app()->baseUrl.'/images/user-2.png';
              	}
              ?>
	            <img id="preview" class="img-thumbnail img-responsive" height="150" width="150" src="<?php echo $img_url; ?>" alt="no avatar">
	</div>
	<?php if ($model->isNewRecord) { ?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'encrypted_password'); ?>
		<?php echo $form->passwordField($model,'encrypted_password',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'encrypted_password'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'password2'); ?>
		<?php echo $form->passwordField($model,'password2',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'password2'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'role_id'); ?>
		<?php echo $form->dropDownList($model,'role_id',  $status_array, array('onchange'=>'changeact()','class'=>'form-control')); ?>
		<?php echo $form->error($model,'role_id'); ?>
	</div>

	<div class="form-group" id="child-student">
		<?php echo $form->labelEx($model,'class_id'); ?>
		<?php
			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				//'model'=>$model,
	    		'name'=>'class_id',
				// additional javascript options for the autocomplete plugin
				'options'=>array(
				'minLength'=>'1',
				),
				'source'=>$this->createUrl("clases/suggest"),
				'htmlOptions'=>array(
				//'style'=>'height:20px;',
				'class'=>'form-control'
				//'required'=>$model->verification_status>=1,
				),
			));
		?>
		<?php echo $form->error($model,'class_id'); ?>
	</div>

	<?php } ?>
	
	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<?php if($model->isNewRecord){ ?>
<script type="text/javascript">
	$("#child-student").hide();
	/*if (document.getElementById("User_role_id").value == "2"){
		$("#child-student").show();
	}
function changeact() {
    if (document.getElementById("User_role_id").value == "2"){
         //$("#child-student").append("<li>Appended item</li>");
         $("#child-student").show();
         //console.log("benar : "+label_input);
    } else {
    	//console.log("lain");
    }           
}*/
</script>
<?php } ?>
<script type="text/javascript">
	//console.log(total);
    function readURL(input) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	            $('#preview').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}
	//for(x=1;x<total;x++){
		$("#img").change(function(){
	    	readURL(this);
		});
	//}	
</script>