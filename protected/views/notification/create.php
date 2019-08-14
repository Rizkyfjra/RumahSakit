<?php
/* @var $this NotificationController */
/* @var $model Notification */

$this->breadcrumbs=array(
	'Notifikasi'=>array('index'),
	'Tambah',
);

$this->menu=array(
	array('label'=>'List Notification', 'url'=>array('index')),
	array('label'=>'Manage Notification', 'url'=>array('admin')),
);
?>

<h1>Create Notification</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>