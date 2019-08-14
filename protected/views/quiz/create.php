<?php
/* @var $this QuizController */
/* @var $model Quiz */

// if($model->moving_class == 1){
// 										$kelasnya = $model->name;
// 										$idkelasnya = $model->id;
// 										$path_nya = 'lesson/'.$idkelasnya;
// 									}else{
// 										$kelasnya = $model->name;
// 										$idkelasnya = $model->id;
// 										$path_nya = 'lesson/'.$idkelasnya;
// 									}

// echo"<pre>";
// 	print_r($model);
// echo"</pre>";

$this->breadcrumbs=array(
	'Ulangan'=>array('index'),
	'Tambah',
);

/*$this->menu=array(
	array('label'=>'List Quiz', 'url'=>array('index')),
	array('label'=>'Manage Quiz', 'url'=>array('admin')),
);*/
?>
<div class="container">

	<h1>Buat Ulangan</h1>
	<div class="class-md-4">
	<?php $this->renderPartial('_form', array('model'=>$model,'semester'=>$semester,'year'=>$year)); ?>
	</div><!-- form -->
</div>