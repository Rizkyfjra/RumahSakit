<?php
    if($school !=="all"){
        $classlist = $class_list[$school];
    } else {
        $classlist = array();
    }

$the_school_name = Option::model()->findByAttributes(array('key_config' => 'school_name'))->value;

?>
<div class="login-page-container">
    <div class="login-page">
        <div class="container">
            <div class="col-md-10 col-md-push-1">
                <div class="login-box">
                    <div class="login-box-header">
                        <h1>Edubox</h1>
                        <div class="school-name">Hasil <?php echo strtoupper($type) ?> Bersama</div>
                    </div>
                    <div class="col-card">
                        <form>
                            <input type="hidden" name="type" value="<?php echo $type?>"/>
                            <?php echo CHtml::dropDownList('quiz', $quiz, $quiz_list, array(
                                'class' => 'selectpicker',
                                'id' => 'quizlist',
                                'data-style' => 'btn-default input-lg',
                                'data-live-search' => 'true'));
                            ?>
                            <?php echo CHtml::dropDownList('school', $school, $school_list, array(
                                'class' => 'selectpicker',
                                'id' => 'schoolist',
                                'data-style' => 'btn-default input-lg',
                                'data-live-search' => 'true'));
                            ?>
                            <?php echo CHtml::dropDownList('class', $class, $classlist, array(
                                'class' => 'selectpicker',
                                'id' => 'classlist',
                                'data-style' => 'btn-default input-lg',
                                'data-live-search' => 'true'));
                            ?>
                            <input type="submit" class="btn btn-success input-lg pull-right" style="width: 205px" value="Cari...">
                        </form>
                        <br />
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nama Ujian</th>
                                    <th>Nama Pelajaran</th>
                                    <th>No Peserta</th>
                                    <th>Nama Siswa</th>
                                    <th>Nama Kelas</th>
                                    <th>Nama Sekolah</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                if (!empty($dataProvider->getData())) {
                                    $kuis = $dataProvider->getData();

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
                                        ?>
                                        <tr>
                                            <th class="text-center"><?php echo $no; ?></th>
                                            <td class="collapsible-row-container">
                                                <?php echo $value->quiz->title; ?>
                                            </td>
                                            <td>
                                                <?php
                                                if (!empty($value->quiz->lesson)) {
                                                    echo ucwords($value->quiz->lesson->name);
                                                }
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
                                            <td>
                                                <?php
                                                echo $value->class
                                                ?>
                                            </td>
                                            <td>
                                                <?php
                                                echo $value->school_name
                                                ?>
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
<script type="text/javascript">
    $(document).ready(function () {
        var classes = <?php echo json_encode($class_list); ?>;
        var quizes = <?php echo json_encode($quiz_list); ?>;
        $.fn.fill_class = function (id) {
            var classlist = classes[id];
            var option = '';
            $.each(classlist, function(k, v) {
                option += '<option value="' + k + '">' + v + '</option>';
            });
            $('#classlist').html(option);
        };

        $.fn.fill_quiz = function (id) {
            var quizlist = quizes[id];
            var option = '';
            $.each(quizlist, function(k, v) {
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

        if ($(window).width() > 960) {
            $("#wrapper").toggleClass("toggled");
        }
    });
</script>
