<?php
/* @var $this UserProfileScoreController */
/* @var $model UserProfileScore */

$this->breadcrumbs=array(
	$nama=>array('user/view','id'=>$user_id),	
	'Sejarah Nilai'=>array('view','id'=>$user_id),
	'Edit',
);

?>

<h1>Edit Sejarah Nilai : <?php echo $nama; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'user_id'=>$user_id)); ?>