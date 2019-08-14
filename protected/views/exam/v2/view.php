<?php
$hari = array("Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu");
$bulan = array("", "Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
$nama_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_name%"'));
$alamat_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_address%"'));
$kepala_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%kepsek_id%"'));

$semester_name = array('GANJIL', 'GENAP');
$sc_name = "PINISI SCHOOL";
if (!empty($nama_sekolah)) {
    if (!empty($nama_sekolah[0]->value)) {
        $sc_name = strtoupper($nama_sekolah[0]->value);
    }
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div id="bc1" class="btn-group btn-breadcrumb">
                <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda', array('/site/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Adminitrasi</div>', array('/exam/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>' . CHtml::encode($model->title) . '</div>', array('#'), array('class' => 'btn btn-success')); ?>
            </div>
        </div>

        <div class="col-lg-12">
            <h3>
                <?php echo $model->title ?>
            </h3>
            <div class="row">
                <div class="col-md-3">
                    <div class="col-card">
                        <ul class="nav nav-pills nav-stacked">
                            <li role="presentation"
                                class="<?php echo '', ($type == "jadwal" || $type == NULL) ? 'active' : '' ?>">
                                <?php echo CHtml::link('Jadwal', array('view', 'id' => $model->id, 'type' => 'jadwal')); ?>
                            </li>
                            <li role="presentation"
                                class="<?php echo '', ($type == "ruang") ? 'active' : '' ?>">
                                <?php echo CHtml::link('Ruangan', array('view', 'id' => $model->id, 'type' => 'ruang')); ?>
                            </li>
                            
                        </ul>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="col-card">
                        <?php if ($type == "jadwal") {
                            foreach ($datas as $key => $data) {
                                $tanggal[$data->id] = $data->date;
                                $jadwal[$data->id] = ExamScheduleClass::model()->findAll(array('condition' => 'schedule_id = ' . $data->id));
                            }
                            ?>
                            <h4><strong>Jadwal Ruangan Kosong</strong></h4>
                            <div>
                                <ul class="nav nav-tabs" role="tablist">
                                    <?php
                                    $i = 1;
                                    foreach ($datas as $key => $data) { ?>
                                        <li role="presentation" class="<?php echo $i == 1 ? "active" : "" ?>">
                                            <a href="#schedule<?php echo $data->id ?>"
                                               aria-controls="schedule<?php echo $data->id ?>" role="tab"
                                               data-toggle="tab">
                                                Hari ke-<?php echo $i ?>
                                            </a>
                                        </li>
                                        <?php
                                        $i++;
                                    }
                                    ?>
                                </ul>
                                <br/>
                                <div class="tab-content">
                                    <?php
                                    $i = 1;
                                    foreach ($jadwal as $key => $datas) {
                                        if ($datas) { ?>
                                            <div role="tabpanel" class="tab-pane <?php echo $i == 1 ? "active" : "" ?>"
                                                 id="schedule<?php echo $key ?>">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h3><?php echo $hari[date("w", strtotime($tanggal[$key]))] . ", " . date("j", strtotime($tanggal[$key])) . " " . $bulan[date("n", strtotime($tanggal[$key]))] . " " . date("Y", strtotime($tanggal[$key])); ?></h3>
                                                        <?php echo CHtml::link('<i class="fa fa-plus-circle"></i> Tambah Jadwal Kelas lain',
                                                            array('create_schedule?schedule_id=' . $key), array('class' => 'btn btn-sm btn-pn-primary pull-right')) ?>
                                                    </div>
                                                </div>
                                                <div class="table-responsive">
                                                    <table class="table table-hover">
                                                        <thead>
                                                        <tr>
                                                            <th>Kelas</th>
                                                            <th>Pelajaran</th>
                                                            <th>Waktu</th>
                                                            <th width="15%">Aksi</th>
                                                        </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach ($datas as $data) { ?>
                                                            <tr>
                                                                <td width="40%"><?php
                                                                    $kelases = ClassDetail::model()->findAll(array(
                                                                        'select' => 'name',
                                                                        'condition' => 'id IN (' . implode(",", json_decode($data->class_id)) . ')'));
                                                                    foreach ($kelases as $kelas) {
                                                                        echo "<lable class='label label-danger' style='margin:0 3px;padding: 5px;display: inline-block;'>" . $kelas->name . "</lable>";
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><?php
                                                                    $i = 1;
                                                                    foreach (json_decode($data->lesson_id) as $lesson) {
                                                                        $lessons = LessonList::model()->find(array(
                                                                            'select' => 'name',
                                                                            'condition' => 'id = "' . $lesson . '"'));
                                                                        echo "Pelajaran {$i} : <lable class='label label-info' style='margin:0 3px;padding: 5px;display: inline-block;'>" . $lessons->name . "</lable><br />";
                                                                        $i++;
                                                                    }
                                                                    ?>
                                                                </td>
                                                                <td><?php
                                                                    if (!empty($data->lesson_time)) {
                                                                        $i = 1;
                                                                        foreach (json_decode($data->lesson_time) as $time) {
                        
                                                                            echo "<lable class='label label-success' style='margin:0 3px;padding: 5px;display: inline-block;'>" . $time . "</lable><br />";
                                                                            $i++;
                                                                        }
                                                                    }    
                                                                    ?>
                                                                </td>
                                                                <td>
                                                                    <div class="btn-group">
                                                                        <?php echo CHtml::link('<i class="fa fa-pencil"></i>', array('update_schedule', 'id' => $data->id), array('class' => 'btn btn-primary btn-xs')); ?>
                                                                        <?php echo CHtml::link('<i class="fa fa-trash"></i>', array('hapus_schedule', 'id' => $data->id), array('class' => 'btn btn-danger btn-xs', 'title' => 'Hapus', 'confirm' => 'Yakin Menghapus Pengguna Ini?')); ?>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php } ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div role="tabpanel" class="tab-pane <?php echo $i == 1 ? "active" : "" ?>"
                                                 id="schedule<?php echo $key ?>">
                                                <h3><?php echo $hari[date("w", strtotime($tanggal[$key]))] . ", " . date("j", strtotime($tanggal[$key])) . " " . $bulan[date("n", strtotime($tanggal[$key]))] . " " . date("Y", strtotime($tanggal[$key])); ?></h3>
                                                <div class="table-empty">
                                                    <p>
                                                        Jadwal Masih Kosong
                                                    </p>
                                                    <a href="<?php echo $this->createUrl('/exam/create_schedule?schedule_id=' . $key) ?>"
                                                       class="btn btn-pn-primary btn-sm">
                                                        <i class="fa fa-plus-circle"></i> Tambah Jadwal
                                                    </a>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                        $i++;
                                    }
                                    ?>
                                </div>
                            </div>
                        <?php } else if ($type == "ruang") { ?>
                            <h4><strong>Ruang Ujian</strong>
                                <div class="btn-group pull-right">
                                    <a href="<?php echo $this->createUrl('/exam/create_room?exam_id=' . $model->id) ?>"
                                       class="btn btn-pn-primary"><i class="fa fa-plus"></i> Tambah Ruangan</a>
                                </div>
                            </h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                            <tr>
                                                <th>No.</th>
                                                <th>Ruangan</th>
                                                <th>Kelas</th>
                                                <th width="15%">Aksi</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php
                                            $i = 1;
                                            foreach ($datas as $data) { ?>
                                                <tr>
                                                    <td><?php echo $i ?></td>
                                                    <td><?php echo $data->no_room; ?></td>
                                                    <td>
                                                        <?php
                                                        $list_class = ExamStudentlist::model()->findAll(array(
                                                            'condition' => 'room_id = "' . $data->id . '" group by class_id'
                                                        ));
                                                        if (!empty($list_class)) {
                                                            foreach ($list_class as $class) {
                                                                $classes = ClassDetail::model()->find(array(
                                                                    'select' => 'name',
                                                                    'condition' => 'id = ' . $class->class_id . ''));
                                                                echo "<lable class='label label-danger' style='margin:0 3px;padding: 5px;display: inline-block;'>" . $classes->name . "</lable>";
                                                            }
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group">
                                                            <?php echo CHtml::link('<i class="fa fa-eye"></i>', array('view_room?room_id=' . $data->id), array('class' => 'btn btn-success btn-xs')); ?>
                                                            <?php echo CHtml::link('<i class="fa fa-pencil"></i>', array('update_room?room_id=' . $data->id), array('class' => 'btn btn-primary btn-xs')); ?>
                                                            <?php echo CHtml::link('<i class="fa fa-print"></i>', array('print_room?exam_id=' . $model->id . '&room_id=' . $data->id), array('class' => 'btn btn-warning btn-xs')); ?>
                                                            <?php echo CHtml::link('<i class="fa fa-trash"></i>', array('hapus_room?room_id=' . $data->id), array('class' => 'btn btn-danger btn-xs', 'title' => 'Hapus', 'confirm' => 'Yakin Menghapus Pengguna Ini?')); ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <?php $i++;
                                            } ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php } else if ($type == "kartu") { ?>
                            <h4><strong>Kartu Perserta</strong></h4>
                            <div class="row">
                                <div class="col-md-12">
                                    <?php $form = $this->beginWidget('CActiveForm', array(
                                        'id' => 'room-form',
                                        'enableAjaxValidation' => false,
                                        'htmlOptions' => array('enctype' => 'multipart/form-data'),
                                    )); ?>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="ClassDetail_class_id">Kelas</label>
                                                <?php echo $form->dropDownList(new ClassDetail, 'class_id', $class, array('id' => 'classes', 'class' => 'selectpicker form-control input-lg', 'data-style' => 'btn-default input-lg', 'data-live-search' => 'true', 'title' => "Please Select...")); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="table-responsive">
                                                <table class="table table-hover table-student">
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    <?php $this->endWidget(); ?>
                                </div>
                            </div>
                        <?php } else if ($type == 'tertib') { ?>
                            <div class="btn-group pull-right">
                                <a href="<?php echo $this->createUrl('/exam/tatatertib') ?>"
                                   class="btn btn-primary"><i class="fa fa-pencil"></i> Edit
                                </a>
                                <button data-toggle="print" data-target="#tatatertib"
                                        class="btn btn-pn-primary no-print"><i class="fa fa-print"></i> Cetak
                                </button>
                            </div>
                            <div id="tatatertib" class="row">
                                <div class="col-md-12" style="line-height: 35px">
                                    <?php echo $datas->edited; ?>
                                </div>
                            </div>
                        <?php } else if ($type == "berita") { ?>
                            <div class="btn-group pull-right">
                                <a href="<?php echo $this->createUrl('/exam/beritaacara') ?>"
                                   class="btn btn-primary"><i class="fa fa-pencil"></i> Edit
                                </a>
                                <button data-toggle="print" data-target="#beritaacara"
                                        class="btn btn-pn-primary no-print"><i class="fa fa-print"></i> Cetak
                                </button>
                            </div>
                            <div id="beritaacara" class="row">
                                <div class="col-md-12">
                                    <span class="cop">
                                    <table width="100%" >
                                        <tr>
                                            <!-- <td width="15%">
                                                <img src="<?php //echo Yii::app()->baseUrl . '/images/kemenag.png' ?>"
                                                     width="95%">
                                            </td> -->

                                            <td width="100%" class="text-center">
                                                <h3>
                                                    <!-- <strong style="font-family: 'Times New Roman'">Kementrian Agama Kabupaten Lumajang<br/> -->
                                                        <!-- DINAS PENDIDIKAN<br/> -->
                                                       <strong style="font-family: 'Times New Roman'"><?php echo $sc_name; ?></strong><br/>
                                                    <small>
                                                        <?php
                                                        if (!empty($alamat_sekolah[0]->value)) {
                                                            echo $alamat_sekolah[0]->value;
                                                        }
                                                        ?>
                                                    </small>
                                                </h3>
                                            </td>
                                        </tr>
                                    </table>
                                    <hr style="margin-top: 0px;margin-bottom: 5px;border: 0;border-top: 1.2px solid #666">
                                    <hr style="margin-top: 0px;margin-bottom: 5px;border: 0;border-top: 1.2px solid #666">
                                    </span>
                                    <span class="isi" style="line-height: 35px;font-family: 'Times New Roman'">
                                    <?php echo $datas->edited; ?>
                                    </span>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    if ($(window).width() > 960) {
        $("#wrapper").toggleClass("toggled");
    }

    var type =  <?php echo json_encode($type); ?>;

    $(document).ready(function () {
        if (type === "kartu") {
            $('#classes').on('change', function () {
                var url_load = "<?php echo $this->createUrl('/exam/list_student?class_id=') ?>" + $(this).val();
                var classes = $(this).children('option[value=' + $(this).val() + ']').text();
                var class_id = $(this).val();
                $.ajax({
                    url: url_load,
                    type: 'GET',
                    dataType: 'json',
                    success: function (data) {
                        var table = '<thead><tr><th>No.</th><th>Nama</th><th width="15%">Aksi</th></tr></thead><tbody>';
                        var i = 1;
                        $.each(data, function (name, id) {
                            url = "<?php echo $this->createUrl('/exam/print_out?exam_id=' . $model->id . '&student_id=') ?>" + id;
                            table = table + '<tr>'
                                + '<td>' + i + '</td>'
                                + '<td>' + name + '</td>'
                                + '<td><a class="btn btn-danger btn-md" href="' + url + '"><i class="fa fa-print"></i></a></td>'
                                + '</tr>';
                            i++;
                        });
                        table = table + "</tbody>";
                        $('.table-student').html(' ');
                        $('.table-student').append(table);
                    }
                })
            });
        }
    });
</script>