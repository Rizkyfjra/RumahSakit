<?php
/* @var $this LessonController */
/* @var $dataProvider CActiveDataProvider */

if($model->moving_class == 1){
										$kelasnya = $model->name;
										$idkelasnya = $model->id;
										$path_nya = 'lesson/'.$idkelasnya;
									}else{
										$kelasnya = $model->name;
										$idkelasnya = $model->id;
										$path_nya = 'lesson/'.$idkelasnya;
									}

$this->breadcrumbs=array(
	$kelasnya=>array($path_nya),
	$model->name,
);

// echo"<pre>";
// 	print_r($model);
// echo"</pre>";

/*$this->menu=array(
	array('label'=>'Create Lesson', 'url'=>array('create')),
	array('label'=>'Manage Lesson', 'url'=>array('admin')),
);*/
$jml = 0;
if(!empty($tugas)){
	foreach ($tugas as $count) {
		$jml++;
	}
}

$tkuis=0;
if(!empty($kuis)){
	$tkuis=count($kuis);
}
//$total = $jml+$result;
$total = $jml+$tkuis;

$nilai_harian = Option::model()->findAll(array('condition'=>'key_config LIKE "%nilai_harian%"'));
$nilai_uts = Option::model()->findAll(array('condition'=>'key_config LIKE "%nilai_uts%"'));
$nilai_uas = Option::model()->findAll(array('condition'=>'key_config LIKE "%nilai_uas%"'));
$kurikulum_sekolah = Option::model()->findAll(array('condition'=>'key_config LIKE "%kurikulum%"'));
/*echo "<pre>";
print_r($uas);
echo "</pre>";*/
//print_r($result);
//$mt=array(1=>'Tugas Harian',2=>'Ulangan',3=>'UTS',4=>'UAS');

?>

<h1>Rekap Nilai Kelas <?php echo $kls->name;?></h1>
<p>
	<?php if(!Yii::app()->user->YiiStudent || !Yii::app()->user->isGuest){ ?>
		<?php //echo CHtml::link('Download',array('/lesson/createExcel', 'id'=>$model->id),array('class'=>'btn btn-success')); ?>
		<?php echo CHtml::link('Download','#pilihan',array('class'=>'btn btn-success','data-toggle'=>'collapse')); ?>
		<?php //echo CHtml::link('Tambah Nilai Offline',array('/offlineMark/create','idl'=>$model->id),array('class'=>'btn btn-primary')); ?>
		<?php if(Yii::app()->user->YiiTeacher){ ?>
			<?php //echo CHtml::link('Tambah Tugas Offline','#formTugas',array('class'=>'btn btn-primary','data-toggle'=>'collapse')); ?>
		<?php } ?>
	<?php } ?>
</p>
<?php if(!Yii::app()->user->YiiStudent){ ?>
<div class="collapse" id="pilihan">
		<?php echo CHtml::link('Excel',array('/lesson/createExcel', 'id'=>$model->id),array('class'=>'btn btn-info')); ?>
		<?php echo CHtml::link('PDF',array('/lesson/rekapDownload', 'id'=>$model->id),array('class'=>'btn btn-warning')); ?>
</div>
<div class="collapse" id="formTugas">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'assignment-form',
		'action'=>$this->createUrl('lesson/offlineTask/'.$model->id),
		// Please note: When you enable ajax validation, make sure the corresponding
		// controller action is handling ajax validation correctly.
		// There is a call to performAjaxValidation() commented in generated controller code.
		// See class documentation of CActiveForm for details on this.
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
		'enableAjaxValidation'=>false,
	)); ?>

		<?php

		/*if (!empty($model->lesson_id)) {
			$cekcount = explode(":", $model->lesson_id);
			if ($cekcount >= 2){
				$lesson = $model->lesson;
				$model->lesson_id = $lesson->name.' (ID:'.$model->lesson_id.')';
			} else {
				$lesson = $model->lesson;
				$model->lesson_id = $lesson->name.' (ID:'.$model->lesson_id.')';
			}
			
		}*/

		if(!empty($assign->lesson_id)){
			$selected = $assign->lesson_id;
		}else{
			$selected = 1;
		} 

		if(!empty($assign->file)){
			$assign->file = $assign->file;
		}

		?>

		<!-- <p class="note">Fields with <span class="required">*</span> are required.</p> -->

		<?php echo $form->errorSummary($assign); ?>

		<div class="form-group">
			<?php echo $form->labelEx($assign,'title'); ?>
			<?php echo $form->textField($assign,'title',array('class'=>'form-control')); ?>
			<?php echo $form->error($assign,'title'); ?>
		</div>

		<div class="form-group">
			<?php //echo $form->labelEx($assign,'lesson_id'); ?>
			<?php //echo $form->textField($assign,'lesson_id',array('class'=>'form-control','value'=>$model->name.' ('.$model->class->name.')'))?>
			<?php //echo $form->error($assign,'lesson_id'); ?>
		</div>

		<!-- <div class="form-group">
			<?php //echo $form->labelEx($assign,'due_date'); ?>
			<?php
			/*$this->widget('ext.YiiDateTimePicker.jqueryDateTime', array(
		        'model' => $assign,
		        'attribute' => 'due_date',
		        'options' => array(), //DateTimePicker options
		        'htmlOptions' => array(
		        	'class'=>'form-control'),
		    ));*/
		    ?>
			<?php //echo $form->error($assign,'due_date'); ?>
		</div> -->

		<div class="form-group">
			<?php echo CHtml::submitButton($assign->isNewRecord ? 'Buat' : 'Simpan',array('class'=>'btn btn-success')); ?>
		</div>

	<?php $this->endWidget(); ?>	
	
</div>
<br>
<?php
$kurikulum = Option::model()->find(array('condition'=>'key_config LIKE "%kurikulum%"'));

if(!empty($kurikulum) and $kurikulum->value == '2013'){
	echo '<a href="'.Yii::app()->createUrl('/lesson/NilaiKetSikap/'.$model->id).'"><button type="button" class="btn btn-primary pull-right">Input Nilai Rapor</button></a>';
	echo "<br>";
	echo "<br>";
}

?>
<?php $url_mark = Yii::app()->createUrl('/lesson/addMark/'.$model->id);?>
<div class="table-responsive">
<form method="post" action="<?php echo $url_mark;?>">

<table class='table table-hover table-bordered' id='rekapTable'>
	<tr class="danger">
	<th rowspan="2" class="text-center">No</th>
	<th rowspan="2" class="text-center">NIS</th>
	<th rowspan="2" class="text-center">Nama Siswa</th>
	<th colspan="<?php echo $total; ?>" class="text-center">Nilai Harian</th>
	<th rowspan="2" class="text-center">RNH</th>
	<th rowspan="2" class="text-center">UTS</th>
	<th rowspan="2" class="text-center">UAS</th>
	<th rowspan="2" class="text-center">Raport<br>RNH + UTS + UAS</th>
	<tr class="info">
		<?php
			//echo $total; 
			for ($i=1; $i <= $jml; $i++) {
				if($tugas[$i-1]->assignment_type == NULL){
					//if(Yii::app()->user->YiiTeacher){
						echo "<th id='".$tugas[$i-1]->id."'>".CHtml::link($tugas[$i-1]->title, array('/assignment/view','id'=>$tugas[$i-1]->id))."</th>";
					//}else{
					//	echo "<th id='".$tugas[$i-1]->id."'>".$tugas[$i-1]->title."</th>";
					//}
				}else{
					//echo "<th id='".$tugas[$i-1]->id."' class='tugas'>".$tugas[$i-1]->title."</th>";
					//if(Yii::app()->user->YiiTeacher){
						echo "<th> ".CHtml::link($tugas[$i-1]->title, array('/assignment/view','id'=>$tugas[$i-1]->id))." (Offline)</th>";
					//}else{
					//	echo "<th> ".$tugas[$i-1]->title." (Offline)</th>";
					//}
				}
			}

			for ($x=0; $x < $tkuis; $x++) { 
				//if(Yii::app()->user->YiiTeacher){
					echo "<th>".CHtml::link($kuis[$x]->title,array('/quiz/view','id'=>$kuis[$x]->id))."(Ulangan)</th>";
				//}else{
				//	echo "<th>".$kuis[$x]->title."(Ulangan)</th>";
				//}
			}
		?>
	</tr>
	</tr>
	<?php $no=1;?>
	<?php $a;?>
	<?php foreach ($siswa as $key) { ?>
	<tr id="<?php echo $key->id;?>" class="kolom active">
		<td class="text-center"><?php echo $no;?></td>
		<td><?php echo $key->student->username;?></td>
		<td><?php echo $key->student->display_name;?></td>
		<?php if(!empty($tugas) || !empty($kuis)){ ?>
			<?php
				$counter=0;
				$cnt=0;
				$rnh=0;
				$tnh=0;
				$pnh=0;
				$div=0;
				$div2=0;
				$nuts=0;
				$puts=0;
				$tuas=0;
				$ruas=0;
				$puas=0;
				$nrpt=0;
				foreach ($tugas as $tgs) {
					//$ts = StudentAssignment::model()->with('teacher_assign')->together()->findAll(array('condition'=>'t.student_id = '.$key->id.' AND teacher_assign.lesson_id = '.$model->id.' AND status = 1'));
					$ts = StudentAssignment::model()->findByAttributes(array('student_id'=>$key->student->id,'assignment_id'=>$tgs->id,'status'=>1));
					$om = OfflineMark::model()->findAll(array('condition'=>'student_id = '.$key->student->id.' AND lesson_id = '.$model->id.' AND mark_type = 1 AND assignment_id = '.$tgs->id));
					/*echo "<pre>";
					if(!empty($ts->score)){
						echo $ts->score;
					}						
					echo "</pre>";*/
					if($tgs->assignment_type == NULL){	
						if(!empty($ts->score)){
							//echo "<td>".$ts[$cnt]->score."</td>";
							echo "<td>".$ts->score."</td>";
							$cnt++;
							$tnh=$tnh+$ts->score;
						}else{
							echo "<td></td>";
						}
					}else{
						if(!empty($om)){
							//echo "<td>".$om[$counter]->score."</td>";
							echo "<td>".$om[0]->score."</td>";
							$counter++;
							$tnh=$tnh+$om[0]->score;
						}else{
							echo "<td></td>";
						}	
					}
					$div++;
					
				}

				foreach ($kuis as $q) {
					$sq=StudentQuiz::model()->findByAttributes(array('quiz_id'=>$q->id,'student_id'=>$key->student->id));
					if(!empty($sq)){
						echo "<td>".$sq->score."</td>";
						$tnh=$tnh+$sq->score;
					}else{
						echo "<td></td>";
					}
					$div++;
				}
				
				$rnh=$tnh/$div;

				echo "<td>".number_format($rnh,'0',',','.')."</td>";
				
				if(!empty($uts)){
					foreach ($uts as $uks) {
						$suts=StudentQuiz::model()->findByAttributes(array('quiz_id'=>$uks->id,'student_id'=>$key->student->id));
						if(!empty($suts)){
							echo "<td>".$suts->score."</td>";
							$puts=$suts->score;
						}else{
							echo "<td></td>";
						}
						$div2++;
					}
				}else{
					echo "<td></td>";	
				}
				
				if(!empty($uas)){
					foreach ($uas as $ukk) {
						$suas=StudentQuiz::model()->findByAttributes(array('quiz_id'=>$ukk->id,'student_id'=>$key->student->id));
						if(!empty($suas)){
							echo "<td>".$suas->score."</td>";
							$puas=$suas->score;
						}else{
							echo "<td></td>";
						}
						$div2++;
					}
				}else{
					echo "<td></td>";	
				}

				if($div > 0 || $div2 > 0){
					
					if($kurikulum_sekolah[0]->value == 2013){
						$pnh=($tnh+$puts+$puas)/($div+$div2);
					}else{
						$pnh=$rnh*$nilai_harian[0]->value/100;
					}
				}
					
				if($kurikulum_sekolah[0]->value == 2013){
					$nrpt=round($pnh);
				}else{
					$nuts=$puts*$nilai_uts[0]->value/100;
					$ruas=$puas*$nilai_uas[0]->value/100;
					$nrpt=round($pnh+$nuts+$ruas);
				}	
				
				echo "<td>".number_format($nrpt,'0',',','.')."</td>";

			?>

			<input type="hidden" name="id[]" value="<?php echo $key->id;?>">
			<?php }else{ ?>
				<td></td>
				<td></td>
				<td></td>
				<td></td>
				<!-- <td></td> -->
			<?php } ?>

	</tr>
	<?php $no++;?>
	<?php } ?>
	<?php //print_r($sid);?>
</table>

	<input type="hidden" name="assignment_id" id="aid">
</form>
</div>
<script type="text/javascript">
	$(document).ready(function(){
		var myTable = $("#rekapTable");
		var	iter = 0;
		
		$(".tugas").click(function(){
			var id = $(this).attr('id');
			$("#aid").val(id);
			$("<td><label></label>Nilai "+$('#'+id+'').text()+"<input type='text' class='form-control' size=3 name='nilai[]'><br><input type='submit' class='form-control btn btn-danger' value='Nilai'/></td>").insertAfter(".kolom");
			//alert(id);
		});
	});
</script>
<?php } ?>