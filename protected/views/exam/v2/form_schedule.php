<?php
$list = LessonList::model()->findAll(array('order' => 'id'));
$lesson_list = array();
if (!empty($list)) {
    foreach ($list as $ll) {
        if ($ll->group == 1) {
            $ket = "Obat Keras";
        } elseif ($ll->group == 2) {
            $ket = "Obat Apotik";
        } else {
            $ket = "Obat Umum";
        }

        $lesson_list[$ll->id] = $ll->name . " (" . $ket . ")";
    }
}

$clist = ClassDetail::model()->findAll(array('order' => 'name'));
$classes_list = array();

if (!empty($clist)) {
    foreach ($clist as $cl) {
        $classes_list[$cl->id] = $cl->name;
    }
}

if (!empty($model->class_id)) {
    foreach ($model->class_id as $class) {
        $selected[$class] = array("selected" => true);
    }
} else {
    $selected = array(
        '1' => array("selected" => true)
    );
} ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div id="bc1" class="btn-group btn-breadcrumb">
                <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda', array('/site/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Adminitrasi</div>', array('/exam/index'), array('class' => 'btn btn-default')); ?>
                <?php if (!$model->isNewRecord) { ?>
                    <?php echo CHtml::link('<div>Sunting Jadwal</div>', array('#'), array('class' => 'btn btn-success')); ?>
                <?php } else { ?>
                    <?php echo CHtml::link('<div>Tambah Jadwal</div>', array('#'), array('class' => 'btn btn-success')); ?>
                <?php } ?>
            </div>
        </div>

        <div class="col-lg-12">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'lesson-form',
                'enableAjaxValidation' => false,
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
            )); ?>
            <?php if (!$model->isNewRecord) { ?>
                <h3>Sunting Jadwal</h3>
            <?php } else { ?>
                <h3>Membuat Jadwal Baru</h3>
            <?php } ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-card">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Lesson_class_id">Kelas</label>
                                    <?php echo $form->dropDownList($model, 'class_id', $classes_list, array('multiple' => true, 'class' => 'selectpicker form-control input-lg', 'data-style' => 'btn-default input-lg', 'data-live-search' => 'true', 'options' => $selected)); ?>
                                </div>
                            </div>
                        </div>
                        <div id="list-lessons" class="row">
                            <div class="col-md-12">
                                <label>Mata Pelajaran</label>
                            </div>
                            <?php if (!empty($model->lesson_id)) { ?>
                                <?php $i = 1;
                                foreach ($model->lesson_id as $lesson) { ?>
                                    <div class="count-lesson">
                                        <div class="col-md-11">
                                            <div class="form-group">
                                                 <?php echo $form->dropDownList($model, 'lesson_id[]', $lesson_list, array('class' => 'selectpicker form-control input-lg', 'data-style' => 'btn-default input-lg', 'data-live-search' => 'true', 'options' => array($lesson => array('selected' => true)))); ?>
                                          
                                                 <?php echo $form->textField($model,'lesson_time[]',array('value'=>json_decode($model->lesson_time)[$i-1],'class'=>'form-control input-pn input-lg','placeholder'=>'Isi waktu ujian contoh (09:30-10:30) …','required'=>'required')); ?>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <span class="delete-lesson btn btn-danger btn-lg btn-block"><i
                                                        class="fa fa-trash"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <?php $i++;
                                } ?>
                            <?php } else { ?>
                                <div class="count-lesson">
                                    <div class="col-md-11">
                                        <div class="form-group">
                                            <?php echo $form->dropDownList($model, 'lesson_id[]', $lesson_list, array('class' => 'selectpicker form-control input-lg', 'data-style' => 'btn-default input-lg', 'data-live-search' => 'true', 'options' => array(1 => array('selected' => true)))); ?>
                                            <?php echo $form->textField($model,'lesson_time[]',array('class'=>'form-control input-pn input-lg','placeholder'=>'Isi waktu ujian contoh (09:30-10:30) …','required'=>'required')); ?>    
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <span class="delete-lesson btn btn-danger btn-lg btn-block"><i
                                                    class="fa fa-trash"></i>
                                        </span>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <span class="add-lesson btn btn-block btn-info btn-lg"><i
                                                class="fa fa-plus-circle"></i>
                                        Tambah Mata Pelajaran
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="ExamScheduleClass[schedule_id]"
                                       value="<?php echo $schedule_id ?>">
                                <?php if (!$model->isNewRecord) { ?>
                                    <button type="submit"
                                            class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Simpan
                                        Jadwal
                                    </button>
                                <?php } else { ?>
                                    <button type="submit"
                                            class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Buat
                                        Jadwal
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
        //tamabah mata pelajaran
        $(this).on('click', '.add-lesson', function () {
            var lessons = <?php echo json_encode($lesson_list); ?>;
            var select = '';
            $.each(lessons, function (key, val) {
                select = select + '<option value="' + key + '">' + val + '</option>';
            });

            var elm = '<div class="count-lesson">'
                + '<div class="col-md-11">'
                + '<div class="form-group">'
                + '<select class="selectpicker form-control input-lg" data-style="btn-default input-lg" data-live-search="true" name="ExamScheduleClass[lesson_id][]" id="ExamScheduleClass_lesson_id">' + select + '</select>'
                + '<input class="form-control input-pn input-lg" placeholder="Isi waktu ujian contoh (09:30-10:30) …" required="required" name="ExamScheduleClass[lesson_time][]" id="ExamScheduleClass_lesson_time" type="text">'
                + '</div>'
                + '</div>'
                + '<div class="col-md-1">'
                + '<span class="delete-lesson btn btn-danger btn-lg btn-block"><i class="fa fa-trash"></i>'
                + '</span>'
                + '</div>'
                + '</div>';
            $('#list-lessons').append(elm);
            $('.selectpicker').selectpicker('refresh');
        });
        //hapus mata pelajaran
        $(this).on('click', '.delete-lesson', function () {
            var lesson = $(this).parent().parent();

            if ($('.count-lesson').length > 1) {
                lesson.remove();
            } else {
                alert("Mata Pelajaran tidak bisa di hapus!!");
            }
        })
    });
</script>