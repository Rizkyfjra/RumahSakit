<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_task', array(
        
      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
      <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Tugas</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
        <h3>Terkini
          <small class="hidden-xs">Daftar Data Terbaru</small>
          <div class="pull-right">
            <a href="<?php echo $this->createUrl('/assignment/list') ?>" class="btn btn-sm btn-pn-gray btn-pn-round">
              <span class="hidden-sm hidden-xs"><i class="fa fa-list"></i> LIHAT SEMUA</span>
              <span class="hidden-md hidden-lg"><i class="fa fa-list"></i></span>
            </a>
            <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
            <a href="<?php echo $this->createUrl('/clases') ?>" class="btn btn-sm btn-pn-primary btn-pn-round">
              <span class="hidden-sm hidden-xs"><i class="fa fa-plus-circle"></i>TAMBAH</span>
              <span class="hidden-md hidden-lg"><i class="fa fa-plus-circle"></i></span>
            </a>
            <?php } ?>
          </div>
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
            if(!empty($dataProvider->getData())){
              $kuis = $dataProvider->getData();

              $no = 1;
              foreach ($kuis as $value) {
                // $this->renderPartial('v2/_card_task', array(
                //   'value'=>$value, 'no'=>$no
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
</div>
