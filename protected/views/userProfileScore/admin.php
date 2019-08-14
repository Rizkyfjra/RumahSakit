<?php
/* @var $this UserProfileScoreController */
/* @var $model UserProfileScore */

$this->breadcrumbs=array(
	'User Profile Scores'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List UserProfileScore', 'url'=>array('index')),
	array('label'=>'Create UserProfileScore', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-profile-score-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage User Profile Scores</h1>

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
	'id'=>'user-profile-score-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'user_id',
		'smt_01_pai',
		'smt_01_pkn',
		'smt_01_bindo',
		'smt_01_bingg',
		/*
		'smt_01_mat',
		'smt_01_ipa',
		'smt_01_ips',
		'smt_01_seni',
		'smt_01_or',
		'smt_01_tik',
		'smt_02_pai',
		'smt_02_pkn',
		'smt_02_bindo',
		'smt_02_bingg',
		'smt_02_mat',
		'smt_02_ipa',
		'smt_02_ips',
		'smt_02_seni',
		'smt_02_or',
		'smt_02_tik',
		'smt_03_pai',
		'smt_03_pkn',
		'smt_03_bindo',
		'smt_03_bingg',
		'smt_03_mat',
		'smt_03_ipa',
		'smt_03_ips',
		'smt_03_seni',
		'smt_03_or',
		'smt_03_tik',
		'smt_04_pai',
		'smt_04_pkn',
		'smt_04_bindo',
		'smt_04_bingg',
		'smt_04_mat',
		'smt_04_ipa',
		'smt_04_ips',
		'smt_04_seni',
		'smt_04_or',
		'smt_04_tik',
		'smt_05_pai',
		'smt_05_pkn',
		'smt_05_binddo',
		'smt_05_bingg',
		'smt_05_mat',
		'smt_05_ipa',
		'smt_05_ips',
		'smt_05_seni',
		'smt_05_or',
		'smt_05_tik',
		'smt_06_pai',
		'smt_06_pkn',
		'smt_06_bindo',
		'smt_06_bingg',
		'smt_06_mat',
		'smt_06_ipa',
		'smt_06_ips',
		'smt_06_seni',
		'smt_06_or',
		'smt_06_tik',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
