<?php
/* @var $this StudentAssignmentController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Tugas Siswa',
);

/*$this->menu=array(
	array('label'=>'Upload Tugas', 'url'=>array('create')),
	//array('label'=>'Manage StudentAssignment', 'url'=>array('admin')),
);*/

Yii::app()->clientScript->registerScript('search', "
$('#srch').click(function(){
	$('#s-form').toggle();
	//return false;
});
$('.search-form form').submit(function(){
	$('#student-assignment-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");

?>
<?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
<div class="container">
	<h1>Nama Tugas : <?php echo $t_title;?></h1>
	<div id="s-form" class="search-form col-md-3">
		<p><b>Cari berdasarkan</b></p>
	<?php $this->renderPartial('_search2',array(
		//'model'=>$dataProvider,
	)); ?>
	</div>
	<p></p>
	<?php $tugas = $dataProvider->getData();?>
	<?php if(!empty($tugas)){ 
		echo "<form method='POST' name='checkform' id='checkform' action='".Yii::app()->createUrl("StudentAssignment/bulkNilai")."'>";
	?>
	<table class="table table-bordered table-condensed table-striped table-responsive">
		<tr>
			<th class="text-center">No</th>
			<th class="text-center">Nama Tugas</th>
			<th class="text-center">Nama Pelajaran</th>
			<th class="text-center">Kelas</th>
			<th class="text-center">Batas Pengumpulan</th>
			<th class="text-center">Dikumpulkan Tanggal</th>
			<th class="text-center">Nama Siswa</th>
			<th class="text-center">Status</th>
			<th class="text-center">Tepat Waktu</th>
			<th class="text-center">Nilai</th>
			<th class="text-center">Aksi</th>
			<th class="text-center">Bulk</th>
		</tr>
		<?php $no=1;?>
		<?php foreach ($tugas as $key) { ?>
		<tr>
			<td class="text-center"><?php echo $no;?></td>
			<td><?php echo CHtml::link($key->title, array('view','id'=>$key->id));?></td>
			<td><?php echo $key->lesson_name;?></td>
			<td><?php echo $key->class_name;?></td>
			<td><?php echo date('d M Y G:i:s',strtotime($key->due_date));?></td>
			<td><?php echo date('d M Y G:i:s',strtotime($key->created_at));?></td>
			<td><?php echo $key->student->display_name;?></td>
			 <td>
				<?php
					
					if(!empty($key->score)){
						echo "Sudah Mengumpulkan dan Diberi Nilai";	
					} else {
						echo "Sudah Mengumpulkan dan Belum Diberi Nilai";
					}
				?>
			</td>
			<td>
				<?php
					
					if(!empty($key->due_date > $key->created_at)){
						echo "Ya";	
					} else {
						echo "Tidak";
					}
				?>
			</td>
			<td><?php echo $key->score;?></td>
			<td class="text-center"><?php echo CHtml::link("Lihat",array('view','id'=>$key->id),array('class'=>'btn btn-success btn-xs','title'=>'Lihat Tugas'));?> 
				<?php echo CHtml::link("Nilai",array('update','id'=>$key->id),array('class'=>'btn btn-primary btn-xs','title'=>'Beri Nilai'));?></td>
			<?php echo "<td><input type='checkbox' name='check[]' value=$key->id class='chk_boxes1'></td>"; ?>
			<?php $no++;?>
			
		</tr>

		<?php }	?>
	</table>
	<?php
	echo "<div id='hideshow'>";
	echo "<br>";
    echo "<input type='submit' name='submit2' value='Bulk Nilai' class='btn btn-success pull-right'>";
    echo "</div>";
    echo "</form>"; 
	} ?>
</div>
<div class="text-center">
	<?php
	  $this->widget('CLinkPager', array(
	                'pages'=>$dataProvider->pagination,
	                ));
	?>
</div>
<?php } elseif(Yii::app()->user->YiiStudent) { ?>
	<div class="container">
	<h1>Daftar Tugas</h1>
	<div id="s-form" class="search-form col-md-3">
		<p><b>Cari berdasarkan</b></p>
	<?php $this->renderPartial('_search2',array(
		//'model'=>$dataProvider,
	)); ?>
	</div><!-- search-form -->
	<p></p>
	<?php $tugas = $dataProvider->getData();?>
	<?php if(!empty($tugas)){ ?>
	<table class="table table-bordered table-condensed table-striped table-responsive">
		<tr>
			<th class="text-center">No</th>
			<th class="text-center">Nama Tugas</th>
			<th class="text-center">Nama Pelajaran</th>
			<th class="text-center">Kelas</th>
			<th class="text-center">Batas Pengumpulan</th>
			<th class="text-center">Dikumpulkan Tanggal</th>
			<th class="text-center">Status</th>
			<th class="text-center">Nilai</th>
			<th class="text-center">Aksi</th>
		</tr>
		<?php $no=1;?>
		<?php foreach ($tugas as $key) { ?>
		<tr>
			<td class="text-center"><?php echo $no;?></td>
			<td><?php echo $key->title;?></td>
			<td><?php echo $key->lesson_name;?></td>
			<td><?php echo $key->class_name;?></td>
			<td><?php echo date('d M Y',strtotime($key->due_date));?></td>
			<td><?php echo date('d M Y',strtotime($key->created_at));?></td>
			<td>
				<?php
					
					if(!empty($key->score)){
						echo "Sudah Mengumpulkan dan Diberi Nilai";	
					} elseif(empty($key->score) && $key->status == NULL) {
						echo "Belum Mengumpulkan";
					} elseif(empty($key->score) && $key->status == 1) {
						echo "Sudah Mengumpulkan dan Belum Diberi Nilai";
					}	
				?>
			</td>
			<td><?php echo $key->score;?></td>
			<td class="text-center"><?php echo CHtml::link("Lihat",array('view','id'=>$key->id),array('class'=>'btn btn-success btn-xs','title'=>'Lihat Tugas'));?></td>
			
		</tr>
		<?php $no++;} ?>
	</table>
	<?php } ?>
</div>
<div class="text-center">
	<?php
	  $this->widget('CLinkPager', array(
	                'pages'=>$dataProvider->pagination,
	                ));
	?>
</div>
<?php } ?>

<script type="text/javascript">
$(document).ready(function() {
	$('#hideshow').hide();
    $('.chk_boxes').click(function() {
        $('.chk_boxes1').prop('checked', this.checked);
    });

    $("#checkform input").click(function(){
	    if(jQuery('#checkform input[type=checkbox]:checked').length) {
	    	$("#hideshow").show();
	    } else {
	    	$('#hideshow').hide();
	    }
	});

	$('#unClick').click(function() {
		if (confirm('Are you sure you want UnBaned?')) {
		  return true;
		} else {
			return false;
		}
	});

	$('#click').click(function() {
		if (confirm('Are you sure you want Baned?')) {
		  return true;
		} else {
			return false;
		}
	});
});

</script>


