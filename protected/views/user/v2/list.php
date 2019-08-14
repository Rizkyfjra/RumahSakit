<?php
  if(isset($_GET['pilihan'])){
    $pil = $_GET['pilihan'];
  }else{
    $pil = "";
  }

  if(isset($_GET['keyword'])){
    $teks = $_GET['keyword'];
  }else{
    $teks = "";
  }
?>
<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_user_list', array(
        
      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
      <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Pengguna</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
        <form action="<?php echo Yii::app()->createUrl("/user/HapusAll") ?>" method="post" onsubmit="return confirm('Yakin Melakukan Aksi Ini Untuk Semua Yang Dipilih?');">         
        
        <h3>Pengguna
          <small class="hidden-xs">Daftar Pengguna</small>
          <div class="pull-right">
            <?php echo CHtml::link('<span class="hidden-sm hidden-xs"><i class="fa fa-download"></i> UNDUH DATA</span>
                                    <span class="hidden-md hidden-lg"><i class="fa fa-download"></i></span>',
                                    array('downloadUser','pilihan'=>$pil,'keyword'=>$teks),array('class'=>'btn btn-sm btn-pn-gray btn-pn-round'))?>
            <?php echo CHtml::link('<span class="hidden-sm hidden-xs"><i class="fa fa-plus"></i> TAMBAH PENGGUNA</span>
                                    <span class="hidden-md hidden-lg"><i class="fa fa-plus"></i></span>',
                                    array('create'),array('class'=>'btn btn-sm btn-pn-primary btn-pn-round'))?>
          <input type="submit" name="hapus" value="Hapus Pengguna" class="btn btn-danger">
          </div>
        </h3>
        <div class="row">
          <div class="col-md-12">
            <div class="col-card">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th>NIP/No Pasien</th>
                      <th width="25%">Nama</th>
                      <th>ID User</th>
                      <th>E-Mail</th>
                      <th>Password</th>
                      <th>Status</th>
                      <th>Kelas</th>
                      <th width="15%">Aksi</th>
                      <th width="5%">
                          <div class="text-center">
                            <input type="checkbox" id="selectAll">
                          </div>
                        </th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if(!empty($dataProvider->getData())){
                        $kuis = $dataProvider->getData();

                        if(empty($_GET['User_page'])){
                          $no = 1;
                        }else{
                          $no = 1 + (($_GET['User_page']-1) * 15);
                        }

                        foreach ($kuis as $value) {
                            // $this->renderPartial('v2/_table_user_admin_list', array(
                            //   'value'=>$value, 'no'=>$no
                            // ));
                            ?>
                            <tr>
                                <th class="text-center"><?php echo $no; ?></th>
                                <td>
                                  <?php echo CHtml::link(CHtml::encode($value->username), array('/user/view', 'id'=>$value->id)); ?>
                                </td>
                                <td>
                                  <?php echo CHtml::link(CHtml::encode($value->display_name), array('/user/view', 'id'=>$value->id)); ?>
                                </td>
                                <td>
                                  <?php echo $value->id; ?>
                                </td>
                                <td>
                                  <?php echo $value->email; ?>
                                </td>
                                <td>
                                  <?php echo $value->reset_password; ?>
                                </td>
                                <td>
                                  <?php
                                    if($value->role_id == 2){
                                      echo "Pasien";
                                    }elseif($value->role_id == 1){
                                      echo "Dokter";
                                    }elseif($value->role_id == 3){
                                      echo "Dirut";
                                    }elseif($value->role_id == 4){
                                      echo "Staf";  
                                    }elseif($value->role_id == 99){
                                      echo "Administrator";
                                    }
                                  ?>
                                </td>
                                <td>
                                  <?php
                                    if(!empty($value->class_id)){
                                      $kls = ClassDetail::model()->findAll(array("condition"=>"id = ".$value->class_id));
                                      if(!empty($kls)){
                                        echo $kls[0]->name;
                                      }
                                    }
                                  ?>
                                </td>
                                <td>
                                  <div class="btn-group">
                                    <?php echo CHtml::link('<i class="fa fa-eye"></i>',array('view', 'id'=>$value->id), array('class'=>'btn btn-success btn-xs')); ?>
                                    <?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('update', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?>
                                    <?php echo CHtml::link('<i class="fa fa-trash"></i>',array('hapus', 'id'=>$value->id), array('class'=>'btn btn-danger btn-xs','title'=>'Hapus','confirm'=>'Yakin Menghapus Pengguna Ini?')); ?>
                                  </div>
                                </td>
                                <td>
                                  <div> 
                                      <input type="checkbox" name="users[]" class="quiz" value="<?php echo $value->id ?>">
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
<script type="text/javascript">
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