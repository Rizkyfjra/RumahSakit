<?php
/* @var $this AnnouncementsController */
/* @var $model Announcements */

$this->breadcrumbs=array(
	'Pengumuman'=>array('index'),
	'Tambah',
);

$this->menu=array(
	array('label'=>'List Announcements', 'url'=>array('index')),
	array('label'=>'Manage Announcements', 'url'=>array('admin')),
);
?>


<div class="container">
	<div class="col-md-12">
		<h1>Buat Pengumuman Baru</h1>
		<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	</div><!-- form -->
</div>