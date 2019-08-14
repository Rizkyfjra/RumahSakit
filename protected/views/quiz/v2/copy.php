<?php
  if (!empty($model->lesson_id)) {
    $lesson = $model->mapel;
    $model->lesson_id = $lesson->name.' (ID:'.$model->lesson_id.')';
  }

  $mapel=array();
  if(!empty($lessons)){
    foreach ($lessons as $value) {
      if($value->moving_class == 1){
        $mapel[$value->id]=$value->name." (".$value->grade->name.")";
      }else{
        $mapel[$value->id]=$value->name." (".$value->class->name.")";
      }
    }
  }

  if(!empty($model->lesson_id)){
    $selected = $model->lesson_id;
  }else{
    $selected = 1;
  }

  $lid=NULL;
  if(isset($_GET['lks_id'])){
    $lid=$_GET['lks_id'];
  }
?>

<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_exam_copy', array(

      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
      <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Ujian</div>',array('/quiz/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>List</div>',array('/quiz/list'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Salin Ujian</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
      <?php $form=$this->beginWidget('CActiveForm', array(
        'id'=>'chapters-form',
        'enableAjaxValidation'=>false,
        'htmlOptions' => array('enctype' => 'multipart/form-data'),
      )); ?>
      <h3>Salin Ujian</h3>
      <div class="row">
        <div class="col-md-12">
          <div class="col-card">
            <h4>Informasi Umum</h4>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="judul">Nama Ujian</label>
                  
                  <input type="text" class="form-control input-pn input-lg" name="judul">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label for="Quiz_lesson">Nama Mata Pelajaran</label>
                  <select data-placeholder="Pilih Mata Pelajaran ..." class="form-control input-pn input-lg chosen-select" multiple style="width:100%;" tabindex="4" name="pelajaran[]" id="chosenPelajaran">
                    <?php foreach ($mapel as $key => $value) { ?>
                      <option value="<?php echo $key;?>"><?php echo $value;?></option>
                    <?php } ?>
                  </select>
                </div>
              </div>
            </div>
            <hr/>
            <div class="row">
              <div class="col-md-12">
                <button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Salin Ujian</button>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
  <?php $this->endWidget(); ?>
</div>
<script>
  $("#chosenPelajaran").chosen({
    allow_single_deselect: true,
    max_shown_results: 10,
    no_results_text: "Nama Mata Pelajaran Tidak Ditemukan!",
    width: "95%"
  });
</script>
