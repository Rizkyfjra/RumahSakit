<?php
/* @var $this StudentSkillController */
/* @var $model StudentSkill */

$this->breadcrumbs=array(
	'Student Skills'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List StudentSkill', 'url'=>array('index')),
	array('label'=>'Create StudentSkill', 'url'=>array('create')),
	array('label'=>'View StudentSkill', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage StudentSkill', 'url'=>array('admin')),
);
?>

<h1>Update StudentSkill <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>