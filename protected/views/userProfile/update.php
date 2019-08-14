<?php
/* @var $this UserProfileController */
/* @var $model UserProfile */

$this->breadcrumbs=array(
	$nama=>array('user/view','id'=>$user_id),	
	'Profil Siswa'=>array('view','id'=>$user_id),
	'Edit',
);

?>

<h1>Edit Profil Atas Nama : <?php echo $nama; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model,'user_id'=>$user_id)); ?>