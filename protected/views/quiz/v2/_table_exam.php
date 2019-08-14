<div class="table-responsive">
  <table class="table table-hover">
    <thead>
      <tr>
        <th>No.</th>
        <th>Pertanyaan</th>
        <th width="5%">Jumlah Benar</th>
        <th width="5%">Jumlah Salah</th>
        <th width="10%">Aksi</th>
      </tr>
    </thead>
    <tbody>
      <?php
        $no = 1;
        foreach ($pertanyaan as $key) {
          $detail = Questions::model()->findByPk($key);
          
          $benar_per_soal=0;
          $salah_per_soal=0;
          $jawab=NULL;

          foreach ($student_quiz as $jawabs) {
            $siswa_jawab = json_decode($jawabs->student_answer);  
            if(!empty($siswa_jawab)){
              foreach ($siswa_jawab as $jwbs => $isis) {
                if($detail->id == $jwbs){
                  if($isis == $detail->key_answer){
                    $benar_per_soal=$benar_per_soal+1;
                  }else{
                    $salah_per_soal=$salah_per_soal+1;
                  }
                }
              }
            }else{
              $total_salah=$total_salah+1;
            }
          }

          $total_per_soal = $benar_per_soal + $salah_per_soal;

          if($total_per_soal!=0){
            $persenBenar = round(($benar_per_soal/$total_per_soal) * 100);
            $persenSalah = round(($salah_per_soal/$total_per_soal) * 100);
          }else{
            $persenBenar = 0;
            $persenSalah = 0;
          }

          $this->renderPartial('v2/_table_exam_list', array(
            'no'=>$no,'detail'=>$detail,'persenBenar'=>$persenBenar,'persenSalah'=>$persenSalah,'quiz_id'=>$quiz_id,'key' =>$key
          ));

          $no++;
        }
      ?>
    </tbody>
  </table>
</div>
