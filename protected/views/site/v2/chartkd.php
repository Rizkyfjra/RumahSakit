<?php
if ($school != "all") {
    $classlist = $class_list[$school];
    $total_student = $total_class;
} else {
    $classlist = array();
    $total_student = $total_school;
}
?>
<style type="text/css">
    .tab-pane h1 {
        font-family: "ProximaNova-Bold";
        font-size: 72px;
    }
    #page-content-wrapper {
        padding: 0px !important;
    }
</style>
<div class="container-fluid" style="background: #ecf0f5">
    <div class="row">
        <div class="col-md-12">
            <!-- <div id="bc1" class="btn-group btn-breadcrumb">
            <?php //echo CHtml::link('<i class="fa fa-home"></i> Beranda', array('/site/index'), array('class' => 'btn btn-default'));  ?>
            <?php //echo CHtml::link('<div>Hasil Ujian KD</div>', array('#'), array('class' => 'btn btn-success')); ?>
            </div> -->
        </div>
        <div class="col-md-12">
            <div class="login-box">
                <div class="col-card">
                    <form>
                        <?php echo CHtml::dropDownList('type', $type, array('uts'=>'UTS', 'uas'=>'UAS', 'us'=>'US'), array(
                            'class' => 'selectpicker',
                            'id' => 'typelist',
                            'data-style' => 'btn-default input-lg',
                            'data-live-search' => 'true'));
                        ?>
                        <?php
                        echo CHtml::dropDownList('quiz', $quiz, $quiz_list, array(
                            'class' => 'selectpicker',
                            'id' => 'quizlist',
                            'data-style' => 'btn-default input-lg',
                            'data-live-search' => 'true'));
                        ?>
                        <?php
                        echo CHtml::dropDownList('school', $school, $school_list, array(
                            'class' => 'selectpicker',
                            'id' => 'schoolist',
                            'data-style' => 'btn-default input-lg',
                            'data-live-search' => 'true'));
                        ?>
                        <?php
                        echo CHtml::dropDownList('class', $class, $classlist, array(
                            'class' => 'selectpicker',
                            'id' => 'classlist',
                            'data-style' => 'btn-default input-lg',
                            'data-live-search' => 'true'));
                        ?>
                        <input type="submit" class="btn btn-success input-lg pull-right" style="width: 205px" value="Cari...">
                    </form>
                </div>
                <div class="col-card">
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation">
<?php echo CHtml::link('<i class="fa fa-list"></i> Nilai KD', array('site/hasilkd?quiz=' . $quiz . '&type=' . $type . '&school=' . $school . '&class=' . $class), array('class' => '')); ?>
                            <!-- <a href="#tabChart" aria-controls="nilai" role="tab" data-toggle="tab">
                            <i class="fa fa-line-chart"></i> Chart KD -->
                            <!-- </a> -->
                        </li>
                        <li role="presentation" class="active">
                            <a href="#tabNilai" aria-controls="tabSoal" role="tab" data-toggle="tab">
                                <i class="fa fa-bar-chart"></i> Chart KD
                            </a>
                        </li>  
                        <li role="presentation">
<?php echo CHtml::link('<i class="fa fa-map"></i> Map KD', array('site/mapkd?quiz=' . $quiz . '&type=' . $type . '&school=' . $school . '&class=' . $class), array('class' => '')); ?>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="tabNilai">
                            <div class="row">
                                <div class="col-md-4 text-center">
                                    <h1 class="text-success"><?php echo number_format($max, 2); ?></h1>
                                    <h3>Nilai Tertinggi</h3>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h1 class="text-danger"><?php echo number_format($min, 2); ?></h1>
                                    <h3>Nilai Terendah</h3>
                                </div>
                                <div class="col-md-4 text-center">
                                    <h1 class="text-primary">
                                        <?php
                                        if (array_sum($score) > 0) {
                                            echo number_format(array_sum($score) / count($score), 2);
                                        } else {
                                            echo "0";
                                        }
                                        ?>
                                    </h1>
                                    <h3>Rata-rata</h3>
                                </div>
                            </div>
                            <br />
                            <br />
                            <div class="row">
                                <div class="chart-column col-md-12">
                                    <div id="chartKD" style="height: 640px; width: 100%;"></div>
                                    <br/>
                                    <div class="text-center">Sebaran Nilai KD </div>
                                </div>
                            </div>
                            <br />
                            <br />
                            <div class="row">
                                <div class="chart-column col-md-12">
                                    <div id="chartSiswa" style="height: 300px; width: 100%;"></div>
                                    <br/>
                                    <div class="text-center">Total Peserta</div>
                                </div>
                            </div>
                        </div>
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
    $(document).ready(function () {
        var kd = <?php echo json_encode($kd); ?>;
        var total_student = <?php echo json_encode($total_student); ?>;
        var classes = <?php echo json_encode($class_list); ?>;
        var quizes = <?php echo json_encode($quiz_list); ?>;
        var school = <?php echo json_encode($school); ?>;

        $.fn.fill_class = function (id) {
            var classlist = classes[id];
            var option = '';
            $.each(classlist, function (k, v) {
                option += '<option value="' + k + '">' + v + '</option>';
            });
            $('#classlist').html(option);
        };

        $.fn.fill_quiz = function (id) {
            var quizlist = quizes[id];
            var option = '';
            $.each(quizlist, function (k, v) {
                option += '<option value="' + k + '">' + v + '</option>';
            });
            $('#quizlist').html(option);
        };

        $(this).on('change', '#schoolist', function () {
            $('#classlist').empty();
//            $('#quizlist').empty();

            var id = $(this).val();
            $(this).fill_class(id);
//            $(this).fill_quiz(id);
            $('.selectpicker').selectpicker('refresh');
        });

        //loadchart
        var databenar = [];
        var datasalah = [];
        $.each(kd, function (key, data) {
            var desc = ""

            var benar = 0
            if (typeof (data.benar) != "undefined" && data.benar !== null) {
                benar = "" + data.benar
            }
            databenar.push({
                y: parseInt(benar),
                label: key.toString(),
                // indexLabel: benar.toString()
            });
            var salah = 0
            if (typeof (data.salah) != "undefined" && data.salah !== null) {
                salah = "" + data.salah
            }
            datasalah.push({
                y: parseInt(salah),
                label: key.toString(),
                // indexLabel: salah.toString()
            });
        });
        var chartKD = new CanvasJS.Chart("chartKD", {
            animationEnabled: true,
            height: 640,
            axisY: {
                gridThickness: 1,
                lineThickness: 0,
                tickThickness: 0,
                labelFontSize: 12,
                labelFontFamily: 'Proxima Nova'
            },
            axisX: {
                gridThickness: 0,
                lineThickness: 0,
                tickThickness: 0,
                labelFontSize: 12,
                labelFontFamily: 'Proxima Nova'
            },
            toolTip: {
                content: function (e) {
                    var point = e.entries[0].dataPoint;
                    var desc = '';
                    if (typeof (kd[point.label].desc) != "undefined" && kd[point.label].desc != null) {
                        desc = '<br/><p style="width:300px;white-space: normal;">' + kd[point.label].desc + '</p>';
                    }
                    return '<b>KD ' + point.label + ':</b> ' + desc;
                }
            },
            data: [{
                    type: "stackedBar100", //change it to line, area, bar, pie, etcshowInLegend: "true",
                    indexLabel: "#percent %",
                    indexLabelPlacement: "inside",
                    indexLabelFontColor: "white",
                    indexLabelFontSize: 11,
                    dataPoints: databenar
                }, {
                    type: "stackedBar100", //change it to line, area, bar, pie, etcshowInLegend: "true",
                    indexLabel: "#percent %",
                    indexLabelPlacement: "inside",
                    indexLabelFontColor: "white",
                    indexLabelFontSize: 11,
                    dataPoints: datasalah
                }]
        });

        var datasiswa = []
        $.each(total_student, function (key, data) {
            datasiswa.push({
                y: parseInt(data),
                label: key.toString()
            });
        });
        var chartSiswa = new CanvasJS.Chart("chartSiswa", {
            animationEnabled: true,
            height: 300,
            axisY: {
                gridThickness: 1,
                lineThickness: 0,
                tickThickness: 0,
                labelFontSize: 12,
                labelFontFamily: 'Proxima Nova'
            },
            axisX: {
                gridThickness: 0,
                lineThickness: 0,
                tickThickness: 0,
                labelFontSize: 12,
                labelFontFamily: 'Proxima Nova'
            },
            data: [{
                    type: "column", //change it to line, area, bar, pie, etcshowInLegend: "true",
                    indexLabelPlacement: "inside",
                    indexLabelFontColor: "white",
                    indexLabelFontSize: 11,
                    dataPoints: datasiswa
                }]
        });
        setTimeout(function () {
            chartKD.render();
            chartSiswa.render();
        }, 500);
    });
</script>
