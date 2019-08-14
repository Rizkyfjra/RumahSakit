<?php
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

    if(!empty($model->lesson_id)){
        $selected = $model->lesson_id;
    }else{
        $selected = 1;
    } 

    if(!empty($model->file)){
        $model->file = $model->file;
    }

    if(!empty($model->add_to_summary)){
        $slc = $model->add_to_summary;
    }else{
        $slc = NULL;
    }

    $tipe = NULL;
    if(isset($_GET['type'])){
        $tipe = $_GET['type'];
    }

    $lid=NULL;
    if(isset($_GET['lks_id'])){
        $lid=$_GET['lks_id'];
    }


    $lsn=NULL;
    if(isset($_GET['lesson_id'])){
        $lsn = $_GET['lesson_id'];
        $selected = $lsn;
    }
?>


<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_task_add', array(
      //   'model'=>$model
      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
        <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>Tugas</div>',array('/assignment/index'), array('class'=>'btn btn-default')); ?>
        <?php if(!$model->isNewRecord){ ?>
        <?php echo CHtml::link('<div>Sunting Tugas</div>',array('#'), array('class'=>'btn btn-success')); ?>
        <?php }else{ ?>
        <?php echo CHtml::link('<div>Tambah Tugas</div>',array('#'), array('class'=>'btn btn-success')); ?> 
        <?php } ?>  
      </div>
    </div>

    <div class="col-lg-12">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'fileupload',
        	'htmlOptions' => array('enctype' => 'multipart/form-data'),
        	'enableAjaxValidation'=>false,
        )); ?>
      <?php if(!$model->isNewRecord){ ?>
      <h3>Sunting Tugas</h3>
      <?php }else{ ?>
      <h3>Membuat Tugas Baru</h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-12">
          <div class="col-card">
            <h4>Informasi Umum</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="Assignment_title">Nama Tugas</label>
                        <?php echo $form->textField($model,'title',array('class'=>'form-control input-pn input-lg','placeholder'=>'Isi nama tugas disini (contoh: Tugas Membuat Puisi) …','required'=>'required')); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="Assignment_lesson_id">Nama Mata Pelajaran</label>
                        <?php
                            if(empty($lsn)){
                                echo $form->dropDownList($model,'lesson_id',$mapel,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg','data-live-search'=>'true','options'=>array($selected=>array('selected'=>true))));
                            }else{
                                echo $form->dropDownList($model,'lesson_id',$mapel,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg','data-live-search'=>'true','disabled'=>'disabled','readonly'=>'readonly','options'=>array($selected=>array('selected'=>true))));
                            }
                         ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Assignment_add_to_summary">Tambah Ke Rekap Nilai</label>
                        <div class="btn-group btn-group-justified" data-toggle="buttons">
                            <label class="btn btn-info btn-lg <?php echo '',$slc == NULL ? "active" : "" ?> <?php echo '',empty($tipe) ? "" : "disabled readonly" ?>">
                              <input type="radio" name="Assignment[add_to_summary]" id="Assignment_add_to_sumary_1" autocomplete="off" value="" <?php echo '',$slc == NULL ? "checked" : "" ?> <?php echo '',empty($tipe) ? "" : "disabled readonly" ?>> Ya
                            </label>
                            <label class="btn btn-info btn-lg <?php echo '',$slc == 1 ? "active" : "" ?> <?php echo '',empty($tipe) ? "" : "disabled readonly" ?>">
                              <input type="radio" name="Assignment[add_to_summary]" id="Assignment_add_to_sumary_2" autocomplete="off" value="1" <?php echo '',$slc == 1 ? "checked" : "" ?> <?php echo '',empty($tipe) ? "" : "disabled readonly" ?>> Tidak
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <h4>Tugas</h4>
            <?php if(empty($tipe)){ ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="Assignment_due_date">Batas Akhir Pengumpulan</label>
                        <?php
                            $this->widget('ext.YiiDateTimePicker.jqueryDateTime', array(
                                'model' => $model,
                                'attribute' => 'due_date',
                                'options' => array(),
                                'htmlOptions' => array(
                                'class'=>'form-control input-pn input-lg'),
                            ));
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="Assignment_content">Konten Tugas</label>
                        <?php echo $form->textArea($model,'content',array('value'=>$model->content,'rows'=>6, 'cols'=>50, 'class'=>'textarea textarea-input form-control input-pn input-lg')); ?>
                    </div>
                </div>
            </div>
            <?php if(empty($tipe)){ ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="Assignment_file">Lampiran Berkas</label>
                        <input type="file" name="files[]" class="form-control input-pn input-lg" placeholder="Klik tombol Browse atau Choose untuk melampirkan berkas materi" multiple>
                        <p class="help-block">Maksimal Ukuran Lampiran Berkas 2 MB</p>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="row">
                <input type="hidden" name="lks_id" value="<?php echo $lid?>">
                <div class="col-md-12">
                    <?php if(!$model->isNewRecord){ ?>
                    <button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Simpan Perubahan</button>
                    <?php }else{ ?>
                    <button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Buat Tugas</button>
                    <?php } ?>
                </div>
            </div>
          </div>
        </div>
    </div>
  </div>
  <?php $this->endWidget(); ?>
</div>
