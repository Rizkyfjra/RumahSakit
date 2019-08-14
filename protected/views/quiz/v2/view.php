<?php
$server = Option::model()->find(array('condition'=>'key_config LIKE "%server%"'));
$online = @Option::model()->findByAttributes(array('key_config' => 'online'))->value;
$analisisoff = @Option::model()->findByAttributes(array('key_config' => 'analisisoff'))->value;

if ($model->lesson->moving_class == 1) {
    $namaKelas = $model->lesson->grade->name;
} else {
    $namaKelas = $model->lesson->class->name;
}

if (!Yii::app()->user->YiiStudent) {
    $range_1 = 0;
    $range_2 = 0;
    $range_3 = 0;
    $range_4 = 0;
    $range_5 = 0;
    $range_6 = 0;
    $range_7 = 0;
    $range_8 = 0;
    $range_9 = 0;
    $range_10 = 0;

    $raw = explode(',', $model->question);
    $soal_pg = 0;
    $soal_essay = 0;

    foreach ($raw as $sl) {
        $cekSoal = Questions::model()->findByPk($sl);
        if (!empty($cekSoal)) {
            if ($cekSoal->type != 2) {
                $soal_pg++;
            } else {
                $soal_essay++;
            }
        }
    }

    foreach ($student_quiz as $nk) {
        $benar = NULL;
        $salah = NULL;
        $kosong = NULL;
        $total_jawab = NULL;

        $nilaiEsai = 0;
         if($online && ($model->quiz_type == 4 || $model->quiz_type == 5 || $model->quiz_type == 6)){
            //dp nothin
         }else {

         
                if (!empty($nk->student_answer)) {
                        $jawaban = json_decode($nk->student_answer, true);
                      
                        if (!empty($jawaban)) {
                            foreach ($jawaban as $k => $val) {
                                $soal = Questions::model()->findByPk($k);
                                if ($soal->type != 2) {
                                    if (strtolower($soal->key_answer) == strtolower($val)) {
                                        $benar = $benar + 1;
                                    } else {
                                        $salah = $salah + 1;
                                    }
                                }
                            }
                        }
                        
                    if ($soal_pg != 0) {
                        $nilaiPg = round(($benar / $soal_pg) * 100);
                    } else {
                        $nilaiPg = 0;
                    }

                }
                $nilaiEsai = $nk->score - $nilaiPg;
        }
        
        if($model->quiz_type == 4 OR $model->quiz_type == 5 OR $model->quiz_type == 6){
            break;
        } else {
            if (($nk->score >= 0) && ($nk->score <= 10)) {
                $range_1 = $range_1 + 1;
            } else if (($nk->score >= 11) && ($nk->score <= 20)) {
                $range_2 = $range_2 + 1;
            } else if (($nk->score >= 21) && ($nk->score <= 30)) {
                $range_3 = $range_3 + 1;
            } else if (($nk->score >= 31) && ($nk->score <= 40)) {
                $range_4 = $range_4 + 1;
            } else if (($nk->score >= 41) && ($nk->score <= 50)) {
                $range_5 = $range_5 + 1;
            } else if (($nk->score >= 51) && ($nk->score <= 60)) {
                $range_6 = $range_6 + 1;
            } else if (($nk->score >= 61) && ($nk->score <= 70)) {
                $range_7 = $range_7 + 1;
            } else if (($nk->score >= 71) && ($nk->score <= 80)) {
                $range_8 = $range_8 + 1;
            } else if (($nk->score >= 81) && ($nk->score <= 90)) {
                $range_9 = $range_9 + 1;
            } else if (($nk->score >= 91) && ($nk->score <= 100)) {
                $range_10 = $range_10 + 1;
            }
        }

    }
}
?>
<div class="container-fluid">
    <div class="row">
        <?php
        // $this->renderPartial('v2/_breadcrumb_exam_detail', array(
        //   'model'=>$model
        // ));
        ?>
        <?php
        if ($model->lesson->moving_class == 1) {
            $kelasnya = $model->lesson->name;
            $idkelasnya = $model->lesson->id;
            $path_nya = 'lesson/' . $idkelasnya;
        } else {
            $kelasnya = $model->lesson->name;
            $idkelasnya = $model->lesson->id;
            $path_nya = 'lesson/' . $idkelasnya;
        }
        ?>
        <div class="col-md-12">
            <div id="bc1" class="btn-group btn-breadcrumb">
                <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda', array('/site/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Ujian</div>', array('/quiz/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>List</div>', array('/quiz/list'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>' . CHtml::encode($kelasnya) . '</div>', array($path_nya, 'type' => 'ulangan'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>' . CHtml::encode($model->title) . '</div>', array('#'), array('class' => 'btn btn-success')); ?>
            </div>
        </div>

        <div class="col-lg-12">
            <h2><?php echo ucfirst($model->title); ?> - <?php echo $namaKelas; ?></h2>
            <?php if (!Yii::app()->user->YiiStudent) { ?>
                <div class="row">
                    <div class="col-md-8">
                        <h4>Grafik Sebaran Nilai</h4>
                        <div class="col-card">
                            <div class="chart-column">
                                <div id="chartContainer" style="height: 323px; width: 100%;">
                                </div>
                                <?php if (empty($cekSoal)) { ?>
                                    <div class="chart-empty">
                                        <p>
                                            Grafik Sebaran Nilai Masih Kosong
                                        </p>
                                        <a href="<?php echo $this->createUrl('/questions/create?quiz_id=' . $model->id) ?>"
                                           class="btn btn-pn-primary btn-sm">
                                            <i class="fa fa-plus-circle"></i> Tambah Soal
                                        </a>
                                    </div>
                                <?php } ?>
                                <br/>
                                <div class="text-center">Sebaran Nilai Siswa</div>
                            </div>
                            <script>
                                window.onload = function () {
                                    var chart = new CanvasJS.Chart("chartContainer",
                                        {
                                            animationEnabled: true,
                                            height: 323,
                                            axisY: {
                                                gridThickness: 1,
                                                lineThickness: 0,
                                                tickThickness: 0,
                                                labelFontFamily: 'Proxima Nova'
                                            },
                                            axisX: {
                                                gridThickness: 0,
                                                lineThickness: 0,
                                                tickThickness: 0,
                                                labelFontFamily: 'Proxima Nova'
                                            },
                                            data: [
                                                {
                                                    type: "column", //change it to line, area, bar, pie, etc
                                                    dataPoints: [
                                                        {
                                                            x: 1,
                                                            y: <?php echo $range_1; ?>,
                                                            label: "0-10",
                                                            indexLabel: "" + <?php echo $range_1; ?> },
                                                        {
                                                            x: 2,
                                                            y: <?php echo $range_2; ?>,
                                                            label: "11-20",
                                                            indexLabel: "" + <?php echo $range_2; ?> },
                                                        {
                                                            x: 3,
                                                            y: <?php echo $range_3; ?>,
                                                            label: "21-30",
                                                            indexLabel: "" + <?php echo $range_3; ?> },
                                                        {
                                                            x: 4,
                                                            y: <?php echo $range_4; ?>,
                                                            label: "31-40",
                                                            indexLabel: "" + <?php echo $range_4; ?> },
                                                        {
                                                            x: 5,
                                                            y: <?php echo $range_5; ?>,
                                                            label: "41-50",
                                                            indexLabel: "" + <?php echo $range_5; ?> },
                                                        {
                                                            x: 6,
                                                            y: <?php echo $range_6; ?>,
                                                            label: "51-60",
                                                            indexLabel: "" + <?php echo $range_6; ?> },
                                                        {
                                                            x: 7,
                                                            y: <?php echo $range_7; ?>,
                                                            label: "61-70",
                                                            indexLabel: "" + <?php echo $range_7; ?> },
                                                        {
                                                            x: 8,
                                                            y: <?php echo $range_8; ?>,
                                                            label: "71-80",
                                                            indexLabel: "" + <?php echo $range_8; ?> },
                                                        {
                                                            x: 9,
                                                            y: <?php echo $range_9; ?>,
                                                            label: "81-90",
                                                            indexLabel: "" + <?php echo $range_9; ?> },
                                                        {
                                                            x: 10,
                                                            y: <?php echo $range_10; ?>,
                                                            label: "91-100",
                                                            indexLabel: "" + <?php echo $range_10; ?> },
                                                    ]
                                                }
                                            ]
                                        });
                                    console.log(chart)
                                    chart.render();
                                }
                            </script>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <h4>Informasi dan Status</h4>
                        <?php
                        // $this->renderPartial('v2/_card_exam_information', array(
                        //     'model'=>$model
                        // ));
                        ?>
                        <div class="col-card card-exam-switch">
                            <div class="pull-left">
                                <h4>
                                    Status Ujian
                                    <?php if ($model->status == 1) { ?>
                                        <span id="status-<?php echo $model->id ?>" class="label label-success">Ditampilkan</span>
                                    <?php } else if ($model->status == 2) { ?>
                                        <span id="status-<?php echo $model->id ?>"
                                              class="label label-danger">Ditutup</span>
                                    <?php } else { ?>
                                        <span id="status-<?php echo $model->id ?>"
                                              class="label label-warning">Draft</span>
                                    <?php } ?>
                                </h4>
                            </div>
                            <?php if (!Yii::app()->user->YiiStudent) { ?>
                                <div class="pull-right">
                                    <div class="pn-switch">
                                        <input type="checkbox" quiz-id="<?php echo $model->id; ?>" name="switch1"
                                               class="pn-switch-checkbox"
                                               id="switch1" <?php echo '', $model->status == 1 ? "checked" : ""; ?>>
                                        <label class="pn-switch-label" for="switch1"></label>
                                    </div>
                                    <script type="text/javascript">
                                        $('.pn-switch-checkbox').on("change", function () {
                                            $('.modal-loading').addClass("show");
                                            if (!$(this).prop('checked')) {
                                                var url = "<?php echo Yii::app()->baseUrl; ?>/quiz/ajaxHideQuiz/" + $(this).attr('quiz-id');
                                            } else {
                                                var url = "<?php echo Yii::app()->baseUrl; ?>/quiz/ajaxDisplayQuiz/" + $(this).attr('quiz-id');
                                            }
                                            var idStatus = 'status-' + $(this).attr('quiz-id');
                                            var idQuiz = $(this).attr('quiz-id');
                                            $.post(url,
                                                {},
                                                function (data, status) {
                                                    if ($('#' + idStatus).hasClass('label-success')) {
                                                        $('#' + idStatus).removeClass('label-success');
                                                        $('#' + idStatus).addClass('label-danger');
                                                        $('#' + idStatus).html('Ditutup');
                                                        $('.modal-loading').removeClass("show");
                                                    } else if ($('#' + idStatus).hasClass('label-warning')) {
                                                        $('#' + idStatus).removeClass('label-warning');
                                                        $('#' + idStatus).addClass('label-success');
                                                        $('#' + idStatus).html('Ditampilkan');
                                                        $('.modal-loading').removeClass("show");
                                                    } else if ($('#' + idStatus).hasClass('label-danger')) {
                                                        $('#' + idStatus).removeClass('label-danger');
                                                        $('#' + idStatus).addClass('label-success');
                                                        $('#' + idStatus).html('Ditampilkan');
                                                        $('.modal-loading').removeClass("show");
                                                    }
                                                });
                                        });
                                    </script>
                                </div>
                            <?php } ?>
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-card">
                            <table class="table table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>
                                        Informasi
                                    </th>
                                    <th>
                                        Detail
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        Waktu Pengerjaan
                                    </td>
                                    <td>
                                        <?php echo $model->end_time; ?> Menit
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Total Pertanyaan
                                    </td>
                                    <td>
                                        <?php echo $model->total_question; ?>
                                    </td>
                                </tr>
                                <?php if (!Yii::app()->user->YiiStudent) { ?>
                                    <tr>
                                        <td>
                                            Acak Soal
                                        </td>
                                        <td>
                                            <?php
                                            if ($model->random == 1) {
                                                echo "Ya";
                                            } else {
                                                echo "Tidak";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Acak Jawaban
                                        </td>
                                        <td>
                                            <?php
                                            if ($model->random_opt == 1) {
                                                echo "Ya";
                                            } else {
                                                echo "Tidak";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Tampil Nilai
                                        </td>
                                        <td>
                                            <?php
                                            if ($model->show_nilai == 1) {
                                                echo "Ya";
                                            } else {
                                                echo "Tidak";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Percobaan Kuis
                                        </td>
                                        <td>
                                            <?php echo $model->repeat_quiz; ?> Kali
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Kode Pembuka
                                        </td>
                                        <td>
                                            <code><?php echo $model->passcode; ?></code>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>

                            <?php if (!Yii::app()->user->YiiStudent) { ?>

                         
                                <!-- Single button -->
                                <div class="btn-group btn-block dropup">
                                  
                                    <button type="button" class="btn btn-pn-primary btn-lg btn-block dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-gear"></i> Aksi Ujian <i class="fa fa-caret-up"></i>
                                    </button>
                                    <ul class="dropdown-menu btn-block">
                                        <li><?php 
                                            if(!$online && ($model->quiz_type == 4 || $model->quiz_type == 5 || $model->quiz_type == 6)){
                                                echo "";
                                            } else {
                                                echo CHtml::link('<i class="fa fa-pencil"></i> Sunting Ujian', array('update', 'id' => $model->id)); 
                                            }
                                            ?>
                                        <li><?php 
                                        if(!$online && ($model->quiz_type == 4 || $model->quiz_type == 5 || $model->quiz_type == 6)){
                                                echo "";
                                            } else {
                                                 echo CHtml::link('<i class="fa fa-pencil"></i> Export Sql Ujian', array('Exportsql', 'id' => $model->id));
                                             }
                                          ?>
                                            
                                        <li><?php 
                                            if(!$online && ($model->quiz_type == 4 || $model->quiz_type == 5 || $model->quiz_type == 6)){
                                                echo "";
                                            } else {
                                                echo CHtml::link('<i class="fa fa-eye"></i> Pratinjau Ujian', array('startQuiz', 'id' => $model->id)); 
                                            } ?>
                                        <li><?php 
                                            // if(!$online && ($model->quiz_type == 4 || $model->quiz_type == 5 || $model->quiz_type == 6)){
                                           // /     echo "";
                                            // } else {
                                                echo CHtml::link('<i class="fa fa-pencil"></i> Download Nilai', array('downloadnilai', 'id' => $model->id)); 
                                            // }    
                                            ?>
                                            <li><?php 
                                            // if(!$online && ($model->quiz_type == 4 || $model->quiz_type == 5 || $model->quiz_type == 6)){
                                           // /     echo "";
                                            // } else {
                                                echo CHtml::link('<i class="fa fa-pencil"></i> Download Nilai dan Jawaban', array('downloadnilairesult', 'id' => $model->id)); 
                                            // }    
                                            ?>
                                        <li role="separator" class="divider"></li>
                                        <li>
                                        <?php 
                                         if(!$online && ($model->quiz_type == 4 || $model->quiz_type == 5 || $model->quiz_type == 6)){
                                                echo "";
                                             } else {
                                                echo CHtml::link('<i class="fa fa-copy"></i> Salin Ujian', array('copy', 'id' => $model->id)); 
                                             }
                                        ?>
                                    </ul>
                                    
                                </div>
                                <?php 
                            } else {
                                $cekQuiz = StudentQuiz::model()->findByAttributes(array('quiz_id' => $model->id, 'student_id' => Yii::app()->user->id));

                                if (!empty($cekQuiz)) {
                                    if ($cekQuiz->attempt == $model->repeat_quiz) {
                                        ?>
                                        <a href="#" class="btn btn-danger btn-lg btn-block">
                                            <i class="fa fa-times"></i> Sudah Mengerjakan
                                        </a>
                                        <?php
                                    } else {
                                        if ($model->status == 1) {
                                            ?>
                                            <a href="<?php echo $this->createUrl('/quiz/startQuiz?id=' . $model->id . '&sq=' . $cekQuiz->id) ?>"
                                               class="btn btn-pn-primary btn-lg btn-block">
                                                <i class="fa fa-pencil"></i> Mulai
                                            </a>
                                            <?php
                                        }
                                    }
                                } else {
                                    if ($model->status == 1) {
                                        ?>
                                        <a href="<?php echo $this->createUrl('/quiz/startQuiz?id=' . $model->id) ?>"
                                           class="btn btn-pn-primary btn-lg btn-block">
                                            <i class="fa fa-pencil"></i> Mulai
                                        </a>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>

                    </div>
                </div>
            <?php } else { //if student ?>
                <div class="row">
                    <div class="col-md-12">
                        <h4>Informasi dan Status</h4>
                        <?php
                        // $this->renderPartial('v2/_card_exam_information', array(
                        //     'model'=>$model
                        // ));
                        ?>
                        <div class="col-card card-exam-switch">
                            <div class="pull-left">
                                <h4>
                                    Status Ujian
                                    <?php if ($model->status == 1) { ?>
                                        <span id="status-<?php echo $model->id ?>" class="label label-success">Ditampilkan</span>
                                    <?php } else if ($model->status == 2) { ?>
                                        <span id="status-<?php echo $model->id ?>"
                                              class="label label-danger">Ditutup</span>
                                    <?php } else { ?>
                                        <span id="status-<?php echo $model->id ?>"
                                              class="label label-warning">Draft</span>
                                    <?php } ?>
                                </h4>
                            </div>
                            <?php if (!Yii::app()->user->YiiStudent) { ?>
                                <div class="pull-right">
                                    <div class="pn-switch">
                                        <input type="checkbox" quiz-id="<?php echo $model->id; ?>" name="switch1"
                                               class="pn-switch-checkbox"
                                               id="switch1" <?php echo '', $model->status == 1 ? "checked" : ""; ?>>
                                        <label class="pn-switch-label" for="switch1"></label>
                                    </div>
                                    <script type="text/javascript">
                                        $('.pn-switch-checkbox').on("change", function () {
                                            $('.modal-loading').addClass("show");
                                            if (!$(this).prop('checked')) {
                                                var url = "<?php echo Yii::app()->baseUrl; ?>/quiz/ajaxHideQuiz/" + $(this).attr('quiz-id');
                                            } else {
                                                var url = "<?php echo Yii::app()->baseUrl; ?>/quiz/ajaxDisplayQuiz/" + $(this).attr('quiz-id');
                                            }
                                            var idStatus = 'status-' + $(this).attr('quiz-id');
                                            var idQuiz = $(this).attr('quiz-id');
                                            $.post(url,
                                                {},
                                                function (data, status) {
                                                    if ($('#' + idStatus).hasClass('label-success')) {
                                                        $('#' + idStatus).removeClass('label-success');
                                                        $('#' + idStatus).addClass('label-danger');
                                                        $('#' + idStatus).html('Ditutup');
                                                        $('.modal-loading').removeClass("show");
                                                    } else if ($('#' + idStatus).hasClass('label-warning')) {
                                                        $('#' + idStatus).removeClass('label-warning');
                                                        $('#' + idStatus).addClass('label-success');
                                                        $('#' + idStatus).html('Ditampilkan');
                                                        $('.modal-loading').removeClass("show");
                                                    } else if ($('#' + idStatus).hasClass('label-danger')) {
                                                        $('#' + idStatus).removeClass('label-danger');
                                                        $('#' + idStatus).addClass('label-success');
                                                        $('#' + idStatus).html('Ditampilkan');
                                                        $('.modal-loading').removeClass("show");
                                                    }
                                                });
                                        });
                                    </script>
                                </div>
                            <?php } ?>
                            <div class="clearfix"></div>
                        </div>
                        <div class="col-card">
                            <table class="table table-hover table-condensed">
                                <thead>
                                <tr>
                                    <th>
                                        Informasi
                                    </th>
                                    <th>
                                        Detail
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>
                                        Waktu Pengerjaan
                                    </td>
                                    <td>
                                        <?php echo $model->end_time; ?> Menit
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Total Pertanyan
                                    </td>
                                    <td>
                                        <?php echo $model->total_question; ?>
                                    </td>
                                </tr>
                                <?php if (!Yii::app()->user->YiiStudent) { ?>
                                    <tr>
                                        <td>
                                            Acak Soal
                                        </td>
                                        <td>
                                            <?php
                                            if ($model->random == 1) {
                                                echo "Ya";
                                            } else {
                                                echo "Tidak";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Acak Jawaban
                                        </td>
                                        <td>
                                            <?php
                                            if ($model->random_opt == 1) {
                                                echo "Ya";
                                            } else {
                                                echo "Tidak";
                                            }
                                            ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Percobaan Kuis
                                        </td>
                                        <td>
                                            <?php echo $model->repeat_quiz; ?> Kali
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            Kode Pembuka
                                        </td>
                                        <td>
                                            <code><?php echo $model->passcode; ?></code>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </tbody>
                            </table>

                            <?php if (!Yii::app()->user->YiiStudent) { ?>
                                <!-- Single button -->
                                <div class="btn-group btn-block dropup">
                                    <button type="button" class="btn btn-pn-primary btn-lg btn-block dropdown-toggle"
                                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <i class="fa fa-gear"></i> Aksi Ujian <i class="fa fa-caret-up"></i>
                                    </button>
                                    <ul class="dropdown-menu btn-block">
                                        <li><?php echo CHtml::link('<i class="fa fa-pencil"></i> Sunting Ujian', array('update', 'id' => $model->id)); ?>
                                        <li><?php echo CHtml::link('<i class="fa fa-eye"></i> Pratinjau Ujian', array('startQuiz', 'id' => $model->id)); ?>
                                        <li><?php echo CHtml::link('<i class="fa fa-download"></i> Unduh Nilai Ujian', array('downloadNilai', 'id' => $model->id)); ?>
                                        <li role="separator" class="divider"></li>
                                        <li><?php echo CHtml::link('<i class="fa fa-copy"></i> Salin Ujian', array('copy', 'id' => $model->id)); ?>
                                    </ul>
                                </div>
                                <?php
                            } else {
                                $cekQuiz = StudentQuiz::model()->findByAttributes(array('quiz_id' => $model->id, 'student_id' => Yii::app()->user->id));

                                if (!empty($cekQuiz)) {
                                    if ($cekQuiz->attempt == $model->repeat_quiz) {
                                        ?>
                                        <a href="#" class="btn btn-danger btn-lg btn-block">
                                            <i class="fa fa-times"></i> Sudah Mengerjakan
                                        </a>
                                        <?php
                                    } else {
                                        if ($model->status == 1) {
                                            ?>
                                            <a href="<?php echo $this->createUrl('/quiz/startQuiz?id=' . $model->id . '&sq=' . $cekQuiz->id) ?>"
                                               class="btn btn-pn-primary btn-lg btn-block">
                                                <i class="fa fa-pencil"></i> Mulai
                                            </a>
                                            <?php
                                        }
                                    }
                                } else {
                                    if ($model->status == 1) {
                                        ?>
                                        <a href="<?php echo $this->createUrl('/quiz/startQuiz?id=' . $model->id) ?>"
                                           class="btn btn-pn-primary btn-lg btn-block">
                                            <i class="fa fa-pencil"></i> Mulai
                                        </a>
                                        <?php
                                    }
                                }
                            }
                            ?>
                        </div>

                    </div>
                </div>
            <?php } ?>
            <?php if (!Yii::app()->user->YiiStudent){ ?>
            <div class="row">
                <div class="col-md-12">
                    <h4>Detail Ujian</h4>
                    <div class="col-card card-floating-button">
                        <div>
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs" role="tablist">
                                <li role="presentation" class="active">
                                    <a href="#tabSoal" aria-controls="tabSoal" role="tab" data-toggle="tab">
                                        <i class="fa fa-list"></i> Soal
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#nilai" aria-controls="nilai" role="tab" data-toggle="tab"><i
                                                class="fa fa-chart"></i>
                                        <i class="fa fa-line-chart"></i> Daftar Nilai
                                    </a>
                                </li>
                                <li role="presentation">
                                    <a href="#nilai-sudah" aria-controls="nilai-sudah" role="tab" data-toggle="tab"><i
                                                class="fa fa-chart"></i>
                                        <i class="fa fa-line-chart"></i> Status
                                    </a>
                                </li>
                                <?php if(!$analisisoff) { ?>
                                <li role="presentation">
                                    <a href="#tabAnalisis" aria-controls="tabAnalisis" role="tab" data-toggle="tab"><i
                                                class="fa fa-chart"></i>
                                        <i class="fa fa-line-chart"></i> Analisis
                                    </a>
                                </li>
                                <?php } ?>
                            </ul>
                            <br/>
                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div role="tabpanel" class="tab-pane active" id="tabSoal">
                                    <form action="<?php echo Yii::app()->createUrl("/quiz/DeleteBulkQuestion") ?>"
                                          method="post"
                                          onsubmit="return confirm('Yakin Melakukan Aksi Ini Untuk Semua Yang Dipilih?');">
                                        <div class="button-floating-right-top">
                                            <a href="<?php echo $this->createUrl('/quiz/UploadNilai/' . $model->id) ?>"
                                               class="btn btn-success btn-sm push-score">
                                                <i class="fa fa-upload"></i> Unggah Nilai Ujian
                                            </a>
                                   <!--          <a href="<?php //echo $this->createUrl('/quiz/ExportNilai/' . $model->id) ?>"
                                               class="btn btn-primary btn-sm">
                                                <i class="fa fa-file-text-o"></i> Export Hasil Ujian
                                            </a> -->
                                            <a href="<?php echo $this->createUrl('/questions/create?quiz_id=' . $model->id) ?>"
                                               class="btn btn-pn-primary btn-sm">
                                                <i class="fa fa-plus-circle"></i> Tambah Soal
                                            </a>
                                            <a href="#" data-toggle="modal" data-target="#modalBankSoal"
                                               class="btn btn-primary btn-sm">
                                                <i class="fa fa-file-text-o"></i> Bank Soal
                                            </a>
                                            <input type="hidden" name="quiz_id" value="<?php echo $model->id ?>">
                                            <input type="submit" name="hapus" value="Hapus Soal" class="btn btn-danger">
                                        </div>
                                        <?php if (empty($cekSoal)){ ?>
                                        <div class="table-empty">
                                            <p>
                                                Data Soal Anda Masih Kosong
                                            </p>
                                            <a href="<?php echo $this->createUrl('/questions/create?quiz_id=' . $model->id) ?>"
                                               class="btn btn-pn-primary btn-sm">
                                                <i class="fa fa-plus-circle"></i> Tambah Soal
                                            </a>
                                        </div>
                                        <?php } else if(!$online && ($model->quiz_type == 4 OR $model->quiz_type == 5 OR $model->quiz_type == 6)){ ?>
                                        <div class="table-empty">
                                            <p>
                                                Data Soal TidaK Bisa Dilihat
                                            </p>
                                        </div>
                                        <?php } else { ?>
                                          
                                            <div class="exam-list-table">
                                                <?php
                                                if (!empty($model->question)) {
                                                    $pertanyaan = explode(',', $model->question);

                                                    // $this->renderPartial('v2/_table_exam', array(
                                                    //   'pertanyaan'=>$pertanyaan,'student_quiz'=>$student_quiz,'quiz_id'=>$model->id
                                                    // ));
                                                    ?>
                                                    <!-- Table Pertannyaan -->
                                                    <div class="table-responsive">
                                                        <table class="table table-hover">
                                                            <thead>
                                                            <tr>
                                                                <th>No.</th>
                                                                <th>Pertanyaan</th>
                                                                <th width="5%">Jumlah Benar</th>
                                                                <th width="5%">Jumlah Salah</th>
                                                                <th width="10%">Aksi</th>
                                                                <th width="5%">
                                                                    <input type="checkbox" id="selectAllQuestion">
                                                                </th>

                                                            </tr>
                                                            </thead>
                                                            <tbody>
                                                            <?php
                                                            $no = 1;
                                                            $doc_types = array('doc', 'docx', 'pdf', 'xls', 'xlsx', 'ppt', 'pptx');
                                                            $vid_types = array('swf', 'mp4', 'MP4', 'avi', 'mkv', 'flv');
                                                            $img_types = array('jpg', 'png', 'gif');
                                                            $audio_types = array('mp3', 'ogg', 'aac');
                                                            foreach ($pertanyaan as $key) {
                                                                $detail = Questions::model()->findByPk($key);


                                                                $benar_per_soal = 0;
                                                                $salah_per_soal = 0;
                                                                $jawab = NULL;
                                                                $total_salah = 0;

                                                                foreach ($student_quiz as $jawabs) {
                                                                    $siswa_jawab = json_decode($jawabs->student_answer);
                                                                    if (!empty($siswa_jawab)) {
                                                                        foreach ($siswa_jawab as $jwbs => $isis) {
                                                                            if ($detail->id == $jwbs) {
                                                                                $the_key = $detail->key_answer;

                                                                                $the_key = preg_replace("/\r|\n/", "", $the_key);
                                                                                $the_key = strip_tags($the_key,"<img>");
                                                                                $the_key = preg_replace('/\s+/', '', $the_key);
                                                                                $the_key =  str_replace("/>","",$the_key);
                                                                                $the_key =  str_replace(">","",$the_key); 
                                                                                    

                                                                                $isis = preg_replace("/\r|\n/", "", $isis);
                                                                                $isis = strip_tags($isis,"<img>");
                                                                                $isis = preg_replace('/\s+/', '', $isis);
                                                                                $isis =  str_replace("/>","",$isis);
                                                                                $isis =  str_replace(">","",$isis);          

                                                                                if ($isis == $the_key) {
                                                                                    $benar_per_soal = $benar_per_soal + 1;
                                                                                } else {
                                                                                    $salah_per_soal = $salah_per_soal + 1;
                                                                                }
                                                                            }
                                                                        }
                                                                    } else {
                                                                        $total_salah = $total_salah + 1;
                                                                    }
                                                                }

                                                                $total_per_soal = $benar_per_soal + $salah_per_soal;

                                                                if ($total_per_soal != 0) {
                                                                    $persenBenar = round(($benar_per_soal / $total_per_soal) * 100);
                                                                    $persenSalah = round(($salah_per_soal / $total_per_soal) * 100);
                                                                } else {
                                                                    $persenBenar = 0;
                                                                    $persenSalah = 0;
                                                                }

                                                                // $this->renderPartial('v2/_table_exam_list', array(
                                                                //   'no'=>$no,'detail'=>$detail,'persenBenar'=>$persenBenar,'persenSalah'=>$persenSalah,'quiz_id'=>$model->id,'key' =>$key
                                                                // ));
                                                                ?>
                                                                <!-- Butir Pertanyaan -->
                                                                <?php
                                                                $choices = json_decode($detail->choices);
                                                                $jumChoices = count($choices);
                                                                ?>
                                                                <tr>
                                                                    <td><?php echo $no; ?></td>
                                                                    <td class="collapsible-row-container">
                                                                        <div class="collapsible-row">
                                                                            <div>
                                                                                <?php echo $detail->text; ?>
                                                                                <?php if (!empty($detail->file)) { ?>

                                                                                    <?php
                                                                                    $path_image = Clases::model()->path_image($detail->id);
                                                                                    $img_url = Yii::app()->baseUrl . '/images/question/' . $path_image . '/' . $detail->file; ?>

                                                                                    <?php $ext = pathinfo($detail->file, PATHINFO_EXTENSION); ?>
                                                                                    <?php if (in_array($ext, $audio_types)) { ?>
                                                                                        <audio controls>
                                                                                            <!-- <source src="horse.ogg" type="audio/ogg"> -->
                                                                                            <source src="<?php echo $img_url; ?>"
                                                                                                    type="audio/mpeg">

                                                                                        </audio>
                                                                                    <?php } elseif (in_array($ext, $img_types)) { ?>
                                                                                        <img src="<?php echo $img_url; ?>"
                                                                                             class="img-responsive">
                                                                                    <?php } elseif (in_array($ext, $vid_types)) { ?>
                                                                                        <div class="img-responsive">
                                                                                            <center>
                                                                                                <?php
                                                                                                $this->widget('ext.jwplayer.Jwplayer', array(
                                                                                                    'width' => 250,
                                                                                                    'height' => 250,
                                                                                                    'file' => $img_url, // the file of the player, if null we use demo file of jwplayer
                                                                                                    'image' => NULL, // the thumbnail image of the player, if null we use demo image of jwplayer
                                                                                                    'options' => array(
                                                                                                        'controlbar' => 'bottom'
                                                                                                    )
                                                                                                ));
                                                                                                ?>
                                                                                            </center>
                                                                                        </div>
                                                                                    <?php } ?>
                                                                                <?php } ?>
                                                                            </div>
                                                                            <br/>
                                                                            <div class="clearfix"></div>
                                                                            <?php if ($detail->type == NULL) { ?>
                                                                                <div class="exam-answer">
                                                                                    <h4>Pilihan Jawaban</h4>
                                                                                    <?php
                                                                                    // print_r($choices);
                                                                                    // for($i=0; $i<$jumChoices; $i++){
                                                                                    if (!empty($choices)) {
                                                                                    foreach ($choices as $choi) {

                                                                                        $the_key_answer = $detail->key_answer;
                                                                                        $the_choi = $choi;

                                                                                      $the_key_answer = preg_replace("/\r|\n/", "", $the_key_answer);
                                                                                      $the_key_answer = strip_tags($the_key_answer,"<img>");
                                                                                      $the_key_answer = preg_replace('/\s+/', '', $the_key_answer);
                                                                                      $the_key_answer =  str_replace("/>","",$the_key_answer);
                                                                                      $the_key_answer =  str_replace(">","",$the_key_answer);     

                                                                                      $the_choi = preg_replace("/\r|\n/", "", $the_choi);
                                                                                      $the_choi = strip_tags($the_choi,"<img>");
                                                                                      $the_choi = preg_replace('/\s+/', '', $the_choi);
                                                                                      $the_choi =  str_replace("/>","",$the_choi);
                                                                                      $the_choi =  str_replace(">","",$the_choi); 
                                                                                    

                                                                                           


                                                                                    if ($the_choi == $the_key_answer){
                                                                                    ?>
                                                                                    <div class="alert alert-success"
                                                                                         role="alert">
                                                                                        <?php
                                                                                        }else{
                                                                                        ?>
                                                                                        <div class="alert alert-danger"
                                                                                             role="alert">
                                                                                            <?php
                                                                                            }
                                                                                            ?>
                                                                                            <?php
                                                                                            echo $choi
                                                                                            ?>
                                                                                        </div>
                                                                                        <?php
                                                                                        }
                                                                                        }
                                                                                        ?>
                                                                                    </div>
                                                                                    <br/><br/>
                                                                                </div>
                                                                            <?php } else { ?>
                                                                                <div class="exam-answer">
                                                                                    <h4>Soal Essay</h4>
                                                                                </div>
                                                                            <?php } ?>
                                                                    </td>
                                                                    <td>
                                                                        <span class="text-green text-bold"><?php echo $persenBenar; ?>
                                                                            %</span>
                                                                    </td>
                                                                    <td>
                                                                        <span class="text-red text-bold"><?php echo $persenSalah; ?>
                                                                            %</span>
                                                                    </td>
                                                                    <td>
                                                                        <div class="btn-group">
                                                                            <?php if (Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin) { ?>
                                                                                <a href="<?php echo $this->createUrl('/questions/update/' . $detail->id . '?quiz_id=' . $model->id) ?>"
                                                                                   class="btn btn-primary btn-xs">
                                                                                    <i class="fa fa-pencil"></i>
                                                                                </a>
                                                                                <a href="<?php echo $this->createUrl('/quiz/deleteQuestion?quiz=' . $model->id . '&question=' . $key) ?>"
                                                                                   class="btn btn-danger btn-xs"
                                                                                   onclick="return confirm('Yakin Menghapus Soal Ini Dari Ujian ?');">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </a>
                                                                            <?php } ?>
                                                                        </div>
                                                                    </td>
                                                                    <td>
                                                                        <input type="checkbox" name="questions[]"
                                                                               class="questions"
                                                                               value="<?php echo $key ?>">
                                                                    </td>
                                                                </tr>

                                                                <!-- Akhir Butir Pertanyaan -->
                                                                <?php

                                                                $no++;
                                                            }
                                                            ?>
                                                            </tbody>
                                                        </table>

                                                    </div>


                                                    <!-- Akhir Table Pertannyaan -->
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                       
                                        <?php } ?>
                                    </form>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="tabAnalisis">
                                    <?php if (empty($cekSoal)) { ?>
                                        <div class="table-empty">
                                            <p>
                                                Data Analisis Anda Masih Kosong
                                            </p>
                                            <a href="<?php echo $this->createUrl('/questions/create?quiz_id=' . $model->id) ?>"
                                               class="btn btn-pn-primary btn-sm">
                                                <i class="fa fa-plus-circle"></i> Tambah Soal
                                            </a>
                                        </div>
                                    <?php } else if($model->quiz_type == 4 OR $model->quiz_type == 5 OR $model->quiz_type == 6){ ?>
                                        <div class="table-empty">
                                            <p>
                                                Data Analisis TidaK Bisa Dilihat
                                            </p>
                                        </div>
                                    <?php } else {
                                        if ($model->status != 1) {
                                            ?>
                                            <!--  <div class="button-floating-right-top">
                                               <a href="#" class="btn btn-primary btn-sm">
                                                 <i class="fa fa-download"></i> Unduh ke Berkas Excel
                                               </a>
                                             </div> -->
                                            <div class="tab-pane-analysis">
                                                <div class="row text-center">
                                                    <div class="col-md-3 col-sm-3">
                                                        <span class="text-point"><i class="fa fa-arrow-circle-up"></i> Nilai Tertinggi:</span>
                                                        <span class="text-green text-score"><?php echo $max[0]->score; ?></span>
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        <span class="text-point"><i class="fa fa-arrow-circle-down"></i> Nilai Terendah:</span>
                                                        <span class="text-green text-score"><?php echo $min[0]->score; ?></span>
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        <span class="text-point"><i class="fa fa-info-circle"></i> Rata-Rata:</span>
                                                        <span class="text-green text-score"><?php echo round($avg[0]->score, 2); ?></span>
                                                    </div>
                                                    <div class="col-md-3 col-sm-3">
                                                        <span class="text-point"><i class="fa fa-info-circle"></i> Standar Deviasi:</span>
                                                        <span class="text-green text-score"><?php echo round($stdev, 2); ?></span>
                                                    </div>
                                                </div>
                                                <div class="analysis-table">
                                                    <?php
                                                    $this->renderPartial('v2/_table_exam_analysis', array(
                                                        'model' => $model, 'student_quiz' => $student_quiz
                                                    ));
                                                    ?>
                                                </div>
                                            </div>
                                        <?php } else { ?>
                                            <div class="table-empty">
                                                <p>
                                                    Tutup Ulangan Terlebih Dahulu Untuk Melihat Data Analisis
                                                </p>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="nilai">

                                      <?php if ($model->status != 1) { ?>
                                        
                                    <?php $this->renderPartial('v2/_table_nilai', array('siswa' => $siswa, "model" => $model)); ?>

                                     <?php } else { ?>
                                                 <div class="table-empty">
                                                <p>
                                                    Tutup Ulangan Terlebih Dahulu Untuk Melihat Nilai Siswa, untuk melihat status mengerjakan siswa bisa akses tab status.
                                                </p>
                                            </div>
                                        <?php }?>
                                </div>
                                <div role="tabpanel" class="tab-pane" id="nilai-sudah">
                                  

                                      <table class="table table-hover table-bordered table-responsive">
                                        <tr class="danger">
                                        <th>No</th>
                                        <th>Nama Siswa</th>
                                        </tr>
                                       
                                          <?php 
                                            function searchForId($id, $array) {
                                               foreach ($array as $key => $val) {
                                                   if ($val['student_id'] === $id) {
                                                        return $key;
                                                   } 
                                               }
                                               return -1;
                                            }

                                            // echo "<pre>";
                                            // print_r($result_siswa);
                                            // echo "</pre>";
                                            $nonya = 1;
                                        foreach ($result_siswa as $key => $value) {

                                            // echo searchForId($value["user_id"],$student_quiz)."</br>";
                                            if (searchForId($value["user_id"],$student_quiz)>=0) {
                                                // echo "<pre>";
                                                // print_r($value);
                                                // echo "</pre>";
                                                echo "<tr class=\"info\">";
                                                echo "<td>".$nonya."</td>";
                                                echo "<td>".$value["nama_lengkap"]." (Sudah Selesai)"."</td>";
                                                echo "</tr>";
                                            } else {
                                                // echo "<pre>";
                                                // print_r($value["nama_lengkap"]);
                                                // echo "</pre>";
                                                echo "<tr>";
                                                echo "<td>".$nonya."</td>";
                                                echo "<td>".$value["nama_lengkap"]."</td>";
                                                echo "</tr>";
                                            }

                                            $nonya++;
                                            
                                        }  ?>
                                       
                                   
                                     </table>
                                </div>
                                <div class="visible-xs">
                                    <strong>Informasi:</strong>
                                    <br>
                                    <i>Untuk anda yang mengakses lewat handphone/smartphone, silahkan geser tabel ke
                                        kiri
                                        untuk melihat data selengkapnya</i>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>

<?php if (!Yii::app()->user->YiiStudent) { ?>
    <div class="modal fade" id="modalBankSoal" tabindex="-1" role="dialog" aria-labelledby="filterScoreLabel">

        <form method='POST' name='checkform' id='checkform'
              action='<?php echo Yii::app()->createUrl("questions/bulkSoal") ?>'>

            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                    aria-hidden="true">&times;</span></button>
                        <h4 class="modal-title text-center" id="forgotPasswordLabel">Pilihan Soal</h4>
                    </div>
                    <div class="modal-body">

                        <p class="text-center">
                            <strong>
                                Berikut adalah soal-soal yang terdapat dalam bank soal,<br/>
                                Silahkan pilih soal-soal yang ingin dimasukkan ke
                                Ujian <?php echo ucfirst($model->title); ?>.
                            </strong>
                        </p>
                        <div class="scrollable-table-list">
                            <table class="table">
                                <thead>
                                <tr>
                                    <th width="5%">No.</th>
                                    <th width="75%">Pertanyaan</th>
                                    <th width="15%">Kunci Jawaban</th>
                                    <th width="5%"><input type="checkbox" id="selectAll"></th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($_GET['page'])) {
                                    $i = (count($questions) * $_GET['page']) - (count($questions) - 1);
                                } else {
                                    $i = 1;
                                }

                                foreach ($questions as $value) {
                                    ?>
                                    <tr>
                                        <td width="5%"><?php echo $i; ?></td>
                                        <td width="75%"><?php echo $value->text; ?></td>
                                        <?php
                                        if (!empty($value->choices)) {
                                            $pil = json_decode($value->choices, true);
                                        } else {
                                            $pil = null;
                                        }
                                        $n = 'A';
                                        ?>
                                        <td width="15%"><?php echo $value->key_answer; ?></td>
                                        <td width="5%">
                                            <?php echo "<input type='checkbox' name='soal[]' value=$value->id class='soal'>"; ?>
                                        </td>
                                    </tr>
                                    <?php
                                    $i++;
                                }
                                ?>
                                </tbody>
                            </table>
                            <br/><br/>
                            <div class="text-center">
                                <?php
                                $this->widget('CLinkPager', array('pages' => $pages,
                                    'maxButtonCount' => 10,
                                ));
                                ?>
                            </div>
                            <input type="hidden" value="<?php echo $model->id; ?>" name="quiz">
                            <button type="submit" class="btn btn-pn btn-pn-primary btn-lg btn-block">Tambahkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

<?php } ?>

<?php if (!Yii::app()->user->YiiStudent) { ?>
    <script type="text/javascript">
        console.log(localStorage);

        <?php if (!empty($_GET['page'])) { ?>
        $('#modalBankSoal').modal('show');
        <?php }?>

        $('#selectAllQuestion').click(function (event) {
            if (this.checked) {
                $('.questions').each(function () {
                    this.checked = true;
                });
            } else {
                $('.questions').each(function () {
                    this.checked = false;
                });
            }
        });

        $('.push-score').click(function () {
            $('.modal-loading').addClass("show");
        });


        $('#selectAll').click(function (event) {
            if (this.checked) {
                $('.soal').each(function () {
                    this.checked = true;
                });
            } else {
                $('.soal').each(function () {
                    this.checked = false;
                });
            }
        });
    </script>
<?php } ?>
</div>
