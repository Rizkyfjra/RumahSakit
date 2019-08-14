<?php
  if($model->lesson->moving_class==1){
    $namaKelas = $model->lesson->grade->name;
  }else{
    $namaKelas = $model->lesson->class->name;
  }

  // $range_1 = 0;
  // $range_2 = 0;
  // $range_3 = 0;
  // $range_4 = 0;
  // $range_5 = 0;
  // $range_6 = 0;
  // $range_7 = 0;
  // $range_8 = 0;
  // $range_9 = 0;
  // $range_10 = 0; 

  // $raw=explode(',', $model->question);
  // $soal_pg=0;

  // foreach ($raw as $sl) {
  //   $cekSoal = Questions::model()->findByPk($sl);
  //   if(!empty($cekSoal)){
  //     if($cekSoal->type != 2){
  //       $soal_pg++;
  //     }
  //   }
  // }

  // foreach ($student_quiz as $nk) {
  //   $benar=NULL;
  //   $salah=NULL;
  //   $kosong=NULL;
  //   $total_jawab=NULL;
    
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
  //       } 
  //     }
  //     $nilaiPg=round(($benar/$soal_pg)*100);
  //   }
  //   $nilaiEsai = $nk->score - $nilaiPg;

  //   if (($nk->score >= 0) && ($nk->score <= 10)) {
  //     $range_1 = $range_1 + 1;
  //   } else if (($nk->score >= 11) && ($nk->score <= 20)){
  //     $range_2 = $range_2 + 1;
  //   } else if (($nk->score >= 21) && ($nk->score <= 30)){
  //     $range_3 = $range_3 + 1;
  //   } else if (($nk->score >= 31) && ($nk->score <= 40)){
  //     $range_4 = $range_4 + 1;
  //   } else if (($nk->score >= 41) && ($nk->score <= 50)){
  //     $range_5 = $range_5 + 1;
  //   } else if (($nk->score >= 51) && ($nk->score <= 60)){
  //     $range_6 = $range_6 + 1;
  //   } else if (($nk->score >= 61) && ($nk->score <= 70)){
  //     $range_7 = $range_7 + 1;
  //   } else if (($nk->score >= 71) && ($nk->score <= 80)){
  //     $range_8 = $range_8 + 1;
  //   } else if (($nk->score >= 81) && ($nk->score <= 90)){
  //     $range_9 = $range_9 + 1;
  //   } else if (($nk->score >= 91) && ($nk->score <= 100)){
  //     $range_10 = $range_10 + 1;
  //   }                       
  // }
?>
<div class="container-fluid">
  <div class="row">
    <div class="col-lg-12">
      <div class="col-card">
        <div class="row">
          <div class="col-md-12 text-center">
             <h2><?php echo ucfirst($model->title); ?> - <?php echo $namaKelas; ?></h2>
             <p>Waktu Pengerjaan: <strong><?php echo $model->end_time; ?> Menit</strong></p>
          </div>
          <div class="clearfix"></div>
          <hr>
          <?php foreach ($studentQuizTemp as $studentTemp) { ?>
            <div class="col-md-3 col-sm-4 col-pengawas-user">
              <figure>
                 <?php
                          if($studentTemp->user->image){
                            // $foto = Yii::app()->baseUrl.'/images/user/'.$studentTemp->user->id.'/'.$studentTemp->user->image;
                            $foto = Yii::app()->baseUrl.'/images/user-2.png';
                          }else{
                            $foto = Yii::app()->baseUrl.'/images/user-2.png';
                          }
                  ?>
                <?php if ($studentTemp->status) { ?>
                  <?php if ($studentTemp->status==3) { ?>
                    <img
                      src="<?php echo $foto ?>"
                      class="img-circle img-thumbnail img-pengawas-freeze"
                      width="150"
                      alt="">
                  <?php } else { ?>
                    <img
                      src="<?php echo $foto ?>"
                      class="img-circle img-thumbnail"
                      width="150"
                      alt="">
                  <?php } ?>    
                <?php } else { ?>
                <img
                  src="<?php echo $foto ?>"
                  class="img-circle img-thumbnail"
                  width="150"
                  alt="">
                <?php }  ?>  
                
              </figure>
              <hr>
              <div class="pengawas-information">
                <h4 class="pengawas-name"><?php echo $studentTemp->user->display_name; ?></h4>
                <?php if ($studentTemp->status ) { ?>
                      <?php if ($studentTemp->status==1 || $studentTemp->status==3) { ?>
                        <h4><span class="label label-info"><i class="fa fa-spinner fa-spin"></i> Sedang Mengerjakan</span></h4>
                      <?php } elseif ($studentTemp->status==2) { ?>
                        <h4><span class="label label-success"><i class="fa fa-check"></i> Sudah Selesai</span></h4> 
                      <?php } ?>
                <?php } else { ?>
                      <h4><span class="label label-warning"><i class="fa fa-times"></i> Belum Mulai</span></h4>
                <?php } ?> 
               

                <?php if ($studentTemp->status) { ?>
                  <?php if ($studentTemp->status==3) { ?>
                    <button type="button" data-qid="<?php echo $model->id;?>" data-uid="<?php echo $studentTemp->user->id;?>" name="button" class="btn btn-primary btn-sm btn-unfreez"><i class="fa fa-power-off"></i> Buka Freeze</button>
                  <?php } ?>   
                <?php } ?> 

              </div>
              <hr>
            </div> 
          <?php } ?>
        </div>
      </div><!-- /.col-card -->
    </div><!-- /.col-lg-12 -->
  </div><!-- /.row -->
</div>
<script type="text/javascript">
  $(".btn-unfreez").click(function(){
            $('.modal-loading').addClass("show");
            var postData = { //Fetch form data
              'qid'     : $(this).attr('data-qid'),
              'uid'     : $(this).attr('data-uid') //Store name fields value
            };
          $.ajax({
            type:"post",
            url:"<?php echo Yii::app()->baseUrl; ?>/quiz/AjaxUnFreezQuiz",
            data: postData,
            success:function(result)
            {
                //do something with response data
                if (result=='berhasil') {
                  // $('.modal-loading').removeClass("show");
                  // alert($(this).attr('data-qid'));
                   location.reload(); 
                }else {
                  // $('.modal-loading').removeClass("show"); 
                   location.reload(); 
                };
                
            } 
          });

          // kode = $("#kode").val();
          
    });

    setInterval(function () { 
      location.reload(); 
    }, 10000);
</script>

</div>
