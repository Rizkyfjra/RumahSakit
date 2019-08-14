<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>



<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>false,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>
<div class="row">
    <div class="col-md-4"></div>
    <div class="col-md-4">
      <div class="panel panel-default">
        <div class="panel-body">
          <div class="page-header">
            <h3>Login Area</h3>
          </div>
          <form>
            <div class="form-group">
              <label for="exampleInputEmail1">NIK/NIP/Email</label>
              <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                <?php echo $form->textField($model,'username',array('class'=>'form-control', 'placeholder'=>'NIK/NIP')); ?>
                
              </div>
              <?php echo $form->error($model,'username'); ?>
            </div>
            <div class="form-group">
              <label for="exampleInputPassword1">Password</label>
              <div class="input-group">
                <span class="input-group-addon"><span class="glyphicon glyphicon-star"></span></span>
                <?php echo $form->passwordField($model,'password',array('class'=>'form-control', 'placeholder'=>'Password')); ?>
           			
            </div>
            <?php echo $form->error($model,'password'); ?>
            <br/>
            <p class="form-control-static">Lupa Password ? Klik <a href="<?php echo $this->createUrl('site/forget'); ?>">Disini</a></p>
            <hr/>

            <button type="submit" class="btn btn-success"><span class="glyphicon glyphicon-lock"></span>Login</button>
            <p><br/></p>
          </form>
        </div>
      </div>
    <div class="col-md-4"></div>
<?php $this->endWidget(); ?>
</div>
   
