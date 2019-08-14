<?php
$rooms = ExamRoom::model()->findAll(array('condition' => 'exam_id = ' . $exam_id));
$room_no = str_pad(count($rooms)+1, 2, '0', STR_PAD_LEFT);
$clist = ClassDetail::model()->findAll(array('order' => 'name'));
$classes_list = array();

if (!empty($clist)) {
    foreach ($clist as $cl) {
        $classes_list[$cl->id] = $cl->name;
    }
}

$allstudent = ExamStudentlist::model()->findAll(array(
        'select' => 't.student_id',
        'join' => 'JOIN exam_room on exam_room.id = t.room_id',
        'condition' => 'exam_room.exam_id =' . $exam_id,
        ));
$student_ids = array();
foreach ($allstudent as $value) {
    array_push($student_ids, $value->student_id);
}
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div id="bc1" class="btn-group btn-breadcrumb">
                <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda', array('/site/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Adminitrasi</div>', array('/exam/index'), array('class' => 'btn btn-default')); ?>
                <?php if (!$model->isNewRecord) { ?>
                    <?php echo CHtml::link('<div>Sunting Ruangan</div>', array('#'), array('class' => 'btn btn-success')); ?>
                <?php } else { ?>
                    <?php echo CHtml::link('<div>Tambah Ruangan</div>', array('#'), array('class' => 'btn btn-success')); ?>
                <?php } ?>
            </div>
        </div>
        <div class="col-lg-12">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'room-form',
                'enableAjaxValidation' => false,
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
            )); ?>
            <?php if (!$model->isNewRecord) { ?>
                <h3>Sunting Ruangan</h3>
            <?php } else { ?>
                <h3>Membuat Ruangan Baru</h3>
            <?php } ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-card">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="no_room">Nomber Ruangan</label>
                                    <input class="form-control input-pn input-lg" placeholder="Isi Nomber Ruangan disini (contoh: Ruang 23) …" required="required" name="no_room" id="no_room" type="text" value="<?php echo isset($model->no_room) ? $model->no_room :$room_no ?>" readonly>
                                </div>
                                <div class="form-group">
                                    <label for="ClassDetail_class_id">Kelas</label>
                                    <?php echo $form->dropDownList(new ClassDetail, 'class_id', $classes_list, array('id' => 'classes', 'class' => 'selectpicker form-control input-lg', 'data-style' => 'btn-default input-lg', 'data-live-search' => 'true', 'title' => "Please Select...")); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="list-student">Ruang Kelas</label>
                                    <select id="list-student" name="list-student[]" class="form-control input-lg"
                                            multiple="multiple" style="height: 400px">

                                    </select>
                                </div>
                                <div class="form-group">
                                    <span id="add-student" class="btn btn-info btn-lg btn-block">Tambah <i
                                                class="fa fa-angle-double-right"></i></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="listed-student">Ruang Ujian</label>
                                    <select id="listed-student" name="listed-student[]"
                                            class="form-control input-lg" multiple="multiple" style="height: 400px">
                                        <?php if (!$model->isNewRecord AND !empty($list_student)) {
                                            $input = "";
                                            foreach ($list_student as $student) {
                                                $user = User::model()->find(array('select' => 'display_name', 'condition' => 'id=' . $student->student_id));
                                                $class = ClassDetail::model()->find(array('select' => 'name', 'condition' => 'id=' . $student->class_id));

                                                echo '<option value="' . $student->student_id . '">' . $class->name . ' - ' . $user->display_name . '</option>';
                                                $input = $input . '<input id="' . $student->student_id . '" type="hidden" name="selected-student[]" value="' . $student->student_id . '|' . $student->class_id . '">';
                                            }
                                        } ?>
                                    </select>
                                    <span id="input-student">
                                        <?php if (isset($input)) {
                                            echo $input;
                                        } ?>
                                    </span>
                                </div>
                                <div class="form-group">
                                    <span id="delete-student" class="btn btn-danger btn-lg btn-block"><i
                                                class="fa fa-angle-double-left"></i> Hapus</span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <?php if (!$model->isNewRecord) { ?>
                                    <input type="hidden" name="room_id"
                                           value="<?php echo $room_id ?>">
                                    <button type="submit"
                                            class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Simpan
                                        Ruangan
                                    </button>
                                <?php } else { ?>
                                    <input type="hidden" name="exam_id"
                                           value="<?php echo $exam_id ?>">
                                    <button type="submit"
                                            class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Buat
                                        Ruangan
                                    </button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var selected_student = <?php echo json_encode($student_ids) ?>;

        $('#classes').on('change', function () {
            var url_load = "<?php echo $this->createUrl('/exam/list_student?class_id=') ?>" + $(this).val();
            var classes = $(this).children('option[value=' + $(this).val() + ']').text();
            var class_id = $(this).val();
            $.ajax({
                url: url_load,
                type: 'GET',
                dataType: 'json',
                success: function (data) {
                    var select = '';
                    var i = 1;
                    $.each(data, function (name, id) {
                        //validasi jika siswa sudah dipilih tidak akan di munculkan
                        var hiden = ''
                        if ($.inArray(id, selected_student) !== -1) {
                            hiden = 'class="hide"';
                        }
                        select = select + '<option ' + hiden + ' value="' + id + '" data-name="' + name + '" data-class="' + classes + '" data-class_id="' + class_id + '">'
                            + i + '. ' + name
                            + '</option>';
                        i++;

                    });

                    $('#list-student').html(' ');
                    $('#list-student').append(select);
                }
            })
        });

        $(this).on('click', '#add-student', function () {
            var selected = $('select[name="list-student[]"]').val();
            var select_student = '';
            var input_student = '';
            $.each(selected, function (key, val) {
                var option = $('select[name="list-student[]"]').children('option[value=' + val + ']');
                select_student = select_student + '<option value="' + val + '">'
                    + option.attr('data-class') + ' - ' + option.attr('data-name')
                    + '</option>';
                input_student = input_student + '<input id="' + val + '" type="hidden" name="selected-student[]" value="' + val + '|' + option.attr('data-class_id') + '">';
                //sembunyikan siswa yang telah dipilih
                option.addClass('hide');
                //masukan id siswa yang di pilih untuk validasi nanti
                selected_student.push(val);
            });
            console.log(select_student);
            $('#input-student').append(input_student);
            $('#listed-student').append(select_student);
        });

        $(this).on('click', '#delete-student', function () {
            var selected = $('select[name="listed-student[]"]').val();
            $.each(selected, function (key, val) {
                var option = $('select[name="listed-student[]"]').children('option[value="' + val + '"]');
                var input = $('input[id="' + val + '"]');
                option.remove();
                input.remove();

                var optionhide = $('select[name="list-student[]"]').children('option[value=' + val + ']');
                optionhide.removeClass('hide');

                selected_student = $.grep(selected_student, function (value) {
                    return value != val;
                });
            })
        });
    });
</script>