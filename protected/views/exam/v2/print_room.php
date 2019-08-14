<div class="row print-out">
    <div class="col-md-12">
        <div class="col-card">
            <h1 class="text-center"><?php echo $exam->title?></h1>
            <h1 class="text-center">RUANG</h1>
            <p class="text-center" style="font-size: 520px"><?php echo $room->no_room?></p>
            <?php
            $list_class = ExamStudentlist::model()->findAll(array(
                'condition' => 'room_id = "' . $room->id . '" group by class_id'
            ));
            if (!empty($list_class)) {
                foreach ($list_class as $class) {
                    $classes = ClassDetail::model()->find(array(
                        'select' => 'name',
                        'condition' => 'id = ' . $class->class_id . ''));
                    echo "<h1 class=\"text-center\">" . $classes->name . "</h1>";
                }
            }
            ?>
            <div class="btn-group">
                <button data-toggle="print" data-target=".print-out" class="btn no-print"><i class="fa fa-print"></i> Cetak</button>
                <a href="javascript:history.back()" class="btn btn-danger no-print" ><i class="fa fa-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>
    <div class="col-md-2">
    </div>
</div>