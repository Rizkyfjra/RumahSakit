<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_clases_list', array(

      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
      <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Kelas</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
        <h3>Kelas
          <small class="hidden-xs">Daftar Kelas</small>
          <div class="pull-right">
            <a href="<?php echo $this->createUrl('/lesson/importnilaiuas') ?>" class="btn btn-sm btn-pn-gray btn-pn-round">
              <span class="hidden-sm hidden-xs"><i class="fa fa-upload"></i> IMPORT NILAI UAS</span>
              <span class="hidden-md hidden-lg"><i class="fa fa-upload"></i></span>
            </a>
            <a href="<?php echo $this->createUrl('/clases/addexcel') ?>" class="btn btn-sm btn-pn-primary btn-pn-round">
              <span class="hidden-sm hidden-xs"><i class="fa fa-plus-circle"></i> TAMBAH KELAS</span>
              <span class="hidden-md hidden-lg"><i class="fa fa-plus-circle"></i></span>
            </a>
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
                      <th width="10%">Nama Kelas</th>
                      <th width="10%">ID Kelas</th>
                      <th width="40%">Penanggung Jawab</th>
                      <th width="30%">Status Sync</th>
                      <th width="30%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if(!empty($dataProvider->getData())){
                        $kelas = $dataProvider->getData();

                        if(empty($_GET['ClassDetail_page'])){
                          $no = 1;
                        }else{
                          $no = 1 + (($_GET['ClassDetail_page']-1) * 10);
                        }

                        foreach ($kelas as $value) {
                    ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo CHtml::link(CHtml::encode($value->name), array('view', 'id'=>$value->id)); ?></td>
                      <td><?php echo $value->id ?></td>
                      <td>
                        <?php
                          if(!empty($value->teacher_id)){
                            echo CHtml::link(CHtml::encode($value->teacher->display_name), array('/user/view', 'id'=>$value->teacher_id));
                          }else{
                            echo "-";
                          }
                        ?>
                      </td>

                      <td>
                        <?php
                          if(!empty($value->sync_status==null)){
                            echo "terbuka";
                          }else{
                            echo "tertutup";
                          }
                        ?>
                      </td>
                      <td>
                        <div class="btn-group">
                          <?php echo CHtml::link('<i class="fa fa-eye"></i> Lihat',array('view', 'id'=>$value->id), array('class'=>'btn btn-success btn-xs')); ?>
                          <!-- <?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('update', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?> -->

                        
                        </div>

                                     <div class="btn-group">
                         
                          <?php echo CHtml::link('<i class="fa fa-eye"></i> Buka Sync',array('api/ResetByclass?class_id='.$value->id), array('class'=>'btn btn-warning btn-xs')); ?>
                          <!-- <?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('Buka Sync', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?> -->

                         
                        </div>
                      
                                     <div class="btn-group">
                          

                          <?php echo CHtml::link('<i class="fa fa-eye"></i> Tutup Sync',array('api/CloseByclass?class_id='.$value->id), array('class'=>'btn btn-danger btn-xs')); ?>
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
      </div>
    </div>
  </div>
</div>
