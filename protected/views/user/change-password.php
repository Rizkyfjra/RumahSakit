<?php
$this->breadcrumbs=array(
	'User'=>array('index'),
	'Ganti Password',
	$model->display_name,
);

?>
<!-- <div class="col-xs-offset-2 col-xs-8 col-sm-offset-3 col-sm-6 well well-sm"> -->
<div class="col-xs-12 col-sm-offset-3 col-sm-6 well well-sm">
	<h1>Ganti Password <?php echo $model->username; ?></h1>
	<hr>
	<?php $this->renderPartial('_password-change', array('model'=>$model)); ?>
</div>