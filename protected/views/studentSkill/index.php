<?php
/* @var $this StudentSkillController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Student Skills',
);

$this->menu=array(
	array('label'=>'Create StudentSkill', 'url'=>array('create')),
	array('label'=>'Manage StudentSkill', 'url'=>array('admin')),
);
?>

<h1>Student Skills</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
