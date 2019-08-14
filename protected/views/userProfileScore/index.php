<?php
/* @var $this UserProfileScoreController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User Profile Scores',
);

$this->menu=array(
	array('label'=>'Create UserProfileScore', 'url'=>array('create')),
	array('label'=>'Manage UserProfileScore', 'url'=>array('admin')),
);
?>

<h1>User Profile Scores</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
