<?php
/* @var $this AssignmentController */
/* @var $model Assignment */

$this->breadcrumbs=array(
	'Tugas'=>array('index'),
	$model->title,
);
//$cekTugas = StudentAssignment::model()->findByAttributes(array('assignment_id'=>$model->id,'student_id'=>Yii::app()->user->id));

/*$this->menu=array(
	array('label'=>'List Assignment', 'url'=>array('index')),
	array('label'=>'Create Assignment', 'url'=>array('create')),
	array('label'=>'Update Assignment', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Assignment', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Assignment', 'url'=>array('admin')),
);*/
?>
<div class="container">

<h1><?php echo $model->title; ?></h1>
	<div class="col-md-8">
	<div class="panel panel-info">
			<div class="panel-heading ">
			    <h3 class="panel-title">Batas Pengumpulan : <STRONG><?php echo date('d M Y (H:i)',strtotime($model->due_date));?></STRONG>
			    	<?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?> 
			    	<span class="text-right"><?php echo CHtml::link("Edit", array("/assignment/update","id"=>$model->id));?></span>
			    	<?php } ?>
			    </h3>
			</div>
			<div class="panel-body">
				<p><?php echo $model->content;?></p>
				<p>File : <span><?php echo CHtml::link($model->file, array('assignment/download', 'id'=>$model->id));?></span></p>
				<?php $ext = pathinfo($model->file, PATHINFO_EXTENSION);?>
				<?php if($ext == 'jpg' || $ext == 'png' || $ext == 'png'){ ?>
				<img src="<?php echo Yii::app()->baseUrl;?>/images/assignment/<?php echo $model->id;?>/<?php echo $model->file;?>" class="thumbnails" height="250px" width="400px">
				<?php } ?>
			</div>
			<div class="panel-footer">
				<p class="text-right">Published by <STRONG><?php echo ucfirst($model->teacher->display_name);?></STRONG></p>
			</div>    
		</div>
	</div>
	<br>
	<div class="col-md-12">
		<hr>
		<h2>Tugas Siswa</h2>
		<div id="s-form" class="search-form col-md-3">
			<p><b>Cari berdasarkan</b></p>
			<?php $this->renderPartial('_search-tugas',array('id'=>$model->id
					)); 
			?>
		</div>
		<p></p>
		<?php $tsiswa = $studentTasks->getData();?>
		<table class="table table-bordered table-condensed table-striped">
			<tr class="danger">
				<th class="text-center">No</th>
				<th class="text-center">Nama Siswa</th>
				<th class="text-center">Nama Pelajaran</th>
				<th class="text-center">Kelas</th>
				<th class="text-center">Batas Pengumpulan</th>
				<th class="text-center">Dikumpulkan Tanggal</th>
				<th class="text-center">Status</th>
				<th class="text-center">Nilai</th>
				<th class="text-center">Tepat Waktu</th>
				<th class="text-center">Aksi</th>
			</tr>
		<?php if(!empty($tsiswa)){ ?>
			<?php $no=1;?>
			<?php foreach ($tsiswa as $tugas) { ?>
				
				<tr class="active">
					<td class="text-center"><?php echo $no;?></td>
					<td><?php echo $tugas->display_name;?></td>
					<td><?php echo $tugas->lesson_name;?></td>
					<td><?php echo $tugas->class_name;?></td>
					<td><?php echo date('d M Y',strtotime($tugas->due_date));?></td>
					<td><?php echo date('d M Y',strtotime($tugas->created_at));?></td>
					<td>
						<?php
							
							if(!empty($tugas->score)){
								echo "Sudah Mengumpulkan dan Diberi Nilai";	
							} elseif(empty($tugas->score) && $tugas->status == NULL) {
								echo "Belum Mengumpulkan";
							} elseif(empty($tugas->score) && $tugas->status == 1) {
								echo "Sudah Mengumpulkan dan Belum Diberi Nilai";
							}	
						?>
					</td>
					<td><?php echo $tugas->score;?></td>
					<td>
						<?php
				
							if(!empty($tugas->due_date > $tugas->created_at)){
								echo "Ya";	
							} else {
								echo "Tidak";
							}
						?>
					</td>
					<td class="text-center"><?php echo CHtml::link("Lihat",array('studentAssignment/view','id'=>$tugas->id),array('class'=>'btn btn-success btn-xs','title'=>'Lihat Tugas'));?> 
					<?php echo CHtml::link("Nilai",array('studentAssignment/update','id'=>$tugas->id),array('class'=>'btn btn-primary btn-xs','title'=>'Beri Nilai'));?></td>
					
				</tr>
				<?php $no++; ?>

			<?php } ?>
			<div class="text-center">
				<?php
				  $this->widget('CLinkPager', array(
				                'pages'=>$studentTasks->pagination,
				                ));
				?>
			</div>		
		<?php } ?>
		</table>
	</div>
</div>
