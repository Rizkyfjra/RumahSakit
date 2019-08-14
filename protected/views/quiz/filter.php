<?php
/* @var $this QuizController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ulangan',
);

/*$this->menu=array(
	array('label'=>'Create Quiz', 'url'=>array('create')),
	array('label'=>'Manage Quiz', 'url'=>array('admin')),
);*/
?>

<h1>Ulangan</h1>
<?php if(!Yii::app()->user->YiiStudent){ ?>
<p class="text-right">
	<?php echo CHtml::link('Tambah Ulangan', array('create'), array('class'=>'btn btn-success'));?>
	<span><?php
		//echo CHtml::link('Import', array('bulkUlangan2'), array('class'=>'btn btn-info'))
		//if(Yii::app()->user->YiiAdmin){
		//	echo CHtml::link('Tampilkan Semua Ulangan', array('showAll'),array('class'=>'btn btn-primary'));
		//}
	?></span>
</p>
<?php } ?>
<div id="st-form" class="search-form col-md-3">
	<p><b>Cari berdasarkan</b></p>
	<?php $this->renderPartial('_search-new'); 
	?>
</div>
<div class="col-md-12">
<div class="row">
<?php if(!empty($dataProvider->getData())){ ?>
	<?php $kuis = $dataProvider->getData();?>
	<?php if(!empty($dataProvider->getData())){ ?>
	<?php $kuis = $dataProvider->getData();?>
	<?php if(!Yii::app()->user->YiiStudent){ ?>
	<?php 
		$format_url = Yii::app()->createUrl("/quiz/showAll");
	?>
	<form action="<?php echo $format_url;?>" method="post">
	<p class="text-right"><input type="submit" name="hapus" class="btn btn-danger" value="Hapus"> <span><input type="submit" class="btn btn-warning" name="tutup" value="Tutup"></span> <span><input type="submit" class="btn btn-info" name="tampil" value="Tampilkan"></span></p>	
	<?php } ?>	
	<table class="table table-bordered table-condensed table-responisve well">
		<tbody>
			<tr>
				<th class="info">No</th>
				<th class="info">Ulangan</th>
				<th class="info">Pelajaran</th>
				<th class="info">Kelas</th>
				<th class="info">Guru</th>
				<?php if(Yii::app()->user->YiiStudent){ ?>
				<th class="info">Status</th>
				<th class="info"></th>
				<?php } else{ ?>
				<th class="info">Jumlah Siswa</th>
				<th class="info">Jumlah Siswa Yang Mengerjakan</th>
				<th class="info">Passcode</th>
				<th class="info">Waktu</th>
				<th class="info">Acak/Tidak</th>
				<th class="info">Status</th>
				<th class="info">Download Nilai</th>
				<th class="info text-center"><input type="checkbox" id="selectAll"></th>
				<?php } ?>
				
			</tr>
			<?php $no = 1;?>
			<?php foreach ($kuis as $value) { ?>
				<tr>
					<td><?php echo $no;?></td>
					<td><b><?php echo CHtml::link(CHtml::encode($value->title), array('view', 'id'=>$value->id)); ?></b></td>
					<td>
						<?php 
							if(!empty($value->lesson_id)){
								echo $value->lesson->name;
							} 
						?>
					</td>
					<td>
						<?php 
							if(!empty($value->lesson_id)){
								echo $value->lesson->class->name;
							} 
						?>
					</td>
					<td>
						<?php
							if(!empty($value->created_by)){
								echo $value->teacher->display_name;
							}
						?>
					</td>
					<?php if(Yii::app()->user->YiiStudent){ ?>
						<?php
							$cekQuiz = StudentQuiz::model()->findByAttributes(array('quiz_id'=>$value->id,'student_id'=>Yii::app()->user->id)); 
							if(!empty($cekQuiz)){
								$status = "Sudah Mengerjakan";
							}else{
								$status = "Belum Mengerjakan";
							}
						?>
						<td><?php echo $status;?></td>
						<?php if(!empty($cekQuiz)){ ?>
							<td></td>
						<?php }else { ?> 
							<td><?php echo CHtml::link('Mulai', array('startQuiz','id'=>$value->id),array('class'=>'btn btn-success'));?></td>
						<?php } ?>
					<?php }else{ ?>
						<?php
							$cek=StudentQuiz::model()->findAll(array('condition'=>'quiz_id = '.$value->id));
							$total=count($cek);
							if(!empty($value->lesson_id)){
								$cekTotal=User::model()->findAll(array('condition'=>'class_id = '.$value->lesson->class->id));
								$totalSiswa=count($cekTotal);
							}else{
								$totalSiswa = 0;
							}
						?>
						<td>
							<?php echo $totalSiswa;?>
						</td>
						<td><?php echo $total;?></td>
						<td><?php echo $value->passcode;?></td>
						<td><?php echo $value->end_time." Menit";?></td>
						<td><?php
						           if($value->random == 1){
												echo "Acak";
											}else{
												echo "Tidak Acak";
											}

						 ?></td>
							<?php if($value->status==1){ ?>
							<td class="warning"><?php echo "Ditampilkan";?></td>			
							<?php }elseif($value->status==2){ ?>
							<td class="danger"><?php echo "Ditutup";?></td>
							<?php	}else{ ?>
							<td class="success"><?php echo "Draft";?></td>
							<?php	} ?>
						<td><?php echo CHtml::link('<i class="fa fa-download"></i>', array('downloadNilai','id'=>$value->id), array('class'=>'btn btn-success'))?></td>
						<td>
							<input type="checkbox" name="quiz[]" class="text-center quiz" value="<?php echo $value->id?>">
						</td>
					<?php } ?>
					
				</tr>
				<?php $no++;?>
			<?php } ?>
		</tbody>
	</table>
	</form>
<?php } ?>
<?php } ?>
</div>

<div class="text-center">
	<?php
	  $this->widget('CLinkPager', array(
	                'pages'=>$dataProvider->pagination,
	                ));
	?>
</div>	
</div>
<script type="text/javascript">
$('#selectAll').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.quiz').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            $('.quiz').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
    });
</script>