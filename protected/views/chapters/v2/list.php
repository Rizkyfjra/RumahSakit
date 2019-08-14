<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_chapters_list', array(

      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
      <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Materi</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
        <h3>Materi
          <small class="hidden-xs">Daftar Materi</small>
          <div class="pull-right">
            <a href="<?php echo $this->createUrl('/chapters/create') ?>" class="btn btn-sm btn-pn-primary btn-pn-round">
              <span class="hidden-sm hidden-xs"><i class="fa fa-plus-circle"></i> TAMBAH MATERI</span>
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
                      <th width="30%">Judul Materi</th>
                      <th width="20%">Mata Pelajaran</th>
                      <th>Dibuat Tanggal</th>
                      <th width="10%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if(!empty($dataProvider->getData())){
                        $materi = $dataProvider->getData();

                        if(empty($_GET['Chapters_page'])){
                          $no = 1;
                        }else{
                          $no = 1 + (($_GET['Chapters_page']-1) * 15);
                        }

                        foreach ($materi as $value) {
                    ?>
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo CHtml::link(CHtml::encode($value->title), array('view', 'id'=>$value->id)); ?></td>
                      <td><?php echo CHtml::link(CHtml::encode($value->mapel->name), array('/lesson/view', 'id'=>$value->id_lesson)); ?></td>
                      <td><?php echo $value->created_at ?></td>
                      <td>
                        <div class="btn-group">
                          <?php echo CHtml::link('<i class="fa fa-eye"></i>',array('view', 'id'=>$value->id), array('class'=>'btn btn-success btn-xs')); ?>
                          <?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('update', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?>
                          <?php echo CHtml::link('<i class="fa fa-trash"></i>',array('hapus', 'id'=>$value->id), array('class'=>'btn btn-danger btn-xs','title'=>'Hapus','confirm'=>'Yakin Menghapus Materi Ini?')); ?>
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
