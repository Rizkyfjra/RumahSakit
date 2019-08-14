<?php
/* @var $this StudentSkillController */
/* @var $model StudentSkill */

$this->breadcrumbs=array(
	'Student Skills'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List StudentSkill', 'url'=>array('index')),
	array('label'=>'Manage StudentSkill', 'url'=>array('admin')),
);
?>

<h1>Create StudentSkill</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>