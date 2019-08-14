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

  $cekTotalSudah=StudentQuiz::model()->findAll(array('condition'=>'quiz_id = '.$value->id));
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
