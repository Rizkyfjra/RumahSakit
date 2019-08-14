<?php
	$list = LessonList::model()->findAll(array('order'=>'id'));
	$lesson_list = array();
	if(!empty($list)){
		foreach ($list as $ll) {
			if($ll->group == 1){
				$ket = "Obat Dalam";
			}elseif($ll->group == 2){
				$ket = "Obat Luar";
			}else{
				$ket = "Obat Keras";
			}

			$lesson_list[$ll->id]=$ll->name." (".$ket.")";
		}
	}

	$clist = ClassDetail::model()->findAll(array('order'=>'name'));
	$classes_list = array();

	if(!empty($clist)){
		foreach ($clist as $cl) {
			$classes_list[$cl->id]=$cl->name;
		}
	}

	$klist = Clases::model()->findAll(array('order'=>'id'));
	$big_class = array();

	if(!empty($klist)){
		foreach ($klist as $kl) {
			$big_class[$kl->id]=$kl->name;
		}
	}

	if(!empty($model->user_id)){
		$model->user_id = $model->users->display_name.' | '.$model->users->username.' (ID:'.$model->users->id.')';
	}

	if(!empty($model->class_id)){
		$selected=$model->class_id;
	}else{
		$selected=1;
	}

	$show_small = '';
	$show_big = '';
	
	if($model->moving_class == 1){
		$show_small = 'display:none';
	}else{
		$show_big = 'display:none';	
	}

	if(!empty($model->moving_class)){
		$mov_class = $model->moving_class;
	}else{
		$mov_class = 0;
	}


	$nama_sekolah = Option::model()->findAll(array('condition' => 'key_config LIKE "%school_name%"'));
	$nama_sekolah = $nama_sekolah[0]->value;
?>

<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_course_add', array(
      //   'model'=>$model
      // ));
    ?>
    <div class="col-md-12">
	  <div id="bc1" class="btn-group btn-breadcrumb">
		<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>Pelajaran</div>',array('/lesson/index'), array('class'=>'btn btn-default')); ?>
	    <?php if(!$model->isNewRecord){ ?>
		<?php echo CHtml::link('<div>Sunting Pelajaran</div>',array('#'), array('class'=>'btn btn-success')); ?>
		<?php }else{ ?>
		<?php echo CHtml::link('<div>Tambah Pelajaran</div>',array('#'), array('class'=>'btn btn-success')); ?>	
		<?php } ?>	
	  </div>
	</div>

    <div class="col-lg-12">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'lesson-form',
			'enableAjaxValidation'=>false,
			'htmlOptions' => array('enctype' => 'multipart/form-data'),
		)); ?>
      <?php if(!$model->isNewRecord){ ?>
      <h3>Sunting Pelajaran</h3>
      <?php }else{ ?>
      <h3>Membuat Obat</h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-12">
          <div class="col-card">
          	<div class="row">
          		<div class="col-md-10">
          			<div class="form-group">
	                 	<label for="Lesson_name">Nama Obat</label>
                		<?php
                			if($model->isNewRecord){
                    			echo $form->dropDownList($model,'list_id',$lesson_list,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg','data-live-search'=>'true','options'=>array($selected=>array('selected'=>true))));
                			}else{
                    			  
                    			  if ($nama_sekolah=="TAR-Q") {
                    			  	echo $form->textField($model,'name',array('class'=>'form-control input-pn input-lg','placeholder'=>'Isi nama pelajaran','required'=>'required'));
                    			  } else {
                    			  	echo $form->dropDownList($model,'list_id',$lesson_list,array('class'=>'selectpicker form-control','data-style'=>'btn-default input-lg','data-live-search'=>'true','disabled'=>'disabled','readonly'=>'readonly','options'=>array($selected=>array('selected'=>true))));
                    			  }  
                    			  
                    		}
                 		 ?>
          			</div>
          		</div>
          		<div class="col-md-2">
          			<div class="form-group">
	                 	<label for="Lesson_moving_class">Gratis</label>
	                 	<p style="padding-top:13px">
	                 		<?php echo $form->checkBox($model,'moving_class',array('value' => '1', 'uncheckValue'=>'0','onchange'=>'javascript:$("#kelas").toggle();$("#big").toggle()')); ?>
	                 	</p>
			        </div>
          		</div>
          	</div>
          	<div class="row">
          		<div class="col-md-12">
	          		<div class="form-group">
	                 	<label for="Lesson_class_id">Kelas</label>
						<?php echo $form->dropdownList($model,'class_id',$classes_list,array('class'=>'form-control','style'=>$show_small,'id'=>'kelas','options'=>array($selected=>array('selected'=>true))));?>
						<?php echo $form->dropdownList($model,'big',$big_class,array('class'=>'form-control','style'=>$show_big,'id'=>'big','options'=>array($selected=>array('selected'=>true))));?>
	          		</div>
          		</div>
          	</div>
			<?php if(Yii::app()->user->YiiAdmin){ ?>
			<div class="row">
				<div class="col-md-12">
					<div class="form-group">
	                 	<label for="Lesson_user_id">Apotiker</label>
						<?php
							$this->widget('zii.widgets.jui.CJuiAutoComplete',
								array(
									'model'=>$model,
						    		'attribute'=>'user_id',
									'options'=>array(
									'minLength'=>'1',
								),
								'source'=>$this->createUrl("user/suggest"),
								'htmlOptions'=>array(
								'class'=>'form-control'
								),
							));
						?>
					</div>
				</div>
			</div>
			<?php } ?>
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
  <?php $this->endWidget(); ?>
</div>
