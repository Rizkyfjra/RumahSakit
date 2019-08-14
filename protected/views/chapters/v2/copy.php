<?php
	if (!empty($model->id_lesson)) {
		$lesson = $model->mapel;
		$model->id_lesson = $lesson->name.' (ID:'.$model->id_lesson.')';

	}

	$mapel=array();
	if(!empty($lessons)){
		foreach ($lessons as $value) {
			$mapel[$value->id]=$value->name." (".$value->class->name.")";
		}
	}

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

	$lid=NULL;
	if(isset($_GET['lks_id'])){
		$lid=$_GET['lks_id'];
	}
?>

<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_chapters_copy', array(

      // ));
    ?>
    <div class="col-md-12">
	  <div id="bc1" class="btn-group btn-breadcrumb">
		<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>Materi</div>',array('/chapters/index'), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>Salin Materi</div>',array('#'), array('class'=>'btn btn-success')); ?>
	  </div>
	</div>

    <div class="col-lg-12">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'chapters-form',
			'enableAjaxValidation'=>false,
			'htmlOptions' => array('enctype' => 'multipart/form-data'),
		)); ?>
      <h3>Salin Materi</h3>
      <div class="row">
        <div class="col-md-12">
          <div class="col-card">
            <h4>Informasi Umum</h4>
            <div class="row">
            	<div class="col-md-12">
	                <div class="form-group">
	                 	<label for="Chapters_title">Nama Materi</label>
										<?php echo $form->textField($model,'title',array('class'=>'form-control input-pn input-lg','placeholder'=>'Isi nama materi disini (contoh: Materi Trigonometri) …','required'=>'required')); ?>
									</div>
            	</div>
            </div>
            <div class="row">
            	<div class="col-md-12">
                	<div class="form-group">
                  		<label for="Chapters_id_lesson">Nama Mata Pelajaran</label>
                		<?php
                			if(empty($lesson_id)){
                    			echo $form->dropDownList($model,'id_lesson',$mapel,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg','data-live-search'=>'true','options'=>array($selected=>array('selected'=>true))));
                			}else{
                    			echo $form->dropDownList($model,'id_lesson',$mapel,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg','data-live-search'=>'true','disabled'=>'disabled','readonly'=>'readonly','options'=>array($selected=>array('selected'=>true))));
                    	}
                 		 ?>
                 	</div>
                </div>
            </div>
						<hr/>
            <div class="row">
	        		<div class="col-md-12">
	        			<button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Salin Materi</button>
	          	</div>
	          </div>
          </div>
        </div>
    </div>
  </div>
  <?php $this->endWidget(); ?>
</div>
