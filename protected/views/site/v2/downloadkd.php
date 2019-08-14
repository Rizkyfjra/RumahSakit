<?php

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
    @media print {
        body * {
            visibility: hidden;
        }
        .tab-content, .tab-content  * {
            visibility: visible;
        }
        .tab-content {
            padding: 20px;
            position: absolute;
            left: 0;
            top: 0;
        }
    }
</style>
<div class="container-fluid" style="background: #ecf0f5">
    <div class="row">
        <div class="col-md-12">
            <div class="login-box">
                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active" id="tabNilai">
                        <div class="table-responsive">
                            <table id="print-table" class="table table-hover">
                                 <thead>
                                         <tr>
                                            <td colspan="10" align="center">
                                            <?php 
                                            $school_address = Option::model()->findByAttributes(array('key_config' => 'school_address'))->value; 
                                                echo $school_address;
                                            ?>
                                        </td>
                                        </tr>
                                             <tr>
                                            <td colspan="10" align="center">
                                            <?php 
                                            $kelurahan = Option::model()->findByAttributes(array('key_config' => 'kelurahan'))->value; 
                                                echo $kelurahan;
                                            ?>
                                        </td>
                                        </tr>
                                             <tr>
                                            <td colspan="10" align="center">
                                            <?php 
                                            $kecamatan = Option::model()->findByAttributes(array('key_config' => 'kecamatan'))->value; 
                                                echo $kecamatan;
                                            ?>
                                        </td>
                                        </tr>
                                             <tr>
                                            <td colspan="10" align="center">
                                            <?php 
                                            $kota_kabupaten = Option::model()->findByAttributes(array('key_config' => 'kota_kabupaten'))->value; 
                                                echo $kota_kabupaten;
                                            ?>
                                        </td>
                                        </tr>
                                             <tr>
                                            <td colspan="10" align="center">
                                            <?php 
                                            $provinsi = Option::model()->findByAttributes(array('key_config' => 'provinsi'))->value; 
                                                echo $provinsi;
                                            ?>
                                        </td>
                                        </tr>
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
<script type="text/javascript">
    $(document).ready(function () {
        // var tableToExcel = (function() {
        // var uri = 'data:application/vnd.ms-excel;base64,';
        // var template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>';
        // var base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) };
        // var format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) };
        
        // return function(table, name) {
        //     if (!table.nodeType) table = document.getElementById(table)
        //     var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
        //     window.location.href = uri + base64(format(template, ctx))
        //     }
        // })()
        // var print = <?//php echo json_encode($print); ?>;
        // if (print == '1') {
        //     window.print();
        // } else {
        //     tableToExcel('print-table', 'Nilai Siswaß')
        // }
        var classes = <?php echo json_encode($class) ?>;
        var school_name = <?php echo json_encode($school) ?>;
        var type = <?php echo json_encode(strtoupper($type)) ?>;
        // function exceller() {
        //     var uri = 'data:application/vnd.ms-excel;base64,',
        //       template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>',
        //       base64 = function(s) {
        //         return window.btoa(unescape(encodeURIComponent(s)))
        //       },
        //       format = function(s, c) {
        //         return s.replace(/{(\w+)}/g, function(m, p) {
        //           return c[p];
        //         })
        //       }
        //     var toExcel = document.getElementById("print-table").innerHTML;
        //     var ctx = {
        //       worksheet: name || '',
        //       table: toExcel
        //     };
        //     var link = document.createElement("a");
        //     link.download = "Report - " + type + " " + school_name + " Kelas " + classes + ".xls";
        //     link.href = uri + base64(format(template, ctx))
        //     link.click();
        // }

        // exceller();

        setTimeout(function() {
                window.history.back();
        }, 1000);
    });
</script>
