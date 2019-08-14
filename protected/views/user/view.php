<?php
/* @var $this UserController */
/* @var $model User */

$this->breadcrumbs=array(
	//'User'=>array('index'),
	$model->display_name,
);

// $this->menu=array(
// 	array('label'=>'List User', 'url'=>array('index')),
// 	array('label'=>'Create User', 'url'=>array('create')),
// 	array('label'=>'Update User', 'url'=>array('update', 'id'=>$model->id)),
// 	array('label'=>'Delete User', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
// 	array('label'=>'Manage User', 'url'=>array('admin')),
// );
if(!empty($model->class)){
  $kelas=$model->class->name;
} else {
  $kelas=NULL;
}
?>

<h1><?php echo $model->display_name; ?></h1>

<?php 
// $this->widget('zii.widgets.CDetailView', array(
// 	'data'=>$model,
// 	'attributes'=>array(
// 		'id',
// 		'email',
// 		'username',
// 		'encrypted_password',
// 		'role_id',
// 	),
// )); ?>

<div class="row">
      <div class="col-md-5  toppad  pull-right col-md-offset-3 ">
        	
       <br>
      </div>
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad" >
   
   
          <div class="panel panel-info">
            <div class="panel-heading">
              <h3 class="panel-title"><?php echo $model->display_name; ?></h3>
            </div>
            <div class="panel-body">
              <div class="row">
                <div class="col-md-3 col-lg-3 " align="center"> 
                  <?php
                    if(($model->image))
                    {
                      $img_url = Yii::app()->baseUrl.'/images/user/'.$model->id.'/'.$model->image;
                    } else {
                      $img_url = Yii::app()->baseUrl.'/images/user-2.png';
                    }
                  ?>
                  <img alt="User Pic" src="<?php echo $img_url?>" height="100px" class="img-circle"> 
                </div>
                
                <!--<div class="col-xs-10 col-sm-10 hidden-md hidden-lg"> <br>
                  <dl>
                    <dt>DEPARTMENT:</dt>
                    <dd>Administrator</dd>
                    <dt>HIRE DATE</dt>
                    <dd>11/12/2013</dd>
                    <dt>DATE OF BIRTH</dt>
                       <dd>11/12/2013</dd>
                    <dt>GENDER</dt>
                    <dd>Male</dd>
                  </dl>
                </div>-->
                <div class=" col-md-9 col-lg-9 "> 
                  <table class="table table-user-information table-responsive">
                    <tbody>
                      <tr>
                        <td>Email</td>
                        <td><?php echo $model->email; ?></td>
                      </tr>
                      <tr>
                        <td>NIK/NIP</td>
                        <td><?php echo $model->username; ?></td>
                      </tr>
                      <tr>
                        <td>Kelas</td>
                        <td><?php echo $kelas; ?></td>
                      </tr>
                      
                 
                     
                    </tbody>
                  </table>
                  
                  <?php
                    if ($model->id == Yii::app()->user->id || Yii::app()->user->YiiAdmin || Yii::app()->user->YiiKepsek || Yii::app()->user->YiiWali) {
                      if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiKepsek || Yii::app()->user->YiiWali){
                        echo CHtml::link('<i class="fa fa-user"></i> Edit Profile',array('/user/update', 'id'=>$model->id),array('class'=>'btn btn-primary'));
                        if($model->role_id==2){
                          echo CHtml::link('<i class="fa fa-user"></i> Edit Data',array('/userProfile/update', 'id'=>$model->id),array('class'=>'btn btn-primary'));
                          echo CHtml::link('<i class="fa fa-user"></i> Edit Sejarah Nilai',array('/userProfileScore/update', 'id'=>$model->id),array('class'=>'btn btn-primary'));
                        }
                      }else{
                        echo CHtml::link('<i class="fa fa-user"></i> Edit Profile',array('/user/update', 'id'=>Yii::app()->user->id),array('class'=>'btn btn-primary'));
                        if($model->role_id==2){
                          echo CHtml::link('<i class="fa fa-user"></i> Edit Data ',array('/userProfile/update', 'id'=>Yii::app()->user->id),array('class'=>'btn btn-primary'));
                          echo CHtml::link('<i class="fa fa-user"></i> Lihat Data',array('/userProfile/'.Yii::app()->user->id),array('class'=>'btn btn-primary'));
                          echo CHtml::link('<i class="fa fa-user"></i> Edit Sejarah Nilai SMP',array('/userProfileScore/update', 'id'=>Yii::app()->user->id),array('class'=>'btn btn-primary'));
                          echo CHtml::link('<i class="fa fa-user"></i> Lihat Sejarah Nilai SMP',array('/userProfileScore/'.Yii::app()->user->id),array('class'=>'btn btn-primary'));
                        }
                      }
                    }
                  ?>
                </div>
              </div>
            </div>
                 <div class="panel-footer">
                        <?php
                          if ($model->id == Yii::app()->user->id || Yii::app()->user->YiiAdmin || Yii::app()->user->YiiKepsek || Yii::app()->user->YiiWali) { 
                            echo CHtml::link('<i class="fa fa-key"></i> Ganti Password', array('gantiPassword','id'=>$model->id),array('class'=>'btn btn-primary'));
                          }
                        ?>
                        <span class="pull-right">
                        <?php
                            if($model->id == Yii::app()->user->id){
                              echo "<span> ".CHtml::link('<i class="fa fa-sign-out"></i> Logout',array('site/logout'),array('class'=>'btn btn-primary'))."</span>"; 
                            }
                        ?>
<!--                             <a href="" data-original-title="Edit this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-warning"><i class="glyphicon glyphicon-edit"></i></a>
                            <a data-original-title="Remove this user" data-toggle="tooltip" type="button" class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i></a> -->
                        </span>
                        
                    </div>
            
          </div>
        </div>
      </div>
