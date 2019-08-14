<?php
/* @var $this LessonController */
/* @var $model Lesson */
/* @var $form CActiveForm */
$list = LessonList::model()->findAll(array('order'=>'id'));
$lesson_list = array();
if(!empty($list)){
	foreach ($list as $ll) {
		if($ll->group == 1){
			$ket = "Wajib A";
		}elseif($ll->group == 2){
			$ket = "Wajib B";
		}else{
			$ket = "Peminatan C";
		}

		$lesson_list[$ll->id]=$ll->name." (".$ket.")";
	}
}

$clist = ClassDetail::model()->findAll(array('order'=>'name'));
$classes_list = array();

if(!empty($clist)){
	foreach ($clist as $cl) {
		$classes_list[$cl->id]=$cl->name;
	}
}

$klist = Clases::model()->findAll(array('order'=>'id'));
$big_class = array();

if(!empty($klist)){
	foreach ($klist as $kl) {
		$big_class[$kl->id]=$kl->name;
	}
}
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'lesson-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>
	<?php 
		if(!empty($model->user_id)){
			$model->user_id = $model->users->display_name.' | '.$model->users->username.' (ID:'.$model->users->id.')';
		}

		if(!empty($model->class_id)){
			//if($model->moving_class == 1){
			//	$selected2 = $model->class_id;
			//}else{
				$selected=$model->class_id;
			//}
			
		}else{
			//$selected2=1;
			$selected=1;

		}

		$show_small = '';
		$show_big = '';
		
		if($model->moving_class == 1){
			$show_small = 'display:none';
		}else{
			$show_big = 'display:none';	
		}
	?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php if($model->isNewRecord){ ?>
			<?php echo $form->dropdownList($model,'list_id',$lesson_list,array('class'=>'form-control','id'=>'pilihan'))?>
		<?php }else{ ?>
		    <?php echo $form->dropdownList($model,'list_id',$lesson_list,array('class'=>'form-control','id'=>'pilihan'))?>
			<?php //echo $form->textField($model,'name',array('size'=>60,'maxlength'=>255,'class'=>'form-control','disabled'=>'disabled')); ?>
		<?php } ?>
		<?php echo $form->error($model,'name'); ?>
	</div>
	<?php if(Yii::app()->user->YiiAdmin){ ?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php
			$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'model'=>$model,
	    		'attribute'=>'user_id',
				// additional javascript options for the autocomplete plugin
				'options'=>array(
				'minLength'=>'1',
				),
				'source'=>$this->createUrl("user/suggest"),
				'htmlOptions'=>array(
				//'style'=>'height:20px;',
				'class'=>'form-control'
				//'required'=>$model->verification_status>=1,
				),
			));
		?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>
	<?php } ?>
	<div class="form-group">
		<?php echo $form->labelEx($model,'moving_class');?>
		<br>
		<?php echo $form->checkBox($model,'moving_class',array('value' => '1', 'uncheckValue'=>'0','onchange' => 'javascript:$("#kelas").toggle();$("#big").toggle()')); ?>
		<?php echo $form->error($model,'moving_class'); ?>
	</div>
	<div class="form-group">

		<?php echo $form->labelEx($model,'class_id'); ?>
		<?php echo $form->dropdownList($model,'class_id',$classes_list,array('class'=>'form-control','style'=>$show_small,'id'=>'kelas','options'=>array($selected=>array('selected'=>true))));?>
		<?php echo $form->dropdownList($model,'big',$big_class,array('class'=>'form-control','style'=>$show_big,'id'=>'big','options'=>array($selected=>array('selected'=>true))));?>
		<?php
			/*$this->widget('zii.widgets.jui.CJuiAutoComplete',array(
				'model'=>$model,
	    		'attribute'=>'class_id',
				// additional javascript options for the autocomplete plugin
				'options'=>array(
				'minLength'=>'1',
				),
				'source'=>$this->createUrl("clases/suggest"),
				'htmlOptions'=>array(
				//'style'=>'height:20px;',
				'class'=>'form-control',
				'id'=>'kelas'
				//'required'=>$model->verification_status>=1,
				),
			));*/
		?>
		<?php echo $form->error($model,'class_id'); ?>
	</div>

	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
<script type="text/javascript">

	/*$(".checkbox").change(function() {
	    if(this.checked) {
	        console.log("checked");
	    }
	});*/
</script>