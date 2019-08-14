<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_user_password', array(
      //   'model'=>$model
      // ));
    ?>
    <div class="col-md-12">
	  <div id="bc1" class="btn-group btn-breadcrumb">
		<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>Pengguna</div>',array('/user/index'), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>'.CHtml::encode($model->display_name).'</div>',array('/user/view', 'id'=>$model->id), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>Ganti Password</div>',array('#'), array('class'=>'btn btn-success')); ?>
	  </div>
	</div>

    <div class="col-lg-12">
		<?php
			$form=$this->beginWidget('CActiveForm', array(
				'id'=>'change-form',
				'htmlOptions' => array('class'=>'form-horizontal'),
				'enableAjaxValidation'=>false,
			));
		?>
    	<div class="row clearfix">&nbsp;</div>
	    <div class="row">
			<div class="col-md-8 col-md-offset-2" style="padding-bottom:0px">
	        	<div class="col-card">
	        		<div class="panel-body" style="padding-bottom:0px">
		           		<div class="row">
							<h3>Ganti Password</h3>
							<br/>
							<div class="form-group">
								<div class="col-md-4">
									<label for="input-password-0" class="control-label">Password Lama :</label>
								</div>
								<div class="col-md-8">
									<?php echo $form->passwordField($model,'old_password',array('class'=>'form-control input-pn input-lg', 'placeholder'=>'Password Lama')); ?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-4">
									<label for="input-password-1" class="control-label">Password Baru :</label>
								</div>
								<div class="col-md-8">
									<?php echo $form->passwordField($model,'new_password',array('class'=>'form-control input-pn input-lg', 'placeholder'=>'Password Baru')); ?>
								</div>
							</div>
							<div class="form-group">
								<div class="col-md-4">
									<label for="input-password-2" class="control-label">Password Baru (Ulang) :</label>
								</div>
								<div class="col-md-8">
									<?php echo $form->passwordField($model,'new_password2',array('class'=>'form-control input-pn input-lg', 'placeholder'=>'Password Baru (Ulang)')); ?>
								</div>
							</div>
							<hr/>
							<div class="form-group">
								<div class="col-md-12">
									<?php echo CHtml::submitButton('Simpan Perubahan',array('class'=>'btn btn-pn-primary btn-lg btn-pn-round btn-block next-step')); ?>
								</div>
							</div>
		           		</div>
		           	</div>
				</div>
			</div>
		</div>
		<?php $this->endWidget(); ?>
    </div>
  </div>
</div>