<?php
/* @var $this QuestionsController */
/* @var $model Questions */

$this->breadcrumbs=array(
	'Pertanyaan'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

/*$this->menu=array(
	array('label'=>'List Questions', 'url'=>array('index')),
	array('label'=>'Create Questions', 'url'=>array('create')),
	array('label'=>'View Questions', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Questions', 'url'=>array('admin')),
);*/
?>
<div class="container">

	<h1>Update Pertanyaan <?php echo $model->id; ?></h1>
	<div class="col-md-8">
	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	</div><!-- form -->
</div>