<?php
/* @var $this AnnouncementsController */
/* @var $model Announcements */

$this->breadcrumbs=array(
	'Pengumuman'=>array('index'),
	$model->title=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Announcements', 'url'=>array('index')),
	array('label'=>'Create Announcements', 'url'=>array('create')),
	array('label'=>'View Announcements', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Announcements', 'url'=>array('admin')),
);
?>
<div class="container">
	<div class="col-md-12">
		<h1>Edit Pengumuman <?php echo $model->title; ?></h1>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	</div><!-- form -->
</div>