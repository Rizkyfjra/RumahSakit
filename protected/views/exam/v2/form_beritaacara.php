<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div id="bc1" class="btn-group btn-breadcrumb">
                <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda', array('/site/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Adminitrasi </div>', array('/exam/index'), array('class' => 'btn btn-default')); ?>
                <?php echo CHtml::link('<div>Sunting Berita Acara</div>', array('#'), array('class' => 'btn btn-success')); ?>
            </div>
        </div>
        <div class="col-lg-12">
            <?php $form = $this->beginWidget('CActiveForm', array(
                'id' => 'room-form',
                'enableAjaxValidation' => false,
                'htmlOptions' => array('enctype' => 'multipart/form-data'),
            )); ?>
            <h3>Sunting Ruangan</h3>
            <div class="row">
                <div class="col-md-12">
                    <div class="col-card">
                        <div class="row">
                            <div class="col-md-12">
                                <textarea name="edited" id="textword1" cols="30" rows="10"
                                          class="text-naon">
                                    <?php echo $model->edited; ?>
                                </textarea>
                                <br>
                                <br>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="id"
                                       value="<?php echo $model->id ?>">
                                <button type="submit"
                                        class="btn btn-pn-primary btn-lg btn-pn-round btn-block"> Simpan
                                </button>
                                <a href="<?php echo $this->createUrl('/exam/reset_beritaacara') ?>"
                                   class="btn btn-primary btn-lg btn-pn-round btn-block"> <strong>Reset</strong>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
</div>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl ?>/libraries/ckeditor/ckeditor.js"></script>
<script type="text/javascript">
    $(document).ready(function () {
        CKEDITOR.replace('textword1');
    });
</script>