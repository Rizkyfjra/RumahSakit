<?php
foreach ($lessonlist as $lesson){
    $lessons[$lesson->list_id ."|" . $lesson->class->class_id] = $lesson->name . " (Tingkat ". $lesson->class->class_id .")" ;
}

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div id="bc1" class="btn-group btn-breadcrumb">
                <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda', array('/site/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Konfigurasi</div>', array('/option'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Import Ujian</div>', array('/option/pullquiz#'), array('class' => 'btn btn-success')); ?>
            </div>
        </div>
        <div class="col-lg-12">
            <h3>Import Ujian
                <small class="hidden-xs">Daftar Ujian</small>
                <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
                    <div class="pull-right">
<!--                        <a href="--><?php //echo $this->createUrl('/quiz/create') ?><!--" class="btn btn-sm btn-pn-primary btn-pn-round">-->
<!--                            <span class="hidden-sm hidden-xs"><i class="fa fa-plus-circle"></i> TAMBAH UJIAN</span>-->
<!--                            <span class="hidden-md hidden-lg"><i class="fa fa-plus-circle"></i></span>-->
<!--                        </a>-->
                    </div>
                <?php } ?>
            </h3>
            <form action="<?php echo Yii::app()->createUrl("/quiz/showAll") ?>" method="post" onsubmit="return confirm('Yakin Melakukan Aksi Ini Untuk Semua Yang Dipilih?');">
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="Lesson_class_id">Pelajaran</label>
                            <?php echo CHtml::dropDownList('lessons', "", $lessons, array('class' => 'selectpicker form-control input-lg', 'data-style' => 'btn-default input-lg', 'data-live-search' => 'true')); ?>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="Lesson_class_id">Pelajaran</label>
                            <?php echo CHtml::dropDownList('type', "", array("uts" => "UTS", "uas" => "UAS", "us" => "US", ), array('class' => 'selectpicker form-control input-lg', 'data-style' => 'btn-default input-lg', 'data-live-search' => 'true')); ?>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group">
                            <span class="btn btn-pn-primary btn-lg btn-pn-round btn-block pull-quiz">
                                <i class="fa fa-download"></i>Import Ujian
                            </span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-card">
                            <?php if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiTeacher){ ?>
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="btn-group pull-right">
                                            <input type="submit" name="tampil" value="Tampilkan" class="btn btn-pn-primary">
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
                                        <?php if(!Yii::app()->user->YiiStudent){ ?>
                                            <th>Selesai</th>
                                            <th>Belum Selesai</th>
                                        <?php } ?>
                                        <th>Nama Pelajaran</th>
                                        <th>Nama Kelas</th>
                                        <th>Guru</th>
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

                                            if(!Yii::app()->user->YiiStudent){
                                                $cekTotalSudah=StudentQuiz::model()->findAll(array('condition'=>'quiz_id = '.$value->id));
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
                                                <?php if(!Yii::app()->user->YiiStudent){ ?>
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
                                                <?php if(!Yii::app()->user->YiiStudent){ ?>
                                                    <td>
                                                        <div class="btn-group">
                                                            <?php echo CHtml::link('<i class="fa fa-eye"></i>',array('view', 'id'=>$value->id), array('class'=>'btn btn-success btn-xs')); ?>
                                                            <?php echo CHtml::link('<i class="fa fa-pencil"></i>',array('update', 'id'=>$value->id), array('class'=>'btn btn-primary btn-xs')); ?>
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
<script type="text/javascript">
    $(document).ready(function () {
        $(this).on('click', '.pull-quiz', function () {
            var api = '<?php echo Yii::app()->createUrl('/api/pullquiz'); ?>';
            var select = $('select[name="lessons"] option:selected').val().split("|");

            var grade = select[1];
            var lesson = select[0];

            var type = $('select[name="type"] option:selected').val();
            var semester = '<?php echo $optSemester; ?>';
            var year = '<?php echo $optTahunAjaran; ?>';

            var url = api + '?type=' + type + '&grade=' + grade + '&lesson=' + lesson + '&year=' + year + '&semester=' + semester;
            $('.modal-loading').addClass("show");
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    $('html').scrollTop(0);
                    if (data.success === 1) {
                        var quizes = data.result.quiz;
                        console.log(quizes);
                        $.each(quizes, function (index, quiz) {
                            $("#page-content-wrapper").prepend("<div class='alert alert-success notice'>" +
                                "<strong>Data Ujian " + quiz.title + " Untuk " + quiz.class + " Berhasil Tambahkan!</strong>" +
                                "<a href='#' class='close' data-dismiss='alert'>×</a></div>");
                        })
                    } else {
                        $("#page-content-wrapper").prepend("<div class='alert alert-danger notice'>" +
                            "<strong>Tidak Ada Data Ujian Yang Masuk!</strong>" +
                            "<a href='#' class='close' data-dismiss='alert'>×</a></div>");
                    }
                    $('.modal-loading').removeClass("show");
                }
            })
        });
    });
</script>