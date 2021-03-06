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
