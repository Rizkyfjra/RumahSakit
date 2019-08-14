<?php
if ($school !== "all") {
    $classlist = $class_list[$school];
} else {
    $classlist = array();
}

function percent($value = 0, $plus = 0) {
    $percent = 0;
    if ($value != 0 OR $plus != 0)
        $percent = ($value / ($value + $plus));
    return number_format($percent * 100, 1) . '%';
}

$the_school_name = Option::model()->findByAttributes(array('key_config' => 'school_name'))->value;
?>
<style type="text/css">
    #page-content-wrapper {
        padding: 0px !important;
    }
</style>
<div class="container-fluid" style="background: #ecf0f5">
    <div class="row">
        <div class="col-md-12">
            <!-- <div id="bc1" class="btn-group btn-breadcrumb">
            <?php //echo CHtml::link('<i class="fa fa-home"></i> Beranda', array('/site/index'), array('class' => 'btn btn-default')); ?>
            <?php //echo CHtml::link('<div>Hasil Ujian KD</div>', array('#'), array('class' => 'btn btn-success')); ?>
            </div> -->
        </div>
        <div class="col-md-12">
            <div class="login-box">
                <div class="col-card">
                    <form>
                        <?php
                        echo CHtml::dropDownList('type', $type, array('uts' => 'UTS', 'uas' => 'UAS', 'us' => 'US'), array(
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
                    <?php echo CHtml::link('<i class="fa fa-print"></i> Print', array('site/downloadkd?quiz=' . $quiz . '&type=' . $type . '&school=' . $school . '&class=' . $class), array('class' => 'btn btn-success pull-right', 'style' => 'margin-left:10px')); ?>
                    <?php echo CHtml::link('<i class="fa fa-download"></i> Download', array('site/downloadkd?quiz=' . $quiz . '&type=' . $type . '&school=' . $school . '&class=' . $class . '&print=0'), array('class' => 'btn btn-danger pull-right', 'style' => 'margin-left:10px')); ?>                    
                    <?php echo CHtml::link('<i class="fa fa-check"></i> Periksa Ulang', '#', array('class' => 'btn btn-primary pull-right', 'id' => 'ceknilai')); ?>
                    <ul class="nav nav-tabs" role="tablist">
                        <li role="presentation" class="active">
                            <a href="#tabNilai" aria-controls="tabSoal" role="tab" data-toggle="tab">
                                <i class="fa fa-list"></i> Nilai KD
                            </a>
                        </li>
                        <li role="presentation">
                            <?php echo CHtml::link('<i class="fa fa-bar-chart"></i> Chart KD', array('site/chartkd?quiz=' . $quiz . '&type=' . $type . '&school=' . $school . '&class=' . $class), array('class' => '')); ?>
                            <!-- <a href="#tabChart" aria-controls="nilai" role="tab" data-toggle="tab">
                            <i class="fa fa-line-chart"></i> Chart KD -->
                            <!-- </a> -->
                        </li>
                        <li role="presentation">
                            <?php echo CHtml::link('<i class="fa fa-map"></i> Map KD', array('site/mapkd?quiz=' . $quiz . '&type=' . $type . '&school=' . $school . '&class=' . $class), array('class' => '')); ?>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="tabNilai">
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>Ujian</th>
                                            <th>Kelas</th>
                                            <th>Nama Sekolah</th>
                                            <th>No Peserta</th>
                                            <th>Nama Peserta</th>
                                            <th>Nilai</th>
                                            <?php
                                            ksort($kd);
                                            foreach ($kd as $key => $value) {
                                                if ($quiz !== "all") {
                                                    $kd_desc = LessonKd::model()->findByAttributes(
                                                            array(
                                                                'lesson_id' => $dataProvider->getData()[0]->quiz->lesson_id,
                                                                'title' => $key
                                                            )
                                                    );
                                                }
                                                echo "<th width='10%' class='text-center'>";
                                                echo "KD " . $key . " ";
                                                if (isset($kd_desc))
                                                    echo "<a href='#' data-toggle='tooltip' title='" . $kd_desc->description . "'><i class='fa fa-info-circle'></i></a>";
                                                echo "</th>";
                                            }
                                            ?>
                                            <!-- <th>Jawaban Siswa</th> -->
                                            <th>Jawaban Benar</th>
                                            <th>Jawaban Salah</th>
                                            <?php  if ($quiz !== "all") { ?>
                                            <th>Tidak Dijawab</th>
                                            <?php }?>
                                        </tr>
                                    </thead>
                                    <tbody>
                                      
                                        <?php
                                        if (!empty($dataProvider->getData())) {
                                            $kuis = $dataProvider->getData();

                                            $count_soal_pg = 0;
                                            if ($quiz !== "all") {
                                            
                                            
                                            $questions_arr = explode(",",$kuis[0]->quiz->question);
                                            
                                            foreach ($questions_arr as $idQues) {
                                                 $cekSoal = Questions::model()->findByPk($idQues);
                                                    if (!empty($cekSoal)) {
                                                        if ($cekSoal->type != 2) {
                                                            $count_soal_pg++;
                                                        }
                                                    }
                                            }
                                            
                                            }

                                            if (empty($_GET['StudentQuiz_page'])) {
                                                $no = 1;
                                            } else {
                                                $no = 1 + (($_GET['StudentQuiz_page'] - 1) * 20);
                                            }

                                                if($dataProviderCount){
                                                ?>
                                                    <tr>
                                                        <td colspan="6">Jumlah Siswa : <?php echo count($dataProviderCount->getData());?></td>
                                                    </tr>
                                                <?php
                                                }

                                            foreach ($kuis as $value) {
                                                $kd_student = json_decode($value->kd, true);
                                                ?>
                                                <tr>
                                                    <th class="text-center" style='vertical-align: middle' id="<?php echo $value->id; ?>"><?php echo $no; ?></th>
                                                    <td>
                                                        <?php echo $value->quiz->title; ?>
                                                    </td>
                                                    <td>
                                                        <?php echo $value->class; ?>
                                                    </td>
                                                    <td style='vertical-align: middle'>
                                                        <?php
                                                        echo $value->school_name
                                                        ?>
                                                    </td>
                                                    <td style='vertical-align: middle'>
                                                        <?php
                                                        
                                                        if ($the_school_name=="KKMI KOTA MALANG") {
                                                            if (strpos($value->display_name, '-') !== false) {
                                                                $nama_arr = explode("-", $value->display_name);
                                                                // print_r($nama_arr);
                                                                // print_r($nama_arr2);
                                                                echo $nama_arr[0];
                                                            } else {
                                                                echo "-";
                                                            }
                                                        } else {
                                                             if ((strpos($value->display_name, '(') !== false) && (strpos($value->display_name, ')') !== false) ) {
                                                                $nama_arr = explode("(", $value->display_name);
                                                                $nama_arr2 = explode(")", $nama_arr[1]);
                                                                // print_r($nama_arr);
                                                                // print_r($nama_arr2);
                                                                echo $nama_arr2[0];
                                                            } else {
                                                                echo "-";
                                                            }
                                                        }
                                                        ?>
                                                    </td style='vertical-align: middle'>
                                                    <td style='vertical-align: middle'>
                                                        <?php
                                                        
                                                        if ($the_school_name=="KKMI KOTA MALANG") {
                                                            if (strpos($value->display_name, '-') !== false) {
                                                                $nama_arr = explode("-", $value->display_name);
                                                                // print_r($nama_arr);
                                                                // print_r($nama_arr2);
                                                                echo $nama_arr[1];
                                                            } else {
                                                                echo "-";
                                                            }
                                                        } else {
                                                            if ((strpos($value->display_name, '(') !== false) && (strpos($value->display_name, ')') !== false) ) {
                                                                $nama_arr = explode("(", $value->display_name);
                                                                $nama_arr2 = explode(")", $nama_arr[1]);
                                                                // print_r($nama_arr);
                                                                // print_r($nama_arr2);
                                                                echo $nama_arr2[1];
                                                            } else {
                                                                echo $value->display_name;
                                                            }
                                                        }

                                                       
                                                        ?>
                                                    </td style='vertical-align: middle'>
                                             
                                                    <td class="text-bold text-info" style='vertical-align: middle'>
                                                        <?php
                                                        echo $value->score
                                                        ?>
                                                    </td>
                                                    <?php
                                                    foreach ($kd as $key => $valuez) {
                                                        echo "<td class='text-center'>";
                                                        if (is_array($kd_student) AND array_key_exists("benar", $kd_student)) {
                                                            if (!array_key_exists($key, $kd_student['benar'])) {
                                                                $kd_student['benar'][$key] = 0;
                                                            }
                                                        }
                                                        if (is_array($kd_student) AND array_key_exists("salah", $kd_student)) {
                                                            if (!array_key_exists($key, $kd_student['salah'])) {
                                                                $kd_student['salah'][$key] = 0;
                                                            }
                                                        }
                                                        echo "<span class='text-bold text-success'>" . percent($kd_student['benar'][$key], $kd_student['salah'][$key]) . "</span>";
                                                        echo "<hr>";
                                                        echo "<span class='text-bold text-danger'>" . percent($kd_student['salah'][$key], $kd_student['benar'][$key]) . "</span>";
                                                        echo "</td>";
                                                    }
                                                    ?>
                                                    <!-- <td class="collapsible-row-container" style='vertical-align: middle; width: 50px'> -->
                                                        <!-- <div class="collapsible-row"> -->
                                                            <?php 
                                                                // if ($value->student_answer) { 
                                                                //     $answers = json_decode($value->student_answer, true);
                                                                //     // echo "<ol>";
                                                                //      $count_salah = 0;
                                                                //      $count_benar = 0;
                                                                     
                                                                //     foreach ($answers as $key => $valuesss) {
                                                                        
                                                                //         if (strpos($valuesss, '<img') !== false) {
                                                                //                 $doc = new DOMDocument();
                                                                //                 $doc->loadHTML($valuesss);
                                                                //                 $tags = $doc->getElementsByTagName('img');
                                                                //                 if (count($tags) > 0) {
                                                                //                     $tag = $tags->item(0);
                                                                //                     $old_src = $tag->getAttribute('src');
                                                                //                     $old_src = str_replace(" ", "+", $old_src);
                                                                //                     $new_src_url = $old_src;
                                                                //                     $tag->setAttribute('src', $new_src_url);
                                                                //                     $valuesss = $doc->saveHTML($tag);
                                                                //                 }
                                                                                
                                                                //          }
                                                                //         // echo "<li>" . $valuesss . " - ";
                                                                //         $cekSoal = Questions::model()->findByPk($key);
                                                                       
                                                                //         if (!empty($kunci_jawaban)) {

                                                                //             $valuesss = preg_replace("/\r|\n/", "", $valuesss);
                                                                //             $valuesss = strip_tags($valuesss, "<img>");
                                                                //             $valuesss = preg_replace('/\s+/', '', $valuesss);
                                                                //             $valuesss = str_replace("/>", "", $valuesss);
                                                                //             $valuesss = str_replace(">", "", $valuesss);
                                                                //             // $valuesss = str_replace(" ", "+", $valuesss);
                                                                //             foreach ($kunci_jawaban as $k => $v){
                                                                                 
                                                                //                 if (strtolower($v) == strtolower($valuesss)) {
                                                                //                     $count_benar = $count_benar + 1;
                                                                //                    // echo "<font color='green'>Benar</font>";
                                                                //                    $jwb_salah = false;
                                                                //                    break;
                                                                //                 } else {
                                                                //                     $jwb_salah = true;
                                                                //                 }
                                                                //             }
                                                                //             if ($jwb_salah  === true){
                                                                //                 $count_salah = $count_salah + 1;
                                                                //                 // echo "<font color='red'>Salah</font>";
                                                                //             }
                                                                //         }

                                                                //         // echo "</li>";

                                                                //     }
                                                                //     // echo "</ol>";
                                                                // }
                                                            ?>
                                                        <!-- </div> -->
                                                    <!-- </td> -->
                                                    <td class="text-bold text-info" style='vertical-align: middle'>
                                                        <?php
                                                        echo $value->right_answer;
                                                        ?>
                                                    </td>
                                                    <td class="text-bold text-info" style='vertical-align: middle'>
                                                        <?php
                                                        echo $value->wrong_answer;
                                                        ?>
                                                    </td>
                                                    <?php  if ($quiz !== "all") { ?>
                                                    <td class="text-bold text-info" style='vertical-align: middle'>
                                                        <?php
                                                        echo $count_soal_pg - ($value->wrong_answer+$value->right_answer);
                                                        ?>
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
                                        'pages' => $dataProvider->pagination,
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
<style type="text/css">
    ol div {
        display: inline;
    }
</style>
<script type="text/javascript">
    $(document).ready(function () {
        var classes = <?php echo json_encode($class_list); ?>;
        var quizes = <?php echo json_encode($quiz_list); ?>;
        var ids = <?php echo json_encode($ids); ?>;
        var url = "<?php echo Yii::app()->createUrl('/site/ceknilaiujian'); ?>";
        var success = 0;
        var gagal = 0;
        
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

        $.fn.cek_nilai = function (id) {
            $.ajax({
                url: url,
                type: "POST",
                data: {id: id},
                success: function (response) {
                    console.log(response);
                    if (response === "1"){
                        success += 1;
                    } else {
                        gagal += 1;
                    }
                }
            });
        }

        $(this).on('change', '#schoolist', function () {
            $('#classlist').empty();
//            $('#quizlist').empty();

            var id = $(this).val();
            $(this).fill_class(id);
//            $(this).fill_quiz(id);
            $('.selectpicker').selectpicker('refresh');
        });
	console.log(<?php echo count($ids);?>);
	console.log(ids);
        $(this).on('click', '#ceknilai', function () {
            $('.modal-loading').addClass("show");
            $.each(ids, function (key, id) {
                $(this).cek_nilai(id); 
            });
            
            setTimeout(function() {
                //your code to be executed after 10 second
                alert( success + " nilai berhasil di Update dan " + gagal + " gagal!");
                $('.modal-loading').removeClass("show");
                location.reload();
              }, 20000);
            
        });

        if ($(window).width() > 960) {
            $("#wrapper").toggleClass("toggled");
        }

        $('[data-toggle="tooltip"]').tooltip();
    });
</script>
