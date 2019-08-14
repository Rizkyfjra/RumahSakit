<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	'Users'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List User', 'url'=>array('index')),
	array('label'=>'Create User', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Users</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'user-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	//'pager'=>array('cssFile' => Yii::app()->theme->baseUrl . '/css/gridView.css'),
	//'cssFile' => Yii::app()->theme->baseUrl . '/css/gridView.css',
	//'summaryText' => 'Showing you {start} - {end} of {count} records',
	'itemsCssClass' => 'table table-striped table-hover',
	'columns'=>array(
		'id',
		'email',
		'username',
		'role_id',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{view}{update}{delete}{getok}',
			'buttons'=>array
		    (
		        'getok' => array
		        (
		            'label'=>'Generate Token For Reset Password',
		            'imageUrl'=>Yii::app()->request->baseUrl.'/images/getok.png',
		            'click'=>'function(){if(confirm("Going down!")) return true; else return false;}',
		            'url'=>'Yii::app()->createUrl("user/getok", array("id"=>$data->id))',

		        ),
		    )
		),
	),
)); ?>
