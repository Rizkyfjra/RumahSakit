<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'studentAssignment',
	'enableAjaxValidation'=>false,
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
)); ?>
<div class="row">

<?php echo $form->hiddenField($model,'student_id',array('value'=>Yii::app()->user->id));?>

<?php
	if(!empty($tugas_id)) {
		echo $form->hiddenField($model,'assignment_id',array('value'=>$tugas_id));
	} else {
		echo $form->hiddenField($model,'assignment_id');
	}

	if(!empty($id_sa)){
		echo $form->hiddenField($model,'id',array('value'=>$id_sa));
	}

	if($type == NULL || $type == 2){
?>
	<div class="col-md-12">
		<div class="form-group">
			<label for="StudentAssignment_content">Jawaban</label>
			<?php echo $form->textArea($model,'content',array('class'=>'textarea textarea-input form-control input-pn input-lg','placeholder'=>'Isi Jawaban Disini','rows'=>4, 'cols'=>50))?>
		</div>
	</div>
<?php
	}

	if(Yii::app()->user->YiiStudent){
?>
	<div class="col-md-12">
		<div class="form-group">
			<label for="StudentAssignment_file">Lampiran Berkas</label>
			<?php echo $form->fileField($model,'file'); ?>
            <p class="help-block">Maksimal Ukuran Lampiran Berkas 2 MB</p>
		</div>
	</div>
<?php
	}else{
		if($type == 1){ 
?>
	<div class="col-md-12">
		<div class="form-group">
			<label for="StudentAssignment_score">Nilai</label>
			<?php echo $form->numberField($model,'score',array('class'=>'form-control input-pn input-lg')); ?>
			<p class="help-block">Skala Nilai 1-100.</p>
		</div>
	</div>
<?php
		}
	}

	if(Yii::app()->user->YiiStudent){	
?>
	<div class="col-md-12">
		<div class="form-group">
			<div class="pull-right">
				<div class="btn-group">
					<?php echo CHtml::submitButton($model->isNewRecord ? 'Kumpulkan' : 'Kumpulkan',array('class'=>'btn btn-success','name'=>'upload', 'confirm'=>'Yakin Mengumpulkan Tugas ?')); ?>
					<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan',array('class'=>'btn btn-primary','name'=>'save')); ?>
				</div>
			</div>
		</div>
	</div>
<?php
	}else{
?>
	<div class="col-md-12">
		<div class="form-group">
			<div class="pull-right">
				<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan',array('class'=>'btn btn-success','name'=>'upload')); ?>
			</div>
		</div>
	</div>
<?php
	}
?>
</div>
<?php $this->endWidget(); ?>