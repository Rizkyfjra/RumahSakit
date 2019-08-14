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
