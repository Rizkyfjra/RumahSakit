<?php
/* @var $this ActivityController */

$this->breadcrumbs=array(
	'Activity',
);
?>
<h1>Daftar Aktivitas User</h1>
<div class="row">
	<?php $dt = $dataProvider->getData();?>
	<?php
		foreach ($dt as $value) {
			echo $value->activity_type."<br>";
			echo $value->content."<br>";
		}
	?> 
</div>