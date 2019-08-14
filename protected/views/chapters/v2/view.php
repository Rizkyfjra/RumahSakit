<?php
    $tipe = NULL;

    $doc_types = array('doc','docx','pdf','xls','xlsx','ppt','pptx');
    $vid_types = array('swf','mp4','MP4','avi','mkv','flv');
    $img_types = array('jpg', 'png', 'gif');
?>
<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_chapters_detail', array(
      //   'model'=>$model
      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
        <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>Materi</div>',array('/chapters/index'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>'.CHtml::encode($model->title).'</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
      <h3>Materi
      	<small><?php echo $model->title ?></small>
      </h3>
      <div class="row">
				<div class="col-md-9">
        	<div class="col-card">
	            <div class="panel-body">
	           		<div class="row">
                  <div class="col-md-12">
                    <div class="pull-right">
                      <div class="btn-group">
                        <?php
                      		if(!Yii::app()->user->YiiStudent){
                      			echo CHtml::link('<i class="fa fa-files-o"></i> Salin', array('copy','id'=>$model->id), array('class'=>'btn btn-pn-primary'));
                      		}

                          if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin) {
                            echo CHtml::link('<i class="fa fa-pencil"></i> Sunting', array('chapters/update','id'=>$model->id,'lesson_id'=>$model->mapel->id),array('class'=>'btn btn-primary'));
                          }

                          $cekFile = ChapterFiles::model()->findByAttributes(array('id_chapter'=>$model->id));
                          if(!empty($cekFile->file) ){
                            if ($cekFile->type != "mp4" && $cekFile->type != "mp3" && $cekFile->type != "MP3" && $cekFile->type != "MP4" && $cekFile->type != "FLV" && $cekFile->type != "flv") {
                    
                              echo CHtml::link('<i class="fa fa-download"></i> Download', array('chapterFiles/download','id'=>$cekFile->id),array('class'=>'btn btn-warning'));
                            }
                          }

                          if(Yii::app()->user->YiiTeacher || Yii::app()->user->YiiAdmin) {
                            echo CHtml::link('<i class="fa fa-trash"></i> Hapus', array('chapters/hapus','id'=>$model->id),array('class'=>'btn btn-danger'));
                          }
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="clearfix"></div>
                <br/>
                <div class="row">
                  <div class="col-md-12">
                    <div class="well">
                      <?php
                        if($model->chapter_type == 1){
                          $cekVideo = ChapterFiles::model()->findByAttributes(array('id_chapter'=>$model->id));
                          if(!empty($cekVideo)){
                            if($cekVideo->type == 'swf'){
                      ?>
                      <div class="img-responsive">
      									<center>
      										<object width="600" height="400" data="<?php echo Yii::app()->baseUrl.'/images/chapters/'.$model->id.'/'.$cekVideo->file;?>"></object>
      									</center>
      								</div>
                      <?php
                            }else{
                      ?>
                      <div class="img-responsive">
      									<center>
      										<?php
      											$this->widget('ext.jwplayer.Jwplayer',array(
      											    'width'=>500,
      											    'height'=>360,
      											    'file'=>Yii::app()->baseUrl.'/images/chapters/'.$model->mapel->id.'/'.$cekVideo->file,
      											    'image'=>NULL,
      											    'options'=>array(
      											        'controlbar'=>'bottom'
      											    )
      											));
      										?>
      									</center>
      								</div>
                      <?php
                            }
                          }else{
                      ?>
                      Video Tidak Tersedia
                      <?php
                          }
                        }elseif($model->chapter_type == 2){
                          $cekGambar = ChapterFiles::model()->findByAttributes(array('id_chapter'=>$model->id));
                          if(!empty($cekGambar->file)){
                            $url = Yii::app()->baseUrl."/images/chapters/".$model->mapel->id."/".$cekGambar->file;
                      ?>
                      <center>
        								<a href="<?php echo $url;?>" target="_blank"><img src="<?php echo $url;?>" class="img-responsive"></a>
        							</center>
                      <?php
                          }
                        }elseif($model->chapter_type == 3){
                          $cekDokumen = ChapterFiles::model()->findByAttributes(array('id_chapter'=>$model->id));
                          if(!empty($cekDokumen)){
                            if($cekDokumen->type == "pdf"){
                      ?>
                      <div style="height:600px;">
                        <?php
                          $this->widget('ext.pdfJs.QPdfJs',array(
          									'url'=>Yii::app()->baseUrl."/images/chapters/upload/".$cekDokumen->file,
          								))
                        ?>
                      </div>
                      <?php
                            }elseif($cekDokumen->type == 'doc' || $cekDokumen->type == 'docx'){
                              $url = Yii::app()->baseUrl."/images/ms-word.jpg";
                              $url_img = Yii::app()->baseUrl."/images/chapters/".$model->mapel->id."/".$cekDokumen->file;
                      ?>
                      <center>
      									<a href="<?php echo $url_img;?>"><img src="<?php echo $url;?>" class="img-responsive"></a>
                        <a href="<?php echo $url_img;?>"><?php echo $cekDokumen->file;?></a>
      								</center>
                      <?php
                            }elseif($cekDokumen->type == 'xls' || $cekDokumen->type == 'xlsx'){
                              $url = Yii::app()->baseUrl."/images/ms-excel.jpg";
            									$url_img = Yii::app()->baseUrl."/images/chapters/".$model->mapel->id."/".$cekDokumen->file;
                      ?>
                      <center>
      									<a href="<?php echo $url_img;?>"><img src="<?php echo $url;?>" class="img-responsive"></a>
                        <a href="<?php echo $url_img;?>"><?php echo $cekDokumen->file;?></a>
      								</center>
                      <?php
                            }elseif($cekDokumen->type == 'ppt' || $cekDokumen->type == 'pptx'){
                              $url = Yii::app()->baseUrl."/images/ms-ppt.png";
            									$url_img = Yii::app()->baseUrl."/images/chapters/".$model->mapel->id."/".$cekDokumen->file;
                      ?>
                      <center>
      									<a href="<?php echo $url_img;?>"><img src="<?php echo $url;?>" class="img-responsive"></a>
                        <a href="<?php echo $url_img;?>"><?php echo $cekDokumen->file;?></a>
      								</center>
                      <?php
                            }
                          }
                        }
                      ?>
                      <br/>
                      <?php echo $model->content ?>
                    </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
