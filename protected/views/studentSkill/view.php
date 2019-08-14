<?php
/* @var $this StudentSkillController */
/* @var $model StudentSkill */

$this->breadcrumbs=array(
	'Student Skills'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List StudentSkill', 'url'=>array('index')),
	array('label'=>'Create StudentSkill', 'url'=>array('create')),
	array('label'=>'Update StudentSkill', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete StudentSkill', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage StudentSkill', 'url'=>array('admin')),
);
?>

<h1>View StudentSkill #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'skill_id',
		'student_id',
		'score',
		'trash',
	),
)); ?>
