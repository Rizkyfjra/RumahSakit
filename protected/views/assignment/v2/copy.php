<?php
    if (!empty($model->lesson_id)) {
        $lesson = $model->mapel;
        $model->lesson_id = $lesson->name.' (ID:'.$model->lesson_id.')';

    }

    $mapel=array();
    if(!empty($lessons)){
       foreach ($lessons as $value) {
            $mapel[$value->id]=$value->name." (".$value->class->name.")";
        }
    }

    if(!empty($model->add_to_summary)){
        $slc = $model->add_to_summary;
    }else{
        $slc = NULL;
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
      // $this->renderPartial('v2/_breadcrumb_task_copy', array(

      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
        <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>Tugas</div>',array('/assignment/index'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>List</div>',array('/assignment/list'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>Salin Tugas</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
        <?php $form=$this->beginWidget('CActiveForm', array(
            'id'=>'chapters-form',
            'enableAjaxValidation'=>false,
            'htmlOptions' => array('enctype' => 'multipart/form-data'),
        )); ?>
      <h3>Salin Tugas</h3>
      <div class="row">
        <div class="col-md-12">
          <div class="col-card">
            <h4>Informasi Umum</h4>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="Assignment_title">Nama Tugas</label>
                        <?php echo $form->textField($model,'title',array('class'=>'form-control input-pn input-lg','placeholder'=>'Isi nama tugas disini (contoh: Tugas Membuat Puisi) …','required'=>'required')); ?>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-8">
                    <div class="form-group">
                        <label for="Assignment_lesson_id">Nama Mata Pelajaran</label>
                        <?php
                            if(empty($lsn)){
                                echo $form->dropDownList($model,'lesson_id',$mapel,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg','data-live-search'=>'true','options'=>array($selected=>array('selected'=>true))));
                            }else{
                                echo $form->dropDownList($model,'lesson_id',$mapel,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg','data-live-search'=>'true','disabled'=>'disabled','readonly'=>'readonly','options'=>array($selected=>array('selected'=>true))));
                            }
                         ?>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="Assignment_add_to_summary">Tambah Ke Rekap Nilai</label>
                        <div class="btn-group btn-group-justified" data-toggle="buttons">
                            <label class="btn btn-info btn-lg <?php echo '',$slc == NULL ? "active" : "" ?> <?php echo '',empty($tipe) ? "" : "disabled readonly" ?>">
                              <input type="radio" name="Assignment[add_to_summary]" id="Assignment_add_to_sumary_1" autocomplete="off" value="" <?php echo '',$slc == NULL ? "checked" : "" ?> <?php echo '',empty($tipe) ? "" : "disabled readonly" ?>> Ya
                            </label>
                            <label class="btn btn-info btn-lg <?php echo '',$slc == 1 ? "active" : "" ?> <?php echo '',empty($tipe) ? "" : "disabled readonly" ?>">
                              <input type="radio" name="Assignment[add_to_summary]" id="Assignment_add_to_sumary_2" autocomplete="off" value="1" <?php echo '',$slc == 1 ? "checked" : "" ?> <?php echo '',empty($tipe) ? "" : "disabled readonly" ?>> Tidak
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <br/>
            <h4>Tugas</h4>
            <?php if(empty($tipe)){ ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="Assignment_due_date">Batas Akhir Pengumpulan</label>
                        <?php
                            $this->widget('ext.YiiDateTimePicker.jqueryDateTime', array(
                                'model' => $model,
                                'attribute' => 'due_date',
                                'options' => array(),
                                'htmlOptions' => array(
                                'class'=>'form-control input-pn input-lg'),
                            ));
                        ?>
                    </div>
                </div>
            </div>
            <?php } ?>
            <hr/>
            <div class="row">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Salin Tugas</button>
                </div>
            </div>
          </div>
        </div>
    </div>
  </div>
  <?php $this->endWidget(); ?>
</div>
