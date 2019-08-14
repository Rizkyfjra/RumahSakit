<?php
	$ket=NULL;
	$kata=NULL;

	if(isset($_GET['tipe'])){
		$ket = $_GET['tipe'];
	}

	if(isset($_GET['nama'])){
		$kata=$_GET['nama'];
	}
?>

<?php if(Yii::app()->user->YiiStudent){ ?>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl('studentAssignment/filter'),
		'method'=>'get',
	)); ?>
<?php } else {?>
	<?php $form=$this->beginWidget('CActiveForm', array(
		'action'=>Yii::app()->createUrl('studentAssignment/filterTeacher'),
		'method'=>'get',
	)); ?>
<?php } ?>
<?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher) { ?>
	<div class="form-group">
		<select class="tipe form-control" name="tipe" id="tipe">
		  <option <?php if($ket == 1) echo "selected";?> value="1" >Nama Pelajaran</option>
		  <option <?php if($ket == 2) echo "selected";?> value="2">Kelas</option>
		  <option <?php if($ket == 3) echo "selected";?> value="3">Nama Siswa</option>
		  <option <?php if($ket == 4) echo "selected";?> value="4">Nama Tugas</option>
		  <option <?php if($ket == 5) echo "selected";?> value="5">Nilai</option>
		</select>
	</div>
<?php } else { ?>
	<div class="form-group">
		<select class="tipe form-control" name="tipe" id="tipe">
		  <option <?php if($ket == 1) echo "selected";?> value="1">Nama Pelajaran</option>
		  <option <?php if($ket == 2) echo "selected";?> value="2">Kelas</option>
		  <option <?php if($ket == 4) echo "selected";?> value="4">Nama Tugas</option>
		</select>
	</div>
<?php } ?>

<div class="form-group">
	<input type="text" name="nama" id="nama" class="form-control" value="<?php echo $kata;?>">
</div> 

<div class="form-group">
	<input type="hidden" name="user_id" id="user_id" value="<?php echo Yii::app()->user->id;?>">
</div> 

<div class="form-group">
	<input type="submit" class="btn btn-primary" value="Cari">
</div>

<?php $this->endWidget(); ?>