<?php
/* @var $this UserProfileController */
/* @var $model UserProfile */

$this->breadcrumbs=array(
	'User Profiles'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List UserProfile', 'url'=>array('index')),
	array('label'=>'Create UserProfile', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#user-profile-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage User Profiles</h1>

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
	'id'=>'user-profile-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'user_id',
		'nisn',
		'j_kelamin',
		'no_seri_ijazah_smp',
		'no_seri_skhun',
		/*
		'no_un_smp',
		'nik',
		'tempat_lahir',
		'tgl_lahir',
		'agama',
		'berkebutuhan_khusus',
		'alamat_tinggal',
		'alamat_dusun',
		'alamat_rt',
		'alamat_rw',
		'alamat_kelurahan',
		'alamat_kodepos',
		'alamat_kecamatan',
		'alamat_kota',
		'alamat_provinsi',
		'alat_transportasi',
		'jenis_tinggal',
		'no_telpon',
		'email',
		'penerima_kps',
		'no_kps',
		'ayah_nama',
		'ayah_thn_lahir',
		'ayah_berkebutuhan_khusus',
		'ayah_pekerjaan',
		'ayah_pendidikan',
		'ayah_penghasilan',
		'ibu_nama',
		'ibu_thn_lahir',
		'ibu_berkebutuhan_khusus',
		'ibu_pekerjaan',
		'ibu_pendidikan',
		'ibu_penghasilan',
		'wali_nama',
		'wali_thn_lahir',
		'wali_berkebutuhan_khusus',
		'wali_pekerjaan',
		'wali_pendidikan',
		'wali_penghasilan',
		'tinggi_badan',
		'berat_badan',
		'jarak_tempat_tgl_ke_sekolah',
		'waktu_tempuh_ke_sekolah',
		'jumlah_saudara_kandung',
		*/
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>
