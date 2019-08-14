<?php
/* @var $this SkillController */
/* @var $model Skill */

$this->breadcrumbs=array(
	'Keterampilan'=>array('index'),
	'Tambah',
);

/*$this->menu=array(
	array('label'=>'List Skill', 'url'=>array('index')),
	array('label'=>'Manage Skill', 'url'=>array('admin')),
);*/
?>
<div class="container">
	<h1>Tambah Nilai Keterampilan</h1>
	<div class="class-md-4">
	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	</div>
</div>