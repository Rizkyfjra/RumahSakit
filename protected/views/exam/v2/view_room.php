<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div id="bc1" class="btn-group btn-breadcrumb">
                <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda', array('/site/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Adminitrasi Ujian</div>', array('/exam/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Lihat Ruangan</div>', array('#'), array('class' => 'btn btn-success')); ?>
            </div>
        </div>

        <div class="col-lg-12">
            <h3>
                Daftar Siswa
            </h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-card">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($model as $data) { ?>
                                            <tr>
                                                <td><?php echo $i ?>.</td>
                                                <td>
                                                    <?php
                                                    $user = User::model()->find(array(
                                                        'select' => 'display_name',
                                                        'condition' => 'id = ' . $data->student_id . ''));
                                                    echo $user->display_name;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $classes = ClassDetail::model()->find(array(
                                                        'select' => 'name',
                                                        'condition' => 'id = ' . $data->class_id . ''));
                                                    echo $classes->name;
                                                    ?>
                                                </td>
                                            </tr>
                                            <?php $i++;
                                        } ?>
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