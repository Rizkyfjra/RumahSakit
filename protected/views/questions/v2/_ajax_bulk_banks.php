<?php
  if(empty($model)){
    $id = NULL;

    $shareStatus = NULL;
    $shareTeacher = NULL;
  }else{
    $id = $model->id;

    $shareStatus = $model->share_status;
    $shareTeacher = $model->share_teacher;

    $arrTeacher = explode(",", substr($shareTeacher, 1, -1));
  }
?>
<input type="hidden" name="single-id" value="<?php echo $id ?>">
<div class="modal-body" style="max-height: calc(100vh - 212px);overflow-y: auto;">
  <div class="container-fluid">
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <input type="radio" class="share" name="share_status" id="share_status_1" autocomplete="off" value="" <?php echo '',(empty($shareStatus)) ? 'checked' : '' ?>>&nbsp;<span class="btn btn-danger btn-xs" style="cursor:default"><i class="fa fa-lock"></i> <strong>Tidak Dibagikan</strong></span>
          <p class="help-block" style="margin-left:16px">
            Soal atau Pertanyaan Tidak Dapat Dilihat Atau Digunakan Oleh Siapapun dan Hanya Dapat Di Sunting Oleh Akun Sendiri
          </p>
        </div> 
      </div>
    </div>
    <br class="clear-fix"/>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <input type="radio" class="share" name="share_status" id="share_status_2" autocomplete="off" value="1" <?php echo '',(!empty($shareStatus) && $shareStatus==1) ? 'checked' : '' ?>>&nbsp;<span class="btn btn-warning btn-xs" style="cursor:default"><i class="fa fa-unlock"></i> <strong>Dibagikan Ke Guru Satu Mata Pelajaran</strong></span>
          <p class="help-block" style="margin-left:16px">
            Soal atau Pertanyaan Dapat Dilihat Atau Digunakan Oleh Guru Satu Mata Pelajaran Tetapi Hanya Dapat Di Sunting Oleh Akun Sendiri
          </p>
        </div> 
      </div>
    </div>
    <br class="clear-fix"/>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <input type="radio" class="share" name="share_status" id="share_status_3" autocomplete="off" value="2" <?php echo '',(!empty($shareStatus) && $shareStatus==2) ? 'checked' : '' ?>>&nbsp;<span class="btn btn-warning btn-xs" style="cursor:default"><i class="fa fa-unlock"></i> <strong>Dibagikan Ke Beberapa Guru</strong></span>
          <div style="padding-top:5px;margin-bottom:5px;margin-left:16px">
            <select data-placeholder="Nama Guru..." class="form-control input-pn input-lg chosen-select" multiple style="width:100%;" name="guru[]" id="chosenGuru" disabled="true">
              <?php
                $guru = array();
                $listGuru = User::model()->findAll(array("condition"=>"role_id = 1 and id != ".Yii::app()->user->id));

                if(!empty($listGuru)){
                  foreach ($listGuru as $value) {
                    $guru[$value->id]=$value->display_name;
                  }

                  foreach ($guru as $key => $value) {
                    if(!empty($shareTeacher)){
                      $isMatch = FALSE;
                      foreach ($arrTeacher as $arrKey => $arrValue) {
                        if($key==$arrValue){
                          $isMatch = TRUE;
                        }
                      }

                      if($isMatch){
              ?>
              <option value="<?php echo $key ?>" selected><?php echo $value ?></option>
              <?php
                      }else{
              ?>
              <option value="<?php echo $key ?>"><?php echo $value ?></option>
              <?php
                      }
                    }else{
              ?>
              <option value="<?php echo $key ?>"><?php echo $value ?></option>
              <?php
                    }
                  }
                }
              ?>
            </select>
          </div>
          <p class="help-block" style="margin-left:16px">
            Soal atau Pertanyaan Dapat Dilihat Atau Digunakan Oleh Beberapa Guru Lainnya Tetapi Hanya Dapat Di Sunting Oleh Akun Sendiri
          </p>          
        </div> 
      </div>
    </div>
    <br class="clear-fix"/>
    <div class="row">
      <div class="col-md-12">
        <div class="form-group">
          <input type="radio" class="share" name="share_status" id="share_status_4" autocomplete="off" value="3" <?php echo '',(!empty($shareStatus) && $shareStatus==3) ? 'checked' : '' ?>>&nbsp;<span class="btn btn-success btn-xs" style="cursor:default"><i class="fa fa-globe"></i> <strong>Dibagikan Ke Publik</strong></span>
          <p class="help-block" style="margin-left:16px">
            Soal atau Pertanyaan Dapat Dilihat Atau Digunakan Oleh Semua Guru Tetapi Hanya Dapat Di Sunting Oleh Akun Sendiri
          </p>
        </div> 
      </div>
    </div>
  </div> 
</div>
<script>
  function toggleChosen(){
    if($('#share_status_3').is(':checked')){
      $('#chosenGuru').prop('disabled', false).trigger("chosen:updated");
    }else{
      $('#chosenGuru').prop('disabled', true).trigger("chosen:updated");       
    }
  }
  toggleChosen();

  $('.share').on("change",function(){
    toggleChosen();
  });

  $("#chosenGuru").chosen({
    allow_single_deselect: true,
    max_shown_results: 5,
    no_results_text: "Nama Guru Tidak Ditemukan!",
    width: "95%"
  });
</script>
