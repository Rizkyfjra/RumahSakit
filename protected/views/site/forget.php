<div id="" class="wrapper">
	<div id="" class="notification container">
	</div>
</div>
<div class="container">


	<h2></h2>

	<div class="row">
		<div class="span8 offset2">
			<div class="">
				<legend>Reset Password</legend>
				
				<form id="login-form" name="login-form" class="well form-horizontal" action="<?php echo $this->createUrl('site/forget'); ?>" method="post" enctype="multipart/form-data">
					<div class="control-group">
						<label class="control-label" for="username">NIK/NIP</label>
						<div class="controls">
							<input type="text" class="input-xlarge " id="username" name="username">
						</div>
					</div>
					<div class="form-actions">
						<button type="submit" class="btn btn-primary">Kirim Permohonan</button>
						<?php echo CHtml::link('Login',array('site/login')); ?>
					</div>
				</form>
			</div>
		</div>
	</div>

		
</div>