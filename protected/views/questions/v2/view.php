<div class="container-fluid">
  <div class="row">
    <?php
        // $this->renderPartial('v2/_breadcrumb_soal', array(
        //   'model'=>$model
        // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
      <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Bank Soal</div>',array('/questions/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>'.CHtml::encode($model->title).'</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
    	<div class="row clearfix">&nbsp;</div>
	    <div class="row">
			 <div class="col-md-10 col-md-offset-1">
	       <div class="col-card">
          <div class="panel-body">
            <div class="row">
              <div class="col-md-12">
                <div class="btn-group pull-right">
                  <a href="<?php echo $this->createUrl('/questions/create') ?>" class="btn btn-pn-primary"><i class="fa fa-plus"></i> Tambah Soal</a>
                  <a href="<?php echo $this->createUrl('/questions/index') ?>" class="btn btn-primary"><i class="fa fa-list"></i> Lihat Daftar Soal</a>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="table-responsive">
                  <table class="table table-hover">
                    <thead>
                      <tr>
                        <th width="50%">Pertanyaan</th>
                        <th width="20%">Pilihan Jawaban</th>
                        <th width="20%">Kunci Jawaban</th>
                        <th width="10%">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td><?php echo $model->text ?></td>
                        <td>
                          <?php
                            if($model->type == NULL){
                              $no='A';
                              if(!empty($model->choices)){
                                $pilihan=json_decode($model->choices,true);

                                foreach ($pilihan as $key => $value) {
                                  echo $no.". ".$value."<br>";
                                  $no++;
                                }
                              }
                            }
                          ?>
                        </td>
                        <td><?php echo $model->key_answer ?></td>
                        <td>
                          <?php echo CHtml::link('<i class="fa fa-pencil"></i> Sunting Soal',array('/questions/update', 'id'=>$model->id), array('class'=>'btn btn-primary btn-xs')); ?>                          
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>