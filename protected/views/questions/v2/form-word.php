<?php
  if(Yii::app()->user->YiiTeacher){
    $mapel = Lesson::model()->findAll(array('condition'=>'user_id = '.Yii::app()->user->id));
  }else{
    $mapel = Lesson::model()->findAll();
  }

  $lesson = array();
  foreach ($mapel as $value) {
    if($value->moving_class == 1){
      $lesson[$value->id]=$value->name." (".$value->grade->name.")";
    }else{
      $lesson[$value->id]=$value->name." (".$value->class->name.")";
    }
  }

  $tipe=array(NULL=>'Pilihan Ganda','2'=>'Essay');

  $qid=NULL;
  if(isset($_GET['quiz_id'])){
    $qid=$_GET['quiz_id'];
  }

  if(!empty($model->tipe)){
    $selected=$model->tipe;
  }else{
    $selected=NULL;
  }

  if(!empty($model->lesson_id)){
    $lsn=$model->lesson_id;
  }else{
    $lsn=NULL;
  }
?>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/ckeditor/ckeditor.js"></script>
<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_soal_add', array(
      //   'model'=>$model
      // ));
    ?>
    <?php
        if(isset($_GET['quiz_id'])){
          $modelQuiz = Quiz::model()->findByPk($_GET['quiz_id']);

          if($modelQuiz->lesson->moving_class == 1){
            $kelasnya = $modelQuiz->lesson->name;
            $idkelasnya = $modelQuiz->lesson->id;
            $path_nya = 'lesson/'.$idkelasnya;
          }else{
            $kelasnya = $modelQuiz->lesson->name;
            $idkelasnya = $modelQuiz->lesson->id;
            $path_nya = 'lesson/'.$idkelasnya;
          }
        }
      ?>
      <div class="col-md-12">
        <div id="bc1" class="btn-group btn-breadcrumb">
          <?php
          if(isset($_GET['quiz_id'])){
          ?>
        <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>Ujian</div>',array('/quiz/index'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>'.CHtml::encode($kelasnya).'</div>',array($path_nya,'type'=>'ulangan'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>'.CHtml::encode($modelQuiz->title).'</div>',array('/quiz/view', 'id'=>$modelQuiz->id), array('class'=>'btn btn-default')); ?>
        <?php
            if(!$model->isNewRecord){
              echo CHtml::link('<div>Sunting Soal</div>',array('#'), array('class'=>'btn btn-success'));
            }else{
              echo CHtml::link('<div>Tambah Soal</div>',array('#'), array('class'=>'btn btn-success'));
            }
        ?>
          <?php
            }else{
          ?>
        <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>Bank Soal</div>',array('/questions/index'), array('class'=>'btn btn-default')); ?>
        <?php
            if(!$model->isNewRecord){
              echo CHtml::link('<div>Sunting Soal</div>',array('#'), array('class'=>'btn btn-success'));
            }else{
              echo CHtml::link('<div>Tambah Soal</div>',array('#'), array('class'=>'btn btn-success'));
            }
        ?>
          <?php
            }
          ?>
        </div>
      </div>

    <div class="col-lg-12">
      <?php
        $form=$this->beginWidget('CActiveForm', array(
          'id'=>'questions-form',
          'htmlOptions' => array('enctype' => 'multipart/form-data'),
          'enableAjaxValidation'=>false,
        ));
      ?>
      <?php if($qid != NULL){?>
          <?php echo $form->hiddenField($model,'quiz_id',array('class'=>'form-control','value'=>$qid)); ?>
      <?php } ?>
      <?php if(!$model->isNewRecord){ ?>
      <h2>Sunting Soal</h2>
      <?php }else{ ?>
      <h2>Buat Soal Baru</h2>
      <?php } ?>
      <div class="row">
        <div id="editor-container" class="col-md-8">
          <h4>Tambah Soal Wizard <small><i>fitur baru!</i></small>
        <div id="act-step-2" class="pull-right hide">
          <a href="javascript:window.location.href=window.location.href"><button type="button" class="btn btn-warning btn-pn-round next-step"><i class="fa fa-chevron-left"></i> Kembali</button></a>
          <button id="submit-add-soal" type="submit" class="btn btn-pn-primary btn-pn-round next-step"><i class="fa fa-save"></i> Simpan Semua Soal</button>
        </div>
          </h4>
          <div class="col-card">
           <!--  <div class="row">
              <div class="col-xs-12">
                  <ul class="nav nav-pills nav-justified setup-panel">
                      <li id="btn-step-1" class="active"><a href="javascript:window.location.href=window.location.href">
                          <h4 class="list-group-item-heading">Tahap 1</h4>
                          <p class="list-group-item-text">Import dari Word </p>
                      </a></li>
                      <li id="btn-step-2" class="disabled"><a href="#step-2">
                          <h4 class="list-group-item-heading">Tahap 2</h4>
                          <p class="list-group-item-text">Preview dan Sunting Soal</p>
                      </a></li>
                  </ul>
              </div>
            </div>
            <hr> -->
            <div class="row">
              <div id="step-1" class="col-md-12">
                <div class="text-center hide">
                  <img src="<?php echo Yii::app()->theme->baseUrl ?>/images/import-icon.png" alt="" class="pull-left" style="width: 110px;">
                  <h3>Tambahkan Soal dari dokumen <strong>Microsoft Word</strong> dibawah ini!</h3>
                  <p>
                    Anda dapat menyalin dan menempelkan (<i>copy & paste</i>) soal yang ingin anda masukkan di kotak editor dibawah ini.
                    <br>
                    Atau anda dapat mengetikan langsung soal dan jawaban dibawah ini.
                  </p>
                  <p>
                    <a href="#" data-toggle="modal" data-target="#modalPanduanImportWord" class="btn btn-pn-primary btn-sm">
                      <i class="fa fa-question-circle"></i> Panduan Menggunakan <strong>Import dari Word</strong>
                    </a>
                  </p>
                </div>
                <div class="clearfix"></div>
                <div class="rich-textarea-container">
                  <!-- <textarea name="name_extword" id="textword" cols="30" rows="10" class="rich-textarea"></textarea> -->
                  <textarea name="name_extword" id="textword1" cols="30" rows="10" class="text-naon"></textarea>
                </div>
                <h4><i class="fa fa-info-circle"></i> Jika tidak bisa menggunaka control V saat paste Gunakan tombol <strong> paste form word</strong>yang ada di baris pertama editor</h4>
              </div>

           <div id="step-2" class="col-md-12 hide">
                <div style="overflow-y: scroll; overflow-x: hidden; max-height: 450px;" class="col-md-1">
                  <ul id="the-nav" class="nav nav-pills"></ul>
                </div>
          </div>
          <!--Mulai Butir soal -->
              <!-- generete oleh ajax request -->
          <!--Akhir Butir soal -->

            </div>
          </div>
         </div>
       <div id="info-right" class="col-md-4">
          <h4>Informasi Soal</h4>
          <div class="col-card">
            <div class="col-dropdown">
              <div class="row">
                <div class="col-md-12">
                  <label for="Questions_title">Judul / Kompetensi Dasar</label>
                  <?php if(isset($_GET['quiz_id'])){
                     $modelQuiz = Quiz::model()->findByPk($_GET['quiz_id']);

                     echo $form->textField($model,'title',array('class'=>'form-control input-pn input-lg','placeholder'=>'Judul / Kompetensi Dasar','required'=>'required','value'=>$modelQuiz->title));

                  } else {
                      echo $form->textField($model,'title',array('class'=>'form-control input-pn input-lg','placeholder'=>'Judul / Kompetensi Dasar','required'=>'required'));
                  }

                  ?>
                  <!-- <select name="kompetensiDasar" class="selectpicker form-control" data-style="btn-default input-lg" data-live-search="true" title="Kompetensi Dasar 1">
                    <option>Isi Kompetensi Dasar 1</option>
                    <option>Isi Kompetensi Dasar 2</option>
                    <option>Isi Kompetensi Dasar 3</option>
                    <option>Isi Kompetensi Dasar 4</option>
                  </select> -->
                </div>
                <br class="visible-xs">
                <div class="col-md-12">
                  <label for="Questions_type">Jenis Soal</label>
                  <?php echo $form->dropdownList($model,'type',$tipe,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg', 'options'=>array($selected=>array('selected'=>true))))?>
                </div>
              </div>
            </div>
          </div>
          <h4>Aksi Soal</h4>
          <div class="col-card">
            <div class="row">
              <div class="col-md-12">
                <!-- <button id="submitword" type="button" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Preview dan Sunting Soal <i class="fa fa-arrow-right"></i></button> -->
                <button  id="submitword" type="button" class="btn btn-warning btn-lg btn-block next-step" style="margin-bottom: 5px"><i class="fa fa-eye"> Pratinjau</i></button>
              </div>
              <!-- <div class="col-md-12">
                <button type="button" class="btn btn-pn-primary btn-lg btn-block next-step"><i class="fa fa-save"></i> Simpan</button>
              </div>
            </div> -->
          </div>
        </div>
        <div  class="col-md-12 hide">
          <div class="col-card">
            <div class="row">
             
              <div class="col-md-6">
                <!-- <button type="submit" class="btn btn-primary btn-lg btn-pn-round btn-block next-step"><i class="fa fa-save"></i> Simpan</button> -->
              </div>
              <div class="col-md-6">
                <button id="submit-add-soal" type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step"><i class="fa fa-check-circle"></i> Selesai</button>
              </div>
              
            </div>
          </div>
        </div>

       
      
      </div>
   </div>
 </div>
      <?php $this->endWidget(); ?>
</div>
  </div>
</div>

 <!-- Modal -->
<div class="modal fade" id="modalPanduanImportWord" tabindex="-1" role="dialog" aria-labelledby="filterPanduanImportWord">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="forgotPasswordLabel">Panduan Tambah Soal Wizard</h4>
      </div>
      <div class="modal-body">
        <h4><i class="fa fa-info-circle"></i> Panduan Menggunakan <strong>Tambah Soal Wizard</strong>:</h4>
        <p>
          Bentuk format teks yang dapat diterima:
          <ul>
            <li>
              Penomoran soal tidak menggunakan numbering
            </li>
            <li>
              Abjad pilihan jawaban tidak menggunakan bullet/numbering
            </li>
            <li>
              Abjad pilihan menggunakan huruf kapital
            </li>
            <li>
              Soal dan pilihan jawaban dipisah menggunakan kalimat <strong>"KUNCI : [Abjad kunci jawaban]"</strong>
            </li>
          </ul>
        </p>
        <h4><i class="fa fa-check-circle"></i> Contoh format soal yang benar:</h4>
        <p>
          <img src="<?php echo Yii::app()->theme->baseUrl ?>/images/contoh-import-word.png" alt="" class="thumbnail" style="max-width: 100%;">
        </p>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
  $(document).ready(function(){

      <?php  if(!$model->isNewRecord){ ?>

         $('#step-2').removeClass('hide');
         $('#step-1').addClass('hide');

      <?php } ?>  

      $("#submit-add-soal").click(function(){
             $('.modal-loading').addClass("show");
      });

     $("#submitword").click(function(){
        //console.log(tinymce.get('textword').getContent());
        $('.modal-loading').addClass("show");
        $.post("ajaxparsingtabel",
              {
                  // wordhtml: tinymce.get('textword').getContent()
                  wordhtml: CKEDITOR.instances.textword1.getData()
              },
              function(result){
                 var obj = $.parseJSON(result);
                 var isoal = 1;
                 var jsoal = 0;
                 // console.log(result);
                 console.log(obj);
                 // console.log(obj['jawaban']);
                 // console.log(obj['jawabanA']);
                 // console.log(obj['jawabanB']);
                 // console.log(obj['jawabanC']);
                 // console.log(obj['jawabanD']);
                 // console.log(obj['jawabanE']);

                 obj.forEach(function(entry) {
                       $('#the-nav').append('<li class="list-soal"><a href="#step'+isoal+'" id="coba'+isoal+'" data-local="768" class="btn btn-soal" data-toggle="tab" data-step="'+isoal+'">'+isoal+'</a></li>');
                       // $('#step-2').append('<div id="'+isoal+'" class="soal-container hide"> <div class="clearfix"></div><div class="rich-textarea-container"> <h2 class="text-center">Soal '+isoal+'</h2> <textarea id="soalnya" name="Questions[soalnya]['+jsoal+'][text]" cols="60" rows="3" class="rich-textarea">'+entry.soal+'</textarea> </div><hr> <div class="rich-textarea-container"> <h2 class="text-center">Jawaban 1</h2> <p class="text-center"> <label for=""> <input id="rad-opt-A-'+jsoal+'" type="radio" name="answer['+jsoal+']" value="1"> Jadikan Kunci Jawaban </label> </p><textarea id="pil0" name="pil['+jsoal+'][0]" cols="30" rows="3" class="rich-textarea">'+entry.jawabanA+'</textarea> </div><hr> <div class="rich-textarea-container"> <h2 class="text-center">Jawaban 2</h2> <p class="text-center"> <label for=""> <input id="rad-opt-B-'+jsoal+'" type="radio" name="answer['+jsoal+']" value="2"> Jadikan Kunci Jawaban </label> </p><textarea id="pil1" name="pil['+jsoal+'][1]" cols="30" rows="3" class="rich-textarea">'+entry.jawabanB+'</textarea> </div><hr> <div class="rich-textarea-container"> <h2 class="text-center">Jawaban 3</h2> <p class="text-center"> <label for=""> <input id="rad-opt-C-'+jsoal+'" type="radio" name="answer['+jsoal+']" value="3"> Jadikan Kunci Jawaban </label> </p><textarea id="pil2" name="pil['+jsoal+'][2]" cols="30" rows="3" class="rich-textarea">'+entry.jawabanC+'</textarea> </div><hr> <div class="rich-textarea-container"> <h2 class="text-center">Jawaban 4</h2> <p class="text-center"> <label for=""> <input id="rad-opt-D-'+jsoal+'" type="radio" name="answer['+jsoal+']" value="4" > Jadikan Kunci Jawaban </label> </p><textarea id="pil3" name="pil['+jsoal+'][3]" cols="30" rows="3" class="rich-textarea">'+entry.jawabanD+'</textarea> </div><hr> <div class="rich-textarea-container"> <h2 class="text-center">Jawaban 5</h2> <p class="text-center"> <label for=""> <input id="rad-opt-E-'+jsoal+'" type="radio" name="answer['+jsoal+']" value="5"> Jadikan Kunci Jawaban </label> </p><textarea id="pil4" name="pil['+jsoal+'][4]" cols="30" rows="3" class="rich-textarea">'+entry.jawabanE+'</textarea> </div> </div>');
                       $('#step-2').append('<div class="col-md-11"> <div id="'+isoal+'" class="soal-container hide"> <div class="clearfix"></div><div class="rich-textarea-container"> <h2 class="text-center">Soal '+isoal+'</h2> <div> <p class="pull-left"> '+entry.soal+' </p></div><div class="pull-right"> <a class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#soal-'+isoal+'" > <i class="fa fa-pencil"></i> </a> </div><div class="clearfix"></div><div class="rich-textarea-container collapse" id="soal-'+isoal+'"> <textarea id="soalnya" name="Questions[soalnya]['+jsoal+'][text]" cols="60" rows="3" class="rich-textarea">'+entry.soal+'</textarea> <br><button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#soal-'+isoal+'"><i class="fa fa-save"></i> Perbarui</button> </div></div><hr> <div class="rich-textarea-container"> <h2 class="text-center">Jawaban</h2> <div class="exam-answer"> <div id="opsi-A-'+isoal+'" class="alert alert-danger" role="alert"> <p class="pull-left"> '+entry.jawabanA+' </p><div class="pull-right"> <a class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#collapseImportSoalWord'+isoal+'" > <i class="fa fa-pencil"></i> </a> </div><div class="clearfix"></div><div class="rich-textarea-container collapse" id="collapseImportSoalWord'+isoal+'"> <p class="text-center"> <label for=""> <input id="rad-opt-A-'+jsoal+'" type="radio" name="answer['+jsoal+']" value="1"> Jadikan Kunci Jawaban </label> </p><textarea id="pil0" name="pil['+jsoal+'][0]" cols="30" rows="3" class="rich-textarea">'+entry.jawabanA+'</textarea> <br><button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#collapseImportSoalWord"><i class="fa fa-save"></i> Perbarui</button> </div><div class="clearfix"></div></div><div id="opsi-B-'+isoal+'" class="alert alert-danger" role="alert"> <p class="pull-left"> '+entry.jawabanB+' </p><div class="pull-right"> <a class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#collapseImportSoalWord2'+isoal+'" > <i class="fa fa-pencil"></i> </a> </div><div class="clearfix"></div><div class="rich-textarea-container collapse" id="collapseImportSoalWord2'+isoal+'"> <p class="text-center"> <label for=""> <input id="rad-opt-B-'+jsoal+'" type="radio" name="answer['+jsoal+']" value="2"> Jadikan Kunci Jawaban </label> </p><textarea id="pil1" name="pil['+jsoal+'][1]" cols="30" rows="3" class="rich-textarea">'+entry.jawabanB+'</textarea> <br><button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#collapseImportSoalWord2"><i class="fa fa-save"></i> Perbarui</button> </div><div class="clearfix"></div></div><div id="opsi-C-'+isoal+'" class="alert alert-danger" role="alert"> <p class="pull-left"> '+entry.jawabanC+' </p><div class="pull-right"> <a class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#collapseImportSoalWord3'+isoal+'" > <i class="fa fa-pencil"></i> </a> </div><div class="clearfix"></div><div class="rich-textarea-container collapse" id="collapseImportSoalWord3'+isoal+'"> <p class="text-center"> <label for=""> <input id="rad-opt-C-'+jsoal+'" type="radio" name="answer['+jsoal+']" value="3"> Jadikan Kunci Jawaban </label> </p><textarea id="pil2" name="pil['+jsoal+'][2]" cols="30" rows="3" class="rich-textarea">'+entry.jawabanC+'</textarea> <br><br><button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#collapseImportSoalWord3"><i class="fa fa-save"></i> Perbarui</button> </div><div class="clearfix"></div></div><div id="opsi-D-'+isoal+'" class="alert alert-danger" role="alert"> <p class="pull-left"> '+entry.jawabanD+' </p><div class="pull-right"> <a class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#collapseImportSoalWord4'+isoal+'" > <i class="fa fa-pencil"></i> </a> </div><div class="clearfix"></div><div class="rich-textarea-container collapse" id="collapseImportSoalWord4'+isoal+'"> <p class="text-center"> <label for=""> <input id="rad-opt-D-'+jsoal+'" type="radio" name="answer['+jsoal+']" value="4"> Jadikan Kunci Jawaban </label> </p><textarea id="pil3" name="pil['+jsoal+'][3]" cols="30" rows="3" class="rich-textarea">'+entry.jawabanD+'</textarea> <br><br><button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#collapseImportSoalWord4"><i class="fa fa-save"></i> Perbarui</button> </div><div class="clearfix"></div></div><div id="opsi-E-'+isoal+'" class="alert alert-danger" role="alert"> <p class="pull-left"> '+entry.jawabanE+' </p><div class="pull-right"> <a class="btn btn-primary btn-sm" data-toggle="collapse" data-target="#collapseImportSoalWord5'+isoal+'" > <i class="fa fa-pencil"></i> </a> </div><div class="clearfix"></div><div class="rich-textarea-container collapse" id="collapseImportSoalWord5'+isoal+'"> <p class="text-center"> <label for=""> <input id="rad-opt-E-'+jsoal+'" type="radio" name="answer['+jsoal+']" value="5"> Jadikan Kunci Jawaban </label> </p><textarea id="pil4" name="pil['+jsoal+'][4]" cols="30" rows="3" class="rich-textarea">'+entry.jawabanE+'</textarea> <br><button type="button" class="btn btn-primary btn-block" data-toggle="collapse" data-target="#collapseImportSoalWord5"><i class="fa fa-save"></i> Perbarui</button> </div><div class="clearfix"></div></div></div></div></div></div>');
                       $("#rad-opt-"+entry.jawaban+"-"+jsoal).attr("checked", "checked");
                       $("#opsi-"+entry.jawaban+"-"+isoal).removeClass("alert-danger");
                       $("#opsi-"+entry.jawaban+"-"+isoal).addClass("alert-success");
                       isoal++;  
                       jsoal++;
                 });
                reloadFunction();

                 $(".btn-soal").click(function(){
                      $('.soal-container ').removeClass('hide');
                      $('.soal-container ').addClass('hide');
                      $('#'+$(this).attr('data-step')).removeClass('hide');
                 }); 
                
                 $('#1').removeClass('hide');
                 $('#coba1').addClass('active');
                 $('#step-2').removeClass('hide');
                 $('#step-1').addClass('hide');

                 
                 $('#editor-container').removeClass('col-md-8');
                 $('#editor-container').addClass('col-md-12');
                 
                 $('#act-step-2').removeClass('hide');
                 $('#act-step-1').addClass('hide');
                 $('#info-right').addClass('hide');
                 

                 $('#btn-step-1').removeClass('active');
                 $('#btn-step-1').addClass('disabled');
                 $('#btn-step-2').removeClass('disabled')
                 $('#btn-step-2').addClass('active');

                  $('.modal-loading').removeClass("show");
                  $("#wrapper").toggleClass("toggled");

                 // tinyMCE.get('soalnya').setContent(obj[0]['soal']);
                 // tinyMCE.get('pil0').setContent(obj[0]['jawabanA']);
                 // tinyMCE.get('pil1').setContent(obj[0]['jawabanB']); 
                 // tinyMCE.get('pil2').setContent(obj[0]['jawabanC']);
                 // tinyMCE.get('pil3').setContent(obj[0]['jawabanD']);
                 // tinyMCE.get('pil4').setContent(obj[0]['jawabanE']);

                 // $("#rad-opt-"+obj[0]['jawaban']).attr("checked", "checked");

                 // $("#modalImportSoalWord").modal('hide');

                  
              })
              .error(function() { 
                  $('.modal-loading').removeClass("show");
                  alert("Terjadi kesalahan format, silahkan cek kembali.");
              })
        // alert("sdfasdf");
      });


    <?php
      if(!$model->isNewRecord){
        if(!empty($model->choices)){
          echo "var no = ".$jumPilihan;
        }
      }else{
        echo "var no = 4";
      }
    ?>
    
    $("#addChoices").click(function(){
      no++;
      
      $('#plh').append('<br/><br/><div class="clearfix"></div><div id="index'+ (no - 1) +'"><h4 class="pull-left">Jawaban</h4><div class="checkbox card-checkbox pull-right"><label><input type="radio" name="answer" value="'+ (no) +'" required> <span><b>Jadikan Kunci Jawaban</b></span> <button type="button" class="btn btn-danger btn-xs" data-index="index'+ (no - 1) +'" data-no="'+ (no - 1) +'"><i class="fa fa-trash"></i></button></label></div><div class="clearfix"></div><textarea class="jawaban" name="pil['+ (no - 1) +']" id="pil'+ (no - 1) +'"  data-placeholder="Tuliskan Jawaban disini.."></textarea></div>');
      reloadFunction();
    });


    CKEDITOR.replace( 'textword1' );

    function reloadFunction(){
      tinymce.init({
          selector: 'textarea',
          theme: 'modern',
          invalid_elements :'script',
          valid_styles : { '*' : 'color,font-size,font-weight,font-style,text-decoration' },
          entity_encoding: 'raw',
          entities: '160,nbsp,38,amp,60,lt,62,gt',
          plugins: [
              "advlist autolink lists link image charmap print preview anchor",
              "searchreplace visualblocks code fullscreen contextmenu",
              "insertdatetime media table contextmenu paste jbimages"
          ],
          contextmenu: "cut copy paste | jbimages inserttable | cell row column deletetable | code preview",
          toolbar: "undo redo | styleselect fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | jbimages | preview",
          contextmenu_never_use_native: true,
          paste_data_images: true,
          relative_urls: false,
          smart_paste: true,
          // initline: true,
          menubar: false,            
          setup : function(editor) {
            editor.on('init', function () {
                // Add class on init
                // this also sets the empty class on the editor on init
                if ( editor.getContent() === "" ) {
                    tinymce.DOM.addClass( editor.bodyElement, 'empty' );
                } else {
                    tinymce.DOM.removeClass( editor.bodyElement, 'empty' );
                }
            });
            // You CAN do it on 'change' event, but tinyMCE sets debouncing on that event
            // so for a tiny moment you would see the placeholder text and the text you typed in the editor
            // the selectionchange event happens a lot more and with no debouncing, so in some situations
            // you might have to go back to the change event instead.
            editor.on('selectionchange', function () {
                if ( editor.getContent() === "" ) {
                    tinymce.DOM.addClass( editor.bodyElement, 'empty' );
                } else {
                    tinymce.DOM.removeClass( editor.bodyElement, 'empty' );
                }
            });
            editor.on('change', function () {
                if ( editor.getContent() === "" ) {
                    tinymce.DOM.addClass( editor.bodyElement, 'empty' );
                } else {
                    tinymce.DOM.removeClass( editor.bodyElement, 'empty' );
                }
            });
          }
      });

      $( ".btn-danger" ).click(function() {
        dataID = $(this).attr('data-index');
        console.log(dataID);     
        $( "#"+dataID ).remove();
      });
    }
    // reloadFunction();


    // LocalStorage
    var theUrl = $(location).attr('href').split("/");
    var theCon = theUrl[theUrl.length - 2];
    var theMeth = theUrl[theUrl.length - 1];

    if (theCon=="questions" && theMeth == "create") {
      $("#Questions_title").change(function(){
        var theTitle = $("#Questions_title").val();
        if(typeof(Storage) !== "undefined") {
          localStorage.setItem("titleQuestion", theTitle);
        }
      });

      var localTitle = localStorage.getItem("titleQuestion");
      $("#Questions_title").val(localTitle);
    }
  }); 

</script>
