<?php
  if(Yii::app()->user->YiiTeacher){
    $mapel = Lesson::model()->findAll(array('condition'=>'trash is null and user_id = '.Yii::app()->user->id." AND semester = ".$semester." AND year = ".$year));
  }else{
    $mapel = Lesson::model()->findAll(array('condition'=>'trash is null and semester = '.$semester." AND year = ".$year));
  }

  $lesson = array();
  foreach ($mapel as $value) {
    if($value->moving_class == 1){
      $lesson[$value->id] = $value->name." (".$value->grade->name.")";
    }else{
      $lesson[$value->id] = $value->name." (".$value->class->name.")";
    }
    
  }

  if(!empty($model->lesson_id)){
    $selected = $model->lesson_id;
  }else{
    $selected = 1;
  }

  if(!empty($model->quiz_type)){
    $tipe = $model->quiz_type;
  }else{
    $tipe = 0;
  }

  if(!empty($model->random)){
    $acak = $model->random;
  }else{
    $acak = 1;
  }

  if(!empty($model->random_opt)){
    $acak_opsi = $model->random_opt;
  }else{
    $acak_opsi = 1;
  }

  if(!empty($model->show_nilai)){
    $show = $model->show_nilai;
  }else{
    $show = 1;
  }

  if(!empty($model->add_to_summary)){
    $rekap = $model->add_to_summary;
  }else{
    $rekap = NULL;
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
      // $this->renderPartial('v2/_breadcrumb_exam_add', array(
      //   'model'=>$model
      // ));
    ?>

    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
      <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Ujian</div>',array('/quiz/index'), array('class'=>'btn btn-default')); ?>
        <?php if(!$model->isNewRecord){ ?>
      <?php echo CHtml::link('<div>Sunting Ujian</div>',array('#'), array('class'=>'btn btn-success')); ?>
      <?php }else{ ?>
      <?php echo CHtml::link('<div>Tambah Ujian</div>',array('#'), array('class'=>'btn btn-success')); ?> 
      <?php } ?>  
      </div>
    </div>

    <div class="col-lg-12">
      <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'quiz-form',
        'enableAjaxValidation'=>false,
      )); ?>
      <?php if(!$model->isNewRecord){ ?>
      <h3>Sunting Ujian</h3>
      <?php }else{ ?>
      <h3>Membuat Ujian Baru</h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-12">
          <div class="col-card">
            <h4>Informasi Umum</h4>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="Quiz_title">Nama Ujian</label>
                  <?php echo $form->textField($model,'title',array('class'=>'form-control input-pn input-lg','placeholder'=>'Isi nama ujian disini (contoh: Ujian Bahasa Indonesia) …','required'=>'required')); ?>
                </div>
              </div>
              <!-- <div class="col-md-6">
                <div class="form-group">
                  <label for="namaMateri">Materi Yang Diujikan</label>
                  <input type="text" class="form-control input-pn input-lg" id="namaMateri" placeholder="Isi materi yang diujikan (contoh: KD 3.1 Membaca) …">
                </div>
              </div> -->
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="Quiz_lesson_id">Nama Mata Pelajaran</label>
                  <?php
                    if(empty($lsn)){
                      echo $form->dropDownList($model,'lesson_id',$lesson,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg','data-live-search'=>'true','options'=>array($selected=>array('selected'=>true))));
                    }else{
                      echo $form->dropDownList($model,'lesson_id',$lesson,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg','data-live-search'=>'true','disabled'=>'disabled','readonly'=>'readonly','options'=>array($selected=>array('selected'=>true))));
                    }
                  ?>
                </div>
              </div>
              <!-- <div class="col-md-3">
                <div class="form-group">
                  <label for="semester">Semester</label>
                  <div class="btn-group btn-group-justified" data-toggle="buttons">
                    <label class="btn btn-info btn-lg active">
                      <input type="radio" name="semester" id="option1" autocomplete="off" checked> 1
                    </label>
                    <label class="btn btn-info btn-lg ">
                      <input type="radio" name="semester" id="option2" autocomplete="off"> 2
                    </label>
                  </div>
                </div>
              </div> -->
              <?php if(!$model->isNewRecord){ ?>
              <div class="col-md-2">
              <?php }else{ ?>
              <div class="col-md-3">
              <?php } ?>
                <div class="form-group">
                  <label for="Quiz_end_time">Lama Ujian (Menit)</label>
                  <?php echo $form->textField($model,'end_time',array('class'=>'form-control input-pn input-lg','placeholder'=>'Contoh: 120','required'=>'required')); ?>                  
                </div>
              </div>
              <?php if(!$model->isNewRecord){ ?>
              <div class="col-md-2">
              <?php }else{ ?>
              <div class="col-md-3">
              <?php } ?>
                <div class="form-group">
                  <label for="Quiz_repeat_quiz">Banyak Percobaan</label>
                  <?php echo $form->textField($model,'repeat_quiz',array('class'=>'form-control input-pn input-lg','placeholder'=>'Contoh: 3')); ?>
                </div>
              </div>
              <?php if(!$model->isNewRecord){ ?>
              <div class="col-md-2">
                <div class="form-group">
                  <label for="Quiz_passcode">Passcode</label>
                  <?php echo $form->textField($model,'passcode',array('class'=>'form-control input-pn input-lg','placeholder'=>'Passcode')); ?>
                </div>
              </div>
              <?php } ?>
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="Quiz_quiz_type">Jenis Ujian</label>
                  <div class="btn-group btn-group-justified" data-toggle="buttons">
                    <label class="btn btn-info btn-lg <?php echo '',$tipe == 0 ? "active" : "" ?>">
                      <input type="radio" name="Quiz[quiz_type]" id="Quiz_type_1" autocomplete="off" value="0" <?php echo '',$tipe == 0 ? "checked" : "" ?>> UH
                    </label>
                    <label class="btn btn-info btn-lg <?php echo '',$tipe == 1 ? "active" : "" ?>">
                      <input type="radio" name="Quiz[quiz_type]" id="Quiz_type_2" autocomplete="off" value="1" <?php echo '',$tipe == 1 ? "checked" : "" ?>> UTS
                    </label>
                    <label class="btn btn-info btn-lg <?php echo '',$tipe == 2 ? "active" : "" ?>">
                      <input type="radio" name="Quiz[quiz_type]" id="Quiz_type_3" autocomplete="off" value="2" <?php echo '',$tipe == 2 ? "checked" : "" ?>> UAS
                    </label>
                  </div>
                </div>
              </div>            
              <div class="col-md-3">
                <div class="form-group">
                  <label for="Quiz_random">Acak Soal</label>
                  <div class="btn-group btn-group-justified" data-toggle="buttons">
                    <label class="btn btn-info btn-lg <?php echo '',$acak == 1 ? "active" : "" ?>">
                      <input type="radio" name="Quiz[random]" id="Quiz_random_1" autocomplete="off" value="1" <?php echo '',$acak == 1 ? "checked" : "" ?>> Ya
                    </label>
                    <label class="btn btn-info btn-lg <?php echo '',$acak == NULL ? "active" : "" ?>">
                      <input type="radio" name="Quiz[random]" id="Quiz_random_2" autocomplete="off" value="" <?php echo '',$acak == NULL ? "checked" : "" ?>> Tidak
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-md-3">
                <div class="form-group">
                  <label for="Quiz_random_opt">Acak Jawaban</label>
                  <div class="btn-group btn-group-justified" data-toggle="buttons">
                    <label class="btn btn-info btn-lg <?php echo '',$acak_opsi == 1 ? "active" : "" ?>">
                      <input type="radio" name="Quiz[random_opt]" id="Quiz_random_opt_1" autocomplete="off" value="1" <?php echo '',$acak_opsi == 1 ? "checked" : "" ?>> Ya
                    </label>
                    <label class="btn btn-info btn-lg <?php echo '',$acak_opsi == NULL ? "active" : "" ?>">
                      <input type="radio" name="Quiz[random_opt]" id="Quiz_random_opt_2" autocomplete="off" value="" <?php echo '',$acak_opsi == NULL ? "checked" : "" ?>> Tidak
                    </label>
                  </div>
                </div>
              </div>
              <!-- <div class="col-md-6">
                <div class="form-group">
                  <label for="Quiz_tipe_soal">Tipe Soal</label>
                  <div class="btn-group btn-group-justified" data-toggle="buttons">
                    <label class="btn btn-info btn-lg active">
                      <input type="radio" name="Quiz_tipe_soal" id="Quiz_tipe_soal_1" autocomplete="off" checked> PG
                    </label>
                    <label class="btn btn-info btn-lg disabled">
                      <input type="radio" name="Quiz_tipe_soal" id="Quiz_tipe_soal_2" autocomplete="off" disabled="disabled"> Essay
                    </label>
                    <label class="btn btn-info btn-lg disabled">
                      <input type="radio" name="Quiz_tipe_soal" id="Quiz_tipe_soal_3" autocomplete="off" disabled="disabled"> Uraian
                    </label>
                  </div>
                </div>
              </div> -->
            </div>
            <div class="row">
              <div class="col-md-6">
                <div class="form-group">
                  <label for="Quiz_show_nilai">Tampil Nilai Setelah Ujian</label>
                  <div class="btn-group btn-group-justified" data-toggle="buttons">
                    <label class="btn btn-info btn-lg <?php echo '',$show == 1 ? "active" : "" ?>">
                      <input type="radio" name="Quiz[show_nilai]" id="Quiz_show_nilai_1" autocomplete="off" value="1" <?php echo '',$show == 1 ? "checked" : "" ?>> Ya
                    </label>
                    <label class="btn btn-info btn-lg <?php echo '',$show == NULL ? "active" : "" ?>">
                      <input type="radio" name="Quiz[show_nilai]" id="Quiz_show_nilai_2" autocomplete="off" value="" <?php echo '',$show == NULL ? "checked" : "" ?>> Tidak
                    </label>
                  </div>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="Quiz_add_to_summary">Masuk Rekap Nilai</label>
                  <div class="btn-group btn-group-justified" data-toggle="buttons">
                    <label class="btn btn-info btn-lg <?php echo '',$rekap == NULL ? "active" : "" ?>">
                      <input type="radio" name="Quiz[add_to_summary]" id="Quiz_add_to_summary_1" autocomplete="off" value="" <?php echo '',$rekap == NULL ? "checked" : "" ?>> Ya
                    </label>
                    <label class="btn btn-info btn-lg <?php echo '',$rekap == 1 ? "active" : "" ?>">
                      <input type="radio" name="Quiz[add_to_summary]" id="Quiz_add_to_summary_2" autocomplete="off" value="1" <?php echo '',$rekap == 1 ? "checked" : "" ?>> Tidak
                    </label>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <input type="hidden" name="lks_id" value="<?php echo $lid?>">
              <div class="col-md-12">
                <?php if(!$model->isNewRecord){ ?>
                <button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Simpan Perubahan</button>
                <?php }else{ ?>
                <button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Buat Ujian</button>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
  <?php $this->endWidget(); ?>
</div>
