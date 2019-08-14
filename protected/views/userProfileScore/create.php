<?php
/* @var $this UserProfileScoreController */
/* @var $model UserProfileScore */

$this->breadcrumbs=array(
	'User Profile Scores'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List UserProfileScore', 'url'=>array('index')),
	array('label'=>'Manage UserProfileScore', 'url'=>array('admin')),
);
?>

<h1>Create UserProfileScore</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>