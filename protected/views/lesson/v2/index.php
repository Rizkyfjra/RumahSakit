<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_course', array(
        
      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
      <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Pelajaran</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
      <h3>Data
          <small>Daftar Obat</small>

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
         <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>ID</th>
                        <th>Nama Obat</th>
                        <th>Kelas</th>
                        <th>Apotiker</th>
                        <th>Status Sync</th>
                        <th>Aksi</th>
                        <th></th>
                      </tr>
                    </thead>
                    <tbody>
        <?php
          if(!empty($dataProvider->getData())){
            $mapel = $dataProvider->getData();

            if(empty($_GET['Lesson_page'])){
              $no = 1;
            }else{
              $no = 1 + (($_GET['Lesson_page']-1) * 6);
            }

            foreach ($mapel as $value) {
              // $this->renderPartial('v2/_card_course', array(
              //   'value'=>$value, 'no'=>$no
              // ));
              ?>
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
                 
                   <tr>
                                <th class="text-center"><?php echo $no; ?></th>
                                <td class="collapsible-row-container"><?php echo $value->id; ?></td>
                                <td class="collapsible-row-container"><a href="<?php echo $lsn_url;?>"><?php echo $value->name; ?></a></td>
                                <td class="collapsible-row-container"><?php echo $nama_kelas; ?></td>
                                <td class="collapsible-row-container"><?php
                                 if(!empty($cekGuru)){ 
                                  echo $cekGuru[0]->display_name;
                                 } else {
                                  print_r($cekGuru);
                                 }
                                 ?></td>

                                  <td class="collapsible-row-container"><?php
                                 if($value->sync_status == 1){ 
                                  echo "ditutup";
                                 } else {
                                  echo "dibuka";
                                 }
                                 ?></td>

                                 <td>
                                                 <div class="btn-group">
                         
                          <?php echo CHtml::link('<i class="fa fa-eye"></i> Buka Sync',array('api/ResetBylesson?lesson_id='.$value->id), array('class'=>'btn btn-warning btn-xs')); ?>
                          <!-- <?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('Buka Sync', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?> -->

                         
                        </div>
                      
                                     <div class="btn-group">
                          

                          <?php echo CHtml::link('<i class="fa fa-eye"></i> Tutup Sync',array('api/CloseBylesson?lesson_id='.$value->id), array('class'=>'btn btn-danger btn-xs')); ?>
                          <!-- <?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('Tutup Sync', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?> -->
                        </div>

                                 </td>
                   </tr>             
                   
                 
              <?php
              $no++;
            }
          }
        ?>
         </tbody>
        </table>  
        </div>
        <div class="clearfix"></div>
        <div class="text-center">
          <?php
            $this->widget('CLinkPager', array(
                          'pages'=>$dataProvider->pagination,
                          ));
          ?>
        </div>
      </div>
    </div>
  </div>
</div>
