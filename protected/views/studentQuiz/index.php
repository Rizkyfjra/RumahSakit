<?php
/* @var $this StudentQuizController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Ulangan Siswa',
);

/*$this->menu=array(
	array('label'=>'Create StudentQuiz', 'url'=>array('create')),
	array('label'=>'Manage StudentQuiz', 'url'=>array('admin')),
);*/
?>
<?php if(!Yii::app()->user->YiiStudent){ ?>
<h1>Daftar Nilai Kuis</h1>
<div class="row">
	<div class="col-md-4">
		<?php $this->renderPartial('_search-new');?>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
		<?php 
				$format_url = Yii::app()->createUrl("/studentQuiz/formatNilai");
		?>
		<form action="<?php echo $format_url;?>" method="post">
			<p class="text-right"><input type="submit" value="Format"></p>
		<table class="table table-hover table-responsive">
			<th>No</th>
			<th>Nama Siswa</th>
			<th>Judul Kuis</th>
			<th>Guru</th>
			<th>Mata Pelajaran</th>
			<th>Kelas</th>
			<th>Nilai</th>
			<th>Status</th>
				<th class="text-center">
				<br>
				<input type="checkbox" id="selectAll">
			</th>
			<?php 
				$kuis = $dataProvider->getData();
				$no=1;
			?>
			<?php if(!empty($kuis)){ ?>
				<?php foreach ($kuis as $value) { ?>
					<tr>
						<td><?php echo CHtml::link($no, array('view', 'id'=>$value->id));?></td>
						<td>
							<?php if(!empty($value->user)){?>
							<?php $nama = $value->user->display_name;?>
							<?php }else{?>
							<?php $nama = $value->id;?>
							<?php } ?>
							<?php echo CHtml::link($nama, array('view', 'id'=>$value->id))?>
						</td>
						<?php if(!empty($value->quiz)){ ?>
						<td>
							<?php echo CHtml::link($value->quiz->title, array('/quiz/view','id'=>$value->quiz_id));?>
						</td>
						<td>
							<?php echo $value->quiz->teacher->display_name;?>
						</td>
						<td>
							<?php echo $value->quiz->lesson->name;?>
						</td>
						<td>
							<?php echo $value->quiz->lesson->class->name;?>
						</td>
						<td>
							<?php echo $value->score;?>
						</td>
						<td>
							<?php 
								//if($value->score == NULL){
									echo "Sudah Mengerjakan";
							 	//}else{ 
							 		//echo "Belum Mengerjakan";
							 	//} 
							?>
						</td>
						<?php } else{?>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td>
							<td></td> 
						<?php } ?>
						<td class="text-center"><input type="checkbox" class="nilai" name="nilai[]" value="<?php echo $value->id;?>"></td>
					</tr>
					<?php $no++;?>
				<?php } ?>
			<?php } ?>	
		</table>
		<div class="text-center">
			<?php
			  $this->widget('CLinkPager', array(
			                'pages'=>$dataProvider->pagination,
			                ));
			?>
		</div>
	</div>
</div>
<?php }else{ ?>
	<!-- <div class="row">
		<div class="col-md-12">
			<table class="table table-hover table-responsive">
				<th>No</th>
				<th>Nama Siswa</th>
				<th>Judul Kuis</th>
				<th>Guru</th>
				<th>Mata Pelajaran</th>
				<th>Kelas</th>
				<th>Nilai</th>
				<th>Status</th>
				<?php 
					//$kuis = $dataProvider->getData();
					//$no=1;
				?>
				<?php //if(!empty($kuis)){ ?>
					<?php //foreach ($kuis as $value) { ?>
						<tr>
							<td><?php //echo CHtml::link($no, array('view', 'id'=>$value->id));?></td>
							<td><?php //echo CHtml::link($value->user->display_name, array('view', 'id'=>$value->id))?></td>
							<td><?php //echo CHtml::link($value->quiz->title, array('/quiz/view','id'=>$value->quiz_id));?></td>
							<td><?php //echo $value->quiz->teacher->display_name;?></td>
							<td><?php //echo $value->quiz->lesson->name;?></td>
							<td><?php //echo $value->quiz->lesson->class->name;?></td>
							<td><?php //echo $value->score;?></td>
							<td>
								<?php 
									//if(!empty($value->score)){
										//echo "Sudah Mengerjakan";
								 	//}else{ 
								 	//	echo "Belum Mengerjakan";
								 	//} 
								?>
							</td>
						</tr>
						<?php //$no++;?>
					<?php //} ?>
				<?php //} ?>
			</table>
			<div class="text-center">
				<?php
				  /*$this->widget('CLinkPager', array(
				                'pages'=>$dataProvider->pagination,
				                ));*/
				?>
			</div>
		</div>
	</div> -->
<?php } ?>

<?php /*$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
));*/ ?>
<script type="text/javascript">
	 $('#selectAll').click(function(event) {  //on click 
        if(this.checked) { // check select status
            $('.nilai').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"               
            });
        }else{
            $('.nilai').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                       
            });         
        }
    });
</script>