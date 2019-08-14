<?php
/* @var $this UserController */
/* @var $model User */
/* @var $form CActiveForm */
$this->breadcrumbs=array(
  'User'=>array('index'),
  $id=>array('view','id'=>$id),
);
	$status_array = array(1 => 'Dokter',2 => 'Staf',3=>'Dirut',4=>'Wali');
	$jenis_kelamin = array('1' => "Laki-laki", '2' => "Perempuan");
	$list_agama = array('1' => "Islam", '2' => "Katholik" , '3' => "Protestan" , '4' => "Hindu" , '5' => "Buddha" , '6' => "Konghucu" , '99' => "Lainnya" );
	$list_status_anak = array('1' => "Anak Kandung", '2' => "Anak Angkat" , '3' => "Anak Tiri" , '4' => "Lainnya");

	for($i = 1;$i <= 15;$i++)
	{
		$anak[$i] = $i;
	}
?>

<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'htmlOptions' => array('enctype' => 'multipart/form-data'),
	'enableClientValidation'=>true,
	'clientOptions'=>array(
	'validateOnSubmit'=>true,
	),
)); ?>

	<legend>Edit Data pasien</legend>
	<legend>Nama : <?php echo $user->display_name?></legend>
	<legend>Nis : <?php echo $user->username?></legend>
	<?php 
	// echo "<pre>";
	// 	print_r($user);
	// echo "</pre>"; 
	?>
	
	<?php echo $form->errorSummary($model); ?>

	<div class="form-group">
		<?php echo $form->labelEx($model,'nisn'); ?>
		<?php echo $form->textField($model,'nisn',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'nisn'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'tempat_lahir'); ?>
		<?php echo $form->textField($model,'tempat_lahir',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tempat_lahir'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'tgl_lahir'); ?>
		<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
	    'model'=>$model,
	    'attribute' => 'tgl_lahir',
    	// additional javascript options for the date picker plugin
    	'options'=>array(
        'showAnim'=>'slide',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
        'dateFormat'=>'dd-mm-yy',//Date format 'mm/dd/yy','yy-mm-dd','d M, y','d MM, 
        'changeMonth'=>true,
        'changeYear'=>true,
        'yearRange'=>'1900:2099'
        ),
    	'htmlOptions'=>array(
        'class'=>'form-control',
    	),
		));
?>
		<?php //echo $form->textField($model,'tgl_lahir',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tgl_lahir'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'j_kelamin'); ?>
		<?php echo $form->dropDownList($model,'j_kelamin', $jenis_kelamin ,array('empty' => '-- Pilih Jenis Kelamin --','class'=>'form-control')); ?>
		<?php echo $form->error($model,'j_kelamin'); ?>
	</div>
	
	<div class="form-group">
		<?php echo $form->labelEx($model,'agama'); ?>
		<?php echo $form->dropDownList($model,'agama', $list_agama ,array('empty' => '-- Pilih Agama --','class'=>'form-control')); ?>
		<?php echo $form->error($model,'agama'); ?>
	</div>
	<div class="form-group">
		<?php echo $form->labelEx($model,'status_keluarga'); ?>
		<?php echo $form->dropDownList($model,'status_keluarga', $list_status_anak ,array('empty' => '-- Pilih Status --','class'=>'form-control')); ?>
		<?php echo $form->error($model,'status_keluarga'); ?>
	</div>
	
	<div class="form-group">
		<?php echo $form->labelEx($model,'anak_ke'); ?>
		<?php echo $form->dropDownList($model,'anak_ke',$anak , array('empty' => '-- Pilih --','class'=>'form-control')); ?>
		<?php echo $form->error($model,'anak_ke'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'alamat'); ?>
		<?php echo $form->textField($model,'alamat',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'alamat'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'no_telp'); ?>
		<?php echo $form->textField($model,'no_telp',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'no_telp'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'sekolah_asal'); ?>
		<?php echo $form->textField($model,'sekolah_asal',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'sekolah_asal'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'kelas_diterima'); ?>
		<?php echo $form->dropDownList($model,'kelas_diterima',CHtml::listData(ClassDetail::model()->findAll(), 'name', 'name')
 , array('empty' => '-- Pilih Kelas --','class'=>'form-control')); ?>
		<?php echo $form->error($model,'kelas_diterima'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'tgl_diterima'); ?>
		<?php
		$this->widget('zii.widgets.jui.CJuiDatePicker',array(
	    'model'=>$model,
	    'attribute' => 'tgl_diterima',
    	// additional javascript options for the date picker plugin
    	'options'=>array(
        'showAnim'=>'slide',//'slide','fold','slideDown','fadeIn','blind','bounce','clip','drop'
        'dateFormat'=>'dd-mm-yy',//Date format 'mm/dd/yy','yy-mm-dd','d M, y','d MM, 
        'changeMonth'=>true,
        'changeYear'=>true,
        'yearRange'=>'1900:2099'
        ),
    	'htmlOptions'=>array(
        'class'=>'form-control',
    	),
		));
?>
		<?php //echo $form->textField($model,'tgl_diterima',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'tgl_diterima'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'nama_ayah'); ?>
		<?php echo $form->textField($model,'nama_ayah',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'nama_ayah'); ?>
	</div>	

	<div class="form-group">
		<?php echo $form->labelEx($model,'nama_ibu'); ?>
		<?php echo $form->textField($model,'nama_ibu',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'nama_ibu'); ?>
	</div>		

	<div class="form-group">
		<?php echo $form->labelEx($model,'alamat_ortu'); ?>
		<?php echo $form->textField($model,'alamat_ortu',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'alamat_ortu'); ?>
	</div>	

	<div class="form-group">
		<?php echo $form->labelEx($model,'no_telp_ortu'); ?>
		<?php echo $form->textField($model,'no_telp_ortu',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'no_telp_ortu'); ?>
	</div>	

	<div class="form-group">
		<?php echo $form->labelEx($model,'pekerjaan_ayah'); ?>
		<?php echo $form->textField($model,'pekerjaan_ayah',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'pekerjaan_ayah'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'pekerjaan_ibu'); ?>
		<?php echo $form->textField($model,'pekerjaan_ibu',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'pekerjaan_ibu'); ?>
	</div>	

	<div class="form-group">
		<?php echo $form->labelEx($model,'nama_wali'); ?>
		<?php echo $form->textField($model,'nama_wali',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'nama_wali'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'alamat_wali'); ?>
		<?php echo $form->textField($model,'alamat_wali',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'alamat_wali'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'no_telp_wali'); ?>
		<?php echo $form->textField($model,'no_telp_wali',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'no_telp_wali'); ?>
	</div>

	<div class="form-group">
		<?php echo $form->labelEx($model,'pekerjaan_wali'); ?>
		<?php echo $form->textField($model,'pekerjaan_wali',array('size'=>60,'maxlength'=>255,'class'=>'form-control')); ?>
		<?php echo $form->error($model,'pekerjaan_wali'); ?>
	</div>	



	<div class="form-group">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save',array('class'=>'btn btn-success')); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->


<?php 
	function dateIndo($tanggal) 
	{ 
		$date = date_create($tanggal);
		return date_format($date, 'd-m-Y');
	}
?>