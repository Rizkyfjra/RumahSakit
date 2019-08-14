<?php 
    $online = @Option::model()->findByAttributes(array('key_config' => 'online'))->value; 
    $online_esemka = @Option::model()->findByAttributes(array('key_config' => 'online_esemka'))->value;
?>
<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_exam_list', array(
        
      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
      <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Ujian</div>',array('/quiz/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>List</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
        <h3>Ujian
          <small class="hidden-xs">Daftar Ujian</small>
          <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
          <div class="pull-right">
            <a href="<?php echo $this->createUrl('/quiz/create') ?>" class="btn btn-sm btn-pn-primary btn-pn-round">
              <span class="hidden-sm hidden-xs"><i class="fa fa-plus-circle"></i> TAMBAH UJIAN</span>
              <span class="hidden-md hidden-lg"><i class="fa fa-plus-circle"></i></span>
            </a>
          </div>
          <?php } ?>
        </h3>
        <form action="<?php echo Yii::app()->createUrl("/quiz/showAll") ?>" method="post" onsubmit="return confirm('Yakin Melakukan Aksi Ini Untuk Semua Yang Dipilih?');">         
          <div class="row">
            <div class="col-md-12">
              <div class="col-card">
                <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
                <div class="row">
                  <div class="col-md-12">
                    <div class="btn-group pull-right">
                      <input type="submit" name="tampil" value="Tampilkan" class="btn btn-pn-primary">
                      <?php if ($online_esemka) { ?>  
                      <input type="submit" name="sync" value="Sync" class="btn btn-info">
                      <?php }?>
                      <input type="submit" name="tutup" value="Tutup" class="btn btn-warning">
                      <input type="submit" name="hapus" value="Hapus" class="btn btn-danger">
                    </div>
                  </div>
                </div>
                <?php } ?>          
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th>No.</th>
                        <th>Nama Ujian</th>
                        <th>Status Ujian</th>
                        <?php if(!Yii::app()->user->YiiStudent and !$online){ ?>
                        <th>Selesai</th>
                        <th>Belum Selesai</th>
                        <?php } ?>
                        <th>Nama Pelajaran</th>
                        <th>Nama Kelas</th>
                        <th>Guru</th>
                        <?php if ($online_esemka) { ?>
                        <th width="10%">Sync</th>
                        <?php }?>
                        <th width="10%">Aksi</th>
                        <?php if(!Yii::app()->user->YiiStudent){ ?>                        
                        <th width="5%">
                          <div class="text-center">
                            <input type="checkbox" id="selectAll">
                          </div>
                        </th>
                        <?php } ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php
                        if(!empty($dataProvider->getData())){
                          $kuis = $dataProvider->getData();

                          if(empty($_GET['Quiz_page'])){
                            $no = 1;
                          }else{
                            $no = 1 + (($_GET['Quiz_page']-1) * 5);
                          }

                          foreach ($kuis as $value) {
                              // $this->renderPartial('v2/_table_exam_admin_list', array(
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
                                  
                                  $cekTotalSiswa=User::model()->findAll(array('condition'=>'class_id = '.$idkelasnya.' and trash is null'));
                                  $totalSiswa=count($cekTotalSiswa);
                                }else{
                                  $totalSiswa = 0;
                                }

                                if(!Yii::app()->user->YiiStudent and !$online){ 
                                  $cekTotalSudah=StudentQuiz::model()->findAll(array('condition'=>'quiz_id = '.$value->id.' and trash is null'));
                                  $totalSudah=count($cekTotalSudah);

                                  if($totalSiswa != 0){
                                    $totalBelum = $totalSiswa - $totalSudah;
                                  } else {
                                    $totalBelum = 0;
                                  }

                                  if($totalSiswa!=0){
                                    $persenSudah = round(($totalSudah/$totalSiswa) * 100, 2);
                                    $persenBelum = round(($totalBelum/$totalSiswa) * 100, 2);
                                  }else{
                                    $persenSudah = 0;
                                    $persenBelum = 0;
                                  }
                                }

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
                                }
                              ?>
                              <tr>
                                <th class="text-center"><?php echo $no; ?></th>
                                <td class="collapsible-row-container">
                                  <?php echo CHtml::link(CHtml::encode($judul), array('/quiz/view', 'id'=>$value->id)); ?>
                                </td>
                                <td>
                                  <?php if($value->status==1){ ?>
                                    <span id="status-<?php echo $value->id ?>" class="label label-success">Ditampilkan</span>
                                  <?php } else if($value->status==2){ ?>
                                    <span id="status-<?php echo $value->id ?>" class="label label-danger">Ditutup</span>
                                  <?php } else { ?>
                                    <span id="status-<?php echo $value->id ?>" class="label label-warning">Draft</span>
                                  <?php } ?>
                                </td>
                                <?php if(!Yii::app()->user->YiiStudent and !$online){ ?>
                                <td>
                                  <span class="text-green text-bold"><?php echo $persenSudah; ?>%</span>
                                </td>
                                <td>
                                  <span class="text-red text-bold"><?php echo $persenBelum; ?>%</span>
                                </td>
                                <?php } ?>
                                <td>
                                  <?php 
                                    if(!empty($value->lesson_id)){
                                      echo ucwords($value->lesson->name);
                                    } 
                                  ?>
                                </td>
                                <td>
                                  <?php 
                                    if(!empty($value->lesson_id)){
                                      if($value->lesson->moving_class == 1){
                                        echo strtoupper($value->lesson->grade->name);
                                      }else{
                                        echo strtoupper($value->lesson->class->name);
                                      } 
                                    } 
                                  ?>
                                </td>
                                <td>
                                  <?php
                                    if(!empty($value->created_by)){
                                      echo $value->teacher->display_name;
                                    }
                                  ?>
                                </td>
                                <?php if ($online_esemka) { ?>
                                <td>
                                  <?php if($value->sync_status==1){ ?>
                                    Sudah Sinkron
                                  <?php } else if($value->status==2){ ?>
                                    Belum Sinkron
                                  <?php } else { ?>
                                    Belum Sinkron
                                  <?php } ?>
                                </td>
                                <?php }?>
                                <?php if(!Yii::app()->user->YiiStudent){ ?>
                                <td>
                                  <div class="btn-group">
                                    <?php echo CHtml::link('<i class="fa fa-eye"></i>',array('view', 'id'=>$value->id), array('class'=>'btn btn-success btn-xs')); ?>
                                    <?php
                                        if(!$online && ($value->quiz_type == 4 || $value->quiz_type == 5 || $value->quiz_type == 6)){
                                            echo "";
                                        }else{
                                            echo CHtml::link('<i class="fa fa-pencil"></i>',array('update', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs'));
                                        }?>
                                    <?php echo CHtml::link('<i class="fa fa-trash"></i>',array('hapus', 'id'=>$value->id), array('class'=>'btn btn-danger btn-xs','title'=>'Hapus','confirm'=>'Yakin Menghapus Ujian Ini?')); ?>
                                  </div>
                                </td>
                                <td>
                                  <div class="text-center">
                                    <input type="checkbox" name="quiz[]" class="quiz" value="<?php echo $value->id ?>">
                                  </div>
                                </td>
                                <?php
                                  }else{
                                    $cekQuiz = StudentQuiz::model()->findByAttributes(array('quiz_id'=>$value->id,'student_id'=>Yii::app()->user->id));

                                    if(!empty($cekQuiz)){
                                      if($cekQuiz->attempt == $value->repeat_quiz){      
                                ?>
                                <td>
                                  <div class="btn-group">
                                    <?php echo CHtml::link('<i class="fa fa-times"></i> Sudah Mengerjakan',array('#'), array('class'=>'btn btn-danger btn-xs')); ?>
                                  </div>
                                </td>
                                <?php
                                      }else{
                                        if($value->status == 1){          
                                ?>
                                <td>
                                  <div class="btn-group">
                                    <?php echo CHtml::link('<i class="fa fa-pencil"></i> Mulai',array('startQuiz', 'id'=>$value->id, 'sq'=>$cekQuiz->id), array('class'=>'btn btn-pn-primary btn-xs')); ?>
                                  </div>
                                </td>
                                <?php
                                        }
                                      }
                                    }else{
                                      if($model->status == 1){        
                                ?>
                                <td>
                                  <div class="btn-group">
                                    <?php echo CHtml::link('<i class="fa fa-pencil"></i> Mulai',array('startQuiz', 'id'=>$value->id), array('class'=>'btn btn-pn-primary btn-xs')); ?>
                                  </div>
                                </td>
                                <?php
                                      }
                                    }
                                  }
                                ?>
                              </tr>



                            <?php
                              $no++;
                          }
                        }
                      ?>
                    </tbody>
                  </table>
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
        </form>
      </div>
    </div>
  </div>
</div>
<script>
  $('#selectAll').click(function(event) {
      if(this.checked) {
          $('.quiz').each(function() {
              this.checked = true;
          });
      }else{
          $('.quiz').each(function() {
              this.checked = false;
          });         
      }
  });
</script>