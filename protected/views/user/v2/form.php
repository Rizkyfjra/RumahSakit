<?php
	$status_array = array(1 => 'Dokter', 2 => 'Pasien', 3 => 'Staf');

	if(($model->image)){
		$img_url = Yii::app()->baseUrl.'/images/user/'.$model->id.'/'.$model->image;
	}else{
		$img_url = Yii::app()->baseUrl.'/images/user-2.png';
	}	
?>

<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_user_add', array(
      //   'model'=>$model
      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
        <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
        <?php echo CHtml::link('<div>Pengguna</div>',array('/user/index'), array('class'=>'btn btn-default')); ?>
        <?php if(!$model->isNewRecord){ ?>
        <?php echo CHtml::link('<div>Sunting Pengguna</div>',array('#'), array('class'=>'btn btn-success')); ?>
        <?php }else{ ?>
        <?php echo CHtml::link('<div>Tambah Pengguna</div>',array('#'), array('class'=>'btn btn-success')); ?>  
        <?php } ?>  
      </div>
    </div>

    <div class="col-lg-12">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'user-form',
			'htmlOptions' => array('enctype' => 'multipart/form-data'),
			'enableAjaxValidation'=>false,
		)); ?>
      <?php if(!$model->isNewRecord){ ?>
      <h3>Sunting Pengguna</h3>
      <?php }else{ ?>
      <h3>Membuat Pengguna Baru</h3>
      <?php } ?>
      <div class="row">
        <div class="col-md-12">
          <div class="col-card">
			<?php if(!Yii::app()->user->YiiStudent){ ?>
            <div class="row">
            	<div class="col-md-12">
            		<div class="form-group">
	                 	<label for="User_username">NIP/NIK/NO Pasien</label>
						<?php echo $form->textField($model,'username',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-pn input-lg','placeholder'=>'NIK/NIP/NIS/NISN')); ?>
            		</div>
            	</div>
            </div>
            <?php } ?>
            <div class="row">
            	<div class="col-md-12">
            		<div class="form-group">
	                 	<label for="User_display_name">Nama Lengkap</label>
						<?php echo $form->textField($model,'display_name',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-pn input-lg','placeholder'=>'Nama Lengkap')); ?>
            		</div>
            	</div>
            </div>
            <div class="row">
            	<div class="col-md-12">
            		<div class="form-group">
	                 	<label for="User_email">E-Mail</label>
						<?php echo $form->textField($model,'email',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-pn input-lg','placeholder'=>'E-Mail')); ?>
            		</div>
            	</div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="User_email">ID Pasien</label>
                        <?php echo $form->textField($model,'id_absen_solution',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-pn input-lg','placeholder'=>'ID Pasien')); ?>
                    </div>
                </div>
            </div>
			<?php if ($model->isNewRecord) { ?>            
            <div class="row">
            	<div class="col-md-12">
            		<div class="form-group">
	                 	<label for="User_role_id">Role</label>
						<?php echo $form->dropDownList($model,'role_id',$status_array, array('class'=>'form-control input-pn input-lg')); ?>
            		</div> 
            	</div>
            </div>
            <div class="row">
            	<div class="col-md-12">
            		<div class="form-group">
	                 	<label for="User_encrypted_password">Password</label>
						<?php echo $form->passwordField($model,'encrypted_password',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-pn input-lg','placeholder'=>'Password')); ?>
            		</div>
            	</div>
            </div>
            <div class="row">
            	<div class="col-md-12">
            		<div class="form-group">
	                 	<label for="User_encrypted_password">Password (Ulang)</label>
						<?php echo $form->passwordField($model,'password2',array('size'=>60,'maxlength'=>255,'class'=>'form-control input-pn input-lg','placeholder'=>'Password (Ulang)')); ?>
            		</div>
            	</div>
            </div>
            <?php } ?>
            <div class="row">
            	<div class="col-md-12">
            		<div class="form-group">
	                 	<label for="User_image">Foto</label>
						<?php echo $form->fileField($model,'image',array('id'=>'img')); ?>
	            		<img id="preview" class="img-thumbnail img-responsive" height="150" width="150" src="<?php echo $img_url; ?>" alt="no avatar">
            		</div>
            	</div>
            </div>
            <div class="row" id="child-student">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="User_class_id">Class ID</label>
                        <?php
                            $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
                                //'model'=>$model,
                                'name'=>'class_id',
                                // additional javascript options for the autocomplete plugin
                                'options'=>array(
                                'minLength'=>'1',
                                ),
                                'source'=>$this->createUrl("clases/suggest"),
                                'htmlOptions'=>array(
                                //'style'=>'height:20px;',
                                'class'=>'form-control'
                                //'required'=>$model->verification_status>=1,
                                ),
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
        			<button type="submit" class="btn btn-pn-primary btn-lg btn-pn-round btn-block next-step">Buat Pengguna</button>
        			<?php } ?>
          		</div>
            </div>
          </div>
        </div>
    </div>
  </div>
  <?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
    $("#child-student").hide();

    function readURL(input) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	            $('#preview').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}

	$("#img").change(function(){
    	readURL(this);
	});
</script>
