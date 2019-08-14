<?php
  if(!empty($dataProvider->getData())){
    $notif = $dataProvider->getData();

    foreach ($notif as $value) {
      $id = $value->id;
      $user_id = $value->user_id;
      $user_dp = $value->user->display_name;
      if (!empty($value->user_id_to)){
        $user_dp_to = $value->user_to->display_name;
        $user_id_to = $value->user_id_to;
      } elseif (!empty($value->class_id_to)) {
        $user_dp_to = $value->class_to->name;
        $user_id_to = $value->class_id_to;
      } else {
        $user_dp_to = 'Tidak Diketahui';
        $user_id_to = 'Tidak Diketahui';
      }
      $content = $value->content;
      $status = $value->status;
      $tipe = $value->tipe;
      $relation_id = $value->relation_id;
      $read_at = $value->read_at;

      if (empty($status)){
        $status = "Belum Direspon";
      }else{
        $status = "Telah Direspon";
      }

      if (empty($read_at)){
        $status = $status." (Baru)";
      }
?>
<li class="notification <?php echo '',(empty($read_at) ? 'active' : '') ?>">
  <div class="media">
    <div class="media-left">
      <div class="media-object">
        <?php if(empty($read_at)){ ?>
        <img data-src="holder.js/50x50?bg=cccccc" class="img-circle" alt="50x50" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/notif-icon-dummy-green.png" data-holder-rendered="true" >
        <?php }else{ ?>
        <img data-src="holder.js/50x50?bg=cccccc" class="img-circle" alt="50x50" src="<?php echo Yii::app()->theme->baseUrl; ?>/images/notif-icon-dummy-red.png" data-holder-rendered="true" >
        <?php } ?>
      </div>
    </div>
    <div class="media-body">
      <p class="notification-title">
        Dari :&nbsp;<?php echo CHtml::link(CHtml::encode($user_dp), array('/user/view', 'id'=>$user_id)); ?><br/>
        <?php echo $content ?>
      </p>

      <div class="notification-meta">
        <small class="timestamp">
          Status : <?php echo $status ?>
          <?php if($status=="Belum Direspon"){ ?>
            &nbsp;|&nbsp;<?php echo CHtml::link('<i class="fa fa-reply"></i> Respon',array('/notification/gene', 'user_id'=>$user_id, 'notif_id'=>$id)); ?>
          <?php } ?>
        </small>
      </div>
    </div>
  </div>
</li>
<?php
    }
  }

  $current_user = Yii::app()->user->id;
  $prefix = Yii::app()->params['tablePrefix'];

  $sql='UPDATE '.$prefix.'notification set read_at = now() where user_id_to = :id and read_at is null';
  $command=Yii::app()->db->createCommand($sql);
  $command->bindParam(":id", $current_user, PDO::PARAM_STR);
  $command->execute();

  $modelUser=User::model()->findByPk($current_user);
  $class_student_id = $modelUser->class_id;

  $current_user_str = ','.$current_user.',';

  $sql="UPDATE ".$prefix."notification set read_id = CONCAT(:current_user_str, read_id), read_at = now() where class_id_to = :id and read_id not like '%,$current_user,%' ";
  $command=Yii::app()->db->createCommand($sql);
  $command->bindParam(":id", $class_student_id, PDO::PARAM_STR);
  $command->bindParam(":current_user_str", $current_user_str, PDO::PARAM_STR);
  $command->execute();
?>
