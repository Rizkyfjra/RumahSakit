<?php
  $no = 1;
?>
<!-- <div id="notif-flash">
  <div id="alrt"class="alert alert-warning hidden" role="alert">Masih    ada data ulangan yang tersisa <a href="#" id="lanjut">Lanjut Mengerjakan</a> atau <a href="" id="hapusData">Batal Mengerjakan</a></div>
</div> -->
<div id="notif-flash">
  <div id="alrt" class="alert alert-success  alert-dismissable hidden">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
          <p>
            <strong>Masih </strong> ada data ulangan yang tersisa !
          </p>
          <p>
            <div class="row">
              <div class="col-md-6">
                <a id="lanjut" style="margin-bottom: 5px;" class="btn btn-success btn-block">Lanjut Mengerjakan</a>
              </div><!-- /.col-md-6 -->
              <div class="col-md-6">
                <a id="hapusData" style="margin-bottom: 5px;" href="" class="btn btn-danger btn-block">Batal Mengerjakan</a>
              </div><!-- /.col-md-6 -->
            </div><!-- /.row -->
          </p>
  </div>
</div>
<?php if(Yii::app()->user->YiiStudent){ ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="col-card">
        <div class="row row-student-overview">
          <h3>Selamat Datang</h3>
          <p>
            <strong>Berikut ini merupakan informasi singkat mengenai ujian dan tugas anda</strong>
          </p>
          <br>
          <div class="col-lg-3 col-sm-6 col-xs-6">
            <div class="overview-number overview-green">
              <?php echo count($modelAssignmentDone);?>
            </div>
            <div class="overview-description">
              Tugas Selesai
            </div>
          </div><!-- /.col-lg-3 col-sm-6 col-xs-6 -->
          <div class="col-lg-3 col-sm-6 col-xs-6">
            <div class="overview-number overview-red">
              <?php echo count($modelAssignment);?>
            </div>
            <div class="overview-description">
              Tugas Belum Selesai
            </div>
          </div><!-- /.col-lg-3 col-sm-6 col-xs-6 -->
          <div class="col-lg-3 col-sm-6 col-xs-6">
            <div class="overview-number overview-green">
              <?php echo count($modelQuizDone);?>
            </div>
            <div class="overview-description">
              Ujian Selesai
            </div>
          </div><!-- /.col-lg-3 col-sm-6 col-xs-6 -->
          <div class="col-lg-3 col-sm-6 col-xs-6">
            <div class="overview-number overview-yellow">
              <?php echo count($modelQuiz);?>
            </div>
            <div class="overview-description">
              Ujian Mendatang
            </div>
          </div><!-- /.col-lg-3 col-sm-6 col-xs-6 -->
        </div><!-- /.row -->
      </div><!-- /.col-card -->
    </div><!-- /.col-lg-12 -->
  </div><!-- /.row -->
  <div class="row">
    <div class="col-lg-6">
        <h3>Informasi Ujian</h3>
        <div>
            <div class="news-box">
                <div class="news-card-container">
                  
                    <?php
                      foreach($modelQuiz as $value){
                        // $this->renderPartial('v2/_card_exam_student', array(
                        //   'value'=>$value,'no'=>$no
                        // ));
                        ?>
                        <?php
                            if(!empty($value->lesson_id)){
                              if(!$value->lesson->moving_class == 1){
                                $idkelasnya = $value->lesson->class->id;
                              }else{
                                $idkelasnya = $value->lesson->grade->id;
                              }
                              
                              $cekTotalSiswa=User::model()->findAll(array('condition'=>'class_id = '.$idkelasnya));
                              if(!empty($cekTotalSiswa)){
                                $totalSiswa = count($cekTotalSiswa);
                              }else{
                                $totalSiswa = 0;
                              }
                            }else{
                              $totalSiswa = 0;
                            }

                            $cekTotalSudah=StudentQuiz::model()->findAll(array('condition'=>'quiz_id = '.$value->id.' and trash is null'));
                            $totalSudah=count($cekTotalSudah);

                            if($totalSiswa > 0){
                              $totalBelum = $totalSiswa - $totalSudah;

                              $persenSudah = round(($totalSudah/$totalSiswa) * 100, 2);
                              $persenBelum = round(($totalBelum/$totalSiswa) * 100, 2);    
                            } else {
                              $totalBelum = 0;

                              $persenSudah = 0;
                              $persenBelum = 0;
                            }
                          ?>
                          <div class="news-card">
                                                  <div class="news-card-title">
                                                      <h3>
                                                          <?php 
                                                            if(!empty($value->title)){
                                                              $judul = ucwords(strtolower($value->title));
                                                              
                                                              $judul = str_replace("Uas", "UAS", $judul);
                                                              $judul = str_replace("Uts", "UTS", $judul);
                                                              $judul = str_replace("Uh", "UH", $judul);
                                                              $judul = str_replace("Us", "US", $judul);
                                                              $judul = str_replace("bk", "BK", $judul);
                                                              
                                                              $judul = str_replace("Tp", "TP", $judul);
                                                              $judul = str_replace("To", "TO", $judul);
                                                              $judul = str_replace("Ta", "TA", $judul);

                                                              $judul = strip_tags($judul);

                                                              if (strlen($judul) > 30) {
                                                                $judulCut = substr($judul, 0, 25);
                                                                $judul = substr($judulCut, 0, strrpos($judulCut, ' ')).'...'; 
                                                              }

                                                              echo CHtml::link($judul,array('/quiz/startQuiz', 'id'=>$value->id), array('confirm' => 'Kamu yakin akan memulai ujian ini?'));
                                                            }
                                                          ?>
                                                      </h3>
                                                      <p>
                                                        <?php 
                                                        if(!empty($value->title)){
                                                          $judul = ucwords(strtolower($value->title));
                                                          
                                                          $judul = str_replace("Uas", "UAS", $judul);
                                                          $judul = str_replace("Uts", "UTS", $judul);
                                                          $judul = str_replace("Uh", "UH", $judul);
                                                          $judul = str_replace("Us", "US", $judul);
                                                          $judul = str_replace("bk", "BK", $judul);
                                                          
                                                          $judul = str_replace("Tp", "TP", $judul);
                                                          $judul = str_replace("To", "TO", $judul);
                                                          $judul = str_replace("Ta", "TA", $judul);

                                                          echo $judul;
                                                        }
                                                      ?>
                                                      </p>
                                                  </div>
                                                  <div class="news-card-content">
                                                      <div class="news-card-schedule">
                                                          <ul>
                                                              <li class="schedule-date schedule-date-near">
                                                                  <?php if($value->status==1){ ?>
                                                                    <span id="status-<?php echo $value->id ?>" class="label label-success">Ditampilkan</span>
                                                                  <?php } else if($value->status==2){ ?>
                                                                    <span id="status-<?php echo $value->id ?>" class="label label-danger">Ditutup</span>
                                                                  <?php } else { ?>
                                                                    <span id="status-<?php echo $value->id ?>" class="label label-warning">Draft</span>
                                                                  <?php } ?>
                                                              </li>
                                                              <li class="schedule-time">
                                                                  <i class="icon-pn-course"></i> 
                                                                  <?php 
                                                                      if(!empty($value->lesson_id)){
                                                                        echo ucwords($value->lesson->name);
                                                                      } 
                                                                    ?>
                                                              </li>
                                                              <li class="schedule-location">
                                                                  <i class="icon-pn-exam"></i>
                                                                    <?php 
                                                                      if(!empty($value->lesson_id)){
                                                                        if($value->lesson->moving_class == 1){
                                                                          echo strtoupper($value->lesson->grade->name);
                                                                        }else{
                                                                          echo strtoupper($value->lesson->class->name);
                                                                        } 
                                                                      } 
                                                                    ?>
                                                              </li>
                                                          </ul>
                                                      </div>
                                                  </div>
                                              </div>



                        <?php
                        $no++;
                      }
                    ?>



                </div>
            </div>
          </div>
        </div>
        <div class="col-lg-6">
            <h3>Tugas Terbaru</h3>
            <div>
                <div class="news-box">
                    <div class="news-card-container">
                      
                        <?php
                          foreach($modelAssignment as $value){
                            // $this->renderPartial('v2/_card_task_student', array(
                            //   'value'=>$value,'no'=>$no
                            // ));
                            ?>  
                            <?php
                                if(!empty($value->lesson_id)){
                                  if(!$value->lesson->moving_class == 1){
                                    $idkelasnya = $value->lesson->class->id;
                                  }else{
                                    $idkelasnya = $value->lesson->grade->id;
                                  }
                                  
                                  $cekTotalSiswa=User::model()->findAll(array('condition'=>'class_id = '.$idkelasnya));
                                  if(!empty($cekTotalSiswa)){
                                    $totalSiswa = count($cekTotalSiswa);
                                  }else{
                                    $totalSiswa = 0;
                                  }
                                }else{
                                  $totalSiswa = 0;
                                }

                                $cekTotalSudah=StudentAssignment::model()->findAll(array('condition'=>'assignment_id = '.$value->id.' and trash is null'));
                                $totalSudah=count($cekTotalSudah);

                                if($totalSiswa > 0){
                                  $totalBelum = $totalSiswa - $totalSudah;

                                  $persenSudah = round(($totalSudah/$totalSiswa) * 100, 2);
                                  $persenBelum = round(($totalBelum/$totalSiswa) * 100, 2);    
                                } else {
                                  $totalBelum = 0;

                                  $persenSudah = 0;
                                  $persenBelum = 0;
                                }

                                $cekBelumPeriksa=StudentAssignment::model()->findAll(array('condition'=>'assignment_id = '.$value->id.' and status != 1'));
                                $totalBelumPeriksa=count($cekBelumPeriksa);
                              ?>
                               <div class="news-card">
                                                          <div class="news-card-title">
                                                              <h3>
                                                                  <?php 
                                                                    if(!empty($value->title)){
                                                                      $judul = ucwords(strtolower($value->title));
                                                                      
                                                                      $judul = str_replace("Uas", "UAS", $judul);
                                                                      $judul = str_replace("Uts", "UTS", $judul);
                                                                      $judul = str_replace("Uh", "UH", $judul);
                                                                      $judul = str_replace("Us", "US", $judul);
                                                                      $judul = str_replace("bk", "BK", $judul);
                                                                      
                                                                      $judul = str_replace("Tp", "TP", $judul);
                                                                      $judul = str_replace("To", "TO", $judul);
                                                                      $judul = str_replace("Ta", "TA", $judul);

                                                                      $judul = strip_tags($judul);

                                                                      if (strlen($judul) > 25) {
                                                                        $judulCut = substr($judul, 0, 25);
                                                                        $judul = substr($judulCut, 0, strrpos($judulCut, ' ')).'...'; 
                                                                      }

                                                                      echo CHtml::link($value->title,array('/assignment/view', 'id'=>$value->id), array());
                                                                    } 
                                                                  ?>
                                                              </h3>
                                                              <p>
                                                                <?php echo $value->title ?>
                                                              </p>
                                                          </div>
                                                          <div class="news-card-content">
                                                              <div class="news-card-schedule">
                                                                  <ul>
                                                                      <li class="schedule-date schedule-date-far">
                                                                          <i class="icon-pn-course"></i> <?php echo $value->lesson->name ?>
                                                                      </li>
                                                                      <li class="schedule-time">
                                                                          <i class="icon-pn-exam"></i> 
                                                                            <?php 
                                                                            if($value->lesson->moving_class == 1){
                                                                              echo $value->lesson->grade->name; 
                                                                            }else{
                                                                              echo $value->lesson->class->name;
                                                                            }
                                                                          ?>
                                                                      </li>

                                                                  </ul>
                                                              </div>
                                                          </div>
                               </div>




                            <?php
                            $no++;
                          }
                        ?>


                    </div>
                </div>
              </div>
            </div>
      </div>
  </div>
</div>

<?php } else { ?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
        <h3>Terkini
          <small class="hidden-xs">Daftar Terbaru</small>
          <a href="<?php echo $this->createUrl('/quiz/list') ?>" class="btn btn-sm btn-pn-gray btn-pn-round pull-right">
            <span class="hidden-sm hidden-xs">SELENGKAPNYA</span>
            <span class="hidden-md hidden-lg"><i class="fa fa-chevron-right"></i></span>
          </a>
        </h3>
        <script>
          CanvasJS
            .addColorSet(
              "pinisiPercentChart", [
                "#51C553",
                "#D10025",
          ]);
        </script>        
        <div class="row">
          <?php
            foreach($modelQuiz as $value){
              // $this->renderPartial('v2/_card_exam', array(
              //   'value'=>$value,'no'=>$no
              // ));
              ?>

              <?php
                  if(!empty($value->lesson_id)){
                    if(!$value->lesson->moving_class == 1){
                      $idkelasnya = $value->lesson->class->id;
                    }else{
                      $idkelasnya = $value->lesson->grade->id;
                    }
                    
                    $cekTotalSiswa=User::model()->findAll(
                      array(
                      'condition'=>'class_id = '.$idkelasnya.' and trash is null',
                      ));
                    if(!empty($cekTotalSiswa)){
                      $totalSiswa = count($cekTotalSiswa);
                    }else{
                      $totalSiswa = 0;
                    }
                  }else{
                    $totalSiswa = 0;
                  }

                  $cekTotalSudah=StudentQuiz::model()->findAll(array('condition'=>'quiz_id = '.$value->id.' and trash is null'));
                  $totalSudah=count($cekTotalSudah);

                  if($totalSiswa > 0){
                    $totalBelum = $totalSiswa - $totalSudah;

                    $persenSudah = round(($totalSudah/$totalSiswa) * 100, 2);
                    $persenBelum = round(($totalBelum/$totalSiswa) * 100, 2);    
                  } else {
                    $totalBelum = 0;

                    $persenSudah = 0;
                    $persenBelum = 0;
                  }
                ?>
                <div class="col-md-4">
                    <div class="col-card row">
                      <div class="col-md-12">
                        <div class="row">
                          <div class="title-top-left">
                              <h3>
                                <?php 
                                  if(!empty($value->title)){
                                    $judul = ucwords(strtolower($value->title));
                                    
                                    $judul = str_replace("Uas", "UAS", $judul);
                                    $judul = str_replace("Uts", "UTS", $judul);
                                    $judul = str_replace("Uh", "UH", $judul);
                                    $judul = str_replace("Us", "US", $judul);
                                    $judul = str_replace("bk", "BK", $judul);
                                    
                                    $judul = str_replace("Tp", "TP", $judul);
                                    $judul = str_replace("To", "TO", $judul);
                                    $judul = str_replace("Ta", "TA", $judul);

                                    $judul = str_replace("Un", "UN", $judul);
                                    $judul = str_replace("Cbt", "UN", $judul);
                                    
                                    

                                    $judul = strip_tags($judul);

                                    if (strlen($judul) > 30) {
                                      $judulCut = substr($judul, 0, 25);
                                      $judul = substr($judulCut, 0, strrpos($judulCut, ' ')).'...'; 
                                    }
                                    echo $judul;
                                  } 
                                ?>
                              </h3>
                              <p>
                                <?php if($value->status==1){ ?>
                                  <span id="status-<?php echo $value->id ?>" class="label label-success">Ditampilkan</span>
                                <?php } else if($value->status==2){ ?>
                                  <span id="status-<?php echo $value->id ?>" class="label label-danger">Ditutup</span>
                                <?php } else { ?>
                                  <span id="status-<?php echo $value->id ?>" class="label label-warning">Draft</span>
                                <?php } ?>
                              </p>
                              <p>
                                <?php 
                                  if(!empty($value->lesson_id)){
                                    echo ucwords($value->lesson->name);
                                  } 
                                ?>
                                <br>
                                <?php 
                                  if(!empty($value->lesson_id)){
                                    if($value->lesson->moving_class == 1){
                                      echo strtoupper($value->lesson->grade->name);
                                    }else{
                                      echo strtoupper($value->lesson->class->name);
                                    } 
                                  } 
                                ?>
                              </p>
                          </div>
                          <?php if(!Yii::app()->user->YiiStudent){ ?>
                          <div class="toggle-top-right">
                            <div class="pn-switch">
                                <input type="checkbox" quiz-id="<?php echo $value->id;?>" name="switch<?php echo $no; ?>" class="pn-switch-checkbox" id="switch<?php echo $no; ?>" <?php echo '',$value->status == 1 ? "checked" : "" ;?>>
                                <label class="pn-switch-label" for="switch<?php echo $no; ?>"></label>
                            </div>
                          </div>
                          <?php } ?>
                        </div>
                      </div>
                      <div class="col-md-12">
                        <br>
                        <div class="chart-pie">
                          <div id="chartPieContainer<?php echo $no; ?>"
                               style="height: 200px; width: 100%;">
                          </div>
                          <div class="total" id="total-<?php echo $no; ?>"></div>
                        </div>
                        <br>
                      </div>
                      <div class="col-md-12">
                          <div class="row no-gutter row-accumulate">
                              <div class="col-md-6 col-sm-6 col-xs-6 row-accumulate-left">
                                  <div class="accumulate-score accumulate-red"><?php echo $totalBelum; ?></div>
                                  <div class="accumulate-description">
                                      Siswa <br>
                                      Belum Selesai
                                  </div>
                              </div>
                              <div class="col-md-6 col-sm-6 col-xs-6 row-accumulate-right">
                                  <div class="accumulate-score accumulate-green"><?php echo $totalSudah; ?></div>
                                  <div class="accumulate-description">
                                      Siswa <br>
                                      Sudah Selesai
                                  </div>
                              </div>
                          </div>
                      </div>
                      <div class="col-md-12">
                         <div class="row row-btn-integrated-card no-gutter">
                             <br>
                              <?php if(!Yii::app()->user->YiiStudent){ ?>
                               <?php echo CHtml::link('DETAIL',array('/quiz/view', 'id'=>$value->id), array('class'=>'btn btn-pn-primary btn-pn-card-integrated btn-lg btn-block','type'=>'button','name'=>'button')); ?>
                              <?php } else {?>
                               <?php echo CHtml::link('MULAI',array('/quiz/startQuiz', 'id'=>$value->id), array('class'=>'btn btn-pn-primary btn-pn-card-integrated btn-lg btn-block','type'=>'button','name'=>'button', 'confirm' => 'Kamu yakin akan memulai ujian ini?')); ?>
                              <?php }?>
                         </div>
                      </div>
                      <div class="clearfix"></div>
                    </div>
                </div>
                <script>
                  var pieChart<?php echo $no; ?> = new CanvasJS.Chart("chartPieContainer<?php echo $no ?>",
                  {
                    colorSet: "pinisiPercentChart",
                      axisY:{
                         valueFormatString: " ",
                         tickLength: 0
                      },
                      axisX:{
                          valueFormatString: " ",
                          tickLength: 0
                      },
                      interactivityEnabled: false,
                      data: [
                      {
                        type: "doughnut",
                        startAngle: 270,
                        dataPoints: [
                        { x: 10, y: <?php echo $persenSudah; ?>},
                        { x: 20, y: <?php echo $persenBelum; ?>}
                        ]
                      }
                    ]
                  });

                  pieChart<?php echo $no; ?>.render();
                  
                  var dps<?php echo $no; ?> = pieChart<?php echo $no; ?>.options.data[0].dataPoints;
                  document.getElementById("total-<?php echo $no; ?>").innerHTML = dps<?php echo $no; ?>[0].y + '%';

                </script>




              <?php
              $no++;
            }
          ?>
          <?php
            if(!Yii::app()->user->YiiStudent){
              // $this->renderPartial('v2/_card_blank_add_exam', array(
                
              // ));
              ?>
              <div class="col-md-4">
                <div class="col-blank col-blank-exam row">
                  <a href="<?php echo $this->createUrl('/quiz/create') ?>">
                      <i class="fa fa-plus-circle fa-3x"></i>
                      <p>
                          Tambah
                      </p>
                  </a>
                </div>
            </div>


              <?php
            }
          ?>
        </div>
        <?php if(!Yii::app()->user->YiiStudent){ ?>
        <script type="text/javascript">
          $('.pn-switch-checkbox').on("change",function(){
            $('.modal-loading').addClass("show");
            if(!$(this).prop('checked')){
              var url = "<?php echo Yii::app()->baseUrl; ?>/quiz/ajaxHideQuiz/"+$(this).attr('quiz-id');
            } else {
              var url = "<?php echo Yii::app()->baseUrl; ?>/quiz/ajaxDisplayQuiz/"+$(this).attr('quiz-id');
            }

            var idStatus = 'status-'+$(this).attr('quiz-id');
            var idQuiz = $(this).attr('quiz-id');

            $.post(url,
            {

            },
            function(data, status){
                if($('#'+idStatus).hasClass('label-success')){
                  $('#'+idStatus).removeClass('label-success');
                  $('#'+idStatus).addClass('label-danger');
                  $('#'+idStatus).html('Ditutup');
                  $('.modal-loading').removeClass("show");
                } else if ($('#'+idStatus).hasClass('label-warning')){
                  $('#'+idStatus).removeClass('label-warning');
                  $('#'+idStatus).addClass('label-success');
                  $('#'+idStatus).html('Ditampilkan');
                  $('.modal-loading').removeClass("show");
                } else if ($('#'+idStatus).hasClass('label-danger')){
                  $('#'+idStatus).removeClass('label-danger');
                  $('#'+idStatus).addClass('label-success');
                  $('#'+idStatus).html('Ditampilkan');
                  $('.modal-loading').removeClass("show");
                }
            });
          });
        </script>
        <?php } ?>
        <h3>Terkini
          <small class="hidden-xs">Daftar Terbar</small>
          <a href="<?php echo $this->createUrl('/assignment') ?>" class="btn btn-sm btn-pn-gray btn-pn-round pull-right">
            <span class="hidden-sm hidden-xs">SELENGKAPNYA</span>
            <span class="hidden-md hidden-lg"><i class="fa fa-chevron-right"></i></span>
          </a>
        </h3>
        <div class="row">
          <?php
            foreach($modelAssignment as $value){
              // $this->renderPartial('v2/_card_task', array(
              //   'value'=>$value,'no'=>$no
              // ));
              ?>
              <?php
                    if(!empty($value->lesson_id)){
                      if(!$value->lesson->moving_class == 1){
                        $idkelasnya = $value->lesson->class->id;
                      }else{
                        $idkelasnya = $value->lesson->grade->id;
                      }
                      
                      $cekTotalSiswa=User::model()->findAll(array('condition'=>'class_id = '.$idkelasnya));
                      if(!empty($cekTotalSiswa)){
                        $totalSiswa = count($cekTotalSiswa);
                      }else{
                        $totalSiswa = 0;
                      }
                    }else{
                      $totalSiswa = 0;
                    }

                    $cekTotalSudah=StudentAssignment::model()->findAll(array('condition'=>'assignment_id = '.$value->id));
                    $totalSudah=count($cekTotalSudah);

                    if($totalSiswa > 0){
                      $totalBelum = $totalSiswa - $totalSudah;

                      $persenSudah = round(($totalSudah/$totalSiswa) * 100, 2);
                      $persenBelum = round(($totalBelum/$totalSiswa) * 100, 2);    
                    } else {
                      $totalBelum = 0;

                      $persenSudah = 0;
                      $persenBelum = 0;
                    }

                    $cekBelumPeriksa=StudentAssignment::model()->findAll(array('condition'=>'assignment_id = '.$value->id.' and status != 1'));
                    $totalBelumPeriksa=count($cekBelumPeriksa);
                  ?>
                  <div class="col-md-4">
                      <div class="col-card row">
                        <div class="col-md-12">
                          <div class="row">
                            <div class="title-top-left">
                                <h3>
                                  <?php 
                                    if(!empty($value->title)){
                                      $judul = ucwords(strtolower($value->title));
                                      
                                      $judul = str_replace("Uas", "UAS", $judul);
                                      $judul = str_replace("Uts", "UTS", $judul);
                                      $judul = str_replace("Uh", "UH", $judul);
                                      $judul = str_replace("Us", "US", $judul);
                                      $judul = str_replace("bk", "BK", $judul);
                                      
                                      $judul = str_replace("Tp", "TP", $judul);
                                      $judul = str_replace("To", "TO", $judul);
                                      $judul = str_replace("Ta", "TA", $judul);

                                      $judul = strip_tags($judul);

                                      if (strlen($judul) > 30) {
                                        $judulCut = substr($judul, 0, 25);
                                        $judul = substr($judulCut, 0, strrpos($judulCut, ' ')).'...'; 
                                      }
                                      echo $judul;
                                    } 
                                  ?>                
                                </h3>
                                <p>
                                    <?php echo $value->lesson->name ?>
                                    <br>
                                    <?php 
                                      if($value->lesson->moving_class == 1){
                                        echo $value->lesson->grade->name; 
                                      }else{
                                        echo $value->lesson->class->name;
                                      }
                                    ?>
                                </p>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-12">
                          <br>
                          <div class="chart-pie">
                            <div id="chartPieContainer<?php echo $no; ?>"
                                 style="height: 200px; width: 100%;">
                            </div>
                            <div class="total" id="total-<?php echo $no; ?>">300</div>
                          </div>
                          <br>
                        </div>
                        <div class="col-md-12">
                            <div class="row no-gutter row-accumulate">
                                <div class="col-md-12 col-sm-12 col-xs-12 row-accumulate-middle">
                                    <div class="accumulate-score accumulate-yellow"><?php echo $totalBelumPeriksa; ?></div>
                                    <div class="accumulate-description">
                                        Tugas
                                        Belum Diperiksa
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 row-accumulate-left">
                                    <div class="accumulate-score accumulate-red"><?php echo $totalBelum; ?></div>
                                    <div class="accumulate-description">
                                        Siswa <br>
                                        Belum Selesai
                                    </div>
                                </div>
                                <div class="col-md-6 col-sm-6 col-xs-6 row-accumulate-right">
                                    <div class="accumulate-score accumulate-green"><?php echo $totalSudah; ?></div>
                                    <div class="accumulate-description">
                                        Siswa <br>
                                        Sudah Selesai
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                           <div class="row row-btn-integrated-card no-gutter">
                               <br>
                               <?php echo CHtml::link('DETAIL',array('/assignment/view', 'id'=>$value->id), array('class'=>'btn btn-pn-primary btn-pn-card-integrated btn-lg btn-block','type'=>'button','name'=>'button')); ?>
                           </div>
                        </div>
                        <div class="clearfix"></div>
                      </div>
                  </div>
                  <script>
                    var pieChart<?php echo $no; ?> = new CanvasJS.Chart("chartPieContainer<?php echo $no ?>",
                    {
                      colorSet: "pinisiPercentChart",
                        axisY:{
                           valueFormatString: " ",
                           tickLength: 0
                        },
                        axisX:{
                            valueFormatString: " ",
                            tickLength: 0
                        },
                        interactivityEnabled: false,
                        data: [
                        {
                          type: "doughnut",
                          startAngle: 270,
                          dataPoints: [
                          { x: 10, y: <?php echo $persenSudah; ?>},
                          { x: 20, y: <?php echo $persenBelum; ?>}
                          ]
                        }
                      ]
                    });

                    pieChart<?php echo $no; ?>.render();
                    
                    var dps<?php echo $no; ?> = pieChart<?php echo $no; ?>.options.data[0].dataPoints;
                    document.getElementById("total-<?php echo $no; ?>").innerHTML = dps<?php echo $no; ?>[0].y + '%';

                  </script>



              <?php
              $no++;
            }
          ?>
          <?php
            if(!Yii::app()->user->YiiStudent){
              // $this->renderPartial('v2/_card_blank_add_task', array(
                
              // ));
              ?>

              <div class="col-md-4">
                <div class="col-blank col-blank-task row">
                  <a href="<?php echo $this->createUrl('/assignment/create') ?>">
                      <i class="fa fa-plus-circle fa-3x"></i>
                      <p>
                          Tambah
                      </p>
                  </a>
                </div>
            </div>

              <?php
            }
          ?>
        </div>
      </div>
  </div>
</div>
<?php } ?>
<script>
  url_data  = "<?php echo $this->createUrl('/site/cekData') ?>";
  url_quiz  = "<?php echo $this->createUrl('/quiz/startQuiz') ?>";
  url_notif = "<?php echo $this->createUrl('/notification/index') ?>";

  var ajax_call_2 = function() {
    url_load = "<?php echo $this->createUrl('/site/autoNotif2') ?>";
    $.ajax({
      url: url_load,
      type: "POST",
      success: function(msg){
        if(msg > 0){
          if($("#dg").length == 0){
            $("#notif-flash").append('<div id="dg" class="alert alert-info" role="alert"><a href="'+url_notif+'">Ada '+msg+' Notifikasi Baru</a></div>');
          }
        }
      }
    });
  };
  
  var interval = 1000 * 60 * 0.1;
  setInterval(ajax_call_2, interval);
  $('#sinkron').click(function(){
    $("#loading-indicator").show(0).delay(5000).hide(0);
  });


  console.log(localStorage);

  if(typeof(localStorage) !== "undefined") {

  } else {
     alert ("Peringatan, borwser anda tidak support untuk penyimpanan sementara, silahkan hubungi administrator.");
  }

  if(typeof(localStorage.questionsundefinedqid) !== "undefined"){

    if(localStorage["questionsundefinedStudentQuiz[student_id]"]!=<?php echo Yii::app()->user->id;?>){
        localStorage.clear();
      }

    $.ajax({
      url: url_data,
      type: "POST",
      data: {quiz:localStorage.questionsundefinedqid},
      success: function(msg){
         if(msg == 2){
          console.log(msg);

          $("#alrt").removeClass('hidden');
          $("#lanjut").attr("href",url_quiz+"/"+localStorage.questionsundefinedqid);
         } else if (msg == 1) {
          console.log(msg);
          localStorage.clear();
         }
      }
    });
  }

  $("#hapusData").click(function(){
    localStorage.clear();
  });
</script>
