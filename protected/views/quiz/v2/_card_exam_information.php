<div class="col-card card-exam-switch">
  <div class="pull-left">
    <h4>
      Status Ujian
      <?php if($model->status==1){ ?>
        <span id="status-<?php echo $model->id ?>" class="label label-success">Ditampilkan</span>
      <?php } else if($model->status==2){ ?>
        <span id="status-<?php echo $model->id ?>" class="label label-danger">Ditutup</span>
      <?php } else { ?>
        <span id="status-<?php echo $model->id ?>" class="label label-warning">Draft</span>
      <?php } ?>
    </h4>
  </div>
  <?php if(!Yii::app()->user->YiiStudent){ ?>  
  <div class="pull-right">
    <div class="pn-switch">
        <input type="checkbox" quiz-id="<?php echo $model->id;?>" name="switch1" class="pn-switch-checkbox" id="switch1" <?php echo '',$model->status == 1 ? "checked" : "" ;?>>
        <label class="pn-switch-label" for="switch1"></label>
    </div>
    <script type="text/javascript">
      $('.pn-switch-checkbox').on("change",function(){
          $('.modal-loading').addClass("show");
          if(!$(this).prop('checked')){
            var url = "<?php echo Yii::app()->baseUrl; ?>/quiz/ajaxHideQuiz/"+$(this).attr('quiz-id');
          } else {
            var url = "<?php echo Yii::app()->baseUrl; ?>/quiz/ajaxDisplayQuiz/"+$(this).attr('quiz-id');
          }
          var idStatus = 'status-'+$(this).attr('quiz-id');
          var idQuiz = $(this).attr('quiz-id');
            $.post(url,
            {

            },
            function(data, status){
                if($('#'+idStatus).hasClass('label-success')){
                  $('#'+idStatus).removeClass('label-success');
                  $('#'+idStatus).addClass('label-danger');
                  $('#'+idStatus).html('Ditutup');
                  $('.modal-loading').removeClass("show");
                } else if ($('#'+idStatus).hasClass('label-warning')){
                  $('#'+idStatus).removeClass('label-warning');
                  $('#'+idStatus).addClass('label-success');
                  $('#'+idStatus).html('Ditampilkan');
                  $('.modal-loading').removeClass("show");
                } else if ($('#'+idStatus).hasClass('label-danger')){
                  $('#'+idStatus).removeClass('label-danger');
                  $('#'+idStatus).addClass('label-success');
                  $('#'+idStatus).html('Ditampilkan');
                  $('.modal-loading').removeClass("show");
                }
            });
      });
    </script>
  </div>
  <?php } ?>   
  <div class="clearfix"></div>
</div>
<div class="col-card">
  <table class="table table-hover table-condensed">
    <thead>
        <tr>
          <th>
            Informasi
          </th>
          <th>
            Detail
          </th>
        </tr>
      </thead>
    <tbody>
      <tr>
        <td>
          Waktu Pengerjaan
        </td>
        <td>
          <?php echo $model->end_time;?> Menit
        </td>
      </tr>
      <tr>
        <td>
          Total Pertanyan
        </td>
        <td>
          <?php echo $model->total_question;?>
        </td>
      </tr>
      <?php if(!Yii::app()->user->YiiStudent){ ?>
      <tr>
        <td>
          Acak Soal
        </td>
        <td>
          <?php
            if($model->random == 1){
              echo "Ya";
            }else{
              echo "Tidak";
            }
          ?>
        </td>
      </tr>
      <tr>
        <td>
          Acak Jawaban
        </td>
        <td>
          <?php
            if($model->random_opt == 1){
              echo "Ya";
            }else{
              echo "Tidak";
            }
          ?>
        </td>
      </tr>
      <tr>
        <td>
          Percobaan Kuis
        </td>
        <td>
          <?php echo $model->repeat_quiz;?> Kali
        </td>
      </tr>
      <tr>
        <td>
          Kode Pembuka
        </td>
        <td>
          <code><?php echo $model->passcode;?></code>
        </td>
      </tr>
      <?php } ?>      
    </tbody>
  </table>

  <?php if(!Yii::app()->user->YiiStudent){ ?>  
  <!-- Single button -->
  <div class="btn-group btn-block dropup">
    <button type="button" class="btn btn-pn-primary btn-lg btn-block dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <i class="fa fa-gear"></i> Aksi Ujian <i class="fa fa-caret-up"></i>
    </button>
    <ul class="dropdown-menu btn-block">
      <li><?php echo CHtml::link('<i class="fa fa-pencil"></i> Sunting Ujian',array('update', 'id'=>$model->id)); ?>
      <li><?php echo CHtml::link('<i class="fa fa-eye"></i> Pratinjau Ujian',array('startQuiz', 'id'=>$model->id)); ?>
      <li><?php echo CHtml::link('<i class="fa fa-download"></i> Unduh Nilai Ujian',array('downloadNilai', 'id'=>$model->id)); ?>
      <li role="separator" class="divider"></li>
      <li><?php echo CHtml::link('<i class="fa fa-copy"></i> Salin Ujian',array('copy', 'id'=>$model->id)); ?>
    </ul>
  </div>
  <?php
    }else{
      $cekQuiz = StudentQuiz::model()->findByAttributes(array('quiz_id'=>$model->id,'student_id'=>Yii::app()->user->id));

      if(!empty($cekQuiz)){
        if($cekQuiz->attempt == $model->repeat_quiz){
  ?>
  <a href="#" class="btn btn-danger btn-lg btn-block">
    <i class="fa fa-times"></i> Sudah Mengerjakan
  </a>
  <?php
        }else{
          if($model->status == 1){
  ?>
  <a href="<?php echo $this->createUrl('/quiz/startQuiz?id='.$model->id.'&sq='.$cekQuiz->id) ?>" class="btn btn-pn-primary btn-lg btn-block">
    <i class="fa fa-pencil"></i> Mulai
  </a>
  <?php
          }
        }
      }else{
        if($model->status == 1){
  ?>
  <a href="<?php echo $this->createUrl('/quiz/startQuiz?id='.$model->id) ?>" class="btn btn-pn-primary btn-lg btn-block">
    <i class="fa fa-pencil"></i> Mulai
  </a>
  <?php
        }
      }
    }
  ?>  
</div>
