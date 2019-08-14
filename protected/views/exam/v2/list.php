<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div id="bc1" class="btn-group btn-breadcrumb">
                <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Administrasi Ujian</div>',array('#'), array('class'=>'btn btn-success')); ?>
            </div>
        </div>

        <div class="col-lg-12">
            <h3>Administrasi
                <small class="hidden-xs">Daftar Administrasi</small>
                <?php if (!Yii::app()->user->YiiStudent) {?>
                <div class="pull-right">
                    <?php echo CHtml::link('<span class="hidden-sm hidden-xs"><i class="fa fa-plus"></i> TAMBAH ADMINISTRASI</span>
                                    <span class="hidden-md hidden-lg"><i class="fa fa-plus"></i></span>',
                        array('create'),array('class'=>'btn btn-sm btn-pn-primary btn-pn-round'))?>
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
                                    <th>Nama</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Akhir</th>
                                    <th>Jumlah Ruangan</th>
                                    <?php if (!Yii::app()->user->YiiStudent) {?>
                                    <th width="15%">Aksi</th>
                                    <?php }?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if(!empty($dataProvider->getData())){
                                    $kuis = $dataProvider->getData();

                                    if(empty($_GET['Exam_page'])){
                                        $no = 1;
                                    }else{
                                        $no = 1 + (($_GET['Exam_page']-1) * 15);
                                    }

                                    foreach ($kuis as $value) {
                                        ?>
                                        <tr>
                                            <th class="text-center"><?php echo $no; ?></th>
                                            <td>
                                                <?php if (!Yii::app()->user->YiiStudent) {?>
                                                <?php echo CHtml::link(CHtml::encode($value->title), array('/exam/view', 'id'=>$value->id)); ?>
                                                <?php } else {?>
                                                 <?php echo CHtml::link(CHtml::encode($value->title), array('/exam/print_out?exam_id='.$value->id.'&student_id='.Yii::app()->user->id)); ?>
                                                <?php }?>
                                            </td>
                                            <td>
                                                <?php echo $value->start_date; ?>
                                            </td>
                                            <td>
                                                <?php echo $value->end_date; ?>
                                            </td>
                                            <td class="text-center">
                                                <?php
                                                    $room = ExamRoom::model()->findAll(array('condition' => 'exam_id=' . $value->id));
                                                    echo count($room);
                                                ?>
                                            </td>
                                            <?php if (!Yii::app()->user->YiiStudent) {?>
                                            <td>
                                                <div class="btn-group">
                                                    <?php echo CHtml::link('<i class="fa fa-eye"></i>',array('view', 'id'=>$value->id), array('class'=>'btn btn-success btn-xs')); ?>
                                                    <?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('update', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?>
                                                    <?php echo CHtml::link('<i class="fa fa-trash"></i>',array('hapus', 'id'=>$value->id), array('class'=>'btn btn-danger btn-xs','title'=>'Hapus','confirm'=>'Yakin Menghapus Pengguna Ini?')); ?>
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
