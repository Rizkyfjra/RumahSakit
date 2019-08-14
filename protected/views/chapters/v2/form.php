<?php
	if (!empty($model->id_lesson)) {
		$lesson = $model->mapel;
		$model->id_lesson = $lesson->name.' (ID:'.$model->id_lesson.')';
		
	}

	$mapel=array();
	if(!empty($lessons)){
		foreach ($lessons as $value) {
			if($value->moving_class == 1){
				$mapel[$value->id]=$value->name." (".$value->grade->name.")";
			}else{
				$mapel[$value->id]=$value->name." (".$value->class->name.")";
			}
			
		}
	}

	if(!empty($model->id_lesson)){
		$selected = $model->id_lesson;
	}else{
		$selected = 1;
	}

	if(!empty($model->chapter_type)){
		$type = $model->chapter_type;
	} else {
		$type = 3;
	}

	if(!empty($model->semester)){
		$sld = $model->semester;
	}else{
		$sld = 1;
	}

	$lid=NULL;
	if(isset($_GET['lks_id'])){
		$lid=$_GET['lks_id'];
	}

	$lesson_id = null;
	if(isset($_GET['lesson_id'])){
		$lesson_id = $_GET['lesson_id'];
		$selected = $lesson_id;
	}
	// echo "<pre>";
	// print_r($lessons);
	// echo "</pre>";
?>

<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_chapters_add', array(
      //   'model'=>$model
      // ));
    ?>
    <div class="col-md-12">
	  <div id="bc1" class="btn-group btn-breadcrumb">
		<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>Materi</div>',array('/chapters/index'), array('class'=>'btn btn-default')); ?>
	    <?php if(!$model->isNewRecord){ ?>
		<?php echo CHtml::link('<div>Sunting Materi</div>',array('#'), array('class'=>'btn btn-success')); ?>
		<?php }else{ ?>
		<?php echo CHtml::link('<div>Tambah Materi</div>',array('#'), array('class'=>'btn btn-success')); ?>	
		<?php } ?>	
	  </div>
	</div>

    <div class="col-lg-12">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'fileupload',
			'enableAjaxValidation'=>false,
			'htmlOptions' => array('enctype' => 'multipart/form-data'),	
		)); ?>
      <?php if(!$model->isNewRecord){ ?>
      <h3>Sunting Materi</h3>
      <?php }else{ ?>
      <h3>Membuat Materi Baru</h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-12">
          <div class="col-card">
            <h4>Informasi Umum</h4>
            <div class="row">
            	<div class="col-md-12">
	                <div class="form-group">
	                 	<label for="Chapters_title">Nama Materi</label>
						<?php echo $form->textField($model,'title',array('class'=>'form-control input-pn input-lg','placeholder'=>'Isi nama materi disini (contoh: Materi Trigonometri) …','required'=>'required')); ?>
					</div>
            	</div>
            </div>
            <div class="row">
            	<div class="col-md-6">
                	<div class="form-group">
                  		<label for="Chapters_id_lesson">Nama Mata Pelajaran</label>
                		<?php
                			if(empty($lesson_id)){
                    			echo $form->dropDownList($model,'id_lesson',$mapel,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg','data-live-search'=>'true','options'=>array($selected=>array('selected'=>true))));
                			}else{
                    			echo $form->dropDownList($model,'id_lesson',$mapel,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg','data-live-search'=>'true','disabled'=>'disabled','readonly'=>'readonly','options'=>array($selected=>array('selected'=>true))));
                    		}
                 		 ?>
                 	</div>
                </div>
            	<div class="col-md-6">
            		<div class="form-group">
	                	<label for="Chapters_chapter_type">Jenis Materi</label>
	                	<div class="btn-group btn-group-justified" data-toggle="buttons">
		                    <label class="btn btn-info btn-lg <?php echo '',$type == 3 ? "active" : "" ?>">
		                      <input type="radio" name="Chapters[chapter_type]" id="Chapters_chapter_type_3" autocomplete="off" value="3" <?php echo '',$type == 3 ? "checked" : "" ?>> Dokumen
		                    </label>
		                    <label class="btn btn-info btn-lg <?php echo '',$type == 2 ? "active" : "" ?>">
		                      <input type="radio" name="Chapters[chapter_type]" id="Chapters_chapter_type_2" autocomplete="off" value="2" <?php echo '',$type == 2 ? "checked" : "" ?>> Gambar
		                    </label>
		                    <label class="btn btn-info btn-lg <?php echo '',$type == 1 ? "active" : "" ?>">
		                      <input type="radio" name="Chapters[chapter_type]" id="Chapters_chapter_type_1" autocomplete="off" value="1" <?php echo '',$type == 1 ? "checked" : "" ?>> Video
		                    </label>
		                </div>
            		</div>
            	</div>
            </div>
            <br/>
            <h4>Materi</h4>
            <div class="row">
            	<div class="col-md-12">
            		<div class="form-group">
	                 	<label for="Chapters_content">Konten Materi</label>
						<?php echo $form->textArea($model,'content',array('class'=>'textarea textarea-input form-control input-pn input-lg','rows'=>4, 'cols'=>30)); ?>
	                </div>
	            </div>
            </div>
            <div class="row">
            	<div class="col-md-12">
            		<div class="form-group">
	                 	<label for="Chapters_file">Lampiran Berkas</label>
	                 	<?php echo $form->fileField($model2,'file',array('class'=>'form-control input-pn input-lg','placeholder'=>'Klik tombol Browse atau Choose untuk melampirkan berkas materi')); ?>
						<p class="help-block">Maksimal Ukuran Lampiran Berkas 2 MB</p>
            		</div>
            	</div>
            </div>
            <div class="row">
            	<input type="hidden" name="lks_id" value="<?php echo $lid?>">
        		<div class="col-md-12">
        			<?php if(!$model->isNewRecord){ ?>
        			<button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Simpan Perubahan</button>
        			<?php }else{ ?>
        			<button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Buat Materi</button>
        			<?php } ?>
          		</div>
            </div>
          </div>
        </div>
    </div>
  </div>
  <?php $this->endWidget(); ?>
</div>
