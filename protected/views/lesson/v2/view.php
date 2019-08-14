<?php
  $copy_url = null;

  $fiturUlangan = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_ulangan%"'));
  $fiturTugas = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_tugas%"'));
  $fiturMateri = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_materi%"'));
  $fiturRekap = Option::model()->findAll(array('condition'=>'key_config LIKE "%fitur_rekap%"'));

  if($model->moving_class == 1){
    $nama_kelas = $model->grade->name;
  }else{
    $nama_kelas = $model->class->name; 
  }

  if(!empty($model->user_id)){
    $cekGuru=User::model()->findAll(array('condition'=>'id = '.$model->user_id));
  }else{
    $cekGuru=NULL;
  }
?>
<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_course_detail', array(
      //   'model'=>$model
      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
        <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>Pelajaran</div>',array('/lesson/index'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>'.CHtml::encode($model->name).'</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
        <h3>
          <?php echo $model->name ?> <?php echo $nama_kelas ?>, Semester <?php echo $model->semester ?>
          <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
          <div class="btn-group pull-right">
            <a href="<?php echo $this->createUrl('/lesson/NilaiRapor/'.$model->id) ?>" class="btn btn-pn-primary"><i class="fa fa-book"></i> Rekap Nilai & Absen</a>
            <a href="<?php echo $this->createUrl('/lesson/update/'.$model->id) ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
            <a href="<?php echo $this->createUrl('/lesson/hapus/'.$model->id) ?>" class="btn btn-danger"><i class="fa fa-trash"></i></a>
          </div>
          <?php } ?>
        </h3>
        <p><?php echo $cekGuru[0]->display_name; ?></p>
        <div class="row">
          <div class="col-md-3">
            <div class="row">
              <div class="col-md-12">
                <div class="col-card">
                  <ul class="nav nav-pills nav-stacked">
                    <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
                    <li role="presentation" class="<?php echo '',($type=="siswa" || $type==NULL) ? 'active' : '' ?>">
                      <?php echo CHtml::link('Siswa', array('view','id'=>$model->id,'type'=>'siswa')); ?>
                    </li>
                    <?php } ?>
                    <?php
                      if(empty($fiturMateri) || $fiturMateri[0]->value != 2){ 
                    ?>
                    <li role="presentation" class="<?php echo '',($type=="materi") ? 'active' : '' ?>">
                      <?php echo CHtml::link('Materi', array('view','id'=>$model->id,'type'=>'materi')); ?>
                    </li>
                    <?php
                      }
                      if(empty($fiturTugas) || $fiturTugas[0]->value != 2){ 
                    ?>
                    <li role="presentation" class="<?php echo '',($type=="tugas") ? 'active' : '' ?>">
                      <?php echo CHtml::link('Tugas', array('view','id'=>$model->id,'type'=>'tugas')); ?>
                    </li>
                    <?php
                      }
                      if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){                       
                    ?>
                    <li role="presentation" class="<?php echo '',($type=="ulangan") ? 'active' : '' ?>">
                      <?php echo CHtml::link('Ujian', array('view','id'=>$model->id,'type'=>'ulangan')); ?>
                    </li>
                    <?php
                      }
                      if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){
                        if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){
                    ?>
                    <li role="presentation" class="<?php echo '',($type=="banks") ? 'active' : '' ?>">
                      <?php echo CHtml::link('Bank Soal', array('view','id'=>$model->id,'type'=>'banks')); ?>
                    </li>
                    <?php
                        }
                      }
                      if(empty($fiturRekap) || $fiturRekap[0]->value != 2){
                        if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){
                    ?>
                    <li role="presentation" class="<?php echo '',($type=="nilai") ? 'active' : '' ?>">
                      <?php echo CHtml::link('Penilaian', array('view','id'=>$model->id,'type'=>'nilai')); ?>
                    </li>
                    <?php
                        }
                      }
                    ?>
                  </ul>
                </div>
              </div>
              <!-- <div class="col-md-12">
                <div class="col-card row">
                  <div class="title-top-left pull-left">
                      <h4>Siswa</h4>
                  </div>
                  <div class="title-top-right pull-left">
                      <a class="btn-link-pointer" data-toggle="modal" data-target="#newStudent" style="cursor:pointer"><i class="fa fa-plus-circle"></i> Tambah Siswa</a>
                  </div>
                  <div class="title-top-right pull-right">
                      <a class="btn-link-pointer" data-toggle="modal" data-target="#copyExcel" style="cursor:pointer"><i class="fa fa-plus-circle"></i> Tambah Siswa (Excel)</a>
                  </div>
                  <div class="clearfix"></div>
                  <hr style="margin-top: 0;">
                  <div class="row">
                    <?php
                      foreach($datasis as $siswa){
                        if($siswa->student->image){
                          $foto = Yii::app()->baseUrl.'/images/user/'.$siswa->user_id.'/'.$siswa->student->image;
                        }else{
                          $foto = Yii::app()->baseUrl.'/images/user-2.png';
                        }
                        $link = "/user/view/".$siswa->user_id;
                    ?>
                    <div class="col-xs-4">
                      <a href="<?php echo $this->createUrl($link) ?>" class="thumbnail-plain">
                           <img src="<?php echo $foto ?>" class="img-responsive">
                      </a>
                    </div>
                    <?php
                      }
                    ?>
                  </div>
                </div>
              </div> -->
            </div>
          </div>
          <div class="col-md-9">
            <div class="col-card">
              <?php
                if($type == "materi"){
                  if(empty($fiturMateri) || $fiturMateri[0]->value != 2){
              ?>
              <h4><strong>Materi</strong>
                <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
                <div class="btn-group pull-right">
                  <a href="<?php echo $this->createUrl('/chapters/create?lesson_id='.$model->id) ?>" class="btn btn-pn-primary"><i class="fa fa-plus"></i> Tambah Materi</a>
                </div>
                <?php } ?>
              </h4>
              <hr>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th width="10%">No.</th>
                      <th>Nama Materi</th>
                      <?php if (!Yii::app()->user->YiiStudent) { ?>
                      <th width="10%">Aksi</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if(!empty($datas)){
                        $no = 1;
                        foreach ($datas as $mtr) {
                    ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo CHtml::link($mtr->title, array('/chapters/view','id'=>$mtr->id)) ?></td>
                      <?php if (!Yii::app()->user->YiiStudent) { ?>
                      <td>
                        <div class="btn-group">
                          <?php echo CHtml::link('<i class="fa fa-trash"></i> Hapus Materi',array('/chapters/hapus', 'id'=>$mtr->id), array('class'=>'btn btn-danger btn-xs','title'=>'Hapus','confirm'=>'Yakin Menghapus Materi Ini?')); ?>
                        </div>
                      </td>
                      <?php } ?>
                    </tr>
                    <?php
                          $no++;
                        }
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <?php 
                  }  
                }elseif($type == "tugas"){
                  if(empty($fiturTugas) || $fiturTugas[0]->value != 2){
              ?>
              <h4><strong>Tugas</strong>
                <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
                <div class="btn-group pull-right">
                  <a href="<?php echo $this->createUrl('/assignment/create?lesson_id='.$model->id) ?>" class="btn btn-pn-primary"><i class="fa fa-plus"></i> Tambah Tugas</a>
                  <a href="<?php echo $this->createUrl('/assignment/create?type=1&lesson_id='.$model->id) ?>" class="btn btn-primary"> Praktek / Offline</a>
                  <a href="<?php echo $this->createUrl('/skill/create?lesson_id='.$model->id) ?>" class="btn btn-warning"> Ketrampilan</a>
                </div>
                <?php } ?>
              </h4>
              <hr>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th width="10%">No.</th>
                      <th>Nama Tugas</th>
                      <?php if (!Yii::app()->user->YiiStudent) { ?>
                      <th width="10%">Aksi</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if(!empty($datas)){
                        $no = 1;
                        foreach ($datas as $mtr) {
                    ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo CHtml::link($mtr->title, array('/assignment/view','id'=>$mtr->id)) ?></td>
                      <?php if (!Yii::app()->user->YiiStudent) { ?>
                      <td>
                        <div class="btn-group">
                          <?php echo CHtml::link('<i class="fa fa-trash"></i> Hapus Tugas',array('/assignment/hapus', 'id'=>$mtr->id), array('class'=>'btn btn-danger btn-xs','title'=>'Hapus','confirm'=>'Yakin Menghapus Tugas Ini?')); ?>
                        </div>
                      </td>
                      <?php } ?>
                    </tr>
                    <?php
                          $no++;
                        }
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <?php
                  }
                }elseif($type == "ulangan"){
                  if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){
              ?>
              <h4><strong>Ujian</strong>
                <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
                <div class="btn-group pull-right">
                  <a href="#" data-toggle="modal" data-target="#importSoal" class="btn btn-primary"><i class="fa fa-plus"></i> IMPORT UJIAN</a>
                  <a href="<?php echo $this->createUrl('/quiz/create?lesson_id='.$model->id) ?>" class="btn btn-pn-primary"><i class="fa fa-plus"></i> Tambah Ujian</a>
                </div>
                <?php } ?>
              </h4>
              <hr>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th width="10%">No.</th>
                      <th>Nama Ujian</th>
                      <?php if (!Yii::app()->user->YiiStudent) { ?>
                      <th width="10%">Aksi</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if(!empty($datas)){
                        $no = 1;
                        foreach ($datas as $mtr) {
                    ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo CHtml::link($mtr->title, array('/quiz/view','id'=>$mtr->id)) ?></td>
                      <?php if (!Yii::app()->user->YiiStudent) { ?>
                      <td>
                        <div class="btn-group">
                          <?php echo CHtml::link('<i class="fa fa-trash"></i> Hapus Ulangan',array('/quiz/hapus', 'id'=>$mtr->id), array('class'=>'btn btn-danger btn-xs','title'=>'Hapus','confirm'=>'Yakin Menghapus Ulangan Ini?')); ?>
                        </div>
                      </td>
                      <?php } ?>
                    </tr>
                    <?php
                          $no++;
                        }
                      }
                    ?>
                  </tbody>
                </table>
              </div>
              <?php
                  }
                }elseif($type == "banks"){
                  if(empty($fiturUlangan) || $fiturUlangan[0]->value != 2){
                    if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){
              ?>
              <form action="<?php echo Yii::app()->createUrl('/questions/bulkBanks') ?>" method="post" onsubmit="return confirm('Yakin Melakukan Aksi Ini Terhadap Soal Yang Dipilih?');"> 
                <h4><strong>Bank Soal</strong>
                  <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
                  <div class="btn-group pull-right">
                    <a href="<?php echo $this->createUrl('/questions/create') ?>" class="btn btn-pn-primary"><i class="fa fa-plus"></i> Tambah Soal</a>|
                    <a href="<?php echo $this->createUrl('/questions/bulkxml') ?>" class="btn btn-pn-primary"><i class="fa fa-plus"></i> Tambah Soal XML</a>
                    <input type="button" id="shareModal" name="shareModal" value="Bagikan" class="btn btn-primary">
                    <input type="submit" id="delete" name="delete" value="Hapus" class="btn btn-danger">
                  </div>
                  <?php } ?>
                </h4>
                <hr>
                <div class="exam-list-table">
                  <div class="table-responsive">
                    <table class="table table-hover">
                      <thead>
                        <tr>
                          <th>No.</th>
                          <th>Pertanyaan</th>
                          <th width="15%">Tipe Pertanyaan</th>
                          <?php if (!Yii::app()->user->YiiStudent) { ?>                          
                          <th width="20%">Status Bagikan</th>
                          <th width="10%">Aksi</th>
                          <th width="5%">
                            <div class="text-center">
                              <input type="checkbox" id="selectAllBanks">
                            </div>
                          </th>
                          <?php } ?>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          if(!empty($dataProvider->getData())){
                            $questions = $dataProvider->getData();

                            if(empty($_GET['Questions_page'])){
                              $no = 1;
                            }else{
                              $no = 1 + (($_GET['Questions_page']-1) * 15);
                            }

                            foreach ($questions as $value) {
                              $choices = json_decode($value->choices);
                              $jumChoices = count($choices);
                        ?>
                        <tr>
                          <td><?php echo $no ?></td>
                          <td class="collapsible-row-container">
                            <div class="collapsible-row">
                              <div>
                                <?php echo $value->text; ?>
                              </div>
                              <br/>
                              <div class="clearfix"></div>
                              <div class="exam-answer">
                                <h4>Pilihan Jawaban</h4>
                                <?php
                                  for($i=0; $i<$jumChoices; $i++){
                                    if($choices[$i]==$value->key_answer){ 
                                ?>       
                                <div class="alert alert-success" role="alert">
                                <?php
                                    }else{ 
                                ?>       
                                <div class="alert alert-danger" role="alert">
                                <?php
                                    }
                                ?>
                                  <?php  
                                    echo $choices[$i]
                                  ?>
                                </div>
                                <?php
                                  }
                                ?>
                              </div>
                              <br/><br/>
                            </div>
                          </td>
                          <?php if (!Yii::app()->user->YiiStudent) { ?>                          
                          <td>
                            <?php
                              if(!empty($value->type == 1)){
                                echo "Isian";
                              }elseif($value->type == 2){
                                echo "Essay";
                              }else{
                                echo "Pilihan Ganda";
                              }
                            ?>
                          </td>
                          <td>
                            <?php
                              if(Yii::app()->user->id==$value->teacher_id || Yii::app()->user->YiiAdmin){
                                if(empty($value->share_status)){
                                  echo '<a href="#" class="btn btn-danger btn-xs share-modal" question-id="'.$value->id.'"><i class="fa fa-lock"></i> Tidak Dibagikan</a>';
                                }elseif($value->share_status == 1){
                                  echo '<a href="#" class="btn btn-warning btn-xs share-modal" question-id="'.$value->id.'"><i class="fa fa-unlock"></i> Dibagikan Ke Guru Satu Mata Pelajaran</span>';
                                }elseif($value->share_status == 2){
                                  echo '<a href="#" class="btn btn-warning btn-xs share-modal" question-id="'.$value->id.'"><i class="fa fa-unlock"></i> Dibagikan Ke Beberapa Guru</span>';
                                }elseif($value->share_status == 3){
                                  echo '<a href="#" class="btn btn-success btn-xs share-modal" question-id="'.$value->id.'"><i class="fa fa-globe"></i> Dibagikan Ke Publik</span>';
                                }
                              }else{
                                if(empty($value->share_status)){
                                  echo '<a href="#" class="btn btn-danger btn-xs" question-id="'.$value->id.'"><i class="fa fa-lock"></i> Tidak Dibagikan</a>';
                                }elseif($value->share_status == 1){
                                  echo '<a href="#" class="btn btn-warning btn-xs" question-id="'.$value->id.'"><i class="fa fa-unlock"></i> Dibagikan Ke Guru Satu Mata Pelajaran</span>';
                                }elseif($value->share_status == 2){
                                  echo '<a href="#" class="btn btn-warning btn-xs" question-id="'.$value->id.'"><i class="fa fa-unlock"></i> Dibagikan Ke Beberapa Guru</span>';
                                }elseif($value->share_status == 3){
                                  echo '<a href="#" class="btn btn-success btn-xs" question-id="'.$value->id.'"><i class="fa fa-globe"></i> Dibagikan Ke Publik</span>';
                                }                                
                              }
                            ?>
                          </td>
                          <td>
                            <?php if($value->teacher_id==Yii::app()->user->id || Yii::app()->user->YiiAdmin) { ?>
                            <div class="btn-group">
                              <!-- <?php echo CHtml::link('<i class="fa fa-eye"></i>',array('/questions/view', 'id'=>$value->id), array('class'=>'btn btn-success btn-xs')); ?> -->
                              <?php echo CHtml::link('<i class="fa fa-pencil"></i> Sunting Soal',array('/questions/update', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?>
                            </div>
                            <?php } ?>
                          </td>
                          <td>
                            <?php if($value->teacher_id==Yii::app()->user->id || Yii::app()->user->YiiAdmin) { ?>
                            <div class="text-center">
                              <input type="checkbox" class="soal" name="soal[]" value="<?php echo $value->id;?>">
                            </div>
                            <?php } ?>                            
                          </td>
                          <?php } ?>
                        </tr>
                        <?php
                              $no++;
                            }
                          }
                        ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </form>
                <script>
                  var checkedSoal = [];

                  function runCheckedSoal(){
                    if(checkedSoal.length==0){
                      $('#shareModal').prop('disabled', true);
                      $('#delete').prop('disabled', true);
                    }else{
                      $('#shareModal').prop('disabled', false);
                      $('#delete').prop('disabled', false);
                    }
                  }

                  $('.soal').click(function(event) {
                    var index = this.value;

                    if(this.checked){
                      checkedSoal.push(index);
                    }else{
                      var removeIndex = checkedSoal.indexOf(index);
                      if(removeIndex > -1){
                        checkedSoal.splice(removeIndex, 1);
                      }
                    }

                    runCheckedSoal();
                  });

                  $('.share-modal').click(function(event) {
                    var url = "<?php echo Yii::app()->baseUrl; ?>/questions/ajaxBulkBanks/"+$(this).attr('question-id');
                    
                    $.post(url,
                    {

                    },
                    function(data, status){
                      $('#ajaxBulkBanks').html(data);
                      $('#shareQuestions').modal('show');
                    });
                  });

                  $('#selectAllBanks').click(function(event) {
                      checkedSoal = [];

                      if(this.checked) {
                          $('.soal').each(function() {
                              var index = this.value;
                              checkedSoal.push(index);
                              
                              this.checked = true;
                          });
                      }else{
                          $('.soal').each(function() {
                              var index = this.value;
                              var removeIndex = checkedSoal.indexOf(index);
                              
                              if(removeIndex > -1){
                                checkedSoal.splice(removeIndex, 1);
                              }

                              this.checked = false;
                          });
                      }
                      runCheckedSoal();
                  });

                  $('#shareModal').click(function(event) {
                    if(checkedSoal.length==1){
                        var url = "<?php echo Yii::app()->baseUrl; ?>/questions/ajaxBulkBanks/"+checkedSoal[0];
                        
                        $.post(url,
                        {

                        },
                        function(data, status){
                          $('#ajaxBulkBanks').html(data);
                          $('#shareQuestions').modal('show');
                        });
                    }else{
                        var url = "<?php echo Yii::app()->baseUrl; ?>/questions/ajaxBulkBanks";

                        $.post(url,
                        {

                        },
                        function(data, status){
                          $('#ajaxBulkBanks').html(data);
                          $('#shareQuestions').modal('show');                          
                        });
                    }
                  });

                  runCheckedSoal();
                </script>
                <div class="modal fade" id="shareQuestions" tabindex="-1" role="dialog" aria-labelledby="shareQuestions">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title" id="dataExcel"><b>Bagikan Soal</b></h4>
                      </div>
                      <div id="ajaxBulkBanks"></div>
                      <div class="modal-footer">
                        <input type="submit" name="share" value="Simpan Perubahan" class="btn btn-primary">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                      </div>
                    </div>
                  </div>
                </div>
              </form>
              <?php
                    }
                  }
                }elseif($type == "siswa" || $type==NULL){
                  if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){
              ?>
              <h4><strong>Siswa</strong>
                 <form action="<?php echo Yii::app()->createUrl("/lesson/showAll") ?>" method="post" onsubmit="return confirm('Yakin Melakukan Aksi Ini Untuk Semua Yang Dipilih?');">         
                <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
                <div class="btn-group pull-right">
                  <a href="#" data-toggle="modal" data-target="#copyExcel" class="btn btn-pn-primary"><i class="fa fa-plus"></i> Tambah Siswa Dari Excel</a>
                  <a href="#" data-toggle="modal" data-target="#newStudent" class="btn btn-primary"> Dari Siswa Terdaftar</a>
                  <input type="button" id="ganjil" value="Pilih Ganjil" class="btn btn-pn-primary">
                  <input type="button" id="genap" value="Pilih Genap" class="btn btn-warning">
                  <input type="submit" name="hapus" value="Keluarkan Siswa" class="btn btn-danger">
                  <input type="hidden" name="id_lesson" value="<?php echo $model->id; ?>">
                </div>
                <?php } ?>
                
              </h4>
              <hr>
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th width="10%">No.</th>
                      <th width="20%">NIS/NISN</th>
                      <th>Nama Siswa</th>
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
                      if(!empty($datasis)){
                        $no = 1;
                        foreach ($datasis as $lkss) {
                          if (!Yii::app()->user->YiiStudent) {
                    ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo CHtml::link($lkss->student->username, array('/user/view','id'=>$lkss->user_id)) ?></td>
                      <td>
                        <?php
                          if($lkss->student->image){
                            $foto = Yii::app()->baseUrl.'/images/user/'.$lkss->user_id.'/'.$lkss->student->image;
                          }else{
                            $foto = Yii::app()->baseUrl.'/images/user-2.png';
                          }
                        ?>
                        <img src="<?php echo $foto ?>" alt="Profile Picture" class="img-circle" style="width:24px;height:24px">&nbsp;&nbsp;
                        <?php echo CHtml::link($lkss->student->display_name, array('/user/view','id'=>$lkss->user_id)) ?>
                      </td>
                      <td>
                        <div class="btn-group">
                          <?php echo CHtml::link('<i class="fa fa-trash"></i> Hapus Dari Pelajaran',array('/lesson/hapusMurid', 'id'=>$lkss->id), array('class'=>'btn btn-danger btn-xs','title'=>'Hapus','confirm'=>'Yakin Menghapus Siswa Ini?')); ?>
                         
                        </div>
                      </td>
                      <td>
                                 <div class="text-center">
                                     <?php if($no % 2 == 0) {?>
                                     <input type="checkbox" name="users[]" class="quiz genap" value="<?php echo $lkss->user_id ?>">
                                     <?php } else {?>
                                     <input type="checkbox" name="users[]" class="quiz ganjil" value="<?php echo $lkss->user_id ?>">
                                     <?php } ?>
                                  </div>
                                </td>
                    </tr>
                    <?php
                            $no++;
                          }
                        }
                      }
                    ?>
                  </tbody>
                </table>
              </form>
              </div>
              <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
              <div class="modal fade" id="newStudent" tabindex="-1" role="dialog" aria-labelledby="myNewModalLabel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myNewModalLabel"><b>Pilih Siswa</b></h4>
                    </div>
                    <div class="modal-body">
                      <?php
                        if(!empty($students)){
                          $table_url = Yii::app()->createUrl('/lesson/addFromTable/'.$model->id);
                      ?>
                      <div class="table-responsive">
                        <form method="post" action="<?php echo $table_url;?>" onsubmit="return confirm('Yakin menambahkan siswa ke kelas ini ?');">
                          <p class="text-right"><input type="reset" value="Reset" class="btn btn-warning"> <span><input type="submit" value="Tambah" class="btn btn-success"></span></p>
                          <table class="table table-bordered">
                            <tbody>
                              <th>NIS</th>
                              <th>Nama Siswa</th>
                              <th><input type="checkbox" id="selectAllmodal"></th>
                              <?php foreach ($students as $value) { ?>
                                <tr>
                                  <td><?php echo $value->username;?></td>
                                  <td><?php echo $value->display_name;?></td>
                                  <td><input type="checkbox" name="siswa[]" value="<?php echo $value->id;?>" class="murid"></td>
                                </tr>
                              <?php } ?> 
                            </tbody>
                          </table>
                        </form>
                      </div>
                      <?php } ?>
                      <script>
                        $('#selectAllmodal').click(function(event) {
                          if(this.checked) { 
                              $('.murid').each(function() { 
                                  this.checked = true;               
                              });
                          }else{
                              $('.murid').each(function() {
                                  this.checked = false;                      
                              });         
                          }
                        });
                      </script>        
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal fade" id="copyExcel" tabindex="-1" role="dialog" aria-labelledby="myNewCopy">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="myNewCopy"><b>Data Siswa</b></h4>
                    </div>
                    <div class="modal-body idata">
                      <?php
                        $copy_url = Yii::app()->createUrl('/lesson/copyExcel/'.$model->id);
                      ?>
                      <div class="form-group">
                        <label>Copy dan Paste Dua Kolom Secara Berurutan NIS dan Nama Siswa Dari Excel Ke Area Dibawh ini :</label>
                        <textarea id="datamasuk" class="form-control" cols="5" rows="5" name="datasiswa"></textarea>
                      </div>
                      <button class="btn btn-success" id="tambah">Tambah</button>
                    </div>
                    <script>
                      var ds;
                      var obj;
                      var url_post = "<?php echo $copy_url; ?>";

                      $("#tambah").click(function(){
                        ds = $("#datamasuk").val();
                        $.ajax({
                            url: url_post,
                            type: "POST",
                            data: {datasiswa:ds},
                            success: function(resp){
                               obj = jQuery.parseJSON(resp);

                               if(obj.messages == "success"){
                                  $('#copyExcel').modal('hide');
                                  $('#inputData').modal('show');
                                  
                                  $.each(obj.data, function(key,value){
                                    $('#siswas').append('<tr class="tambahan"><td><input type="text" name="nis[]" value="'+value.nomor_induk+'" class="form-control"></td> <td><input type="text" name="nama[]" value="'+value.nama_lengkap+'" class="form-control"></td></tr>');
                                  });
                                  
                                  
                               }
                               console.log(obj);
                            }
                          });
                      });
                    </script>
                    <div class="modal-footer">
                      <span></span><button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </div>
                  </div>
                </div>
              </div>

              <div class="modal fade" id="inputData" tabindex="-1" role="dialog" aria-labelledby="dataExcel">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                      <h4 class="modal-title" id="dataExcel"><b>Data Siswa</b></h4>
                    </div>
                    <div class="modal-body" style="max-height: calc(100vh - 212px);overflow-y: auto;">
                      <?php
                        $input_url = Yii::app()->createUrl('/lesson/inputData/'.$model->id);
                      ?>
                      <form method="post" action="<?php echo $input_url ?>" onsubmit="return confirm('Yakin menambahkan siswa ke kelas ini ?');">
                        <table class="table">
                          <tbody id="siswas">
                            <th class="success text-center">NOMOR INDUK</th>
                            <th class="success text-center" id="tanda">NAMA LENGKAP</th>
                          </tbody>
                        </table>
                        <input type="submit" class="btn btn-success" value="Simpan">
                      </form>    
                    </div>
                    <div class="modal-footer">
                      <span><button type="button" class="btn btn-default" id="kembali">Kembali</button></span><button type="button" class="btn btn-danger" data-dismiss="modal">Tutup</button>
                    </div>
                    <script>
                      $("#kembali").click(function(){
                        $('#copyExcel').modal('show');
                        $('#inputData').modal('hide');
                        $("tr").remove(".tambahan");
                      });
                    </script>
                  </div>
                </div>
              </div>
              <?php
                    }
                  }
                }elseif($type == "nilai"){
                  if(empty($fiturRekap) || $fiturRekap[0]->value != 2){ 
                    if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){
              ?>
              <h4><strong>Penilaian</strong></h4>
              <hr/>
              <div class="btn-group">
                <a href="<?php echo $this->createUrl('/lesson/NilaiRapor/'.$model->id) ?>" class="btn btn-danger"><i class="fa fa-plus"></i> Input Nilai Rapor</a>
              </div>
              <div class="clearfix"></div><br/>
              <div class="btn-group">
                <a href="<?php echo $this->createUrl('/lesson/NilaiKd/'.$model->id) ?>" class="btn btn-pn-primary"><i class="fa fa-plus"></i> Input Deskripsi KD Pengetahuan</a>
                <!-- <a href="<?php echo $this->createUrl('/lesson/NilaiKetSikap/'.$model->id) ?>" class="btn btn-primary"> Nilai Rapor Pengetahuan</a> -->
              </div>
              <div class="clearfix"></div><br/>
              <div class="btn-group">
                <a href="<?php echo $this->createUrl('/lesson/NilaiKdDua/'.$model->id) ?>" class="btn btn-pn-primary"><i class="fa fa-plus"></i> Input Deskripsi KD Keterampilan</a>
                <!-- <a href="<?php echo $this->createUrl('/lesson/NilaiKetSikapDua/'.$model->id) ?>" class="btn btn-primary"> Nilai Rapor Keterampilan</a> -->
              </div>
              <div class="clearfix"></div><br/>              
              <div class="btn-group">
                 <?php if($model->moving_class==1){ ?>
                    <a href="<?php echo $this->createUrl('/clases/UpdateDesc/'.$model->class_id.'?lesson_id='.$model->id) ?>" class="btn btn-warning"><i class="fa fa-bolt"></i> Update Deskripsi</a>
                 <?php }else{ ?>
                    <a href="<?php echo $this->createUrl('/clases/UpdateDesc/'.$model->class->id.'?lesson_id='.$model->id) ?>" class="btn btn-warning"><i class="fa fa-bolt"></i> Update Deskripsi</a>
                 <?php } ?>

              </div>
              <?php
                    }
                  }
                }
              ?>

              <?php if(!empty($dataProvider)){ ?>
              <div class="text-center">
                <?php
                  $this->widget('CLinkPager', array(
                                'pages'=>$dataProvider->pagination,
                                ));
                ?>
              </div>
              <?php } ?>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>
  <div class="modal fade" id="importSoal" tabindex="-1" role="dialog" aria-labelledby="filterScoreLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title text-center" id="forgotPasswordLabel">Import Soal</h4>
        </div>
        <div class="modal-body">
          <div class="scrollable-table-list">
          <div class="form">
              <?php
               $form=$this->beginWidget('CActiveForm', array(
               'id'=>'sql-form',
               'action'=>Yii::app()->baseUrl.'/lesson/ImportQuiz/'.$model->id,
                'enableAjaxValidation'=>false,
                 'htmlOptions'=>array('enctype'=>'multipart/form-data')
              )); ?>
               
               <div class="form-group">
                <?php echo $form->labelEx($modelAct,'Pilih Sql :'); ?>
                      <?php 
                           echo $form->fileField($modelAct,'sqlFile',array('size'=>60000,'maxlength'=>20000000));
                      ?>
                <?php echo $form->error($modelAct,'sqlFile'); ?>
               </div>
               
               <div class="form-group">
                <?php echo CHtml::submitButton('Import',array("id"=>"Import",'name'=>'Import','class'=>'btn btn-success')); ?>
               </div>
              <?php $this->endWidget(); ?>
              </div><!-- form -->

          </div>
        </div>
      </div>
    </div>
  </div>
<script>
  if ($(window).width() > 960) {
    $("#wrapper").toggleClass("toggled");
  }
</script>
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


    $('#genap').click(function(event) {
      // if(this.checked) {
          $('.genap').each(function() {
              this.checked = true;
          });
      // }else{
          $('.ganjil').each(function() {
              this.checked = false;
          });         
      // }
  });

  $('#ganjil').click(function(event) {
      // if(this.checked) {
          $('.ganjil').each(function() {
              this.checked = true;
          });
      // }else{
          $('.genap').each(function() {
              this.checked = false;
          });         
      // }
  });
</script>