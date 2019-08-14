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

  $tipe=array(NULL=>'Pilihan Ganda','2'=>'Essay', '3'=>'Isian Singkat');

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
        <div class="col-md-12">
          <h4>Informasi Soal</h4>
          <div class="col-card">
            <div class="col-dropdown">
              <div class="row">
                <div class="col-md-9">
                  <label for="Questions_title">Judul / Kompetensi Dasar</label>
                  <?php echo $form->textField($model,'title',array('class'=>'form-control input-pn input-lg','placeholder'=>'Judul / Kompetensi Dasar','required'=>'required')); ?>
                  <!-- <select name="kompetensiDasar" class="selectpicker form-control" data-style="btn-default input-lg" data-live-search="true" title="Kompetensi Dasar 1">
                    <option>Isi Kompetensi Dasar 1</option>
                    <option>Isi Kompetensi Dasar 2</option>
                    <option>Isi Kompetensi Dasar 3</option>
                    <option>Isi Kompetensi Dasar 4</option>
                  </select> -->
                  <div class="form-group">
                    <?php echo $form->labelEx($model,'file'); ?> 
                    <?php echo $form->fileField($model,'file'); ?>  
                    <?php echo $form->error($model,'file'); ?>
                    <p class="help-block">Maksimal Ukuran file 2 Mb</p>

                  </div>
                </div>
                <br class="visible-xs">
                <div class="col-md-3">
                  <label for="Questions_type">Jenis Soal</label>
                  <?php echo $form->dropdownList($model,'type',$tipe,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg', 'options'=>array($selected=>array('selected'=>true))))?>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <h4>Detail Soal</h4>
          <div class="col-card">
            <div class="row">
              <div class="col-md-12">
                <!-- <div>
                  <div>
                   <button type="button" class="btn btn-pn-primary btn-sm" data-toggle="modal" data-target="#modalImportSoalWord">
                      <i class="fa fa-download"></i> Import Soal dari Dokumen Word
                    </button>
                  </div>
                  <div class="clearfix"></div>
                </div>
                <br> -->
                <h4 class="pull-left">Soal </h4>
                <div class="rich-textarea-container">
                  <div class="jawaban-container">
                    <textarea id="soalnya" class="jawaban jawaban-title" name="Questions[text]" data-placeholder="Tuliskan Pertanyaan Disini..">
                      <?php
                        if(!empty($model->text)){
                          echo $model->text;
                        }
                      ?>
                    </textarea>
                    <br/>
                    <br/>
                    <div class="clearfix"></div>
                      <div id="plh">
                        <?php
                          if(!$model->isNewRecord){
                            if(!empty($model->choices)){
                              if($model->type==NULL ||  $model->type=='3'){
                                $pilihan = json_decode($model->choices, true);
                                $jumPilihan = count($pilihan);

                                $no = 1;
                                foreach($pilihan as $key => $value){

                                   $the_key_answer = $model->key_answer;
                                   $the_choi = $value;

                                    $the_key_answer = preg_replace("/\r|\n/", "", $the_key_answer);
                                    $the_key_answer = strip_tags($the_key_answer,"<img>");
                                    $the_key_answer = preg_replace('/\s+/', '', $the_key_answer);
                                    $the_key_answer =  str_replace("/>","",$the_key_answer);
                                    $the_key_answer =  str_replace(">","",$the_key_answer);                                         
                                   
                                    $the_choi = preg_replace("/\r|\n/", "", $the_choi);
                                    $the_choi = strip_tags($the_choi,"<img>");
                                    $the_choi = preg_replace('/\s+/', '', $the_choi);
                                    $the_choi =  str_replace("/>","",$the_choi);
                                    $the_choi =  str_replace(">","",$the_choi); 

                        ?>
                        <div id="index<?php echo $key; ?>">
                          <h4 class="pull-left">Jawaban <?php echo $no ?></h4>
                          <div class="checkbox card-checkbox pull-right">
                            <label>
                              <?php
                                  if($the_choi == $the_key_answer){
                              ?>
                            <!-- <button type="button" class="btn btn-pn-primary btn-xs"><i class="fa fa-toggle-on"></i> Kunci Jawaban</button> -->
                              <input type="radio" name="answer" value="<?php echo $no;?>" checked required> <span><b>Jadikan Kunci Jawaban</b></span>
                              <?php
                                  }else{
                              ?>
                              <!-- <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-toggle-off"></i> Jadikan Kunci Jawaban</button> -->
                              <input type="radio" name="answer" value="<?php echo $no;?>" required> <span><b>Jadikan Kunci Jawaban</b></span>
                              <?php
                                  }
                              ?>
                              <?php if ($model->type == NULL){ ?>
                              <button type="button" class="btn btn-danger btn-xs" data-index="index<?php echo $key ?>" data-no="<?php echo $key ?>"><i class="fa fa-trash"></i></button>
                              <?php } ?>
                            </label>
                          </div>
                          <div class="clearfix"></div>
                          <textarea class="jawaban" name="pil[<?php echo $key?>]" id="pil<?php echo $key?>" data-placeholder="Tuliskan Jawaban <?php echo $no ?> disini...."><?php echo $value ?></textarea>
                        </div>
                        <br/>
                        <br/>                        
                        <?php
                                  $no++;
                                }
                              } else {
                                $pilihan = json_decode($model->choices, true);
                                $jumPilihan = count($pilihan);
                              }
                            }
                          }else{
                        ?>
                        <div id="index0">
                          <h4 class="pull-left">Jawaban 1</h4>
                          <div class="checkbox card-checkbox pull-right">
                            <label>
                              <!-- <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-toggle-off"></i>Jadikan Kunci Jawaban</button> -->
                              <input type="radio" name="answer" value="1" checked> <span><b>Jadikan Kunci Jawaban</b></span>   
                              <button type="button" class="btn btn-danger btn-xs" data-index="index0" data-no="0"><i class="fa fa-trash"></i></button>
                            </label>
                          </div>
                          <div class="clearfix"></div>
                          <textarea id="pil0" name="pil[0]" data-placeholder="Tuliskan Jawaban 1 disini.."></textarea>
                        </div>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                        <div id="index1">
                          <h4 class="pull-left">Jawaban 2</h4>
                          <div class="checkbox card-checkbox pull-right">
                            <label>
                              <!-- <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-toggle-off"></i> Jadikan Kunci Jawaban</button> -->
                              <input type="radio" name="answer" value="2" > <span><b>Jadikan Kunci Jawaban</b></span>   
                              <button type="button" class="btn btn-danger btn-xs" data-index="index1" data-no="1"><i class="fa fa-trash"></i></button>                              
                            </label>
                          </div>
                          <div class="clearfix"></div>
                          <textarea id="pil1" name="pil[1]" data-placeholder="Tuliskan Jawaban 2 disini.."></textarea>
                        </div>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                        <div id="index2">
                          <h4 class="pull-left">Jawaban 3</h4>
                          <div class="checkbox card-checkbox pull-right">
                            <label>
                              <!-- <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-toggle-off"></i> Jadikan Kunci Jawaban</button> -->
                              <input type="radio" name="answer" value="3" > <span><b>Jadikan Kunci Jawaban</b></span> 
                              <button type="button" class="btn btn-danger btn-xs" data-index="index2" data-no="2"><i class="fa fa-trash"></i></button>
                            </label>
                          </div>
                          <div class="clearfix"></div>
                          <textarea id="pil2" name="pil[2]" data-placeholder="Tuliskan Jawaban 3 disini.."></textarea>
                        </div>
                        <br/>
                        <br/>
                        <div class="clearfix"></div>
                        <div id="index3">
                          <h4 class="pull-left">Jawaban 4</h4>
                          <div class="checkbox card-checkbox pull-right">
                            <label>
                              <!-- <button type="button" class="btn btn-primary btn-xs"><i class="fa fa-toggle-off"></i> Jadikan Kunci Jawaban</button> -->
                              <input type="radio" name="answer" value="4" > <span><b>Jadikan Kunci Jawaban</b></span> 
                              <button type="button" class="btn btn-danger btn-xs" data-index="index3" data-no="3"><i class="fa fa-trash"></i></button>
                            </label>
                          </div>
                          <div class="clearfix"></div>
                          <textarea id="pil3" name="pil[3]" data-placeholder="Tuliskan Jawaban 4 disini.."></textarea>
                        </div>
                        <br/>
                        <br/>
                      </div>
                    <?php
                      }
                    ?>
                    </div>
                    <?php if ($model->type == NULL){ ?>
                    <div class="text-center">
                      <button type="button" id="addChoices" class="btn btn-success btn-block btn-sm"><i class="fa fa-plus-circle"></i> Tambah Jawaban</button>
                    </div>
                    <?php } ?>
                    <div class="clearfix"></div>
                  </form>
                </div>
              </div>
            </div>
          </div>
         </div>
        <div class="col-md-12">
          <div class="col-card">
            <div class="row">
              <!-- <div class="col-md-4">
                <button type="button" class="btn btn-primary btn-lg btn-pn-round btn-block next-step"><i class="fa fa-plus"></i> Tambah Soal</button>
              </div> -->
              <?php if(!$model->isNewRecord){ ?>
              <div class="col-md-12">
                <button type="submit" class="btn btn-primary btn-lg btn-pn-round btn-block next-step"><i class="fa fa-save"></i> Simpan Perubahan</button>
              </div>
              <?php }else{ ?>
              <div class="col-md-6">
                <button type="submit" class="btn btn-primary btn-lg btn-pn-round btn-block next-step"><i class="fa fa-save"></i> Simpan</button>
              </div>
              <div class="col-md-6">
                <button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step"><i class="fa fa-check-circle"></i> Selesai</button>
              </div>
              <?php } ?>
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
<div class="modal fade" id="modalImportSoalWord" tabindex="-1" role="dialog" aria-labelledby="filterImportSoalWord">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title text-center" id="forgotPasswordLabel">Import Soal dari Word</h4>
      </div>
      <div class="modal-body">
        <p>
          Silahkan tempelkan teks soal pada kotak dibawah ini
        </p>
        <div class="alert alert-info">
          <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <h4>Bentuk format teks yang dapat diterima:</h4>
          <ol>
            <li>Penomoran soal tidak menggunakan numbering</li>
            <li>Abjad pilihan jawaban tidak menggunakan bullet/numbbering</li>
            <li>Abjad pilihan menggunakan huruf kapital</li>
            <li>Soal dan pilihan jawaban dipisah menggunakan kalimat "KUNCI : [Abjad kunci jawaban]"</li>
          </ol>
        </div>
        <div>
          <textarea id="textword" class="simple-textarea" name="name" rows="8" cols="40">
          </textarea>
          <!-- Note for sigit: direkomendasiin sih paste as text by default, biar mengurangi markup yang gak penting
          tapi entar gambar di soal ga akan kebawa sih kalau pakai ini
          http://stackoverflow.com/questions/2695731/how-to-make-tinymce-paste-in-plain-text-by-default -->
        </div>
        <div>
          <br>
          <button id="submitword" class="btn btn-pn btn-pn-primary btn-lg btn-block">Import Soal</button>
        </div>
      </div>
    </div>
  </div>
  <!-- script modal -->
  <script type="text/javascript">
      $("#submitword").click(function(){
        //console.log(tinymce.get('textword').getContent());
        $.post("ajaxparsinghtml",
              {
                  wordhtml: tinymce.get('textword').getContent()
              },
              function(result){
                 var obj = $.parseJSON(result);
                 console.log(obj['soal']);
                 tinyMCE.get('soalnya').setContent(obj['soal']);
                 tinyMCE.get('pil0').setContent(obj['jawabanA']);
                 tinyMCE.get('pil1').setContent(obj['jawabanB']);
                 tinyMCE.get('pil2').setContent(obj['jawabanC']);
                 tinyMCE.get('pil3').setContent(obj['jawabanD']);

                 $("#modalImportSoalWord").modal('hide');

                  
              });
        // alert("sdfasdf");
      });
      
  </script>
</div>
<script type="text/javascript">
  $(document).ready(function(){
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
      
      $('#plh').append('<div class="clearfix"></div><div id="index'+ (no - 1) +'"><h4 class="pull-left">Jawaban</h4><div class="checkbox card-checkbox pull-right"><label><input type="radio" name="answer" value="'+ (no) +'" required> <span><b>Jadikan Kunci Jawaban</b></span> <button type="button" class="btn btn-danger btn-xs" data-index="index'+ (no - 1) +'" data-no="'+ (no - 1) +'"><i class="fa fa-trash"></i></button></label></div><div class="clearfix"></div><textarea class="jawaban" name="pil['+ (no - 1) +']" id="pil'+ (no - 1) +'"  data-placeholder="Tuliskan Jawaban disini.."></textarea><br/><br/></div>');
      reloadFunction();
    });
    
    $('#Questions_type').change(function (){
        no++;
        var type_soal = $(this).find(':selected').attr('value');
        if(type_soal === '3'){
            
            $('#plh').html('<div class="clearfix"></div><div id="index'+ (no - 1) +'"><h4 class="pull-left">Jawaban</h4><div class="checkbox card-checkbox pull-right"><label><input type="radio" name="answer" value="'+ (no) +'" required> <span><b>Jadikan Kunci Jawaban</b></span></label></div><div class="clearfix"></div><textarea class="jawaban" name="pil['+ (no - 1) +']" id="pil'+ (no - 1) +'"  data-placeholder="Tuliskan Jawaban disini.."></textarea></div>');
            reloadFunction();
            $("#addChoices").hide();
        }
    });

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
    reloadFunction();


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

