<?php
/* @var $this QuizController */
/* @var $model Quiz */
/* @var $form CActiveForm */
if(Yii::app()->user->YiiTeacher){
	$mapel = Lesson::model()->findAll(array('condition'=>'trash is null and user_id = '.Yii::app()->user->id." AND semester = ".$semester." AND year = ".$year));
}else{
	$mapel = Lesson::model()->findAll(array('condition'=>'trash is null and semester = '.$semester." AND year = ".$year));
}
$lesson = array();
foreach ($mapel as $value) {
	if($value->moving_class == 1){
		$lesson[$value->id]=$value->name." (".$value->grade->name.")";
	}else{
		$lesson[$value->id]=$value->name." (".$value->class->name.")";
	}
	
}

$rekap=array(
	NULL=>'Ya',
	1=>'Tidak');
$smt=array(1=>'1',2=>'2');
Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/wizard.js',CClientScript::POS_HEAD);
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/wizard.css');

$lid=NULL;
$lsn=NULL;
if(isset($_GET['lks_id'])){
	$lid=$_GET['lks_id'];
}

if(isset($_GET['lesson_id'])){
	$lsn = $_GET['lesson_id'];
}

$tipe=array(0=>'Ulangan',1=>'UTS',2=>'UAS');
$acak=array(1=>'Ya',NULL=>'Tidak');
$acak_opsi=array(1=>'Ya',NULL=>'Tidak');
$show=array(1=>'Ya',NULL=>'Tidak');
?>
<div class="stepwizard">
    <div class="stepwizard-row setup-panel">
        <div class="stepwizard-step">
            <a href="#step-1" type="button" class="btn btn-primary btn-circle">1</a>
            <p>Judul & Waktu Pengerjaan</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-2" type="button" class="btn btn-default btn-circle" disabled="disabled">2</a>
            <p>Tipe Ujian</p>
        </div>
        <div class="stepwizard-step">
            <a href="#step-3" type="button" class="btn btn-default btn-circle" disabled="disabled">3</a>
            <p>Pilihan Lain</p>
        </div>
    </div>
</div>


<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'quiz-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Isian dengan <span class="required">*</span> wajib diisi.</p>

	<?php echo $form->errorSummary($model); ?>
	<input type="hidden" name="lks_id" value="<?php echo $lid?>">
	<?php 
		if(!empty($model->lesson_id)){
			$selected=$model->lesson_id;
		}else{
			$selected=1;
		}

		// if(!empty($model->semester)){
		// 	$sld = $model->semester;
		// }else{
		// 	$sld = 1;
		// }

		if(!empty($model->quiz_type)){
			$qtp = $model->quiz_type;
		}else{
			$qtp = 0;
		}

		if(!empty($model->random)){
			$rnd=$model->random;
		}else{
			$rnd=1;
		}

		if(!empty($model->random_opt)){
			$rnd_opt=$model->random_opt;
		}else{
			$rnd_opt=1;
		}

		if(!empty($model->show_nilai)){
			$shw=$model->show_nilai;
		}else{
			$shw=1;
		}
	?>
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
					<?php echo $form->labelEx($model,'end_time'); ?>
					<?php echo $form->textField($model,'end_time',array('class'=>'form-control','required'=>'required')); ?>
					<?php echo $form->error($model,'end_time'); ?>
					<p class="help-block">Waktu Dalam Menit.</p>
				</div>
				<?php if(empty($lsn)){ ?>
                <div class="form-group">
					<?php echo $form->labelEx($model,'lesson_id'); ?>
					<?php echo $form->dropDownList($model,'lesson_id',$lesson,array('class'=>'form-control','options'=>array($selected=>array('selected'=>true)))); ?>
					<?php echo $form->error($model,'lesson_id'); ?>
				</div>
				<?php } ?>
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Berikutnya</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-2">
        <div class="col-xs-12">
            <div class="col-md-12">
                <h3> Step 2</h3>
				<div class="form-group">
					<?php echo $form->labelEx($model,'quiz_type'); ?>
					<?php echo $form->dropDownList($model,'quiz_type',$tipe,array('class'=>'form-control','options'=>array($qtp=>array('selected'=>true)))); ?>
					<?php echo $form->error($model,'quiz_type'); ?>
				</div>
                <div class="form-group">
					<?php echo $form->labelEx($model,'add_to_summary'); ?>
					<?php echo $form->dropDownList($model,'add_to_summary',$rekap,array('class'=>'form-control')); ?>
					<?php echo $form->error($model,'add_to_summary'); ?>
				</div>
                <button class="btn btn-primary nextBtn btn-lg pull-right" type="button" >Berikutnya</button>
            </div>
        </div>
    </div>
    <div class="row setup-content" id="step-3">
        <div class="col-xs-12">
            <div class="col-md-12">
            	<h3> Step 3</h3>
            	<?php if(!$model->isNewRecord){ ?>
            		<div class="form-group">
						<?php echo $form->labelEx($model,'passcode'); ?>
						<?php echo $form->textField($model,'passcode',array('class'=>'form-control')); ?>
						<?php echo $form->error($model,'passcode'); ?>
					</div>
            	<?php } ?>
            	<div class="form-group">
					<?php echo $form->labelEx($model,'random'); ?>
					<?php echo $form->dropdownList($model,'random',$acak,array('class'=>'form-control','options'=>array($rnd=>array('selected'=>true)))); ?>
					<?php echo $form->error($model,'random'); ?>
				</div>

				<div class="form-group">
					<?php echo $form->labelEx($model,'random_opt'); ?>
					<?php echo $form->dropdownList($model,'random_opt',$acak_opsi,array('class'=>'form-control','options'=>array($rnd_opt=>array('selected'=>true)))); ?>
					<?php echo $form->error($model,'random_opt'); ?>
				</div>

				<div class="form-group">
					<?php echo $form->labelEx($model,'show_nilai'); ?>
					<?php echo $form->dropdownList($model,'show_nilai',$show,array('class'=>'form-control','options'=>array($shw=>array('selected'=>true)))); ?>
					<?php echo $form->error($model,'show_nilai'); ?>
				</div>

            	<div class="form-group">
					<?php echo $form->labelEx($model,'repeat_quiz'); ?>
					<?php echo $form->textField($model,'repeat_quiz',array('class'=>'form-control')); ?>
					<?php echo $form->error($model,'repeat_quiz'); ?>
					<p class="help-block">Jika Kosong Akan Diisi 1 Kali.</p>
				</div>
                <button class="btn btn-success btn-lg pull-right" type="submit">Selesai!</button>
            </div>
        </div>
    </div>

<?php $this->endWidget(); ?>

