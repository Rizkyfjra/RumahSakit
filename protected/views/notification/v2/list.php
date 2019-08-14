<div class="container-fluid">
  <div class="row">
    <?php
      // $this->renderPartial('v2/_breadcrumb_notif_list', array(
        
      // ));
    ?>
    <div class="col-md-12">
      <div id="bc1" class="btn-group btn-breadcrumb">
      <?php echo CHtml::link('<i class="fa fa-home"></i> Beranda',array('/site/index'), array('class'=>'btn btn-default')); ?>
      <?php echo CHtml::link('<div>Pemberitahuan</div>',array('#'), array('class'=>'btn btn-success')); ?>
      </div>
    </div>

    <div class="col-lg-12">
        <h3>Pemberitahuan
          <small class="hidden-xs">Daftar Pemberitahuan</small>
          </div>
        </h3>
        <div class="row">
          <div class="col-md-12">
            <div class="col-card">
              <div class="table-responsive">
                <table class="table table-hover">
                  <thead>
                    <tr>
                      <th>No.</th>
                      <th width="20%">Dari</th>
                      <th width="20%">Untuk</th>
                      <th width="35%">Konten</th>
                      <th width="15%">Status</th>
                      <th width="10%">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      if(!empty($dataProvider->getData())){
                        $notif = $dataProvider->getData();

                        if(empty($_GET['Notification_page'])){
                          $no = 1;
                        }else{
                          $no = 1 + (($_GET['Notification_page']-1) * 15);
                        }

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
                    <tr>
                      <td><?php echo $no ?></td>
                      <td><?php echo CHtml::link(CHtml::encode($user_dp), array('/user/view', 'id'=>$user_id)); ?></td>
                      <td><?php echo $user_dp_to ?></td>
                      <td><?php echo $content ?></td>
                      <td><?php echo $status ?></td>
                      <td>
                        <div class="btn-group">
                          <?php echo CHtml::link('<i class="fa fa-reply"></i> Respon',array('/notification/gene', 'user_id'=>$user_id, 'notif_id'=>$id), array('class'=>'btn btn-primary btn-xs')); ?>
                        </div>
                      </td>
                    </tr>
                    <?php
                          $no++;
                        }
                      }
                    ?>
                  </tbody>
                </table>
                <div class="text-center">
                  <?php
                    $this->widget('CLinkPager', array(
                                  'pages'=>$dataProvider->pagination,
                                  ));
                  ?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
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