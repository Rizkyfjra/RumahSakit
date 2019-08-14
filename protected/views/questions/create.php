<?php
/* @var $this QuestionsController */
/* @var $model Questions */

$this->breadcrumbs=array(
	'Pertanyaan'=>array('index'),
	'Tambah',
);

/*$this->menu=array(
	array('label'=>'List Questions', 'url'=>array('index')),
	array('label'=>'Manage Questions', 'url'=>array('admin')),
);*/
?>
<div class="container">

	<h1>Buat Pertanyaan</h1>
	<div class="col-md-10">
	<?php $this->renderPartial('_form', array('model'=>$model)); ?>
	</div><!-- form -->
</div>