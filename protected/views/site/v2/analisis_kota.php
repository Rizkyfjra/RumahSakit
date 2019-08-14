 <?php if (isset($_GET['class'])){
                                if($_GET['class']!='all'){
                                    $student_quiz = $dataProvider->getData();
                                    $model_quiz = Quiz::model()->findByPk($quiz);
                                    $arrCountQuestion = 0;

                                      $arrQuestionScore   = array();
                                      $arrQuestionScore[] = array();

                                      $soal = explode(',', $model_quiz->question);
                                      $countSoal = count($soal);
                                      foreach ($soal as $qids) {
                                        $qval = Questions::model()->findByPk($qids);

                                        $arrCountStudent = 0;
                                        foreach ($student_quiz as $siswa) {
                                          $jawaban = json_decode($siswa->student_answer,true);

                                          if(!empty($jawaban)){
                                            foreach ($jawaban as $keys => $isijawaban) {
                                              if($qval->id == $keys){

                                                // $qval->key_answer = preg_replace("/\r|\n/", "", $qval->key_answer);
                                                // $qval->key_answer = strip_tags($qval->key_answer,"<img>");
                                                // $qval->key_answer = preg_replace('/\s+/', '', $qval->key_answer);
                                                // $qval->key_answer =  str_replace("/>","",$qval->key_answer);
                                                // $qval->key_answer =  str_replace(">","",$qval->key_answer); 

                                                  $qval->key_answer = preg_replace("/\r|\n/", "", $qval->key_answer) ;
                                                  $qval->key_answer = strip_tags($qval->key_answer,"<img>") ;
                                                 
                                                  // $qval->key_answer = preg_replace('/\s+/', '', $qval->key_answer);
                                                  // $qval->key_answer =  str_replace("/>","",$qval->key_answer);
                                                  // $qval->key_answer =  str_replace(">","",$qval->key_answer);

                                                // $isijawaban = preg_replace("/\r|\n/", "", $isijawaban);
                                                // $isijawaban = strip_tags($isijawaban,"<img>");
                                                // $isijawaban = preg_replace('/\s+/', '', $isijawaban);
                                                // $isijawaban =  str_replace("/>","",$isijawaban);
                                                // $isijawaban =  str_replace(">","",$isijawaban);  

                                                 $isijawaban = preg_replace("/\r|\n/", "", $isijawaban);
                                                 $isijawaban = strip_tags($isijawaban,"<img>");
                                               
                                                 // $isijawaban = preg_replace('/\s+/', '', $isijawaban);
                                                 // $isijawaban =  str_replace("/>","",$isijawaban);
                                                 // $isijawaban =  str_replace(">","",$isijawaban);             

                                                if($qval->key_answer === $isijawaban){
                                                  

                                                  if ($arrCountStudent==33) {
                                                    
                                                    $naonwae[$arrCountQuestion]['key_answer'] = $qval->key_answer;
                                                    $naonwae[$arrCountQuestion]['isijawaban'] = $isijawaban;
                                                    $naonwae[$arrCountQuestion]['benarsalah'] = "benar";
                                                    $arrQuestionScore[$arrCountQuestion][$arrCountStudent] = 1;
                                                  } else {
                                                    $arrQuestionScore[$arrCountQuestion][$arrCountStudent] = 1;
                                                  }
                                                }else{
                                                  // echo $isijawaban."</br>";
                                                  // echo $arrCountStudent." ### ".$qval->key_answer." ### ".$isijawaban."naon</br>";
                                                  if ($arrCountStudent==33) {
                                                    
                                                    $naonwae[$arrCountQuestion]['key_answer'] = $qval->key_answer;
                                                    $naonwae[$arrCountQuestion]['isijawaban'] = $isijawaban;
                                                    $naonwae[$arrCountQuestion]['benarsalah'] = "salah";
                                                    $arrQuestionScore[$arrCountQuestion][$arrCountStudent] = 0;
                                                  } else {
                                                    $arrQuestionScore[$arrCountQuestion][$arrCountStudent] = 0;
                                                  }
                                                  
                                                }
                                              }
                                            }

                                            // Unaswered Only a Few Question
                                            if(empty($arrQuestionScore[$arrCountQuestion][$arrCountStudent])){
                                              // echo $isijawaban."</br>";
                                              $arrQuestionScore[$arrCountQuestion][$arrCountStudent] = 0;
                                            }
                                          }else{
                                            // Unaswered All Question        
                                            $arrQuestionScore[$arrCountQuestion][$arrCountStudent] = 0;
                                          }
                                          $arrCountStudent++;
                                        }
                                        $arrCountQuestion++;
                                      }

                                      $arrDataSiswa       = array();
                                      $arrCountDataSiswa  = 0;
                                      if(!empty($student_quiz)){
                                        foreach ($student_quiz as $siswa) {
                                          $arrDataSiswa[$arrCountDataSiswa]['id']           = $siswa->id;
                                          $arrDataSiswa[$arrCountDataSiswa]['display_name'] = "asdf";
                                          // $arrDataSiswa[$arrCountDataSiswa]['display_name'] = $siswa->user->display_name;
                                          $arrDataSiswa[$arrCountDataSiswa]['score']        = $siswa->score;

                                          $arrCountDataSiswa++;
                                        }
                                      }

                                       if($arrCountQuestion>0 && $arrCountStudent>0){
                                        ?>
                                        <div class="table-responsive">
                                          <table class="table table-hover table-bordered ">
                                            <tr class="table-exam-header">
                                              <th rowspan="2">Nama Siswa</th>
                                              <th colspan="<?php echo $countSoal;?>" class="text-center">Nomor Soal</th>
                                              <th rowspan="2">Total</th>
                                              <th rowspan="2">Nilai</th>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <?php
                                                if(!empty($model->question)){

                                                  $no = 1;
                                                  for($no = 1; $no <= $countSoal; $no++) {
                                                    if ($no % 2 == 1){
                                                      echo '<td class="table-striped-horizontal">'.$no.'</td>';
                                                    }else{
                                                      echo '<td>'.$no.'</td>';
                                                    }
                                                  }
                                                }
                                              ?>
                                            </tr>
                                            <?php
                                              $arrStudentScore = array();
                                              for($i=0; $i<$arrCountStudent; $i++){
                                            ?>
                                            <tr class="text-center">
                                              <td class="text-left"><?php echo CHtml::link($arrDataSiswa[$i]['display_name'], array('/studentQuiz/view','id'=>$arrDataSiswa[$i]['id']));?></td>
                                              <?php
                                                  $totalBenar = 0;
                                                  $totalSalah = 0;

                                                  $no = 1;
                                                  for($j=0; $j<$arrCountQuestion; $j++){
                                                    if($arrQuestionScore[$j][$i]==1){
                                                      if($no % 2 == 1){
                                                        echo '<td class="table-striped-horizontal">B</td>';
                                                      }else{
                                                        echo '<td>B</td>';
                                                      }

                                                      $totalBenar++;
                                                    }else{
                                                      if($no % 2 == 1){
                                                        echo '<td class="table-striped-horizontal">S</td>';
                                                      }else{
                                                        echo '<td>S</td>';
                                                      }

                                                      $totalSalah++;
                                                    }

                                                    $no++;
                                                  }
                                              ?>
                                              <td class="text-center text-score">
                                                <span class="text-green"><?php echo $totalBenar; ?></span>|<span class="text-red"><?php echo $totalSalah; ?></span>
                                              </td>
                                              <td class="text-green text-center text-score"><?php echo $arrDataSiswa[$i]['score']; ?></td>
                                            </tr>
                                            <?php
                                                $arrStudentScore[$i] = $totalBenar;
                                              }
                                            ?>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header"><span class="text-green">B</span>|<span class="text-red">S</span></td>
                                              <?php
                                                $arrPerSoalBenar = array();
                                                $arrPerSoalSalah = array();

                                                $no = 1;
                                                for($i=0; $i<$arrCountQuestion; $i++){
                                                  $perSoalBenar   = 0;
                                                  $perSoalSalah   = 0;

                                                  for($j=0; $j<$arrCountStudent; $j++){
                                                    if($arrQuestionScore[$i][$j]==1){
                                                      $perSoalBenar++;
                                                    }else{
                                                      $perSoalSalah++;
                                                    }
                                                  }

                                                  if($no % 2 == 1){
                                                    echo '<td class="table-striped-horizontal"><span class="text-green">'.$perSoalBenar.'</span>|<span class="text-red">'.$perSoalSalah.'</span></td>';
                                                  }else{
                                                    echo '<td><span class="text-green">'.$perSoalBenar.'</span>|<span class="text-red">'.$perSoalSalah.'</span></td>';
                                                  }

                                                  $arrPerSoalBenar[$i] = $perSoalBenar;
                                                  $arrPerSoalSalah[$i] = $perSoalSalah;
                                                  $no++;
                                                }
                                              ?>
                                              <td></td>
                                              <td></td>
                                            </tr>

                                            <tr class="table-exam-header">
                                              <td colspan="<?php echo $countSoal+3 ?>" class="table-exam-header">&nbsp;</td>
                                            </tr>

                                            <tr class="table-exam-header">
                                              <td colspan="<?php echo $countSoal+3 ?>" class="table-exam-header text-green" style="text-align:left !important;padding-left:10px">Reliabilitas</td>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header" style="text-align:left !important;padding-left:10px">Varian Per-Soal</td>
                                              <?php
                                                $no = 1; $varQuestion = 0;
                                                for($i=0; $i<$arrCountQuestion; $i++){
                                                  $varItem[$i]  = $this->statsVars($arrQuestionScore[$i]);

                                                  if($no % 2 == 1){
                                                    echo '<td class="table-striped-horizontal text-green">'.$varItem[$i].'</td>';
                                                  }else{
                                                    echo '<td class="text-green">'.$varItem[$i].'</td>';
                                                  }

                                                  $varQuestion = $varQuestion + $varItem[$i];
                                                  $no++;
                                                }
                                              ?>
                                              <td></td>
                                              <td></td>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header" style="text-align:left !important;padding-left:10px">Jumlah Varian Soal</td>
                                              <td colspan="<?php echo $countSoal ?>" class="table-exam-header text-green" style="text-align:left !important;padding-left:10px"><?php echo $varQuestion; ?></td>
                                              <td></td>
                                              <td></td>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header" style="text-align:left !important;padding-left:10px">Varian Nilai Siswa</td>
                                              <td colspan="<?php echo $countSoal ?>" class="table-exam-header text-green" style="text-align:left !important;padding-left:10px"><?php echo $varStudent = $this->statsVars($arrStudentScore); ?></td>
                                              <td></td>
                                              <td></td>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header" style="text-align:left !important;padding-left:10px">Nilai Reliabilitas</td>
                                              <td colspan="<?php echo $countSoal ?>" class="table-exam-header text-green" style="text-align:left !important;padding-left:10px"><?php echo $reliability = $this->statsReliability($arrCountQuestion, $varQuestion, $varStudent); ?></td>
                                              <td></td>
                                              <td></td>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header" style="text-align:left !important;padding-left:10px">Kategori</td>
                                              <td colspan="<?php echo $countSoal ?>" class="table-exam-header text-green" style="text-align:left !important;padding-left:10px"><?php echo $this->statsReliabilityCategory($reliability); ?></td>
                                              <td></td>
                                              <td></td>
                                            </tr>

                                            <tr class="table-exam-header">
                                              <td colspan="<?php echo $countSoal+3 ?>" class="table-exam-header">&nbsp;</td>
                                            </tr>

                                            <tr class="table-exam-header">
                                              <td colspan="<?php echo $countSoal+3 ?>" class="table-exam-header text-green" style="text-align:left !important;padding-left:10px">Validitas <span class="text-red">(rTabel = <?php echo $this->statsRTable(count($arrStudentScore)); ?>)</span></td>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header" style="text-align:left !important;padding-left:10px">r(x,y)</td>
                                              <?php
                                                $no = 1;
                                                for($i=0; $i<$arrCountQuestion; $i++){
                                                  $rxy[$i]  = $this->statsPearson($arrQuestionScore[$i], $arrStudentScore);

                                                  if($no % 2 == 1){
                                                    echo '<td class="table-striped-horizontal text-green">'.$rxy[$i].'</td>';
                                                  }else{
                                                    echo '<td class="text-green">'.$rxy[$i].'</td>';
                                                  }

                                                  $no++;
                                                }
                                              ?>
                                              <td></td>
                                              <td></td>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header" style="text-align:left !important;padding-left:10px">Kategori</td>
                                              <?php
                                                $no = 1;
                                                for($i=0; $i<$arrCountQuestion; $i++){
                                                  $validityCategory = $this->statsValidityCategory($rxy[$i]);

                                                  if($validityCategory=="S" || $validityCategory=="T" || $validityCategory=="ST"){
                                                    $txtClass = "text-green";
                                                  }else{
                                                    $txtClass = "text-red";
                                                  }

                                                  if($no % 2 == 1){
                                                    echo '<td class="table-striped-horizontal '.$txtClass.'">'.$validityCategory.'</td>';
                                                  }else{
                                                    echo '<td class="'.$txtClass.'">'.$validityCategory.'</td>';
                                                  }

                                                  $no++;
                                                }
                                              ?>
                                              <td></td>
                                              <td></td>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header" style="text-align:left !important;padding-left:10px">Kesimpulan</td>
                                              <?php
                                                $no = 1;
                                                for($i=0; $i<$arrCountQuestion; $i++){
                                                  $validity = $this->statsValidity(count($arrStudentScore), $rxy[$i]);

                                                  if($validity=="Valid"){
                                                    $txtClass = "text-green";
                                                  }else{
                                                    $txtClass = "text-red";
                                                  }

                                                  if($no % 2 == 1){
                                                    echo '<td class="table-striped-horizontal '.$txtClass.'">'.$validity.'</td>';
                                                  }else{
                                                    echo '<td class="'.$txtClass.'">'.$validity.'</td>';
                                                  }

                                                  $no++;
                                                }
                                              ?>
                                              <td></td>
                                              <td></td>
                                            </tr>

                                            <tr class="table-exam-header">
                                              <td colspan="<?php echo $countSoal+3 ?>" class="table-exam-header">&nbsp;</td>
                                            </tr>

                                            <tr class="table-exam-header">
                                              <td colspan="<?php echo $countSoal+3 ?>" class="table-exam-header text-green" style="text-align:left !important;padding-left:10px">Kesukaran</td>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header" style="text-align:left !important;padding-left:10px">Tingkat Kesukaran</td>
                                              <?php
                                                $no = 1;
                                                for($i=0; $i<$arrCountQuestion; $i++){
                                                  $arrKesukaran[$i] = round($arrPerSoalBenar[$i]/$arrCountStudent, 2);

                                                  if($no % 2 == 1){
                                                    echo '<td class="table-striped-horizontal text-green">'.$arrKesukaran[$i].'</td>';
                                                  }else{
                                                    echo '<td class="text-green">'.$arrKesukaran[$i].'</td>';
                                                  }

                                                  $no++;
                                                }
                                              ?>
                                              <td></td>
                                              <td></td>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header" style="text-align:left !important;padding-left:10px">Kategori</td>
                                              <?php
                                                $no = 1;
                                                for($i=0; $i<$arrCountQuestion; $i++){
                                                  $kesukaranCategory = $this->statsKesukaranCatergory($arrKesukaran[$i]);

                                                  if($kesukaranCategory=="Sd" || $kesukaranCategory=="M" || $kesukaranCategory=="Sr"){
                                                    $txtClass = "text-green";
                                                  }else{
                                                    $txtClass = "text-red";
                                                  }

                                                  if($no % 2 == 1){
                                                    echo '<td class="table-striped-horizontal '.$txtClass.'">'.$kesukaranCategory.'</td>';
                                                  }else{
                                                    echo '<td class="'.$txtClass.'">'.$kesukaranCategory.'</td>';
                                                  }

                                                  $no++;
                                                }
                                              ?>
                                              <td></td>
                                              <td></td>
                                            </tr>

                                            <tr class="table-exam-header">
                                              <td colspan="<?php echo $countSoal+3 ?>" class="table-exam-header">&nbsp;</td>
                                            </tr>

                                            <tr class="table-exam-header">
                                              <td colspan="<?php echo $countSoal+3 ?>" class="table-exam-header text-green" style="text-align:left !important;padding-left:10px">Daya Pembeda</td>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header" style="text-align:left !important;padding-left:10px">Nilai Daya Pembeda</td>
                                              <?php
                                                $no = 1;
                                                $retMatrixSorted = $this->statsMatrixSort($arrQuestionScore, $arrStudentScore, $arrCountQuestion);

                                                $sortedArrQuestionScore = $retMatrixSorted[0];
                                                $sortedArrStudentScore  = $retMatrixSorted[1];

                                                for($i=0; $i<$arrCountQuestion; $i++){
                                                  $arrDayaPembeda[$i] = $this->statsDayaPembeda($sortedArrQuestionScore[$i], $sortedArrStudentScore);

                                                  if($no % 2 == 1){
                                                    echo '<td class="table-striped-horizontal text-green">'.$arrDayaPembeda[$i].'</td>';
                                                  }else{
                                                    echo '<td class="text-green">'.$arrDayaPembeda[$i].'</td>';
                                                  }

                                                  $no++;
                                                }
                                              ?>
                                              <td></td>
                                              <td></td>
                                            </tr>
                                            <tr class="table-exam-header">
                                              <td class="table-exam-header" style="text-align:left !important;padding-left:10px">Kategori</td>
                                              <?php
                                                $no = 1;
                                                for($i=0; $i<$arrCountQuestion; $i++){
                                                  $dayaPembedaCategory = $this->statsDayaPembedaCategory($arrDayaPembeda[$i]);

                                                  if($dayaPembedaCategory=="C" || $dayaPembedaCategory=="B" || $dayaPembedaCategory=="SB"){
                                                    $txtClass = "text-green";
                                                  }else{
                                                    $txtClass = "text-red";
                                                  }

                                                  if($no % 2 == 1){
                                                    echo '<td class="table-striped-horizontal '.$txtClass.'">'.$dayaPembedaCategory.'</td>';
                                                  }else{
                                                    echo '<td class="'.$txtClass.'">'.$dayaPembedaCategory.'</td>';
                                                  }

                                                  $no++;
                                                }
                                              ?>
                                              <td></td>
                                              <td></td>
                                            </tr>
                                          </table>
                                        </div>
                                        <?php
                                          

                                          // echo "<pre>";
                                          // //   printr($naonwae);
                                          // // $naonwae = json_encode($naonwae);
                                          // print_r($naonwae);
                                          // // echo "fasdf";
                                          // echo "</pre>";


                                          }
                                        ?>




                           <?php     }



                       }?>