<?php
  $list_siswa = LessonMc::model()->findAll(array('condition'=>'lesson_id = '.$value->id));
  
  $list_big = '';
  $final_list = array();
  
  if(!empty($list_siswa)){
    foreach ($list_siswa as $ls) {
      array_push($final_list, $ls->user_id);
    }
    $list_big = implode(',', $final_list);
  }
  
  if(!empty($list_big)){
    $cek=User::model()->findAll(array('condition'=>'class_id IN ('.$list_big.')'));
  }else{
    $cek=NULL;
  }

  if($value->moving_class == 1){
    $nama_kelas = $value->grade->name;
  }else{
    $nama_kelas = $value->class->name;
  }

  if(!empty($value->user_id)){
    $cekGuru=User::model()->findAll(array('condition'=>'id = '.$value->user_id));
  }else{
    $cekGuru=NULL;
  }
  
  $lsn_url = Yii::app()->createUrl('/lesson/view/'.$value->id);
  if(Yii::app()->user->YiiStudent){
    $lsn_url = $lsn_url."?type=materi";
  }
  $total=count($list_siswa);
?>
<div class="col-md-4">
    <div class="col-card row">
      <div class="col-md-12">
        <div class="row">
          <div class="title-top-left" style="min-height:180px">
              <h3><?php echo $value->name; ?></h3>
              <br style="clearfix"/>              
              <h5><strong><?php echo $nama_kelas; ?></strong></h5>
              <p><?php echo $cekGuru[0]->display_name; ?></p>
              <!-- <p>
                  <strong>Senin</strong> 09:00 - 10:30
                  <br>
                  <strong>Rabu</strong> 06:45 - 08:15
              </p> -->
          </div>
        </div>
      </div>
      <!-- <div class="col-md-12">
        <br>
        <div class="chart-pie">
          <div id="chartPieContainer<?php echo $no; ?>"
              style="height: 200px; width: 100%;">
          </div>
          <div class="total" id="total-<?php echo $no; ?>"></div>
        </div>
        <br>
      </div> -->
      <!-- <div class="col-md-12">
          <div class="row no-gutter row-accumulate">
              <div class="col-md-6 col-sm-6 col-xs-6 row-accumulate-left">
                  <div class="accumulate-score accumulate-red">40</div>
                  <div class="accumulate-description">
                      Siswa
                  </div>
              </div>
              <div class="col-md-6 col-sm-6 col-xs-6 row-accumulate-right">
                  <div class="accumulate-score accumulate-green">5</div>
                  <div class="accumulate-description">
                      Aktifitas
                  </div>
              </div>
          </div>
      </div> -->
      <div class="col-md-12">
         <div class="row row-btn-integrated-card no-gutter">
             <br>
             <a href="<?php echo $lsn_url;?>" type="button" class="btn btn-pn-primary btn-pn-card-integrated btn-lg btn-block" name="button">
                 DETAIL
             </a>
         </div>
      </div>
      <div class="clearfix"></div>
    </div>
</div>
<script>
  // var pieChart<?php echo $no; ?> = new CanvasJS.Chart("chartPieContainer<?php echo $no ?>",
  // {
  //   colorSet: "pinisiPercentChart",
  //     axisY:{
  //        valueFormatString: " ",
  //        tickLength: 0
  //     },
  //     axisX:{
  //         valueFormatString: " ",
  //         tickLength: 0
  //     },
  //     interactivityEnabled: false,
  //     data: [
  //     {
  //       type: "doughnut",
  //       startAngle: 270,
  //       dataPoints: [
  //       { x: 10, y: <?php //echo $persenSudah; ?>},
  //       { x: 20, y: <?php //echo $persenBelum; ?>}
  //       ]
  //     }
  //   ]
  // });

  // pieChart<?php echo $no; ?>.render();
  
  // var dps<?php echo $no; ?> = pieChart<?php echo $no; ?>.options.data[0].dataPoints;
  // document.getElementById("total-<?php echo $no; ?>").innerHTML = dps<?php echo $no; ?>[0].y + '%';
</script>
