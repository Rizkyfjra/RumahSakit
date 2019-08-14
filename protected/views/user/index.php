<?php
/* @var $this UserController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'User',
);

$this->menu=array(
	array('label'=>'Create User', 'url'=>array('create')),
	array('label'=>'Manage User', 'url'=>array('admin')),
);
$user = $dataProvider->getData();

$pil='';
$satu='';
$dua='';
$tiga='';
$empat='';
$lima='';
$teks=NULL;

if(isset($_GET['pilihan'])){
	$pil = $_GET['pilihan'];
	switch ($pil) {
		case 1:
			$satu="selected";
			break;
		case 2:
			$dua="selected";
			break;
		case 3:
			$tiga="selected";
			break;
		case 4:
			$empat="selected";
			break;
		case 5:
			$lima="selected";
			break;
		
		default:
			$pil ='';
			break;
	}
}

if(isset($_GET['keyword'])){
	$teks = $_GET['keyword'];
}


?>

<h1>Daftar User</h1>
<div class="container">
	<div class="row">
		<div class="col-md-4">
			<p><b>Cari User By</b></p>
			<?php 
				$filter_url = Yii::app()->createUrl("/user/filter");
			?>

			<form action="<?php echo $filter_url;?>" method="get">
				<div class="form-group">
					<select class="form-control" name="pilihan">
						<option value="0">--- Pilihan ---</option>
						<option value="1" <?php echo $satu;?> >NIK / NIP</option>
						<option value="2" <?php echo $dua;?> >Email</option>
						<option value="3" <?php echo $tiga;?> >Nama</option>
						<!-- <option value="4" <?php //echo $empat;?> >Role</option> -->
						<option value="5" <?php echo $lima;?> >Kelas</option>
					</select>
				</div>
				<div class="form-group">
					<input type="text" name="keyword" class="form-control" value="<?php echo $teks;?>">
				</div>
				<div class="form-group">
					<input type="submit" value="Search" class="btn btn-primary">
				</div>

			</form>
		</div>
	</div>
	<div class="row">
		<?php if(!Yii::app()->user->YiiTeacher || Yii::app()->user->YiiStudent){ ?>
		<p class="text-right">
			<?php //echo CHtml::link('Bulk User', array('bulk'),array('class'=>'btn btn-primary'))?>
			<?php echo CHtml::link('Download User', array('downloadUser','pilihan'=>$pil,'keyword'=>$teks),array('class'=>'btn btn-info pull-right'))?>
			<?php echo CHtml::link('List Profil', array('/userProfile/showtable'),array('class'=>'btn btn-info pull-right'))?>
		</p>
		<hr>
		<?php } ?>	
	<table class="table table-hover table-bordered well">
		<tbody>
			<th>NIK / NIP</th>
			<th>Email</th>
			<th>Nama</th>
			<th>Role</th>
			<th>Kelas</th>
			<th>Password Baru</th>
			<th>ID User</th>
			<th>Aksi</th>
			<?php foreach ($user as $value) { ?>
				<tr>
					<td><?php echo CHtml::link($value->username, array('view','id'=>$value->id));?></td>
					<td><?php echo $value->email;?></td>
					<td><?php echo $value->display_name;?></td>
					<td>
						<?php
							if($value->role_id == 2){
								echo "Siswa";
							}elseif($value->role_id == 1){
								echo "Guru";
							}elseif($value->role_id == 3){
								echo "Kepala Sekolah";
							}elseif($value->role_id == 4){
								echo "Wali Kelas";	
							}elseif($value->role_id == 99){
								echo "Administrator";
							}
						?>
					</td>
					<td>
						<?php
						//print_r($value);
							if(!empty($value->class_id)){
								$kls = ClassDetail::model()->findAll(array("condition"=>"id = ".$value->class_id));
								if(!empty($kls)){
									echo $kls[0]->name;
								}
							} 
						?>
					</td>
					<td><?php echo $value->reset_password;?></td>
					<td><?php echo $value->id;?></td>
					<td><?php echo CHtml::link('Hapus', array('hapus','id'=>$value->id),array('class'=>'btn btn-danger','confirm'=>'Anda Yakin Menghapus User Ini?'));?></td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	<div class="col-md-12 text-center">
	    <?php
	      $this->widget('CLinkPager', array(
	                    'pages'=>$dataProvider->pagination,
	                    ));
	    ?>
	</div>
	</div>
</div>
<?php /*$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
));*/ ?>