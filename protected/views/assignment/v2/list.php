<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_task_list', array(
        
      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
      <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Tugas</div>',array('/assignment/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>List</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>
    <div class="col-lg-12">
        <h3>Tugas
          <small class="hidden-xs">Daftar Tugas</small>
          <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>          
          <div class="pull-right">
            <a href="<?php echo $this->createUrl('/assignment/create') ?>" class="btn btn-sm btn-pn-primary btn-pn-round">
              <span class="hidden-sm hidden-xs"><i class="fa fa-plus-circle"></i> TAMBAH TUGAS</span>
              <span class="hidden-md hidden-lg"><i class="fa fa-plus-circle"></i></span>
            </a>
          </div>
          <?php } ?>
        </h3>
        <div class="row">
          <div class="col-md-12">
            <div class="col-card">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th width="25%">Nama Tugas</th>
                      <th>Pelajaran</th>
                      <th>Kelas</th>
                      <th>Batas Pengumpulan</th>
                      <?php if(!Yii::app()->user->YiiStudent){ ?>
                      <th width="10%">Aksi</th>
                      <?php } ?>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if(!empty($dataProvider->getData())){
                        $tugas = $dataProvider->getData();

                        if(empty($_GET['Assignment_page'])){
                          $no = 1;
                        }else{
                          $no = 1 + (($_GET['Assignment_page']-1) * 15);
                        }

                        foreach ($tugas as $value) {
                    ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo CHtml::link(CHtml::encode($value->title), array('/assignment/view', 'id'=>$value->id)); ?></td>
                      <td><?php echo $value->lesson->name ?></td>
                      <td>
                        <?php 
                          if($value->lesson->moving_class == 1){
                            echo $value->lesson->grade->name; 
                          }else{
                            echo $value->lesson->class->name;
                          }                          
                        ?>
                      </td>
                      <td><?php echo CHtml::encode($value->due_date) ?></td>
                      <?php if(!Yii::app()->user->YiiStudent){ ?>
                      <td>
                        <div class="btn-group">
                          <?php echo CHtml::link('<i class="fa fa-eye"></i>',array('view', 'id'=>$value->id), array('class'=>'btn btn-success btn-xs')); ?>
                          <?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('update', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?>
                          <?php echo CHtml::link('<i class="fa fa-trash"></i>',array('hapus', 'id'=>$value->id), array('class'=>'btn btn-danger btn-xs')); ?>
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