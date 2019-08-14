<?php
	$cekTugas = StudentAssignment::model()->findByAttributes(array('assignment_id'=>$model->id,'student_id'=>Yii::app()->user->id));
	$video=array('mp4', 'MP4', 'avi', 'flv', 'mkv');
?>
<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_task_detail', array(
      //   'model'=>$model
      // ));
    ?>
    <div class="col-md-12">
	  <div id="bc1" class="btn-group btn-breadcrumb">
	    <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
	    <?php echo CHtml::link('<div>Tugas</div>',array('/assignment/index'), array('class'=>'btn btn-default')); ?>
	    <?php echo CHtml::link('<div>'.CHtml::encode($model->title).'</div>',array('#'), array('class'=>'btn btn-success')); ?>
	  </div>
	</div>

    <div class="col-lg-12">
      <h3>Tugas
      	<small><?php echo $model->title ?></small>
      </h3>
      <div class="row">
		<div class="col-md-8">
        	<div class="col-card">
	            <div class="panel-body">
	           		<div class="row">
									<?php if($type == NULL && empty($model->assignment_type)){ ?>
           				<div class="col-md-6">
           					<div class="pull-left">
           						<h5>
           							Batas Pengumpulan : <strong><?php echo date('d M Y (H:i)',strtotime($model->due_date));?></strong>
           						</h5>
           					</div>
           				</div>
           				<?php } ?>
           				<?php if(!Yii::app()->user->YiiStudent){ ?>
           				<div class="col-md-6">
           					<div class="pull-right">
           						<div class="btn-group">
												<?php
													if($model->assignment_type == NULL){
														echo CHtml::link('<i class="fa fa-files-o"></i> Salin', array('copy','id'=>$model->id),array('class'=>'btn btn-pn-primary'));
													}
												?>
												<?php echo CHtml::link('<i class="fa fa-pencil"></i> Sunting', array('update','id'=>$model->id),array('class'=>'btn btn-primary'))?>
												<?php echo CHtml::link('<i class="fa fa-trash"></i> Hapus', array('hapusoffline','id'=>$model->id,'type'=>1),array('class'=>'btn btn-danger',"confirm"=>"Anda yakin akan menghapus tugas ini ?"))?>
												<?php echo CHtml::link('<i class="fa fa-pencil"></i> Download Nilai', array('DownloadNilai','id'=>$model->id),array('class'=>'btn btn-primary'))?>
           						</div>
           					</div>
           				</div>
           				<?php } ?>
           			</div>
           			<div class="clearfix"></div>
           			<br/>
								<?php if($type == NULL && empty($model->assignment_type)){ ?>
           			<div class="row">
           				<div class="col-md-12">
           					<div class="well">
								<?php echo $model->content ?>
								<?php
									if(!empty($model->file)){
										$file_tugas = json_decode($model->file,true);
										if(json_last_error() === JSON_ERROR_NONE){
											foreach ($file_tugas as $nama) {
												$ext = pathinfo($nama, PATHINFO_EXTENSION);
								?>
								<div class="pull-right">
									<?php echo CHtml::link('<i class="fa fa-download"></i> '.$nama, array('assignment/download', 'id'=>$model->id,'nama'=>$nama), array('class'=>'btn btn-primary'));?>
								</div>
								<?php
												if($ext == 'jpg' || $ext == 'png' || $ext == 'png'){
								?>
								<img src="<?php echo Yii::app()->baseUrl;?>/images/assignment/<?php echo $model->id;?>/<?php echo $nama;?>" class="thumbnails" height="250px" width="400px">
								<?php
												}elseif(in_array($ext, $video)){
								?>
								<div class="img-responsive">
									<center>
										<?php
											$this->widget('ext.jwplayer.Jwplayer',array(
											    'width'=>500,
											    'height'=>360,
											    'file'=>Yii::app()->baseUrl.'/images/assignment/'.$model->id.'/'.$nama,
											    'image'=>NULL,
											    'options'=>array(
											        'controlbar'=>'bottom'
											    )
											));
										?>
									</center>
								</div>
								<?php
												}elseif($ext == 'pdf'){
								?>
								<div style="height:600px;">
									<?php
										$this->widget('ext.pdfJs.QPdfJs',array(
											'url'=>Yii::app()->baseUrl."/images/assignment/".$model->id."/".$nama,
											))
									?>
								</div>
								<?php
												}
											}
										}else{
											$ext = pathinfo($model->file, PATHINFO_EXTENSION);
								?>
								<div class="pull-right">
									<?php echo CHtml::link('<i class="fa fa-download"></i> '.$model->file, array('assignment/download', 'id'=>$model->id), array('class'=>'btn btn-primary'));?>
								</div>
								<?php
											if($ext == 'jpg' || $ext == 'png' || $ext == 'png'){
								?>
								<img src="<?php echo Yii::app()->baseUrl;?>/images/assignment/<?php echo $model->id;?>/<?php echo $model->file;?>" class="thumbnails" height="250px" width="400px">
								<?php
											}elseif(in_array($ext, $video)){
								?>
								<div class="img-responsive">
									<center>
										<?php
											$this->widget('ext.jwplayer.Jwplayer',array(
											    'width'=>500,
											    'height'=>360,
											    'file'=>Yii::app()->baseUrl.'/images/assignment/'.$model->id.'/'.$model->file,
											    'options'=>array(
											        'controlbar'=>'bottom'
											    )
											));
										?>
									</center>
								</div>
								<?php
											}elseif($ext == 'pdf'){
								?>
								<div style="height:600px;">
									<?php
										$this->widget('ext.pdfJs.QPdfJs',array(
											'url'=>Yii::app()->baseUrl."/images/assignment/".$model->id."/".$model->file,
											))
									?>
								</div>
								<?php
											}
										}
									}
								?>
           					</div>
           				</div>
           			</div>
           			<div class="row">
           				<div class="col-md-12">
           					<div class="pull-right">
           						Dibuat Oleh : <?php echo ucwords($model->teacher->display_name);?>
           					</div>
           				</div>
           			</div>
           		</div>
           	</div>
          </div>
			<?php } ?>
		<?php
			if(empty($model->assignment_type)){
				if(Yii::app()->user->YiiStudent){
					if(empty($cekTugas)){
		?>
		<div class="col-md-8">
        	<div class="col-card">
	            <div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<h5>Upload Tugas</h5>
						</div>
						<div class="col-md-12">
						    <?php
						  //     $this->renderPartial('v2/_form_student_assignment', array(
								// 'model'=>$studentAssignment,'tugas_id'=>$model->id,'type'=>$type
						  //     ));
						    ?>
						    <?php $form=$this->beginWidget('CActiveForm', array(
								'id'=>'studentAssignment',
								'enableAjaxValidation'=>false,
								'htmlOptions' => array('enctype' => 'multipart/form-data'),
							)); ?>
							<div class="row">

							<?php //echo $form->hiddenField($model,'student_id',array('value'=>Yii::app()->user->id));?>
							<input type="hidden" name="StudentAssignment[student_id]" value="<?php echo Yii::app()->user->id;?>">
							<?php
								if(!empty($tugas_id)) { ?>
								<input type="hidden" name="StudentAssignment[assignment_id]" value="<?php echo $tugas_id;?>">
								<?php	//echo $form->hiddenField($model,'assignment_id',array('value'=>$tugas_id));
								} else { ?>
								<input type="hidden" name="StudentAssignment[assignment_id]" >
								<?php	//echo $form->hiddenField($model,'assignment_id');
								}

								// if(!empty($id_sa)){
								// 	echo $form->hiddenField($model,'id',array('value'=>$id_sa));
								// }

								if($type == NULL || $type == 2){
							?>
								<div class="col-md-12">
									<div class="form-group">
										<label for="StudentAssignment_content">Jawaban</label>
										<?php echo $form->textArea($studentAssignment,'content',array('class'=>'textarea textarea-input form-control input-pn input-lg','placeholder'=>'Isi Jawaban Disini','rows'=>4, 'cols'=>50))?>
									</div>
								</div>
							<?php
								}

								if(Yii::app()->user->YiiStudent){
							?>
								<div class="col-md-12">
									<div class="form-group">
										<label for="StudentAssignment_file">Lampiran Berkas</label>
										<?php echo $form->fileField($model,'file'); ?>
							            <p class="help-block">Maksimal Ukuran Lampiran Berkas 2 MB</p>
									</div>
								</div>
							<?php
								}else{
									if($type == 1){ 
							?>
								<div class="col-md-12">
									<div class="form-group">
										<label for="StudentAssignment_score">Nilai</label>
										<?php echo $form->numberField($model,'score',array('class'=>'form-control input-pn input-lg')); ?>
										<p class="help-block">Skala Nilai 1-100.</p>
									</div>
								</div>
							<?php
									}
								}

								if(Yii::app()->user->YiiStudent){	
							?>
								<div class="col-md-12">
									<div class="form-group">
										<div class="pull-right">
											<div class="btn-group">
												<?php echo CHtml::submitButton($model->isNewRecord ? 'Kumpulkan' : 'Kumpulkan',array('class'=>'btn btn-success','name'=>'upload', 'confirm'=>'Yakin Mengumpulkan Tugas ?')); ?>
												<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan',array('class'=>'btn btn-primary','name'=>'save')); ?>
											</div>
										</div>
									</div>
								</div>
							<?php
								}else{
							?>
								<div class="col-md-12">
									<div class="form-group">
										<div class="pull-right">
											<?php echo CHtml::submitButton($model->isNewRecord ? 'Simpan' : 'Simpan',array('class'=>'btn btn-success','name'=>'upload')); ?>
										</div>
									</div>
								</div>
							<?php
								}
							?>
							</div>
							<?php $this->endWidget(); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
					}elseif(!empty($cekTugas)){
						if($cekTugas->status == NULL){
		?>
		<div class="col-md-8">
        	<div class="col-card">
	            <div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<h5>Upload Tugas</h5>
						</div>
						<div class="col-md-12">
							<p>Tugas Anda Sudah Tersimpan. Upload Lagi Jika Ingin Memperbaiki</p><br/>
							<?php echo CHtml::link('<i class="fa fa-pencil"></i> Sunting Tugas', array('/studentAssignment/update','id'=>$cekTugas->id),array('class'=>'btn btn-primary'))?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
						}elseif($cekTugas->score == NULL){
		?>
		<div class="col-md-8">
        	<div class="col-card">
	            <div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<h5>Upload Tugas</h5>
						</div>
						<div class="col-md-12">
							<p>Anda Sudah Mengumpulkan Tugas Ini. Upload Lagi Jika Ingin Memperbaiki Sebelum Diberi Nilai</p>
							<?php echo CHtml::link('<i class="fa fa-pencil"></i> Sunting Tugas', array('/studentAssignment/update','id'=>$cekTugas->id),array('class'=>'btn btn-primary'))?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
						}else{
		?>
		<div class="col-md-8">
        	<div class="col-card">
	            <div class="panel-body">
					<div class="row">
						<div class="col-md-12">
							<h5>Upload Tugas</h5>
						</div>
						<div class="col-md-12">
							<p>Anda Sudah Mengumpulkan Tugas Ini Dan Sudah Diberi Nilai</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
						}
		?>
		<script>
			localStorage.clear();
			console.log(localStorage);
		</script>
		<?php
					}
		?>
	  </div>
	  <?php
	  						}else{
								$tsiswa = $studentTasks->getData();
	  ?>
	  <div class="clearfix"></div><br/>
        <div class="col-md-12">
          <div class="col-card">
            <div class="table-responsive">
              <table class="table table-hover">
                <thead>
                  <tr>
										<th>No</th>
										<th width="20%">Nama Siswa</th>
										<th>Dikumpulkan Tanggal</th>
										<th>Nilai</th>
										<th>Tepat Waktu</th>
										<th>Status</th>
										<th width="15%">Aksi</th>
                  </tr>
                </thead>
                <tbody>
				<?php
								if(!empty($tsiswa)){
									$no=1;
									foreach ($tsiswa as $tugas) {
				?>
					<tr>
						<td><?php echo $no ?></td>
						<td><?php echo $tugas->student->display_name ?></td>
						<td><?php echo date('d M Y H:i:s',strtotime($tugas->created_at)) ?></td>
						<td><?php echo $tugas->score;?></td>
						<td>
							<?php
										if(!empty($model->due_date > $tugas->created_at)){
											echo "Ya";
										} else {
											echo "Tidak";
										}
							?>
						</td>
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
						<td>
							<div class="btn-group">
								<?php echo CHtml::link('<i class="fa fa-eye"></i> Lihat',array('studentAssignment/view','id'=>$tugas->id),array('class'=>'btn btn-pn-primary btn-xs','title'=>'Lihat Tugas'));?>
								<?php echo CHtml::link('<i class="fa fa-graduation-cap"></i> Nilai',array('studentAssignment/view','id'=>$tugas->id,'type'=>1),array('class'=>'btn btn-primary btn-xs','title'=>'Beri Nilai'));?>
							</div>
						</td>
					<?php
										$no++;
									}
					?>
				</tbody>
			</table>
			<div class="text-center">
				<?php
				  $this->widget('CLinkPager', array(
				                'pages'=>$studentTasks->pagination,
				                ));
				?>
			</div>
		   </div>
          </div>
        </div>
	  <?php
							}
						}
					}else{
						if(!Yii::app()->user->YiiStudent){
							if(!empty($siswa)){
	  ?>
	  <form method="post" action="<?php echo $url_tugas;?>" onsubmit="return confirm('Yakin selesai menambahkan nilai ?');">
		  <div class="clearfix"></div><br/>
	        <div class="col-md-12">
	          <div class="col-card">
	          	<div class="row">
	          		<div class="col-md-12"
	          			<div class="pull-right">
							<input type="submit" value="Simpan" class="btn btn-pn-primary">
							<input type="hidden" name="assignment_id" value="<?php echo $model->id;?>">
							<input type="hidden" name="lesson_id" value="<?php echo $model->lesson_id;?>">
						</div>
					</div>
	          	</div>
	            <div class="table-responsive">
	              <table class="table table-hover">
	                <thead>
	                  <tr>
						<th>No</th>
						<th width="20%">Nama Siswa</th>
						<th>Nilai</th>
						<th></th>
	                  </tr>
	                </thead>
	                <tbody>
	                	<?php
	                			$no = 1;
	                			foreach ($siswa as $sw) {
	                	?>
						<tr>
							<td><?php echo $no ?></td>
							<td>
								<input type="hidden" name="student_id[]" value="<?php echo $sw->id;?>">
								<?php echo CHtml::encode($sw->display_name);?>
							</td>
							<td class="text-green">
								<?php
									$cekNilai = OfflineMark::model()->findByAttributes(array('student_id'=>$sw->id,'assignment_id'=>$model->id));
									if(!empty($cekNilai)){
										echo $cekNilai->score;
									}
								?>
							</td>
							<td>
								<input type="number" name="score[]" class="form-control">
							</td>
						</tr>
						<?php
									$no++;
								}
						?>
					</tbody>
				  </table>
				</div>
			  </div>
			</div>
	  </form>
	  <?php
  							}
  						}
  					}
	  ?>
	</div>
  </div>
</div>
