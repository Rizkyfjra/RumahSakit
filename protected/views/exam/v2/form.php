<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div id="bc1" class="btn-group btn-breadcrumb">
                <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda', array('/site/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Adminitrasi</div>', array('/exam/index'), array('class' => 'btn btn-default')); ?>
                <?php if (!$model->isNewRecord) { ?>
                    <?php echo CHtml::link('<div>Sunting Adminitrasi</div>', array('#'), array('class' => 'btn btn-success')); ?>
                <?php } else { ?>
                    <?php echo CHtml::link('<div>Tambah Adminitrasi</div>', array('#'), array('class' => 'btn btn-success')); ?>
                <?php } ?>
            </div>
        </div>

        <div class="col-lg-12">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'exam-form',
                'enableAjaxValidation' => false,
            )); ?>
            <?php if (!$model->isNewRecord) { ?>
                <h3>Sunting Adminitrasi </h3>
            <?php } else { ?>
                <h3>Membuat Adminitrasi </h3>
            <?php } ?>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-card">
                        <h4>Informasi Umum</h4>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="Exam_title">Nama</label>
                                    <?php echo $form->textField($model, 'title', array('class' => 'form-control input-pn input-lg', 'placeholder' => 'Isi Nama Disini …', 'required' => 'required')); ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Exam_start_date">Tanggal Mulai</label>
                                    <?php
                                    $this->widget('ext.YiiDateTimePicker.jqueryDateTime', array(
                                        'model' => $model,
                                        'attribute' => 'start_date',
                                        'options' => array('timepicker'=>false,'format'=>'Y-m-d'),
                                        'htmlOptions' => array(
                                            'class'=>'form-control input-pn input-lg'),
                                    ));
                                    ?>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="Exam_end_date">Tanggal Akhir</label>
                                    <?php
                                    $this->widget('ext.YiiDateTimePicker.jqueryDateTime', array(
                                        'model' => $model,
                                        'attribute' => 'end_date',
                                        'options' => array('timepicker'=>false,'format'=>'Y-m-d'),
                                        'htmlOptions' => array(
                                            'class'=>'form-control input-pn input-lg'),
                                    ));
                                    ?>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <?php if(!$model->isNewRecord){ ?>
                                    <button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Simpan Perubahan</button>
                                <?php }else{ ?>
                                    <button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Buat</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php $this->endWidget(); ?>
</div>