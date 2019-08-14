<?php
	if(!empty($model->class)){
	  $kelas=$model->class->name;
	} else {
	  $kelas=NULL;
	}
?>
<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_user', array(
      //   'model'=>$model
      // ));
    ?>
    <div class="col-md-12">
	  <div id="bc1" class="btn-group btn-breadcrumb">
		<?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>Pengguna</div>',array('/user/index'), array('class'=>'btn btn-default')); ?>
		<?php echo CHtml::link('<div>'.CHtml::encode($model->display_name).'</div>',array('#'), array('class'=>'btn btn-success')); ?>
	  </div>
	</div>

    <div class="col-lg-12">
    	<div class="row clearfix">&nbsp;</div>
	    <div class="row">
			<div class="col-md-10 col-md-offset-1">
	        	<div class="col-card">
		            <div class="panel-body">
		           		<div class="row">
    						<h3><?php echo $model->display_name ?></h3>
    						<br/>
		                	<div class="col-md-3 col-lg-3">
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
		                	<div class="col-md-9 col-lg-9"> 
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
				                <hr/>
				                <div class="btn-group">
			                  	<?php
			                    	if ($model->id == Yii::app()->user->id || Yii::app()->user->YiiAdmin || Yii::app()->user->YiiKepsek || Yii::app()->user->YiiWali) {
			                      		if(Yii::app()->user->YiiAdmin || Yii::app()->user->YiiKepsek || Yii::app()->user->YiiWali){
			                        		echo CHtml::link('<i class="fa fa-pencil"></i> Sunting Pengguna',array('/user/update', 'id'=>$model->id),array('class'=>'btn btn-pn-primary'));
			                        		if($model->role_id==2){
			                          			echo CHtml::link('<i class="fa fa-eye"></i> Lihat Data',array('/userProfile/view', 'id'=>$model->id),array('class'=>'btn btn-primary'));
			                          			echo CHtml::link('<i class="fa fa-edit"></i> Sunting Data',array('/userProfile/update', 'id'=>$model->id),array('class'=>'btn btn-warning'));
			                        		}
			                    		}else{
			                    			echo CHtml::link('<i class="fa fa-pencil"></i> Sunting Pengguna',array('/user/update', 'id'=>Yii::app()->user->id),array('class'=>'btn btn-pn-primary'));
			                    			if($model->role_id==2){
			                        			echo CHtml::link('<i class="fa fa-eye"></i> Lihat Data',array('/userProfile/view', 'id'=>Yii::app()->user->id),array('class'=>'btn btn-primary'));
			                        			echo CHtml::link('<i class="fa fa-edit"></i> Sunting Data',array('/userProfile/update', 'id'=>Yii::app()->user->id),array('class'=>'btn btn-warning'));
			                        		}
			                      		}
			                    	}
			                	?>
			                	</div>
		                	</div>
		                	<div class="col-md-12 col-lg-12">
		                		<br/><hr/>
		                        <?php
		                          if ($model->id == Yii::app()->user->id || Yii::app()->user->YiiAdmin || Yii::app()->user->YiiKepsek || Yii::app()->user->YiiWali) { 
		                            echo CHtml::link('<i class="fa fa-key"></i> Ganti Password', array('gantiPassword','id'=>$model->id),array('class'=>'btn btn-primary'));
		                          }
		                        ?>
		                        <span class="pull-right">
		                        <?php
		                            if($model->id == Yii::app()->user->id){
		                              echo "<span> ".CHtml::link('<i class="fa fa-sign-out"></i> Keluar',array('site/logout'),array('class'=>'btn btn-primary'))."</span>"; 
		                            }
		                        ?>
                        		</span>
                			</div>
		              	</div>
		            </div>
        		</div>
	        </div>
	    </div>
    </div>
  </div>
</div>
