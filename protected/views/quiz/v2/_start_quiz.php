<?php
$cekQuiz=StudentQuiz::model()->findByAttributes(array('student_id'=>Yii::app()->user->id,'quiz_id'=>$model->id));
$total_pertanyaan = count($pertanyaan);

$doc_types = array('doc','docx','pdf','xls','xlsx','ppt','pptx');
$vid_types = array('swf','mp4','MP4','avi','mkv','flv');
$img_types = array('jpg','png','gif');
$audio_types = array('aac','mp3','ogg');

$passcode = NULL;
if(!empty($model->passcode)){
  $passcode = $model->passcode;
}

?>
<style type="text/css">
  #overlay {
      background-color: rgba(0, 0, 0, 0.8);
      z-index: 999;
      position: absolute;
      left: 0;
      top: 0;
      width: 100%;
      height: 250%;
      display: none;
  }
  #modalCentered {
    margin: auto;
    position: absolute;
      top: 0; left: 0; bottom: 0; right: 0;
  }

  .btn span.glyphicon {         
    opacity: 0;       
  }
  .btn.active span.glyphicon {        
    opacity: 1;       
  }
</style>
<?php if (Yii::app()->params['freezMethod']=='pengawas') { ?>
<div id="overlay">
  <div id="modalCentered" class="modal" tabindex="-1" role="dialog" aria-labelledby="..." style="position:relative">
       <div class="modal-dialog modal-xs">
        <div class="modal-content">
          <div class="panel panel-danger">
            <div class="panel-heading"></div>
            <div class="panel-body">
              <label>Tekan Tombol</label></br>
                <a href="#" id="kode" class="btn btn-pn-primary btn-lg pull-right next">
                  BUKA <i class="fa fa-chevron-right"></i>
                </a>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
<?php } else if (Yii::app()->params['freezMethod']=='code') { ?>
<div id="overlay">
  <div id="modalCentered" class="modal" tabindex="-1" role="dialog" aria-labelledby="..." style="position:relative">
       <div class="modal-dialog modal-xs">
        <div class="modal-content">
          <div class="panel panel-danger">
            <div class="panel-heading"></div>
            <div class="panel-body">
              <label>Masukkan Kode Untuk Membuka</label>
                <input type="password" class="form-control" id="kode">
            </div>
          </div>
        </div>
      </div>
  </div>
</div>
<?php } ?>
<div id="notif-flash">
</div>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12 container-soal-floating">
        <h2 class="pull-left">
          <?php echo $model->title; ?>
          <br>
          <small>Waktu Pengerjaan : <span id="waktu"><?php echo $model->end_time;?></span> Menit</center></small>
        </h2>
        <div class="pull-right countdown-right">
          <br>
          <?php
            if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin){ 
              echo CHtml::link('Kembali Ke Ujian',array('view', 'id'=>$model->id), array('class'=>'btn btn-pn-primary'));
            }
            ?>
          <div class="clock-timer" id="clock"></div>
          <a href="#" id="check" class="btn btn-pn-primary"><i class="fa fa-check"></i> Cek Jawaban</a>
         
        </div>

        <div class="clearfix"></div>
        <h5 style="float: right;" id="cekkoneksi"></h5>
        <div class="row">
          <div class="col-md-3">
            <div class="row">
              <div class="switch-quiz-box">
              <div class="col-md-12">
                <h4 class="hidden-xs hidden-sm">Daftar Soal</h4>
                <div class="col-card">
                  <div class="list-soal-container">
                    <ul class="nav nav-pills">
                        <div class="hidden-md hidden-lg">
                          &nbsp;&nbsp;<i class="fa fa-arrow-up fa-2x"></i>
                        </div>
                      <?php $urutan=1;?>
                      <?php
                      // for ($i=1; $i <= $model->total_question; $i++) {
                      $i = 1;
                      foreach ($questions as $key){

                        ?>
                        <?php if($i == 1){ ?>
                          <li class="list-soal active"><a href="#step<?php echo $i;?>" id="coba<?php echo $i?>" data-local="<?php echo $key->id; ?>" class="btn btn-soal" data-toggle="tab" data-step="<?php echo $i;?>"><?php echo $i;?></a></li>
                        <?php }else{ ?>
                          <li class="list-soal"><a href="#step<?php echo $i;?>"  id="coba<?php echo $i?>" data-local="<?php echo $key->id; ?>" class="btn btn-soal" data-toggle="tab" data-step="<?php echo $i;?>"><?php echo $i;?></a></li>
                        <?php } ?>
                      <?php $i++; } ?>
  		            	 	<!-- <li class="list-soal active"><a href="#step1" id="coba1" data-local="768" class="btn btn-soal" data-toggle="tab" data-step="1">1</a></li>
                      <li class="list-soal"><a href="#step2" id="coba2" data-local="769" class="btn btn-soal" data-toggle="tab" data-step="2" >2</a></li>
                      <li class="list-soal"><a href="#step2" id="coba2" data-local="769" class="btn btn-soal" data-toggle="tab" data-step="2" >3</a></li>
                      <li class="list-soal"><a href="#step2" id="coba2" data-local="769" class="btn btn-soal" data-toggle="tab" data-step="2" >4</a></li>
                      <li class="list-soal"><a href="#step2" id="coba2" data-local="769" class="btn btn-soal" data-toggle="tab" data-step="2" >5</a></li>
                      <li class="list-soal"><a href="#step2" id="coba2" data-local="769" class="btn btn-soal" data-toggle="tab" data-step="2" >6</a></li>
                      <li class="list-soal"><a href="#step2" id="coba2" data-local="769" class="btn btn-soal" data-toggle="tab" data-step="2" >7</a></li>
                      <li class="list-soal"><a href="#step2" id="coba2" data-local="769" class="btn btn-soal" data-toggle="tab" data-step="2" >8</a></li>
                      <li class="list-soal"><a href="#step2" id="coba2" data-local="769" class="btn btn-soal" data-toggle="tab" data-step="2" >9</a></li>
  			        	    <li class="list-soal"><a href="#step2" id="coba2" data-local="769" class="btn btn-soal" data-toggle="tab" data-step="2" >10</a></li> -->
  			        		     <div class="hidden-md hidden-lg">
                           &nbsp;&nbsp;<i class="fa fa-arrow-down fa-2x"></i>
                        </div>
                    </ul>
                  </div>
                </div>
                <div class="progress-container">
                  <div class="progress">
          			  <div class="progress-bar progress-bar-success" role="progressbar" aria-valuenow="1" aria-valuemin="1" aria-valuemax="8" style="width: 80%;">Soal 1 <?php echo $model->total_question;?> Soal</div>
          			  </div>
                </div>
              </div>
              </div>
            </div>
          </div>
          <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'questions',
            'action'=>Yii::app()->createUrl('quiz/submitQuiz'),
            // Please note: When you enable ajax validation, make sure the corresponding
            // controller action is handling ajax validation correctly.
            // There is a call to performAjaxValidation() commented in generated controller code.
            // See class documentation of CActiveForm for details on this.
            'enableAjaxValidation'=>false,
             'htmlOptions'=>array(
                               'onsubmit'=>"return false;"/* Disable normal form submit */

                             ),
          )); ?>
          <?php echo $form->hiddenField($sq, 'quiz_id', array('value'=>$model->id));?>
          <?php echo $form->hiddenField($sq, 'student_id', array('value'=>Yii::app()->user->id));?>
          <?php $urut=1;?>
          <input type="hidden" name="qid" value="<?php echo $model->id;?>">
          <input id="dude" name="dude" value="0" type="hidden">
          <input type="hidden" name="indikasi" value="0" id="indikator">
          <div class="col-md-9">
            <div class="tab-content" style="margin:0px">
            <h4>Soal</h4>
            <?php foreach ($questions as $key){ ?>
            <div class="tab-pane fade <?php echo $urut == (1) ? "in active" : "" ; ?>" id="step<?php echo $urut;?>">
            <div class="col-card">
              <div>
                <h3>
                  Soal <?php echo $urut;?>
                </h3>
                <p>
                  <?php echo $key->text;?>
                  <br>
                            <?php if(!empty($key->file)){ ?>
                              <?php $ext = pathinfo($key->file, PATHINFO_EXTENSION);?>
                              
                              <?php
                               $path_image=Clases::model()->path_image($key->id); 
                               $img_url = Yii::app()->baseUrl.'/images/question/'.$path_image.$key->file;

                               ?>
                      
                        <?php if(in_array($ext, $vid_types)){ ?>
                          <div class="img-responsive">
                            <center>
                              <?php
                                $this->widget('ext.jwplayer.Jwplayer',array(
                                    'width'=>250,
                                    'height'=>180,
                                    'file'=>$img_url, // the file of the player, if null we use demo file of jwplayer
                                    'image'=>NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
                                    'options'=>array(
                                        'controlbar'=>'bottom'
                                    )
                                ));
                              ?>
                            </center>
                          </div>
                        <?php }elseif(in_array($ext, $img_types)){?>
                          <img src="<?php echo $img_url;?>" class="img-responsive">
                        <?php }elseif(in_array($ext, $audio_types)){ ?>
                          <div class="img-responsive">
                            <audio controls="controls">
                            
                                <source src="<?php echo $img_url;?>" type="audio/mpeg">
                              
                            </audio>
                          </div>
                        <?php } ?>
                      <?php } ?>
                </p>
                <hr>
                <br>
                <div class="panel-jawaban">
                  <?php
                    if ($key->type == "2") {
                      ?>

                      
                         <h3>Jawaban</h3>
                         <p>Silahkan menjawab dengan menuliskannya di form dibawah ini, atau mengupload gambar dengan tombol choose file/pilih file.</p>
                        
                        </br>
                        <div class="captureDiv"><input  style="float: left;" onchange="previewFile(<?php echo $key->id;?>)" type="file" accept="image/*" capture="camera" the-id="<?php echo $key->id;?>" id="capture-<?php echo $key->id;?>"></div>
                  </br>
                  </br>
                  </br>
                      <textarea rows="10" cols="50" name="pil[<?php echo $key->id;?>]" id="answer-<?php echo $key->id;?>" ></textarea>
                      <!-- <input name="pil[<?php //echo $key->id;?>]" id="answer-<?php //echo $key->id;?>" type="text" value="" > -->
                      <?php
                    } elseif ($key->type == "3") {
                        $pil = json_decode($key->choices,true);
                            if($model->random_opt == 1){
                              shuffle($pil);
                            }
                        foreach ($pil as $k => $value) {
                          ?>
                            <input type="text" class="form-control btn-default btn-block btn-opsi" name="pil[<?php echo $key->id;?>]" required="">
                           <br/>
                           <br/>
                          <?php }
                    } else {


                            $pil = json_decode($key->choices,true);
                            if($model->random_opt == 1){
                              shuffle($pil);
                            }
                            foreach ($pil as $k => $value) {
                          ?>
                          <label class="btn btn-default btn-block btn-opsi">
                            <input type="radio" name="pil[<?php echo $key->id;?>]" value='<?php echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');?>'> <span><p><?php echo $value;?></p></span>
                            <?php if(!empty($gambar[$k])){ ?>
                            <?php if(empty($key->id_lama)){?>
                              <?php $img_pil = Yii::app()->baseUrl.'/images/question/'.$path_image.$k.'/'.$gambar[$k];?>
                            <?php }else{ ?>
                              <?php $img_pil = Yii::app()->baseUrl.'/images/question/remote/'.$path_image.$k.'/'.$gambar[$k];?>
                            <?php } ?>
                            <img src="<?php echo $img_pil;?>" class="img-responsive">
                            <?php } ?>
                          </label>
                          <br>
                          <?php }
                    }

                   ?>
                </div>

				    </div>

            </div>

              <div class="row col-button">
                <?php if ($urut!=1){?>
                <div class="col-md-6">
                <a href="#" class="btn btn-warning btn-lg btn-block pull-left prev">
                <i class="fa fa-chevron-left"></i> Sebelumnya
                </a>
                <br/>
                </div>
                <?php }else if ($urut==1) {?>
                <div class="col-md-6">
                <br/>
                </div>
                <?php }?>
                <div class="col-md-6">
                <?php if ($urut == $model->total_question) {?>
                <?php echo CHtml::submitButton($sq->isNewRecord ? 'Selesai' : 'Selesai',array('onclick'=>'myconfirm()','id'=>'finish','class'=>'btn btn-pn-primary btn-block btn-lg pull-right'));?>
                <?php } else {?>
                <a href="#" class="btn btn-pn-primary btn-block btn-lg pull-right next">
                  Selanjutnya <i class="fa fa-chevron-right"></i>
                </a>
                <?php }?>
                <br/>
                </div>
                </div><!-- /.col-button -->
                <div class="row">&nbsp;</div>
              </div>
            <?php
              $urut++;
              }
            ?>
          </div>  
          </div>
          </div>
          <?php $this->endWidget(); ?>
        </div>
    </div>
  </div>
</div>
<?php $url_user = $this->createUrl('site/acak');?>
<?php $redirect= $this->createUrl('site/index');?>
<?php $cek_url = Yii::app()->baseUrl.'/images/pencil.png';?>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/ifvisible/ifvisible.js"></script>
<script>
    console.log(localStorage);
    cek_url = "<?php echo $cek_url;?>";
      var url_user = "<?php echo $url_user;?>";
      var id_user = "<?php echo Yii::app()->user->id;?>";
      var id_quiz = "<?php echo $model->id;?>";
      var red_url = "<?php echo $redirect;?>"
      var clock;
      var idquiz="<?php echo $model->id;?>";
      var wkt = $("#waktu").text()*60;
      var passcode = "<?php echo md5($passcode) ?>";
    function checkNetConnection(){
       jQuery.ajaxSetup({async:false});
       re="";
       r=Math.round(Math.random() * 10000);
       $.get(cek_url,{subins:r},function(d){
        re=true;
       }).error(function(){
        re=false;
       });
       return re;
    }

    function previewFile(id) {

      
      //THe original
      // console.log(id);
      
      // var preview = document.querySelector('#ans-'+id);
      // var form = document.querySelector('#answer-'+id);
      // var file    = document.querySelector('#capture-'+id).files[0];
      // var reader  = new FileReader();

      // reader.addEventListener("load", function () {
      //   // preview.src = reader.result;
      //   // form.value = "<p><img src=\""+reader.result+"\"></p>";
      //   $('.modal-loading').removeClass("show");
      //   tinymce.get('answer-'+id).setContent("<img class=\"naowae\" src=\""+reader.result+"\">");
      //   // $(tinymce.get('answer-'+id).getBody()).html("<p><img src=\""+reader.result+"\"></p>");

      // }, false);

      // if (file) {
      //   reader.readAsDataURL(file);
      // }
      // end

          var filesToUpload = document.getElementById('capture-'+id).files;
          var file = filesToUpload[0];

          // Create an image
          var img = document.createElement("img");
          // Create a file reader
          var reader = new FileReader();
          // Set the image once loaded into file reader
          reader.onload = function(e)
          {
              img.src = e.target.result;

              var canvas = document.createElement("canvas");
              //var canvas = $("<canvas>", {"id":"testing"})[0];
              var ctx = canvas.getContext("2d");
              ctx.drawImage(img, 0, 0);

              var MAX_WIDTH = 300;
              var MAX_HEIGHT = 100;
              var width = img.width;
              var height = img.height;

              if (width > height) {
                if (width > MAX_WIDTH) {
                  height *= MAX_WIDTH / width;
                  width = MAX_WIDTH;
                }
              } else {
                if (height > MAX_HEIGHT) {
                  width *= MAX_HEIGHT / height;
                  height = MAX_HEIGHT;
                }
              }
              canvas.width = width;
              canvas.height = height;
              var ctx = canvas.getContext("2d");
              ctx.drawImage(img, 0, 0, width, height);

              var dataurl = canvas.toDataURL("image/png");
              console.log(dataurl);
              $('.modal-loading').removeClass("show");
              tinymce.get('answer-'+id).setContent("<img src=\""+dataurl+"\">");
              // document.getElementById('image').src = dataurl;     
          }
          // Load files into file reader
          reader.readAsDataURL(file);


          // Post the data
          /*
          var fd = new FormData();
          fd.append("name", "some_filename.jpg");
          fd.append("image", dataurl);
          fd.append("info", "lah_de_dah");
          */


        //Post dataurl to the server with AJAX
    }

    $(document).ready(function() {
      if(typeof(localStorage.questionsundefinedqid) !== "undefined"){
      if(idquiz !== localStorage.questionsundefinedqid){
          //console.log(red_url+"/"+localStorage.questionsundefinedqid);
          $("#notif-flash").append('<div class="alert alert-warning notice" role="alert">Anda Harus Menghapus Data Ulangan Yang Lama Dahulu</div>');
          window.setTimeout(function(){
            window.location.replace(red_url);
          }, 1000);
          //console.log(localStorage.questionsundefinedqid);
        }
        //console.log(localStorage.questionsundefinedqid);
      }

      if(typeof(localStorage.questionsundefineddude) !== "undefined") {
          var wktInv = parseInt(localStorage.questionsundefineddude);
          if (wktInv!=0){
            wkt = wktInv;
          }
         // console.log(localStorage.questionsundefineddude);
      }

       $( function() {
        $("#questions").sisyphus({
            autoRelease: false,
            timeout: 1
          });
          //$("#wkt").sisyphus();
          // or you can persist all forms data at one command
          // $( "form" ).sisyphus();
        } );

       // 15 days from now! example function
        function get15dayFromNow() {
          return new Date(new Date().valueOf() + 1 * wkt * 1000);
        }

        var $clock = $('#clock');

        $clock.countdown(get15dayFromNow(), function(event) {
          $(this).html(event.strftime('%H:%M:%S'));
          var totalMinutes = (event.offset.hours * 3600) + (event.offset.minutes * 60) + event.offset.seconds;
          $("#dude").val(totalMinutes);
          // console.log(totalMinutes);
        });


        // $(document).on("contextmenu",function(e){
        //   if(e.target.nodeName != "INPUT" && e.target.nodeName != "TEXTAREA")
        //        e.preventDefault();
        // });

      
        //  $(document).keydown(function (e) {
        //     return (e.which || e.keyCode) != 116;
        // });

      // $('.captureDiv').click(function(){
      //     $('.modal-loading').addClass("show");
      // });

      $('.next').click(function(){

        var nextId = $(this).parents('.tab-pane').next().attr("id");
        $('.nav-pills a[href="#'+nextId+'"]').tab('show');
        return false;

      })

      $('.prev').click(function(){

        var nextId = $(this).parents('.tab-pane').prev().attr("id");
        $('.nav-pills a[href="#'+nextId+'"]').tab('show');
        return false;

      })

    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {

        //update progress
        var step = $(e.target).data('step');
        var percent = (parseInt(step) / "<?php echo $total_pertanyaan;?>") * 100;

        $('.progress-bar').css({width: percent + '%'});
        $('.progress-bar').text("Soal " + step + " dari <?php echo $total_pertanyaan;?> Soal");

        //e.relatedTarget // previous tab

    })
    var percentInit = (parseInt(1) / "<?php echo $total_pertanyaan;?>") * 100;
    $('.progress-bar').css({width: percentInit + '%'});

    $('.first').click(function(){

        $('#myWizard a:first').tab('show')

    })


    // the freez start
    var isActive = true;
    var indikasi = 0;

    window.onfocus = function () { 
      isActive = true; 
    }; 

    window.onblur = function () { 
      isActive = false;
      indikasi++;
      console.log(indikasi);
      $("#indikator").val(indikasi); 
    }; 
    
    // test
    var kode;
    if(passcode == null){
      pass = "abc123";
    }
    setInterval(function () { 
      console.log(isActive ? 'Active' : 'Inactive'); 
      if(isActive == false){              
        localStorage.setItem('status',"1");
        window.location.href = "<?php echo Yii::app()->baseUrl ?>/quiz/blockQuiz/<?php echo $model->id;?>";
        // $("#modalCentered").show();
        //kode = prompt("Masukan Kode Untuk Membuka", "");
        $("#kode").change(function(){
          kode = $("#kode").val();
          kode =  CryptoJS.MD5(kode);
          if(kode == passcode){
            $("#overlay").hide();
            $("#modalCentered").hide();
            $('#kode').removeAttr('value');
            localStorage.setItem('status',"0");
          }else{
            // location.reload();
          } 
        });
        
      }
    }, 1000);

    var isActive = true;
    var indikasi = 0;
    window.onfocus = function () { 
      isActive = true; 
    }; 

    window.onblur = function () { 
      isActive = false;
      indikasi++;
      console.log(indikasi);
      $("#indikator").val(indikasi); 
    }; 
    
    if(localStorage.status == 1){
      window.location.href = "<?php echo Yii::app()->baseUrl ?>/quiz/blockQuiz/<?php echo $model->id;?>";
      // $("#modalCentered").show();
      //kode = prompt("Masukan Kode Untuk Membuka", "");
      
      $("#kode").change(function(){
          kode = $("#kode").val();
          kode =  CryptoJS.MD5(kode);
          // alert(kode);
          // alert(passcode);
          if(kode == passcode){
            $("#overlay").hide();
            $("#modalCentered").hide();
            $('#kode').removeAttr('value');
            localStorage.setItem('status',"0");
          }else{
            // location.reload();
          } 
        });
        
    }


    


    $("#check").click(function(){
      if(checkNetConnection()==true){
       hitung = $('input[type="radio"]:checked', '#questions').length;
       total = $('.tab-pane').length;
       //alert("Tersambung Ke Wifi "+hitung+" Soal Telah Dijawab dari "+ total );
       //alert("Tersambung Ke Wifi");
        $("#cekkoneksi").html("Tersambung Ke Wifi, "+hitung+" Soal Telah Dijawab dari "+ total);
      }else{
       hitung = $('input[type="radio"]:checked', '#questions').length;
       total = $('.tab-pane').length;
      // alert("Tidak Tersambung Ke Wifi "+hitung+" Soal Telah Dijawab dari "+ total );
       //alert("Tidak Tersambung Ke Wifi");
       //$('#finish').addClass('disabled');
       $("#cekkoneksi").html("Tidak Tersambung Ke Wifi, "+hitung+" Soal Telah Dijawab dari "+ total );
      }
    });
      var hitung = 0;
      var total = 0;
      for ( var i = 0, len = localStorage.length; i < len; ++i ) {
      //console.log(localStorage.getItem( localStorage.key( i ) ) );
      //console.log(localStorage.key( i ));
      //console.log( i );
      //
      if(localStorage.key(i).match(/questionsundefinedpil.*/)){
          console.log(localStorage.key( i ));

          var nosoal=localStorage.key( i ).substring(localStorage.key( i ).lastIndexOf("[")+1,localStorage.key( i ).lastIndexOf("]"));
        //console.log(nosoal);
        //$("#coba"+nosoal+"").css("background-color","green");
        $("[data-local="+nosoal+"]").css("background-color","green");
        $("[data-local="+nosoal+"]").css("color","white");

      }
    }
    //var content_array = JSON.parse(localStorage);
    //console.log(content_array);
    //console.log(localStorage);
      $('input:radio').change(
        function(){
            if (this.checked) {
                var tabid = $(this).closest(".tab-pane").attr("id");
                idtab = tabid.replace("step","coba");
              //console.log($("#"+idtab+"").attr('class'));
              $("#"+idtab+"").css("background-color","green");
              $("#"+idtab+"").css("color","white");
              //$(this).css("background-color","green");
              //console.log($("#"+idtab+"").text());
            }
    });




    });//end document ready

  

    if ($(window).width() > 960) {
       $("#wrapper").toggleClass("toggled");
    }


    function myconfirm(){
      if(checkNetConnection()==true){
        hitung = $('input[type="radio"]:checked', '#questions').length;
        total = $('.tab-pane').length;
        // var r = confirm("Anda sudah mengerjakan "+hitung+" soal dari "+total+" soal, yakin akan mengumpulkan ?");
        // if (r == true) {
        //   $( "#questions" ).removeAttr( "onSubmit" );
        //   $( "#questions").submit();
        // }
        bootbox.confirm({
            message: "Anda sudah mengerjakan "+hitung+" soal dari "+total+" soal, yakin akan mengumpulkan ?",
            buttons: {
                confirm: {
                    label: 'Ya',
                    className: 'btn-success'
                },
                cancel: {
                    label: 'Tidak',
                    className: 'btn-danger'
                }
            },
            callback: function (result) {
                console.log('This was logged in the callback: ' + result);
                if(result){
                      $( "#questions" ).removeAttr( "onSubmit" );
                      $( "#questions").submit();
                }
            }
        });
      } else {
        alert("Tidak Tersambung Ke Wifi TIDAK bisa submit, "+hitung+" Soal Telah Dijawab dari "+ total );
      } 
    }

     
      tinymce.init({
          selector: 'textarea',
          theme: 'modern',
          autosave_interval: "1s",
          invalid_elements :'script',
          valid_styles : { '*' : 'color,font-size,font-weight,font-style,text-decoration' },
          entity_encoding: 'raw',
          entities: '160,nbsp,38,amp,60,lt,62,gt',
          content_style: "img {max-width: 200px}",
          plugins: [
              "autosave advlist autolink lists link image charmap print preview anchor",
              "searchreplace visualblocks code fullscreen contextmenu",
              "insertdatetime media table contextmenu paste jbimages"
          ],
          contextmenu: "cut copy paste | jbimages inserttable | cell row column deletetable | code preview",
          toolbar: "restoredraft | undo redo | styleselect fontsizeselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist | preview",
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
    
</script>
