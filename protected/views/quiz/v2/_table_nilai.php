 <?php if(!Yii::app()->user->YiiStudent){ ?>
                
                  <h4><b>Daftar Siswa Yang Sudah Mengerjakan Kuis Ini</b><p></p></h4>
                
                   <span><?php echo CHtml::ajaxButton ("Refresh data",
                              CController::createUrl('quiz/AjaxSiswa/'.$model->id), 
                              array(
                                'update' => '#nilai',
                                'class'=>'btn btn-danger',
                                'beforeSend' => 'function(){
                                  $("#loading").show();}',
                                'complete' => 'function(){
                                  $("#loading").hide();}'
                              ));?><span>
                  <span id="loading" style="display:none;">loading...</span>
                  <?php if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin){ ?>
                  <p class="pull-right"><?php echo CHtml::link('Update Nilai Ulangan', array('updateNilai','id'=>$model->id), array('class'=>'btn btn-danger'))?></p>
                  <?php  } ?>                  <table class="table table-hover table-bordered table-responsive">
                    <tr class="danger">
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Pelajaran</th>
                    <th>Kelas</th>
                    <!-- <th>Nilai Pilihan Ganda</th> -->
                    <!-- <th>Nilai Esai</th> -->
                    <th>Jawaban Benar</th>
                    <th>Jawaban Salah</th>
                    <th>Jawaban Kosong</th>
                    <th>Nilai Total</th>
                    <th>Nilai Essai</th>
                    <th>Aksi</th>
                    </tr>
                    <?php $no=1;?>
                    <?php if(!empty($siswa)){ ?>
                      <?php foreach ($siswa as $nk) { ?>
                        <?php
                          // $benar=NULL;
                          //   $salah=NULL;
                          //   $kosong=NULL;
                          //   $total_jawab=NULL;
                          //   $raw=explode(',', $model->question);
                          //   $soal_pg=0;
                          // foreach ($raw as $sl) {
                          //   $cekSoal = Questions::model()->findByPk($sl);
                          //   if(!empty($cekSoal)){
                          //     if($cekSoal->type != 2){
                          //       $soal_pg++;
                          //     }
                          //   }
                          // }
                          //   $nilaiEsai=0;
                          //   if(!empty($nk->student_answer)){
                          //     $jawaban = json_decode($nk->student_answer,true);
                          //     foreach ($jawaban as $k => $val) {
                          //       $soal = Questions::model()->findByPk($k);
                          //       if($soal->type != 2){
                          //         if(strtolower($soal->key_answer) == strtolower($val)){
                          //           $benar = $benar+1;
                          //         }else{
                          //           $salah = $salah+1;
                          //         }
                          //         //$soal_pg++;
                          //       } 
                          //     }
                          //     //$nilaiPg=round(($benar/$soal_pg)*100*0.6);
                          //     if ($soal_pg!=0) {
                          //       $nilaiPg=round(($benar/$soal_pg)*100);
                          //     } else {
                          //       $nilaiPg = 0;
                          //     }
                              
                          //   }
                            
                          //   $nilaiEsai = $nk->score - $nilaiPg;

                        
                        $totNilai = StudentQuiz::model()->findByAttributes(array('student_id'=>$nk->user_id,'quiz_id'=>$model->id));
                        if(!empty($totNilai)){
                        ?>
                        <?php 
                        $jawaban=json_decode($totNilai->student_answer,true);
                        if(!empty($jawaban)) { ?>
                        <tr class="info">
                          <td><?php echo $no;?></td>
                          <td><?php echo ($model->quiz_type == 4 OR $model->quiz_type == 5 OR $model->quiz_type == 6) ? $nk->student->display_name : CHtml::link($nk->student->display_name, array('/studentQuiz/view','id'=>$totNilai->id));?></td>
                          <td><?php echo $model->lesson->name?></td>
                          <td><?php echo $totNilai->user->class->name;?></td>
                          <!-- <td><?php //echo $nilaiPg;?></td> -->
                          <!-- <td><?php //echo $nilaiEsai;?></td> -->
                          <td><?php echo ($model->quiz_type == 4 OR $model->quiz_type == 5 OR $model->quiz_type == 6) ? "N/A" : $totNilai->right_answer;?></td>
                          <td><?php echo ($model->quiz_type == 4 OR $model->quiz_type == 5 OR $model->quiz_type == 6) ? "N/A" : $totNilai->wrong_answer;?></td>
                          <td><?php echo ($model->quiz_type == 4 OR $model->quiz_type == 5 OR $model->quiz_type == 6) ? "N/A" : $totNilai->unanswered;?></td>
                          <td><?php echo ($model->quiz_type == 4 OR $model->quiz_type == 5 OR $model->quiz_type == 6) ? "N/A" : $totNilai->score;?></td>
                          <td><?php echo ($model->quiz_type == 4 OR $model->quiz_type == 5 OR $model->quiz_type == 6) ? "N/A" : $totNilai->essay_score;?></td>
                          <td><?php echo ($model->quiz_type == 4 OR $model->quiz_type == 5 OR $model->quiz_type == 6) ? "" : CHtml::link("BATALKAN", array('/studentQuiz/delete','id'=>$totNilai->id,'id_quiz'=>$model->id), array('confirm' => 'Anda yakin akan membatalkan ujian siswa ini?'));?></td>
                        </tr>
                        <?php } else { ?>
                          <tr>
                            <td><?php echo $no;?></td>
                            <td><?php echo $nk->student->display_name; ?></td>
                            <td><?php echo $model->lesson->name?></td>
                            <td>wrong quiz</td>
                            <td>wrong quiz</td>
                            <td>wrong quiz</td>
                            <td>wrong quiz</td>
                            <td>wrong quiz</td>
                            <td>wrong quiz</td>
                            <td><?php echo ($model->quiz_type == 4 OR $model->quiz_type == 5 OR $model->quiz_type == 6) ? "" : CHtml::link("BATALKAN", array('/studentQuiz/delete','id'=>$totNilai->id,'id_quiz'=>$model->id), array('confirm' => 'Anda yakin akan membatalkan ujian siswa ini?'));?></td>
                         </tr>

                        <?php }?>
                      <?php } 
                      else { ?>
                      <tr>
                        <td><?php echo $no;?></td>
                        <td><?php echo $nk->student->display_name; ?></td>
                        <td><?php echo $model->lesson->name?></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                     </tr>
                      <?php }
                      $no++; } ?>
                    <?php  } ?>
                  </table>
<?php } ?>