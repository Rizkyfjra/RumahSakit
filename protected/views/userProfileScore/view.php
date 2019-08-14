<?php
/* @var $this UserProfileController */
/* @var $model UserProfile */

$this->breadcrumbs=array(
	$nama=>array('user/view','id'=>$user_id),	
	'Sejarah Nilai',
);

?>

<h1>Lihat Sejarah Nilai : <?php echo $nama; ?></h1>

<br/><br/>
<div class="form-group">
	<div class="alert alert-info">
		<p><strong>DATA SEJARAH NILAI SISWA</strong></p>
	</div>
</div>

<br/>
<div class="form-group">
	<div class="alert alert-warning">
		<p><strong>SEMESTER 1</strong></p>
	</div>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'smt_01_pai',
		'smt_01_pkn',
		'smt_01_bindo',
		'smt_01_bingg',
		'smt_01_mat',
		'smt_01_ipa',
		'smt_01_ips',
		'smt_01_seni',
		'smt_01_or',
		'smt_01_tik',
	),
)); ?>

<br/>
<div class="form-group">
	<div class="alert alert-warning">
		<p><strong>SEMESTER 2</strong></p>
	</div>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'smt_02_pai',
		'smt_02_pkn',
		'smt_02_bindo',
		'smt_02_bingg',
		'smt_02_mat',
		'smt_02_ipa',
		'smt_02_ips',
		'smt_02_seni',
		'smt_02_or',
		'smt_02_tik',
	),
)); ?>

<br/>
<div class="form-group">
	<div class="alert alert-warning">
		<p><strong>SEMESTER 3</strong></p>
	</div>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'smt_03_pai',
		'smt_03_pkn',
		'smt_03_bindo',
		'smt_03_bingg',
		'smt_03_mat',
		'smt_03_ipa',
		'smt_03_ips',
		'smt_03_seni',
		'smt_03_or',
		'smt_03_tik',
	),
)); ?>

<br/>
<div class="form-group">
	<div class="alert alert-warning">
		<p><strong>SEMESTER 4</strong></p>
	</div>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'smt_04_pai',
		'smt_04_pkn',
		'smt_04_bindo',
		'smt_04_bingg',
		'smt_04_mat',
		'smt_04_ipa',
		'smt_04_ips',
		'smt_04_seni',
		'smt_04_or',
		'smt_04_tik',
	),
)); ?>

<br/>
<div class="form-group">
	<div class="alert alert-warning">
		<p><strong>SEMESTER 5</strong></p>
	</div>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'smt_05_pai',
		'smt_05_pkn',
		'smt_05_bindo',
		'smt_05_bingg',
		'smt_05_mat',
		'smt_05_ipa',
		'smt_05_ips',
		'smt_05_seni',
		'smt_05_or',
		'smt_05_tik',
	),
)); ?>

<br/>
<div class="form-group">
	<div class="alert alert-warning">
		<p><strong>SEMESTER 6</strong></p>
	</div>
</div>
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'smt_06_pai',
		'smt_06_pkn',
		'smt_06_bindo',
		'smt_06_bingg',
		'smt_06_mat',
		'smt_06_ipa',
		'smt_06_ips',
		'smt_06_seni',
		'smt_06_or',
		'smt_06_tik',
	),
)); ?>
