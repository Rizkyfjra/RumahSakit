<?php
	$activity = '';
	$late = '';
	$upcoming = '';
	$nilai = '';

	if(Yii::app()->user->YiiStudent){
		switch ($type) {
			case "tugas":
				$activity = 'active';
				break;
			case "kuis":
				$late = 'active';
				break;
			default:
				$activity = 'active';
		}
	}elseif(Yii::app()->user->YiiTeacher){
		switch ($type) {
			case "tugas":
				$activity = 'active';
				break;
			case "kuis":
				$late = 'active';
				break;
			default:
				$activity = 'active';
		}
	}
?>
<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_student_assignment', array(
        
      // ));
    ?>
    <div class="col-md-12">
	  <div id="bc1" class="btn-group btn-breadcrumb">
	    <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
	    <?php echo CHtml::link('<div>Nilai</div>',array('#'), array('class'=>'btn btn-success')); ?>
	  </div>
	</div>

	<?php if(Yii::app()->user->YiiTeacher){ ?>
    <div class="col-lg-12">
      <h3>Nilai
          <small>Daftar Nilai</small>
      </h3>
	  <div class="row">
		<div class="col-md-12">
	      <div class="row">
	        <div class="col-md-3">
          	  <div class="col-card">
				<ul class="nav nav-pills nav-stacked">
					<li class="<?php echo $activity; ?>"><?php echo CHtml::link('Tugas',array('index', 'type'=>'tugas')); ?></li> 
					<li class="<?php echo $late; ?>"><?php echo CHtml::link('Ulangan',array('index', 'type'=>'kuis')); ?></li> 
				</ul>
			  </div>
	        </div>
	  		<div class="col-md-9">
          	  <div class="col-card">
				<?php
					$tugas = $dataProvider->getData();
					if(!empty($tugas)){ 
						echo "<form method='POST' name='checkform' id='checkform' action='".Yii::app()->createUrl("StudentAssignment/bulkNilai")."'>";
				?>
				<table class="table table-bordered table-condensed table-striped table-responsive">
					<tr class="danger">
					<?php if($type == 'tugas' || empty($type)){ ?>
						<th class="text-center">Nama Siswa</th>
						<th class="text-center">Nama Tugas</th>
						<th class="text-center">Nama Pelajaran</th>
						<th class="text-center" width="10%">Kelas</th>
						<th class="text-center">Dikumpulkan Pada</th>
						<th class="text-center">Tepat Waktu</th>
						<th class="text-center">Nilai</th>
					<?php }else{ ?>
						<th class="text-center">Nama Siswa</th>
						<th class="text-center">Nama Ulangan</th>
						<th class="text-center">Nama Pelajaran</th>
						<th class="text-center" width="10%">Kelas</th>
						<th class="text-center">Dikumpulkan Pada</th>
						<th class="text-center">Nilai</th>
					<?php } ?>
					</tr>
					<?php
						$no=1;
						foreach ($tugas as $key) {
					?>
					<tr class="active">
					<?php if($type == 'tugas' || empty($type)){ ?>
						<td><?php echo CHtml::link($key->student->display_name, array('view','id'=>$key->id));?></td>
						<td><?php echo CHtml::link($key->title, array('/assignment/view','id'=>$key->assignment_id));?></td>
						<td><?php echo $key->teacher_assign->lesson->name;?></td>
						<td><?php echo $key->teacher_assign->lesson->class->name;?></td>
						<td><?php echo date('d M Y G:i:s',strtotime($key->updated_at));?></td>
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
					<?php }else{ ?>
						<td><?php echo CHtml::link($key->user->display_name, array('/studentQuiz/view','id'=>$key->id));?></td>
						<td><?php echo CHtml::link($key->quiz->title, array('/quiz/view','id'=>$key->quiz_id));?></td>
						<td><?php echo $key->quiz->lesson->name;?></td>
						<td><?php echo $key->quiz->lesson->class->name;?></td>
						<td><?php echo date('d M Y G:i:s',strtotime($key->updated_at));?></td>
						<td><?php echo $key->score;?></td>
					<?php } ?>
					</tr>
					<?php				
						  $no++;
						}	
					?>
				</table>
				<?php
						echo "<div id='hideshow'>";
						echo "<br>";
		    			echo "<input type='submit' name='submit2' value='Bulk Nilai' class='btn btn-success pull-right'>";
		   		 		echo "</div>";
		    			echo "</form>"; 
				?>
		        <div class="text-center">
		          <?php
		            $this->widget('CLinkPager', array(
		                          'pages'=>$dataProvider->pagination,
		                          ));
		          ?>
		        </div>
		        <?php
			        }
		        ?>
		    </div>
		  </div>
      	</div>
      </div>
    </div>
	<?php } elseif(Yii::app()->user->YiiStudent) { ?>
    <div class="col-lg-12">
      <h3>Nilai
          <small>Daftar Nilai</small>
      </h3>
	  <div class="row">
		<div class="col-md-12">
	      <div class="row">
	        <div class="col-md-3">
          	  <div class="col-card">
				<ul class="nav nav-pills nav-stacked">
					<li class="<?php echo $activity; ?>"><?php echo CHtml::link('Tugas',array('index', 'type'=>'tugas')); ?></li> 
					<li class="<?php echo $late; ?>"><?php echo CHtml::link('Ulangan',array('index', 'type'=>'kuis')); ?></li> 
				</ul>
			  </div>
	  		</div>
	  		<div class="col-md-9">
          	  <div class="col-card">
				<?php
					$tugas = $dataProvider->getData();
					if(!empty($tugas)){
				?>
				<table class="table table-hover">
					<thead>
						<tr>
						<?php if($type == 'tugas' || empty($type)){ ?>
							<th>Nama Tugas</th>
							<th>Nama Pelajaran</th>
							<th width="10%">Kelas</th>
							<th>Dikumpulkan Pada</th>
							<th>Status</th>
							<th>Nilai</th>
						<?php }else{ ?>
							<th>Nama Ulangan</th>
							<th>Nama Pelajaran</th>
							<th width="10%">Kelas</th>
							<th>Dikumpulkan Pada</th>
							<th>Nilai</th>
						<?php } ?>
						</tr>
					</thead>
					<tbody>
					<?php
						$no=1;
						foreach ($tugas as $key) {
					?>
						<tr>
						<?php if($type == 'tugas' || empty($type)){ ?>
							<td><?php echo CHtml::link($key->title, array('/studentAssignment/view','id'=>$key->id));?></td>
							<td><?php echo $key->teacher_assign->lesson->name;?></td>
							<td><?php echo $key->teacher_assign->lesson->class->name;?></td>
							<td><?php echo date('d M Y G:i:s',strtotime($key->updated_at));?></td>
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
						<?php }else{ ?>
							<td><?php echo CHtml::link($key->quiz->title, array('/studentQuiz/view','id'=>$key->id));?></td>
							<td><?php echo $key->quiz->lesson->name;?></td>
							<td><?php echo $key->quiz->lesson->class->name;?></td>
							<td><?php echo date('d M Y G:i:s',strtotime($key->updated_at));?></td>
							<td>
								<?php
									if($key->quiz->show_nilai == 1){ 
										echo $key->score;
									}
								?>
							</td>
						<?php } ?>
						</tr>
					<?php
							$no++;
						}
					?>
					</tbody>
				</table>
		        <div class="text-center">
		          <?php
		            $this->widget('CLinkPager', array(
		                          'pages'=>$dataProvider->pagination,
		                          ));
		          ?>
		        </div>
		        <?php
		        	}
		        ?>
		    </div>
		  </div>
      	</div>
      </div>
    </div>
	<?php }else{ ?> 
    <div class="col-lg-12">
      <h3>Nilai
          <small>Daftar Nilai</small>
      </h3>
	  <div class="row">
		<div class="col-md-5">
          <div class="col-card">
			<div id="s-form" class="search-form col-md-3">
				<p><strong>Cari Berdasarkan</strong></p>
				<?php 
				// $this->renderPartial('_search2',array(

				// )); 
				?>
				<?php
						$ket=NULL;
						$kata=NULL;

						if(isset($_GET['tipe'])){
							$ket = $_GET['tipe'];
						}

						if(isset($_GET['nama'])){
							$kata=$_GET['nama'];
						}
					?>

					<?php if(Yii::app()->user->YiiStudent){ ?>
						<?php $form=$this->beginWidget('CActiveForm', array(
							'action'=>Yii::app()->createUrl('studentAssignment/filter'),
							'method'=>'get',
						)); ?>
					<?php } else {?>
						<?php $form=$this->beginWidget('CActiveForm', array(
							'action'=>Yii::app()->createUrl('studentAssignment/filterTeacher'),
							'method'=>'get',
						)); ?>
					<?php } ?>
					<?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher) { ?>
						<div class="form-group">
							<select class="tipe form-control" name="tipe" id="tipe">
							  <option <?php if($ket == 1) echo "selected";?> value="1" >Nama Pelajaran</option>
							  <option <?php if($ket == 2) echo "selected";?> value="2">Kelas</option>
							  <option <?php if($ket == 3) echo "selected";?> value="3">Nama Siswa</option>
							  <option <?php if($ket == 4) echo "selected";?> value="4">Nama Tugas</option>
							  <option <?php if($ket == 5) echo "selected";?> value="5">Nilai</option>
							</select>
						</div>
					<?php } else { ?>
						<div class="form-group">
							<select class="tipe form-control" name="tipe" id="tipe">
							  <option <?php if($ket == 1) echo "selected";?> value="1">Nama Pelajaran</option>
							  <option <?php if($ket == 2) echo "selected";?> value="2">Kelas</option>
							  <option <?php if($ket == 4) echo "selected";?> value="4">Nama Tugas</option>
							</select>
						</div>
					<?php } ?>

					<div class="form-group">
						<input type="text" name="nama" id="nama" class="form-control" value="<?php echo $kata;?>">
					</div> 

					<div class="form-group">
						<input type="hidden" name="user_id" id="user_id" value="<?php echo Yii::app()->user->id;?>">
					</div> 

					<div class="form-group">
						<input type="submit" class="btn btn-primary" value="Cari">
					</div>

					<?php $this->endWidget(); ?>
			</div>
			<?php
				$tugas = $dataProvider->getData();
				if(!empty($tugas)){
			?>
		  </div>
		</div>
	   </div>
	   <br class="clearfix" />
	   <div class="row">
	   	 <div class="col-md-12">
	   	   <div class="col-card">
			<table class="table table-bordered table-condensed table-striped table-responsive">
				<tr class="danger">
					<th class="text-center">No</th>
					<th class="text-center">Nama Tugas</th>
					<th class="text-center">Nama Pelajaran</th>
					<th class="text-center" width="10%">Kelas</th>
					<th class="text-center">Batas Pengumpulan</th>
					<th class="text-center">Dikumpulkan Tanggal</th>
					<th class="text-center">Status</th>
					<th class="text-center">Nilai</th>
					<th class="text-center">Aksi</th>
				</tr>
				<?php
					$no=1;
					foreach ($tugas as $key) {
				?>
				<tr class="active">
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
				<?php
						$no++;
					} 
				?>
			</table>
	        <div class="text-center">
	          <?php
	            $this->widget('CLinkPager', array(
	                          'pages'=>$dataProvider->pagination,
	                          ));
	          ?>
	        </div>		
			<?php
				}
			?>
       </div>
      </div>
     </div>
    </div>
    <?php } ?>
  </div>
</div>

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
	
	if ($(window).width() > 960) {
		$("#wrapper").toggleClass("toggled");
	}
</script>


